<?php
include "../koneksi.php";

$kk       = $_POST['no_kk1'];
$nik_baru = $_POST['nik_baru1'];

db_update(
    $koneksi,
    "UPDATE user_warga SET keluarga='kepala keluarga' WHERE no_kk=? AND nik_warga=?",
    "ss", [$kk, $nik_baru]);

$_SESSION['notif'] = "Kepala keluarga berhasil diubah.";
header("Location: kepala_keluarga.php");
exit();
?>