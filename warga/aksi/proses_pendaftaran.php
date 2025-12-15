<?php
session_start();
include "../../koneksi.php";

if (!isset($_SESSION['user_warga']['nik_warga'])) {
    header("Location: login_warga.php");
    exit();
}

$nik = $_SESSION['user_warga']['nik_warga'];

// Ambil POST
$kk            = mysqli_real_escape_string($koneksi, $_POST['Nokkinput']);
$tempat        = mysqli_real_escape_string($koneksi, $_POST['tempatlahir']);
$tanggal       = mysqli_real_escape_string($koneksi, $_POST['tanggallahir']);
$alamat        = mysqli_real_escape_string($koneksi, $_POST['alamatinput']);
$agama         = mysqli_real_escape_string($koneksi, $_POST['pilihan']);
$email         = mysqli_real_escape_string($koneksi, $_POST['emaill']);
$hp            = mysqli_real_escape_string($koneksi, $_POST['nohp']);
$jk            = mysqli_real_escape_string($koneksi, $_POST['jk']);
$pekerjaan     = mysqli_real_escape_string($koneksi, $_POST['inputpekerjaan']);
$kawin         = mysqli_real_escape_string($koneksi, $_POST['pilihkawin']);
$pendidikan    = mysqli_real_escape_string($koneksi, $_POST['pilihpendidikan']);
$kecamatan     = mysqli_real_escape_string($koneksi, $_POST['inputkecamatan']);
$kelurahan     = mysqli_real_escape_string($koneksi, $_POST['inputkelurahan']);

// Cek sudah_lengkap lagi (menghindari bypass)
$cek = mysqli_query($koneksi, "SELECT sudah_lengkap FROM user_warga WHERE nik_warga='$nik'");
$data = mysqli_fetch_assoc($cek);
if ((int)$data['sudah_lengkap'] === 1) {
    header("Location: ../ data_Warga.php");
    exit();
}

// Update data warga
$update = mysqli_query($koneksi, "UPDATE user_warga SET 
    no_kk           ='$kk',
    tempat_lahir    ='$tempat',
    tanggal_lahir   ='$tanggal',
    alamat          ='$alamat',
    agama           ='$agama',
    email           ='$email',
    hp              ='$hp',
    jenis_kelamin   ='$jk',
    pekerjaan       ='$pekerjaan',
    status_kawin    ='$kawin',
    pendidikan      ='$pendidikan',
    kecamatan       ='$kecamatan',
    kelurahan       ='$kelurahan',
    sudah_lengkap   =1
    WHERE nik_warga ='$nik'");

if (!$update) {
    die("Gagal update: " . mysqli_error($koneksi));
}

// Redirect ke halaman data warga
header("Location: ../data_Warga.php");
exit();
