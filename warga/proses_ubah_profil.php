<?php
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';

if (!isset($_SESSION['user_warga']['nik_warga'])) {
    echo "Akses ditolak!";
    exit;
}

$nik = $_POST['nik'];
$hp = $_POST['hp'];
$email = $_POST['email'];
$pekerjaan = $_POST['pekerjaan'];
$password = $_POST['password'];

if (!ctype_digit($hp)) {
    echo "Nomor HP hanya boleh angka";
    exit;
}

if (strlen($hp) <= 10 || strlen($hp) >= 13) {
    echo "Nomor HP harus terdiri dari 10 sampai 13 digit";
    exit;
}

if (!empty($password)) {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE user_warga SET hp=?, email=?, pekerjaan=?, password=? WHERE nik_warga=?";
    $result = db_update($koneksi, $query, "sssss", [$hp, $email, $pekerjaan,$password_hash, $nik]);
} else {
    $query = "UPDATE user_warga SET hp=?, email=?, pekerjaan=? WHERE nik_warga=?";
    $result = db_update($koneksi, $query, "ssss", [$hp, $email, $pekerjaan, $nik]);
}

if ($result) {
    echo "Data berhasil diperbarui!";
} else {
    echo "Gagal mengubah data!";
}
