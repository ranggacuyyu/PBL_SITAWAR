<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['user_warga'])) {
    header("Location: sign-in_Warga.php");
    exit();
}
$nik = $_SESSION['user_warga']['nik_warga'];

mysqli_query($koneksi, "UPDATE user_warga 
SET dokumen='domisili'
WHERE nik_warga='$nik'
");

mysqli_query($koneksi, "
INSERT INTO dokumen (warga, tanggal, status)
VALUES ('$nik', CURDATE(), 'pending')
");
header("Location: dokumen_Warga.php?status=sukses");
exit();
