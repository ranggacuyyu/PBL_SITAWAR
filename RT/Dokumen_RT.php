<?php
session_start();
include "../koneksi.php";
if (!isset($_SESSION['user_rt'])) {
    header('location:../LoginRTWARGA.php');
}
// Ambil ID RT dari session
$id_rt = $_SESSION['user_rt']['sk_rt'];

// Ambil semua data pengajuan dokumen + data warga
$query = mysqli_query($koneksi, "SELECT 
        d.id_dokumen,
        d.tanggal,
        d.warga,
        d.status,
        w.nama_warga,
        TIMESTAMPDIFF(YEAR, w.tanggal_lahir, CURDATE()) AS usia,
        w.jenis_kelamin,
        w.hp,
        w.alamat,
        w.dokumen
    FROM dokumen d
    INNER JOIN user_warga w 
        ON d.warga = w.nik_warga
    WHERE w.rt = '$id_rt'
    ORDER BY d.id_dokumen DESC
");
// Proses persetujuan atau penolakan
if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $aksi = $_GET['aksi'];
    $id_dokumen = $_GET['id'];

    if ($aksi === 'setuju') {
        mysqli_query($koneksi, "UPDATE dokumen SET status='setuju' WHERE id_dokumen='$id_dokumen'");
    } elseif ($aksi === 'tolak') {
        mysqli_query($koneksi, "UPDATE dokumen SET status='tolak' WHERE id_dokumen='$id_dokumen'");
    }

    // Redirect kembali ke halaman dokumen RT setelah aksi
    header('Location: Dokumen_RT.php');
    exit();
}
?>

<style>
    .tanda_setuju {
        background-color: green !important;
        color: white !important;
        border: none !important;
        font-weight: bold;
        border-radius: 8px;
    }

    .tanda_tolak {
        background-color: red !important;
        color: white !important;
        border: none;
        font-weight: bold;
        border-radius: 8px;
    }

    /* Animation */
    @keyframes contentSlideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .content-animate {
        animation: contentSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SITAWAR - Pengajuan Dokumen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Dokumen_RT.css">
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div>
            <div class="sidebar-header">
                <h1>SITAWAR</h1>
                <p>Sistem Terpadu Administrasi Warga</p>
            </div>
            <nav class="nav">
                <a href="Dashboard_RT.php">Dashboard</a>
                <a href="kepala_keluarga.php">Kepala Keluarga</a>
                <a href="DataWarga_RT.php">Data Warga</a>
                <a href="Dokumen_RT.php">Dokumen</a>
                <a href="Laporan_ RT.php">Laporan</a>
            </nav>
        </div>
        <div class="sidebar-footer">
            © 2025 RT Smart System
        </div>
    </aside>

    <!-- MAIN -->
    <div class="bungkus content-animate">
        <div class="datatambahan">
            <div class="main-content">
                <div class="card p-4">
                    <h5>Menu Pengajuan</h5>
                    <p>Panduan:<br>
                        1. Klik nama warga untuk melihat rincian<br>
                        2. Klik “Setuju” atau “Tolak” untuk meninjau pengajuan
                    </p>

                    <table class="table table-bordered bg-success" id="tabelPengajuan">
                        <thead>
                            <tr>
                                <th>Nama Warga</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Jenis Dokumen</th>
                                <th>Persetujuan</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                                <tr>
                                    <!-- Nama, bisa diklik -->
                                    <td style="cursor:pointer" onclick='lihatDetail(<?= json_encode([
                                        "nama" => $row['nama_warga'],
                                        "usia" => $row['usia'],
                                        "jenis_kelamin" => $row['jenis_kelamin'],
                                        "kategori" => $row['dokumen'],
                                        "alamat" => $row['alamat'],
                                        "hp" => $row['hp'],
                                        "tanggal" => $row['tanggal']
                                    ]); ?>)'>
                                        <?= $row['nama_warga'] ?>
                                    </td>
                                    <td><?= $row['tanggal'] ?></td>
                                    <td><?= $row['dokumen'] ?></td>
                                    <td>
                                        <?php if ($row['status'] === 'pending') { ?>
                                            <a href="Dokumen_RT.php?aksi=setuju&id=<?= $row['id_dokumen']; ?>"
                                                class="btn btn-sm btn-success">
                                                Setuju
                                            </a>
                                            <a href="Dokumen_RT.php?aksi=tolak&id=<?= $row['id_dokumen']; ?>"
                                                class="btn btn-sm btn-danger">
                                                Tolak
                                            </a>
                                        <?php } elseif ($row['status'] === 'setuju') { ?>
                                            <button class="tanda_setuju" style="padding: 5px 10px 5px 10px;"
                                                disabled>Disetujui</button>
                                        <?php } elseif ($row['status'] === 'tolak') { ?>
                                            <button class="tanda_tolak" style="padding: 5px 16px 5px 16px;"
                                                disabled>Ditolak</button>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>

            <!-- MODAL DETAIL -->
            <div class="modal fade" id="modalDetail" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Rincian Data Warga</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body" id="detailBody"></div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SCRIPT MODAL -->
    <script>
        function lihatDetail(data) {
            let html = `
                <p><b>Nama:</b> ${data.nama}</p>
                <p><b>Usia:</b> ${data.usia}</p>
                <p><b>Jenis Kelamin:</b> ${data.jenis_kelamin}</p>
                <p><b>Kategori:</b> ${data.kategori}</p>
                <p><b>Alamat:</b> ${data.alamat}</p>
                <p><b>No HP:</b> ${data.hp}</p>
                <p><b>Tanggal Pengajuan:</b> ${data.tanggal}</p>
            `;
            document.getElementById("detailBody").innerHTML = html;
            new bootstrap.Modal(document.getElementById("modalDetail")).show();
        }
    </script>

</body>

</html>