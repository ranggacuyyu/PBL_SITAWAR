<?php
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';
if (!isset($_SESSION['user_rt'])) {
    header('location:../LoginRTWARGA.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Laporan_RT.css?v=<?php echo time(); ?>">
    <style>
        .sidebar {
            width: 250px;
            background-color: #8da070;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
            height: 100%;
            left: 0;
            top: 0;
        }

        .sidebar-header {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar-header h1 {
            font-size: 24px;
            margin-bottom: 4px;
        }

        .sidebar-header p {
            font-size: 12px;
            color: #4b5c30;
            font-weight: bold;
        }

        .nav {
            padding: 20px;
            width: auto;
        }

        .nav a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: background 0.2s;
            font-size: 1.35em;
        }

        .nav a:hover {
            background-color: #6b7a59;
        }

        .sidebar-footer {
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #ffffff;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
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
</head>

<body>
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
        <div class="sidebar-footer">© 2025 RT Smart System</div>
    </aside>

    <div class="main-content content-animate">
        <div class="panduan">
            <b>Panduan:</b>
            Informasi Menu Laporan Warga <br>
            Halaman ini menampilkan daftar laporan yang dikirim oleh warga, seperti laporan warga meninggal dunia dan
            warga melahirkan.
            Data yang ditampilkan berupa identitas pelapor, jenis laporan, serta tanggal laporan.
            Untuk melihat detail lengkap dari laporan, silakan klik tombol “Cek Detail” pada kolom aksi.
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Blok Rumah</th>
                    <th>No Hp</th>
                    <th>Nik</th>
                    <th>Periksa</th>
                </tr>
            </thead>
            <tbody id="kolom">
                <?php
                $id_rt = $_SESSION['user_rt']['sk_rt'];

                // Konfigurasi Pagination
                $halaman_aktif = (isset($_GET['hal'])) ? (int) $_GET['hal'] : 1;
                $limit = 10;
                $offset = ($halaman_aktif - 1) * $limit;

                // Hitung total data
                $count_query = "SELECT COUNT(*) FROM laporan JOIN user_warga ON laporan.nik_pelapor = user_warga.nik_warga WHERE user_warga.rt = ?";
                $total_data = db_count($koneksi, $count_query, "s", [$id_rt]);

                // Query Data dengan Limit & Offset
                $query_str = "SELECT laporan.*  FROM laporan JOIN user_warga ON laporan.nik_pelapor = user_warga.nik_warga WHERE user_warga.rt = ? LIMIT ? OFFSET ?";
                $query = db_select_no_assoc($koneksi, $query_str, "sii", [$id_rt, $limit, $offset]);

                $no = $offset + 1;

                if ($query) {
                    while ($data = mysqli_fetch_assoc($query)) {
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($data['nama_pelapor']); ?></td>
                            <td><?= htmlspecialchars($data['blok_pelapor']); ?></td>
                            <td><?= htmlspecialchars($data['nohp_pelapor']); ?></td>
                            <td><?= htmlspecialchars($data['nik_pelapor']); ?></td>
                            <td>
                                <button onclick="lihatDetail(
                                    '<?= htmlspecialchars($data['nama_subjek']); ?>',
                                    '<?= htmlspecialchars($data['nohp_pelapor']); ?>',
                                    '<?= htmlspecialchars($data['blok_subjek']); ?>',
                                    '<?= htmlspecialchars($data['umur_subjek']); ?>',
                                    '<?= htmlspecialchars($data['jenis_laporan']); ?>'
                                )">
                                    Cek Terlapor
                                </button>
                            </td>
                        </tr>
                    <?php }
                } ?>
            </tbody>
        </table>

        <!-- PAGINATION LINKS -->
        <div style="margin-top: 20px;">
            <?php
            // Kita perlu include db_helper jika belum ada fungsi pagination di global scope
            // Asumsi db_helper.php sudah di-include via koneksi.php atau harus manual
            if (function_exists('db_pagination_links')) {
                echo db_pagination_links($total_data, $limit, $halaman_aktif, 'Laporan_ RT.php');
            }
            ?>
        </div>
    </div>

    <div class="popup" id="popup">
        <div class="popup-content">
            <h3>Rincian Laporan</h3>
            <p><b>JENIS LAPORAN</b> <span id="popupjenis"></span></p>
            <p><b>Nama Pelapor:</b> <span id="popupNama"></span></p>
            <p><b>No. Telepon:</b> <span id="popupTelepon"></span></p>
            <p><b>Blok Rumah yang Dilapor:</b> <span id="popupBlok"></span></p>
            <p><b>Usia yang Dilapor:</b> <span id="popupUsia"></span></p>
            <button class="close-btn" onclick="tutupPopup()">Tutup</button>
        </div>
    </div>


    <script>
        function lihatDetail(nama_subjek, telepon, blok, usia, jenis_laporan) {
            document.getElementById("popupNama").textContent = nama_subjek;
            document.getElementById("popupTelepon").textContent = telepon;
            document.getElementById("popupBlok").textContent = blok;
            document.getElementById("popupUsia").textContent = usia;
            document.getElementById("popupjenis").textContent = jenis_laporan;

            document.getElementById("popup").style.display = "flex";
        }

        function tutupPopup() {
            document.getElementById("popup").style.display = "none";
        }
    </script>
</body>

</html>