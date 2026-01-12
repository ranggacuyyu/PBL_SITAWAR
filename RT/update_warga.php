<?php
session_start();
include "../koneksi.php";

// password RT disimpan di session (sesuaikan)
$passwordBenar = $_SESSION['rt_password'];

$nik = $_POST['nik'];
$kolom = $_POST['kolom'];
$nilaiBaru = $_POST['nilai'];
$password = $_POST['password'];

// Cek password
if ($password !== $passwordBenar) {
    echo "wrong_password";
    exit;
}

// daftar kolom yang boleh diubah
$allowed_columns = [
    "nama_warga",
    "nik_warga",
    "tanggal_lahir",
    "tempat_lahir",
    "agama",
    "keluarga",
    "jenis_kelamin",
    "no_kk",
    "alamat",
    "pekerjaan",
    "pendidikan",
    "status_kawin"
];

if (!in_array($kolom, $allowed_columns)) {
    echo "invalid_column";
    exit;
}

$sql = "UPDATE user_warga SET $kolom = '$nilaiBaru' WHERE nik_warga = '$nik'";
$q = mysqli_query($koneksi, $sql);

if ($q) {
    echo "success";
} else {
    echo "error";
}
