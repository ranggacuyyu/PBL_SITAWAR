<?php
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';

if (!isset($_SESSION['user_warga']) && !isset($_SESSION['user_rt'])) {
    http_response_code(403);
    exit;
}

if (!isset($_GET['id'], $_GET['type'])) {
    http_response_code(400);
    exit;
}

$id = intval($_GET['id']);
$type = ($_GET['type'] === 'kk') ? 'foto_kk' : 'foto_ktp';

$data = db_select_single(
    $koneksi,
    "SELECT foto_kk, foto_ktp FROM dokumen_wargart WHERE id_dokumen=?",
    "i",
    [$id]
);

if (!$data || empty($data[$type])) {
    http_response_code(404);
    exit;
}

$base = dirname(__DIR__) . '/uploads/';
$path = $base . (($type === 'foto_kk') ? "kk/" : "ktp/") . basename($data[$type]);

if (!file_exists($path)) {
    http_response_code(404);
    exit;
}

$mime = mime_content_type($path);
header("Content-Type: $mime");
header("Content-Length: " . filesize($path));
readfile($path);
exit;
