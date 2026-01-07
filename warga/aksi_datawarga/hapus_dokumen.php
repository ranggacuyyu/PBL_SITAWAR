<?php
session_start();
require_once '../../koneksi.php';
require_once '../../db_helper.php';

if (!isset($_SESSION['user_warga'])) {
    http_response_code(403);
    exit;
}

if (!isset($_POST['id_warga'])) {
    $_SESSION['notif'] = "ID warga tidak ditemukan.";
    header("Location: ../data_Warga.php");
    exit;
} 
$id_warga = $_POST['id_warga'];

$folderKK  = "../../uploads/kk/";
$folderKTP = "../../uploads/ktp/";

$data = db_select_single(
    $koneksi,
    "SELECT foto_kk, foto_ktp FROM dokumen_wargart WHERE id_warga=?",
    "s",
    [$id_warga]
);

if ($data) {
    if (!empty($data['foto_kk']) && file_exists($folderKK . $data['foto_kk'])) {
        unlink($folderKK . $data['foto_kk']);
    }

    if (!empty($data['foto_ktp']) && file_exists($folderKTP . $data['foto_ktp'])) {
        unlink($folderKTP . $data['foto_ktp']);
    }
}

db_delete(
    $koneksi,
    "DELETE FROM dokumen_wargart WHERE id_warga=?",
    "s",
    [$id_warga]
);
$_SESSION['notif'] = "Dokumen berhasil dihapus.";
header("Location: ../data_Warga.php");
exit;       

?>
