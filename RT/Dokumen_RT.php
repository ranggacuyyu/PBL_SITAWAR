<?php
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';

if (!isset($_SESSION['user_rt'])) {
    header('Location: ../LoginRTWARGA.php');
    exit();
}

$id_rt = $_SESSION['user_rt']['sk_rt'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama       = $_POST['nama_warga'];
    $aksi       = $_POST['aksi'];
    $id_dokumen = $_POST['id_dokumen'];

    if (in_array($aksi, ['setuju', 'tolak'])) {
        $new_status = ($aksi === 'setuju') ? 'setuju' : 'tolak';
        $status_update = db_update(
            $koneksi,
            "UPDATE dokumen SET status=? WHERE id_dokumen=?",
            "ss",
            [$new_status, $id_dokumen]
        );

        if ($status_update !== false) {
            $_SESSION['notif'] = ($aksi === 'setuju') ? 'Pengajuan atas nama ' . $nama . ' disetujui.' : 'Pengajuan atas nama ' . $nama . ' ditolak.';
        } else {
            $_SESSION['notif'] = 'Terjadi kesalahan saat memperbarui status pengajuan.';
        }
    }
    header('Location: Dokumen_RT.php');
    exit();
}

$halaman_aktif = (isset($_GET['hal'])) ? (int) $_GET['hal'] : 1;
$limit = 10;
$offset = ($halaman_aktif - 1) * $limit;

$count_query = "SELECT COUNT(*) as total FROM dokumen d INNER JOIN user_warga w ON d.warga = w.nik_warga WHERE w.rt = ?";
$total_data = db_count($koneksi, $count_query, "s", [$id_rt]);

$query = db_select_no_assoc(
    $koneksi,
    "SELECT 
        d.id_dokumen,
        d.tanggal,
        d.warga,
        d.status,
        d.jenis_dokumen,
        w.nama_warga,
        TIMESTAMPDIFF(YEAR, w.tanggal_lahir, CURDATE()) AS usia,
        w.jenis_kelamin,
        w.hp,
        w.alamat,
        w.dokumen
    FROM dokumen d
    INNER JOIN user_warga w 
        ON d.warga = w.nik_warga
    WHERE w.rt = ?
    ORDER BY d.id_dokumen DESC
    LIMIT ? OFFSET ?",
    "sii",
    [$id_rt, $limit, $offset]
);


$notif = $_SESSION['notif'] ?? null;
unset($_SESSION['notif']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SITAWAR - Pengajuan Dokumen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Dokumen_RT.css">
    <link rel="stylesheet" href="../notif.css">
    <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

</head>

<body>
    <div class="notifikasi">
        <?php if ($notif): ?>
            <div id="notif" class="notif">
                <?= htmlspecialchars($notif) ?>
            </div>
        <?php endif; ?>
    </div>
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
                                <tr onclick='lihatDetail(<?= json_encode([
                                                                "nama" => htmlspecialchars($row['nama_warga']),
                                                                "usia" => htmlspecialchars($row['usia']),
                                                                "jenis_kelamin" => htmlspecialchars($row['jenis_kelamin']),
                                                                "kategori" => htmlspecialchars($row['jenis_dokumen']),
                                                                "alamat" => htmlspecialchars($row['alamat']),
                                                                "hp" => htmlspecialchars($row['hp']),
                                                                "tanggal" => htmlspecialchars($row['tanggal'])
                                                            ]); ?>)'>
                                    <!-- Nama, bisa diklik -->
                                    <td>
                                        <?= htmlspecialchars($row['nama_warga']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['tanggal']) ?></td>
                                    <td><?= htmlspecialchars($row['jenis_dokumen']) ?></td>
                                    <td>
                                        <?php if ($row['status'] === 'pending') { ?>
                                            <form method="post">
                                                <input type="hidden" name="nama_warga" value="<?= $row['nama_warga']; ?>">
                                                <input type="hidden" name="id_dokumen" value="<?= $row['id_dokumen']; ?>">
                                                <button type="submit" name="aksi" value="setuju" class="btn btn-sm" style="background-color: #788d51ff; color: #f5f5f5f5;" onclick="event.stopPropagation()">Setuju</button>
                                                <button type="submit" name="aksi" value="tolak" class="btn btn-sm" style="background-color: #ad2c2cff; color: #f5f5f5f5;" onclick="event.stopPropagation()">Tolak</button>
                                            </form>
                                        <?php } elseif ($row['status'] === 'setuju') { ?>
                                            <button class="tanda_setuju" disabled>Disetujui</button>
                                        <?php } elseif ($row['status'] === 'tolak') { ?>
                                            <button class="tanda_tolak" disabled>Ditolak</button>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>

                    <!-- PAGINATION LINKS -->
                    <div class="mt-3">
                        <?= db_pagination_links($total_data, $limit, $halaman_aktif, 'Dokumen_RT.php'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetail" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content matcha-modal">

                <!-- Header -->
                <div class="modal-header-matcha">
                    <div class="modal-title-wrapper">
                        <div class="modal-icon">
                            <!-- icon optional (SVG / FontAwesome) -->
                            <i class="bi bi-person"></i>
                        </div>
                        <h5 class="modal-title-matcha">Rincian Data Warga</h5>
                    </div>

                    <button type="button"
                        class="btn-close-matcha"
                        data-bs-dismiss="modal">
                        ✕
                    </button>
                </div>

                <!-- Body -->
                <div class="modal-body-matcha" id="detailBody">
                    <!-- isi detail-grid diinject via JS -->
                </div>

                <!-- Footer -->
                <div class="modal-footer-matcha">
                    <button class="btn-matcha-close" data-bs-dismiss="modal">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    </div>


    <?php if ($notif): ?>
        <script>
            // Hilangkan notifikasi otomatis setelah 4 detik
            setTimeout(() => {
                const notif = document.getElementById('notif');
                if (notif) {
                    notif.classList.add('hide');
                    setTimeout(() => notif.remove(), 500);
                }
            }, 4000);
        </script>
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fungsi untuk menampilkan detail warga
        function lihatDetail(data, event) {
            // Stop propagation jika dipanggil dari child element
            if (event) {
                event.stopPropagation();
            }
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