<?php 
session_start();
include "../koneksi.php";

$nama     = $_POST['nama'];
$nik      = trim($_POST['nik']);
$keluarga = $_POST['keluarga'];

// Ambil SK RT dari session
$sk_rt = $_SESSION['user_rt']['sk_rt'];

// Ambil no_rt dan no_rw dari tabel user_rt
$rtQ = mysqli_query($koneksi, "SELECT no_rt, no_rw FROM user_rt WHERE sk_rt = '$sk_rt'");
$rtData = mysqli_fetch_assoc($rtQ);

$no_rt = $rtData['no_rt'];
$no_rw = $rtData['no_rw'];

// ✅ 1. CEK DUPLIKAT NIK
$cek = mysqli_query($koneksi, "SELECT nik_warga FROM user_warga WHERE nik_warga = '$nik'");
if (mysqli_num_rows($cek) > 0) {
    $_SESSION['notif']  = "NIK sudah terdaftar!";
    $_SESSION['status'] = "gagal";
    header("Location: Dashboard_RT.php");
    exit();
}

$password_hash = password_hash($nik, PASSWORD_DEFAULT);
// ✅ 2. INSERT JIKA TIDAK DUPLIKAT
$input = mysqli_query($koneksi,"INSERT INTO user_warga 
    (nama_warga, nik_warga, keluarga, no_rt, no_rw, rt, password)
    VALUES 
    ('$nama', '$nik', '$keluarga', '$no_rt', '$no_rw', '$sk_rt', '$password_hash')
");

if ($input ){
    $_SESSION['notif']  = "Data berhasil disimpan";
    $_SESSION['status'] = "sukses";
} else {
    $_SESSION['notif']  = "Gagal menyimpan data!";
    $_SESSION['status'] = "gagal";
}

header("Location: Dashboard_RT.php");
exit();
?>
