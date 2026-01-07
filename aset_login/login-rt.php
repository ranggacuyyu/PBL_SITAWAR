<?php
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sk        = trim($_POST['sk_rt']);
    $password  = trim($_POST['password']);

    if (empty($sk) ||  empty($password)) {
        $_SESSION['alertrt'] = "SK atau Password tidak boleh kosong";
        header("Location: ../LoginRTWARGA.php");
        exit();
    }

    $user = db_select_single($koneksi, "SELECT * FROM user_rt WHERE sk_rt = ?", "s", [$sk]);
    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['alertrt'] = "SK atau Password salah";
        header("Location: ../LoginRTWARGA.php");
        exit();
    }
    $_SESSION["login"] = "Selamat Datang";
    $_SESSION['user_rt'] = [
        'sk_rt' => $user['sk_rt'],
        'no_rt' => $user['no_rt'],
        'no_rw' => $user['no_rw'],
        'nama_rt' => $user['nama_rt']
    ];
    session_regenerate_id(true);
    header("Location: ../RT/Dashboard_RT.php");
    exit;
}
