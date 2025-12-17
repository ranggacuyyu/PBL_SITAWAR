<?php
session_start();
include "../koneksi.php";

$nama = trim($_POST['nama']);
$nik = trim($_POST['nik']);
$keluarga = trim($_POST['keluarga']);

if ($nama == "" || $nik == "" || $keluarga == "") {
    $_SESSION['notif'] = "Data tidak boleh kosong!";
    $_SESSION['status'] = "gagal";
    header("Location: Dashboard_RT.php");
    exit();
}

// Ambil SK RT dari session
$sk_rt = $_SESSION['user_rt']['sk_rt'];

// Ambil no_rt dan no_rw dari tabel user_rt
$rtQ = "SELECT no_rt, no_rw FROM user_rt WHERE sk_rt = ?";
$stmt = mysqli_stmt_init($koneksi);
if (!mysqli_stmt_prepare($stmt, $rtQ)) {
    $_SESSION['notif'] = "Gagal mengambil data RT!";
    $_SESSION['status'] = "gagal";
    header("Location: Dashboard_RT.php");
    exit();
} else {
    mysqli_stmt_bind_param($stmt, "s", $sk_rt);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rtData = mysqli_fetch_assoc($result);

    if (!$rtData) {
        $_SESSION['notif'] = "Data RT tidak ditemukan!";
        $_SESSION['status'] = "gagal";
        header("Location: Dashboard_RT.php");
        exit();
    }
}


$no_rt = $rtData['no_rt'];
$no_rw = $rtData['no_rw'];

// ✅ 1. CEK DUPLIKAT NIK
$cek = "SELECT nik_warga FROM user_warga WHERE nik_warga = ?";
if (mysqli_stmt_prepare($stmt, $cek)) {
    mysqli_stmt_bind_param($stmt, "s", $nik);
    mysqli_stmt_execute($stmt);
    $result_cek = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result_cek) > 0) {
        $_SESSION['notif'] = "NIK sudah terdaftar!";
        $_SESSION['status'] = "gagal";
        header("Location: Dashboard_RT.php");
        exit();
    }
}

$password_hash = password_hash($nik, PASSWORD_DEFAULT);
// ✅ 2. INSERT JIKA TIDAK DUPLIKAT
$input = "INSERT INTO user_warga 
    (nama_warga, nik_warga, keluarga, no_rt, no_rw, rt, password)
    VALUES (?,?,?,?,?,?,?)";
if (!mysqli_stmt_prepare($stmt, $input)) {
    $_SESSION['notif'] = "Gagal input data warga!";
    $_SESSION['status'] = "gagal";
    header("Location: Dashboard_RT.php");
    exit();
} else {
    mysqli_stmt_bind_param($stmt, "sssssss", $nama, $nik, $keluarga, $no_rt, $no_rw, $sk_rt, $password_hash);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['notif'] = "Data warga berhasil disimpan";
        $_SESSION['status'] = "sukses";
    } else {
        $_SESSION['notif'] = "Gagal menyimpan data!";
        $_SESSION['status'] = "gagal";
    }
}

header("Location: Dashboard_RT.php");
exit();
?>