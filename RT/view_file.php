<?php
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';

// ===============================
// 1. WAJIB LOGIN RT
// ===============================
if (!isset($_SESSION['user_rt'])) {
    exit("Akses ditolak");
}

// ===============================
// 2. VALIDASI PARAMETER
// ===============================
if (!isset($_GET['id']) || !isset($_GET['type'])) {
    exit("Parameter tidak valid");
}

$id   = intval($_GET['id']);
$type = ($_GET['type'] === 'kk') ? 'foto_kk' : 'foto_ktp';

// ===============================
// 3. AMBIL DATA DARI DB
// ===============================
$data = db_select_single($koneksi, "SELECT foto_kk, foto_ktp FROM dokumen_wargart WHERE id_dokumen=?", "i", [$id]);
if (!$data || empty($data[$type])) {
    exit("File tidak ditemukan");
}

// ===============================
// 4. PATH AMAN
// ===============================
$folder = ($type == 'foto_kk') ? "../uploads/kk/" : "../uploads/ktp/";
$file = basename($data[$type]);
$path = $folder . $file;

if (!file_exists($path)) {
    exit("File tidak tersedia");
}

// ===============================
// 5. TAMPILKAN GAMBAR
// ===============================
header("Content-Type: image/jpeg");
header("Content-Disposition: inline");
readfile($path);
exit();
