<?php
session_start();
require_once "../koneksi.php";
require_once "../db_helper.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $kk       = $_POST['no_kk1'] ?? '';
    $nik_baru = $_POST['nik_baru1'] ?? '';

    if (empty($kk) || empty($nik_baru)) {
        $_SESSION['notif'] = "Data tidak lengkap.";
        header("Location: kepala_keluarga.php");
        exit();
    }

    // Reset kepala keluarga lama
    db_update(
        $koneksi,
        "UPDATE user_warga SET keluarga='anggota keluarga' WHERE no_kk=? AND keluarga='kepala keluarga'",
        "s",
        [$kk]
    );

    // Set kepala keluarga baru
    db_update(
        $koneksi,
        "UPDATE user_warga SET keluarga='kepala keluarga' WHERE no_kk=? AND nik_warga=?",
        "ss",
        [$kk, $nik_baru]
    );

    $_SESSION['notif'] = "Kepala keluarga berhasil diubah.";
    header("Location: kepala_keluarga.php");
    exit();

} else {
    echo "Akses tidak valid.";
}
?>
