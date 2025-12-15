<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['user_rt'])) {
    exit("Akses ditolak");
}

if (!isset($_GET['id']) || !isset($_GET['type'])) {
    exit("Parameter tidak valid");
}

$id   = intval($_GET['id']);
$type = ($_GET['type'] === 'kk') ? 'foto_kk' : 'foto_ktp';

$stmt = $koneksi->prepare("SELECT foto_kk, foto_ktp FROM dokumen_wargart WHERE id_dokumen=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data || empty($data[$type])) {
    exit("File tidak ditemukan");
}

$folder = ($type == 'foto_kk') ? "../uploads/kk/" : "../uploads/ktp/";
$file = basename($data[$type]);
$path = $folder . $file;

if (!file_exists($path)) exit("File tidak tersedia");

header("Content-Disposition: attachment; filename=".$file);
header("Content-Type: application/octet-stream");
readfile($path);
exit();
