<?php
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';

if (!isset($_SESSION['user_rt'])) {
    header('location:../LoginRTWARGA.php');
    exit();
}

$sk_rt = $_SESSION['user_rt']['sk_rt'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $passini       = $_POST['passwordsaatini'];
    $passbaru      = $_POST['passwordbaru'];
    $passtes       = $_POST['passwordtes'];
    $password_hash = password_hash($passbaru, PASSWORD_DEFAULT);

    $datartku = db_select_single($koneksi, "SELECT password FROM user_rt WHERE sk_rt=? ", "s", [$sk_rt]);
    $password_lama = $datartku['password'];

    if (empty($passini) || empty($passbaru) || empty($passtes) || $passini == "" || $passbaru == "" || $passtes == "") {
        $_SESSION['notif'] = "password wajib di isi";
        header("Location: Dashboard_RT.php");
        exit();
    } elseif (!password_verify($passini, $password_lama)) {
        $_SESSION['notif'] = "Pastikan password anda saat ini sebagai verifikasi benar";
        header("Location: Dashboard_RT.php");
        exit();
    } elseif (($passbaru !== $passtes)) {
        $_SESSION['notif'] = "password baru harus di konfirmasi ulang dan pastikan sama dengan password baru";
        header("Location: Dashboard_RT.php");
        exit();
    } else {
        db_update($koneksi, "UPDATE user_rt SET password=? WHERE sk_rt=?", "ss", [$password_hash, $sk_rt]);
        $_SESSION['notif'] = "password anda berhasil di perbarui";
        header("Location: Dashboard_RT.php");
        exit();
    }
}
