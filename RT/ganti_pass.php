<?php
session_start();
include "../koneksi.php";
if (!isset($_SESSION['user_rt'])) {
    header('location:../LoginRTWARGA.php');
    exit();
}

$sk_rt = $_SESSION['user_rt']['sk_rt'];
if (isset($_POST['passwordsaatini'])) {
    $passini = $_POST['passwordsaatini'];
    $passbaru = $_POST['passwordbaru'];
    $passtes = $_POST['passwordtes'];
    $password_hash = password_hash($passbaru, PASSWORD_DEFAULT);

    $q = "SELECT password FROM user_rt WHERE sk_rt=? ";
    $stmt = mysqli_stmt_init($koneksi);
    if (!mysqli_stmt_prepare($stmt, $q)) {
        echo "error";
    } else {
        mysqli_stmt_bind_param($stmt, "s", $sk_rt);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $datartku = mysqli_fetch_assoc($result);
        $password_lama = $datartku['password'];
    }

    if (empty($passini) || empty($passbaru) || empty($passtes)) {
        $_SESSION['alert'] = "password tidak boleh diisi kosong";
        header("Location: Dashboard_RT.php");
        exit();
    } elseif (!password_verify($passini, $password_lama)) {
        $_SESSION['alert'] = "password verifikasi anda salah";
        header("Location: Dashboard_RT.php");
        exit();
    } elseif (($passbaru !== $passtes)) {
        $_SESSION['alert'] = "password baru harus di konfirmasi ulang dan pastikan sama dengan password baru";
        header("Location: Dashboard_RT.php");
        exit();
    } else {
        $update_password = "UPDATE user_rt SET password=? WHERE sk_rt=?";
        if (!mysqli_stmt_prepare($stmt, $update_password)) {
            echo "error";
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $password_hash, $sk_rt);
            mysqli_stmt_execute($stmt);
        }
        $_SESSION['alert'] = "password berhasil di UPDATE";
        header("Location: Dashboard_RT.php");
        exit();
    }
}
