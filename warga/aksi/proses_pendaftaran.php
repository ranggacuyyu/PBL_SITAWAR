<?php
session_start();
require_once '../../koneksi.php';
require_once '../../db_helper.php';

if (!isset($_SESSION['user_warga']['nik_warga'])) {
    header("Location: ../login_warga.php");
    exit();
}

$nik = $_SESSION['user_warga']['nik_warga'];
$data = db_select_single($koneksi, "SELECT sudah_lengkap FROM user_warga WHERE nik_warga=?", "s", [$nik]);

if ((int)$data['sudah_lengkap'] === 1) {
    header("Location: ../data_Warga.php");
    exit();
}

$nik = $_SESSION['user_warga']['nik_warga'];
// Ambil POST
$kk            = trim($_POST['Nokkinput']);
$tempat        = trim($_POST['tempatlahir']);
$tanggal       = trim($_POST['tanggallahir']);
$alamat        = trim($_POST['alamatinput']);
$agama         = trim($_POST['pilihan']);
$email         = trim($_POST['emaill']);
$hp            = trim($_POST['nohp']);
$jk            = trim($_POST['jk']);
$pekerjaan     = trim($_POST['inputpekerjaan']);
$kawin         = trim($_POST['pilihkawin']);
$pendidikan    = trim($_POST['pilihpendidikan']);
$kecamatan     = trim($_POST['inputkecamatan']);
$kelurahan     = trim($_POST['inputkelurahan']);

// Update data warga
$update = db_update($koneksi, "UPDATE user_warga 
    SET 
    no_kk           =?,
    tempat_lahir    =?,
    tanggal_lahir   =?,
    alamat          =?,
    agama           =?,
    email           =?,
    hp              =?,
    jenis_kelamin   =?,
    pekerjaan       =?,
    status_kawin    =?,
    pendidikan      =?,
    kecamatan       =?,
    kelurahan       =?,
    sudah_lengkap   =1
    WHERE nik_warga =? ", "ssssssssssssss", [$kk, $tempat, $tanggal, $alamat, $agama, $email, $hp, $jk, $pekerjaan, $kawin, $pendidikan, $kecamatan, $kelurahan, $nik]);

// Redirect ke halaman data warga
header("Location: ../data_Warga.php");
exit();
