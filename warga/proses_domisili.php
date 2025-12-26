<?php
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';

if (!isset($_SESSION['user_warga'])) {
    header("Location: sign-in_Warga.php");
    exit();
}
$nik = $_SESSION['user_warga']['nik_warga'];
db_update($koneksi,  "UPDATE user_warga SET dokumen='domisili' WHERE nik_warga=?", "s", [$nik]);
db_insert($koneksi, "INSERT INTO dokumen (warga, tanggal, status, jenis_dokumen) VALUES (?, CURDATE(), 'pending', 'domisili')", "s", [$nik]);
header("Location: dokumen_Warga.php?status=sukses");
exit();
