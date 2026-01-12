<?php
session_start();
require_once "../koneksi.php";
require_once "../db_helper.php";

if (!isset($_SESSION['user_rt'])) {
    header("Location: ../LoginRTWARGA.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password_wafat = $_POST['password_wafat'];
    $no_kk          = $_POST['no_kk'];
    $nik            = $_POST['nik_wafat'];
    $nik_baru       = $_POST['nik_baru'];

    $query = "SELECT password FROM user_rt WHERE sk_rt = ?";
    $rt_data = db_select_single($koneksi, $query, "s", [$_SESSION['user_rt']['sk_rt']]);
    
    if ($rt_data && password_verify($password_wafat, $rt_data['password'])) {
        // Update status kepala keluarga menjadi wafat
        $update_query = "UPDATE user_warga SET keluarga = 'wafat' WHERE no_kk = ? and nik_warga =?";
        db_update($koneksi, $update_query, "ss", [$no_kk, $nik]);
        // Update kepala keluarga baru
        $update_baru_query = "UPDATE user_warga SET keluarga = 'kepala keluarga' WHERE no_kk = ? and nik_warga =?";
        db_update($koneksi, $update_baru_query, "ss", [$no_kk, $nik_baru]);
        $nama_baru = db_select_single($koneksi, "SELECT nama_warga FROM user_warga WHERE nik_warga = ?", "s", [$nik_baru])['nama_warga'];
        $_SESSION['notif'] = "Turut berduka cita. Kepala keluarga telah diperbarui menjadi Bpk/ibu" . $nama_baru . ".";
        header("Location: kepala_keluarga.php");
        exit;
    } else {
        $_SESSION['notif'] = "Password RT salah. Silakan coba lagi.";
        header("Location: kepala_keluarga.php");
        exit;
    }
}
?>