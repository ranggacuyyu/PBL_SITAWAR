<?php
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';

if (!isset($_SESSION['user_warga'])) {
    header("Location: sign-in_Warga.php");
    exit();
}
$nik   = $_SESSION['user_warga']['nik_warga'];
$kk    = db_select_single($koneksi, "SELECT no_kk FROM user_warga WHERE nik_warga = ?", "s", [$nik]);
$no_kk = $kk['no_kk'];

$nama_pelap   = $_POST['nama_pelapor'];
$nohp_pelapor = $_POST['nohp_pelapor'];
$blok_pelapor = $_POST['blok_pelapor'];

if (empty($_POST['nama_subjek1']) || empty($_POST['umur_subjek1']) || empty($_POST['blok_subjek1'])) {
    $nama    = $_POST['nama_subjek'];
    $umur    = $_POST['umur_subjek'];
    $blok    = $_POST['blok_subjek'];
    $tanggal = $_POST['tanggal_meninggal'];
    $jenis   = 'warga-meninggal';

    if (empty($nama) || empty($umur) || empty($blok) || empty($tanggal)) {
        $_SESSION['error'] = "Semua field wajib diisi untuk laporan warga meninggal.";
        header("Location: laporan_Warga.php?status=gagal");
        exit();
    } elseif ($nama === "" || $umur === "" || $blok === "" || $tanggal === "") {
        $_SESSION['error'] = "Semua field wajib diisi untuk laporan warga meninggal.";
        header("Location: laporan_Warga.php?status=gagal");
        exit();
    } else {
        $query_cek_data = "SELECT * FROM laporan WHERE nik_pelapor = ? AND nama_subjek = ? AND jenis_laporan = ?";
        $existing_report = db_select_single(
            $koneksi,
            $query_cek_data,
            "sss",
            [$nik, $nama, $jenis]
        );
        if ($existing_report) {
            $_SESSION['error'] = "Laporan untuk warga meninggal dengan nama tersebut sudah ada.";
            header("Location: laporan_Warga.php?status=gagal");
            exit();
        } else {
            $query = "INSERT INTO laporan (nik_pelapor, nama_pelapor, nohp_pelapor, blok_pelapor, jenis_laporan, nama_subjek, umur_subjek, blok_subjek, tanggal_meninggal, KK_pelapor) VALUES (?,?,?,?,?,?,?,?,?,?)";
            db_insert(
                $koneksi,
                $query,
                "ssssssisss",
                [$nik, $nama_pelap, $nohp_pelapor, $blok_pelapor, $jenis, $nama, $umur, $blok, $tanggal, $no_kk]
            );
            db_update(
                $koneksi,
                "UPDATE user_warga SET keluarga = 'Wafat' WHERE nik_warga = ?",
                "s",
                [$nik]
            );
        }
    }
} else {
    $nama = $_POST['nama_subjek1'];
    $umur = $_POST['umur_subjek1'];
    $blok = $_POST['blok_subjek1'];
    $jenis = 'ibu-hamil';

    if (empty($nama) || empty($umur) || empty($blok)) {
        $_SESSION['error'] = "Semua field wajib diisi untuk laporan ibu hamil.";
        header("Location: laporan_Warga.php?status=gagal");
        exit();
    } elseif ($nama === "" || $umur === "" || $blok === "") {
        $_SESSION['error'] = "Semua field wajib diisi untuk laporan ibu hamil.";
        header("Location: laporan_Warga.php?status=gagal");
        exit();
    } else {
        $query_cek_data = "SELECT * FROM laporan WHERE nik_pelapor = ? AND nama_subjek = ? AND jenis_laporan = ?";
        $existing_report = db_select_single(
            $koneksi,
            $query_cek_data,
            "sss",
            [$nik, $nama, $jenis]
        );
        if ($existing_report) {
            $_SESSION['error'] = "Laporan untuk ibu hamil dengan nama tersebut sudah ada.";
            header("Location: laporan_Warga.php?status=gagal");
            exit();
        } else {
            $query = "INSERT INTO laporan (nik_pelapor, nama_pelapor, nohp_pelapor, blok_pelapor, jenis_laporan, nama_subjek, umur_subjek, blok_subjek, KK_pelapor) VALUES (?,?,?,?,?,?,?,?,?)";
            db_insert(
                $koneksi,
                $query,
                "sssssssss",
                [$nik, $nama_pelap, $nohp_pelapor, $blok_pelapor, $jenis, $nama, $umur, $blok, $no_kk]
            );
        }
    }
}
$_SESSION['error'] = "Laporan berhasil diajukan.";
header("Location: laporan_Warga.php?status=sukses");
exit();
