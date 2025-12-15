<?php 
session_start();
include '../koneksi.php';
if (!isset($_SESSION['user_warga'])) {
    header("Location: sign-in_Warga.php");
    exit();
}

$id = $_GET['id'];
$nik = $_SESSION['user_warga']['nik_warga'];

$cek = mysqli_query($koneksi, "SELECT d.*, w.jenis_dokumen 
FROM dokumen d
JOIN user_warga w ON d.warga=w.nik_warga
WHERE d.id_dokumen='$id' 
AND d.warga='$nik'
AND d.status='valid'
");

if (mysqli_num_rows($cek) == 0) {
  die("Dokumen belum divalidasi atau tidak berhak mengakses");
}

?>