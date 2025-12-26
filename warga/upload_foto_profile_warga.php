<?php
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_warga']['nik_warga'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$nik_warga = $_SESSION['user_warga']['nik_warga'];
$response = ['success' => false, 'message' => ''];

try {
    // Check if file was uploaded
    if (!isset($_FILES['foto_profile']) || $_FILES['foto_profile']['error'] === UPLOAD_ERR_NO_FILE) {
        throw new Exception('Tidak ada file yang diupload');
    }

    $file = $_FILES['foto_profile'];

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error saat upload file');
    }

    // Validate file type
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime_type, $allowed_types)) {
        throw new Exception('Tipe file tidak valid. Hanya JPG, PNG, dan GIF yang diperbolehkan');
    }

    // Validate file size (max 2MB)
    $max_size = 2 * 1024 * 1024; // 2MB in bytes
    if ($file['size'] > $max_size) {
        throw new Exception('Ukuran file terlalu besar. Maksimal 2MB');
    }

    // Get file extension
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // Generate unique filename
    $new_filename = time() . '_' . uniqid() . '.' . $file_ext;

    // Set upload directory
    $upload_dir = __DIR__ . '/profile/';
    $upload_path = $upload_dir . $new_filename;

    // Get old photo to delete
    $old_data = db_select_single($koneksi, "SELECT foto_profile FROM user_warga WHERE nik_warga = ?", "s", [$nik_warga]);

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
        throw new Exception('Gagal menyimpan file');
    }

    // Update database
    $update_result = db_update($koneksi, "UPDATE user_warga SET foto_profile = ? WHERE nik_warga = ?", "ss", [$new_filename, $nik_warga]);

    if (!$update_result) {
        // If database update fails, delete the uploaded file
        unlink($upload_path);
        throw new Exception('Gagal menyimpan ke database');
    }

    // Delete old photo if exists and not default
    if ($old_data && !empty($old_data['foto_profile']) && $old_data['foto_profile'] !== 'default.jpg') {
        $old_path = $upload_dir . $old_data['foto_profile'];
        if (file_exists($old_path)) {
            unlink($old_path);
        }
    }

    // Update session data
    $_SESSION['user_warga']['foto_profile'] = $new_filename;

    $response['success'] = true;
    $response['message'] = 'Foto profil berhasil diupdate';
    $response['filename'] = $new_filename;

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
