<?php
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';

if (!isset($_SESSION['user_warga']['nik_warga'])) {
    echo "Akses ditolak!";
    exit;
}

$nik       = $_POST['nik'];
$hp        = $_POST['hp'];
$email     = $_POST['email'];
$pekerjaan = $_POST['pekerjaan'];
$password  = $_POST['password'];


if (!ctype_digit($hp)) {
    $_SESSION['notif'] = "Nomor HP harus berupa angka.";
    header("Location: data_Warga.php");
    exit;
}

if (strlen($hp) <= 10 || strlen($hp) >= 13) {
    $_SESSION['notif'] = "Nomor HP harus antara 11-12 digit.";
    header("Location: data_Warga.php");
    exit;
}

if (!empty($password)) {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE user_warga SET hp=?, email=?, pekerjaan=?, password=? WHERE nik_warga=?";
    $result = db_update($koneksi, $query, "sssss", [$hp, $email, $pekerjaan,$password_hash, $nik]);
    $_SESSION['notif'] = "Password dan data diri berhasil diubah, pastikan mengingat password baru Anda.";
    header("Location: data_Warga.php");
    exit;
} else {
    $query = "UPDATE user_warga SET hp=?, email=?, pekerjaan=? WHERE nik_warga=?";
    $result = db_update($koneksi, $query, "ssss", [$hp, $email, $pekerjaan, $nik]);
    $_SESSION['notif'] = "Data diri berhasil diubah.";
    header("Location: data_Warga.php");
    exit;
}