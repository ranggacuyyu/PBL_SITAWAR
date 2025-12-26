<?php
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';

// Validasi session login
if (!isset($_SESSION['user_rt'])) {
    header('Location: ../LoginRTWARGA.php');
    exit();
}

// Ambil ID RT dari session
$id_rt = $_SESSION['user_rt']['sk_rt'];

// Proses persetujuan atau penolakan
if (isset($_GET['aksi'], $_GET['id'])) {
    $aksi = $_GET['aksi'];
    $id_dokumen = filter_var($_GET['id'], FILTER_SANITIZE_STRING);

    // Validasi aksi yang diizinkan
    if (in_array($aksi, ['setuju', 'tolak'])) {
        $new_status = ($aksi === 'setuju') ? 'setuju' : 'tolak';
        $status_update = db_update(
            $koneksi,
            "UPDATE dokumen SET status=? WHERE id_dokumen=?",
            "ss",
            [$new_status, $id_dokumen]
        );

        // Set flash message menggunakan session
        if ($status_update !== false) {
            $_SESSION['flash_message'] = [
                'type' => 'success',
                'text' => 'Persetujuan berhasil diproses!'
            ];
        } else {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'text' => 'Persetujuan gagal diproses!'
            ];
        }
    }

    // Redirect kembali ke halaman dokumen
    header('Location: Dokumen_RT.php');
    exit();
}

// Konfigurasi Pagination
$halaman_aktif = (isset($_GET['hal'])) ? (int) $_GET['hal'] : 1;
$limit = 10; // Jumlah data per halaman
$offset = ($halaman_aktif - 1) * $limit;

// Hitung total data untuk pagination
$count_query = "SELECT COUNT(*) as total FROM dokumen d INNER JOIN user_warga w ON d.warga = w.nik_warga WHERE w.rt = ?";
$total_data = db_count($koneksi, $count_query, "s", [$id_rt]);

// Ambil data pengajuan dengan LIMIT dan OFFSET
$query = db_select_no_assoc(
    $koneksi,
    "SELECT 
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
    WHERE w.rt = ?
    ORDER BY d.id_dokumen DESC
    LIMIT ? OFFSET ?",
    "sii",
    [$id_rt, $limit, $offset]
);

// Ambil flash message jika ada
$flash_message = null;
if (isset($_SESSION['flash_message'])) {
    $flash_message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}
?>
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

                <!-- Flash Message -->
                <?php if ($flash_message): ?>
                    <div class="alert alert-<?= $flash_message['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show"
                        role="alert" id="flashMessage">
                        <strong><?= $flash_message['type'] === 'success' ? '✓' : '✗' ?></strong>
                        <?= htmlspecialchars($flash_message['text']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

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
                                    "kategori" => htmlspecialchars($row['dokumen']),
                                    "alamat" => htmlspecialchars($row['alamat']),
                                    "hp" => htmlspecialchars($row['hp']),
                                    "tanggal" => htmlspecialchars($row['tanggal'])
                                ]); ?>)'>
                                    <!-- Nama, bisa diklik -->
                                    <td>
                                        <?= htmlspecialchars($row['nama_warga']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['tanggal']) ?></td>
                                    <td><?= htmlspecialchars($row['dokumen']) ?></td>
                                    <td>
                                        <?php if ($row['status'] === 'pending') { ?>
                                            <a href="Dokumen_RT.php?aksi=setuju&id=<?= $row['id_dokumen']; ?>"
                                                class="btn btn-sm btn-success" onclick="event.stopPropagation()">
                                                Setuju
                                            </a>
                                            <a href="Dokumen_RT.php?aksi=tolak&id=<?= $row['id_dokumen']; ?>"
                                                class="btn btn-sm btn-danger" onclick="event.stopPropagation()">
                                                Tolak
                                            </a>
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


    <!-- MODAL DETAIL (Dipindahkan keluar agar tidak kena efek transform/z-index parent) -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SCRIPT MODAL -->
    <script>
        // Auto-hide flash message setelah 3 detik
        const flashMessage = document.getElementById('flashMessage');
        if (flashMessage) {
            setTimeout(() => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(flashMessage);
                bsAlert.close();
            }, 3000);
        }

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