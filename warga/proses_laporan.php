<?php
session_start();
include "../koneksi.php";
// Cek apakah user sudah login
if (!isset($_SESSION['user_warga'])) {
    header("Location: sign-in_Warga.php");
    exit();
}
$nik = $_SESSION['user_warga']['nik_warga'];
$nama_pelap = $_POST['nama_pelapor'];
$nohp_pelapor = $_POST['nohp_pelapor']; 
$blok_pelapor = $_POST['blok_pelapor'];

if (empty($nama_subjek1) || empty($umur_subjek1) || empty($blok_subjek1)) {
    $nama = $_POST['nama_subjek'];
    $umur = $_POST['umur_subjek'];
    $blok = $_POST['blok_subjek'];
    $tanggal = $_POST['tanggal_meninggal'];
    $jenis = 'warga-meninggal';
} else {
    $nama = $_POST['nama_subjek1'];
    $umur = $_POST['umur_subjek1'];
    $blok = $_POST['blok_subjek1'];
    $jenis = 'ibu-hamil';
}

mysqli_query($koneksi, "INSERT INTO laporan (nik_pelapor, nama_pelapor, nohp_pelapor, blok_pelapor, jenis_laporan, nama_subjek, umur_subjek, blok_subjek, tanggal_meninggal) 
VALUES ('$nik', '$nama_pelap', '$nohp_pelapor', '$blok_pelapor', '$jenis', '$nama', '$umur', '$blok', '$tanggal')");
//  Redirect kembali ke halaman laporan warga setelah proses selesai            



header("Location: laporan_Warga.php?status=sukses");
exit();
