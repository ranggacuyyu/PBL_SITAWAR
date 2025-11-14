<?php
session_start();
include "koneksi.php";

// Ambil data dari POST
$username = $_POST['username'];
$password = $_POST['password'];

// Cek apakah username ada di database
$query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username'");
$data  = mysqli_fetch_assoc($query);

// Jika username tidak ditemukan
if ($data == null) {
    echo "user_tidak_ada";
    exit;
}

// Jika password tidak sama
if ($password !== $data['password']) {
    echo "password_salah";
    exit;
}

// Jika login sukses
$_SESSION['admin'] = $data['username'];
echo "sukses";
exit;
?>
