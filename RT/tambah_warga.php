<?php
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';

$nama     = trim($_POST['nama']);
$nik      = trim($_POST['nik']);
$keluarga = trim($_POST['keluarga']);
$sk_rt    = $_SESSION['user_rt']['sk_rt'];

if ($nama == "" || $nik == "" || $keluarga == "") {
    $_SESSION['notif']  = "Data tidak boleh kosong!";
    header("Location: Dashboard_RT.php");
    exit();
}

$rtData = db_select_single($koneksi, "SELECT no_rt, no_rw FROM user_rt WHERE sk_rt = ?", "s", [$sk_rt]);
if (!$rtData) {
    $_SESSION['notif'] = "Data RT tidak ditemukan!";
    header("Location: Dashboard_RT.php");
    exit();
}

$no_rt = $rtData['no_rt'];
$no_rw = $rtData['no_rw'];

$result_cek = db_select_no_assoc($koneksi, "SELECT nik_warga FROM user_warga WHERE nik_warga = ?", "s", [$nik]);
if (mysqli_num_rows($result_cek) > 0) {
    $_SESSION['notif'] = "NIK sudah terdaftar!";
    header("Location: Dashboard_RT.php");
    exit();
}
$password_hash = password_hash($nik, PASSWORD_DEFAULT);

$query = "INSERT INTO user_warga (nama_warga, nik_warga, keluarga, no_rt, no_rw, rt, password) 
VALUES (?, ?, ?, ?, ?, ?, ?)";
$insert_warga = db_insert(
    $koneksi,
    $query,
    "sssssss",
    [$nama, $nik, $keluarga, $no_rt, $no_rw, $sk_rt, $password_hash]
);

if ($insert_warga) {
    $_SESSION['notif'] = "Data warga berhasil disimpan";
} else {
    $_SESSION['notif'] = "Gagal menyimpan data!";
}
header("Location: Dashboard_RT.php");
exit();
