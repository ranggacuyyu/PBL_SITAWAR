<?php
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nik      = trim($_POST['nama']);
    $password = trim($_POST['sandi']);

    if (empty($nik) || empty($password)) {
        $_SESSION['alert'] = "NIK dan Password tidak boleh kosong";
        header("Location: ../LoginRTWARGA.php");
        exit();
    }

    $user = db_select_single(
        $koneksi,
        "SELECT nik_warga, password FROM user_warga WHERE nik_warga = ?","s",[$nik]
    );


    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['alert'] = "NIK atau Password salah";
        header("Location: ../LoginRTWARGA.php");
        exit();
    }

    session_regenerate_id(true);

    $_SESSION['user_warga'] = [
        'nik_warga' => $user['nik_warga']
    ];

    header("Location: ../warga/sign-in_Warga.php");
    exit();
}
