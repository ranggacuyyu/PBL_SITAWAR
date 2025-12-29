<?php 
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';

if (!isset($_SESSION['user_warga'])) {
    header("Location: sign-in_Warga.php");
    exit();
}

$id   = $_GET['id'];
$nik  = $_SESSION['user_warga']['nik_warga'];

$cek = db_select_no_assoc($koneksi, "SELECT d.*, w.jenis_dokumen 
FROM dokumen d
JOIN user_warga w ON d.warga=w.nik_warga
WHERE d.id_dokumen=? 
AND d.warga=?
AND d.status='valid'", "ss", [$id, $nik]);

if (mysqli_num_rows($cek) == 0) {
  die("Dokumen belum divalidasi atau tidak berhak mengakses");
}

?>