<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['user_warga']['nik_warga'])) {
    echo "Akses ditolak!";
    exit;
}

$nik = $_POST['nik'];
$hp = $_POST['hp'];
$email = $_POST['email'];
$pekerjaan = $_POST['pekerjaan'];
$password = $_POST['password'];

// Jika password diisi → update dengan hash
if (!empty($password)) {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $query = mysqli_query($koneksi, "
        UPDATE user_warga SET
        hp='$hp',
        email='$email',
        pekerjaan='$pekerjaan',
        password='$password_hash'
        WHERE nik_warga='$nik'
    ");
} else {
    $query = mysqli_query($koneksi, "
        UPDATE user_warga SET
        hp='$hp',
        email='$email',
        pekerjaan='$pekerjaan'
        WHERE nik_warga='$nik'
    ");
}

if ($query) {
    echo "Data berhasil diperbarui!";
} else {
    echo "Gagal mengubah data!";
}
