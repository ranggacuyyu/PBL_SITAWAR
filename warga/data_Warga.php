<?php
session_start();
// Pastikan file koneksi.php berada di folder yang sama
include 'koneksi.php'; 

// Cek status login
if (!isset($_SESSION['nik_warga'])) {
    header("Location: sign-in_Warga.php");
    exit();
}

$nik_warga_login = $_SESSION['nik_warga'];

// Query SELECT data warga
$query = "SELECT * FROM user_warga WHERE nik_warga = '$nik_warga_login'";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) > 0) {
    $data_warga = mysqli_fetch_assoc($result); 
} else {
    // Jika data tidak ditemukan di DB
    $data_warga = null; 
}

mysqli_close($koneksi);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pribadi - SITAWAR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="data_Warga.css">
</head>

<body>
    <header class="head">
        <h1 class="logo">SITAWAR</h1>
        <div class="waktu-uuu">
            <span id="clock"></span>
            <button class="btn btn-success my-3" id="btnUbah">Ubah Data Diri</button>
        </div>
        <div class="modal fade" id="ubahModal" tabindex="-1" aria-labelledby="ubahModalLabel" aria-hidden="true">
            </div>
    </header>
    
    <nav class="navbar">
        <div class="navigasi-navbar">
            <div class="list-navbar">
                <a href="#">
                    <h2>Data Pribadi</h2>
                </a>
                <a href="riwayatmilikwarga.html">
                    <h2>Dokumen</h2>
                </a>
                <a href="Data Ibu Hamil.html">
                    <h2>Laporan</h2>
                </a>
            </div>
        </div>

        <div class="content">
            <div class="card">
                <h1>👤</h1>
                <h2><?php echo htmlspecialchars($data_warga['nama_warga'] ?? 'Nama Profile'); ?></h2> 
                <hr>
            </div>

            <div class="card2">
                <h2>DATA ANDA</h2>
                <div class="table-wrapper">
                    <table>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>
                                <p id="tampilNama"><?php echo htmlspecialchars($data_warga['nama_warga'] ?? 'N/A'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>NIK</td>
                            <td>:</td>
                            <td>
                                <p id="tampilNIK"><?php echo htmlspecialchars($data_warga['nik_warga'] ?? 'N/A'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>No KK</td>
                            <td>:</td>
                            <td>
                                <p id="tampilNokk"><?php echo htmlspecialchars($data_warga['no-kk'] ?? 'N/A'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>Tempat/Tanggal Lahir</td>
                            <td>:</td>
                            <td>
                                <p id="tampiltanggallahir"><?php echo htmlspecialchars($data_warga['tanggal_lahir'] ?? 'N/A'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>:</td>
                            <td>
                                <p id="tampilkelamin"><?php echo htmlspecialchars($data_warga['jenis_kelamin'] ?? 'N/A'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>Agama</td>
                            <td>:</td>
                            <td>
                                <p id="tampilagama"><?php echo htmlspecialchars($data_warga['agama'] ?? 'N/A'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>Status Perkawinan</td>
                            <td>:</td>
                            <td>
                                <p id="tampilkawinn"><?php echo htmlspecialchars($data_warga['status_kawin'] ?? 'N/A'); ?></p>
                            </td>
                        </tr>
                        
                        <tr>
                            <td>Pendidikan Terakhir</td>
                            <td>:</td>
                            <td>
                                <p id="tampilpendidikan">N/A (Tidak Tersimpan di DB)</p>
                            </td>
                        </tr>
                        <tr>
                            <td>Pekerjaan</td>
                            <td>:</td>
                            <td>
                                <p id="tampilkerja">N/A (Tidak Tersimpan di DB)</p>
                            </td>
                        </tr>
                        <tr>
                            <td>No Telepon</td>
                            <td>:</td>
                            <td>
                                <p id="tampilnomor">N/A (Tidak Tersimpan di DB)</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <td>Domisili</td>
                            <td>:</td>
                            <td>
                                <p id="tampildomisili"><?php echo htmlspecialchars($data_warga['domisili'] ?? 'N/A'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>Alamat Rumah</td>
                            <td>:</td>
                            <td>
                                <p id="tampilalamat"><?php echo htmlspecialchars($data_warga['domisili'] ?? 'N/A'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>RT dan RW</td>
                            <td>:</td>
                            <td>
                                <p id="tampilRTRW"><?php echo htmlspecialchars($data_warga['rt'] ?? 'N/A'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // ... (Kode JavaScript untuk Modal Anda di sini) ...
        });
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').textContent =
                now.toLocaleTimeString('id-ID', { hour12: false });
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>

</html>