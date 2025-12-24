<?php
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';

// Set header untuk JSON response
header('Content-Type: application/json');

// Cek apakah user sudah login
if (!isset($_SESSION["user_warga"])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Ambil NIK pelapor dari session
$nik_pelapor = $_SESSION["user_warga"]["nik_warga"];

try {
    // Ambil no KK pelapor
    $pelapor_data = db_select_single(
        $koneksi,
        "SELECT no_kk FROM user_warga WHERE nik_warga = ?",
        "s",
        [$nik_pelapor]
    );

    if (!$pelapor_data || empty($pelapor_data['no_kk'])) {
        echo json_encode(['error' => 'No KK tidak ditemukan']);
        exit;
    }

    $no_kk = $pelapor_data['no_kk'];

    // Ambil semua anggota keluarga dengan no KK yang sama
    // Hitung umur otomatis menggunakan TIMESTAMPDIFF
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT 
            nik_warga, 
            nama_warga, 
            TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) AS umur,
            jenis_kelamin,
            keluarga
        FROM user_warga 
        WHERE no_kk = ? 
        AND keluarga != 'wafat'
        AND tanggal_lahir IS NOT NULL
        ORDER BY nama_warga ASC"
    );

    if (!$stmt) {
        echo json_encode(['error' => 'Query error: ' . mysqli_error($koneksi)]);
        exit;
    }

    mysqli_stmt_bind_param($stmt, "s", $no_kk);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $family_members = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $family_members[] = [
            'nik_warga'     => $row['nik_warga'],
            'nama_warga'    => $row['nama_warga'],
            'umur'          => $row['umur'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'keluarga'      => $row['keluarga']
        ];
    }

    mysqli_stmt_close($stmt);

    // Kembalikan data dalam format JSON
    echo json_encode($family_members);

} catch (Exception $e) {
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>