<?php
session_start();
require_once '../../koneksi.php';
require_once '../../db_helper.php';

function redirect($msg) {
    $_SESSION['flash'] = $msg;
    header("Location: ../data_Warga.php");
    exit();
}
// 1. VALIDASI LOGIN
if (!isset($_SESSION['user_warga']['nik_warga'])) {
    redirect("⚠️ Silakan login terlebih dahulu");
}
$id_warga = $_SESSION['user_warga']['nik_warga'];

// 2. VALIDASI DATA WARGA
$res = db_select_no_assoc($koneksi, "SELECT nik_warga FROM user_warga WHERE nik_warga=?", "s", [$id_warga]);
if ($res->num_rows == 0) {
    redirect("❌ Data warga tidak valid");
}

// 3. WAJIB ADA 2 FILE
if (empty($_FILES['foto_kk']['name']) || empty($_FILES['foto_ktp']['name'])) {
    redirect("❌ Foto KK dan KTP wajib diupload");
}

// 4. VALIDASI FILE
$allowedExt  = ['jpg','jpeg','png'];
$allowedMime = ['image/jpeg','image/png'];
$maxSize     = 2 * 1024 * 1024;

function validasiFile($file, $allowedExt, $allowedMime, $maxSize) {
    $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $mime = mime_content_type($file['tmp_name']);

    if (!in_array($ext, $allowedExt)) return "❌ Format harus JPG / PNG";
    if (!in_array($mime, $allowedMime)) return "❌ File bukan gambar asli";
    if ($file['size'] > $maxSize) return "❌ Ukuran maksimal 2MB";
    return true;
}

$cekKK  = validasiFile($_FILES['foto_kk'],  $allowedExt, $allowedMime, $maxSize);
$cekKTP = validasiFile($_FILES['foto_ktp'], $allowedExt, $allowedMime, $maxSize);

if ($cekKK !== true)  redirect($cekKK);
if ($cekKTP !== true) redirect($cekKTP);

// ===============================
// 5. FOLDER
// ===============================
$folderKK  = "../../uploads/kk/";
$folderKTP = "../../uploads/ktp/";

if (!is_dir($folderKK))  mkdir($folderKK, 0755, true);
if (!is_dir($folderKTP)) mkdir($folderKTP, 0755, true);

// ===============================
// 6. NAMA FILE AMAN
// ===============================
$namaKK  = hash('sha256', uniqid()) . ".jpg";
$namaKTP = hash('sha256', uniqid()) . ".jpg";

// ===============================
// 7. PINDAH FILE
// ===============================
if (
    !move_uploaded_file($_FILES['foto_kk']['tmp_name'],  $folderKK  . $namaKK) ||
    !move_uploaded_file($_FILES['foto_ktp']['tmp_name'], $folderKTP . $namaKTP)
) {
    redirect("❌ Gagal menyimpan file");
}

// ===============================
// 8. CEK DATA LAMA
// ===============================
$old = db_select_single($koneksi, 
"SELECT id_dokumen, foto_kk, foto_ktp FROM dokumen_wargart WHERE id_warga=?", 
"s", 
[$id_warga]);

if ($old) {

    @unlink($folderKK . $old['foto_kk']);
    @unlink($folderKTP . $old['foto_ktp']);

    $update_query = "UPDATE dokumen_wargart SET 
        foto_kk=?, foto_ktp=?, status_verifikasi='pending',
        catatan_penolakan=NULL, tanggal_upload=CURDATE()
        WHERE id_warga=?";
    db_update($koneksi, 
        $update_query, 
        "sss", 
        [$namaKK, $namaKTP, $id_warga]
    );

} else {
    $insert = "INSERT INTO dokumen_wargart 
        (id_warga, foto_kk, foto_ktp, status_verifikasi, tanggal_upload, jenis_dokumen)
        VALUES (?, ?, ?, 'pending', CURDATE(), 'pengantar_rt')";

    db_insert($koneksi, $insert, "sss", [$id_warga, $namaKK, $namaKTP]);
}

redirect("✅ Upload KK & KTP berhasil!");
