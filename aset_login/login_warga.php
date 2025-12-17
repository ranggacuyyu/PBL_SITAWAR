<?php
session_start();
include "../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $nik = trim($_POST['sandi']);

    if (empty($nama) || empty($nik)) {
        $_SESSION['alert'] = "Nama dan Password tidak boleh kosong";
        header("Location: ../LoginRTWARGA.php"); 
        exit();
    }

    $sql = "SELECT * FROM user_warga WHERE nik_warga=?";
    $stmt = mysqli_stmt_init($koneksi);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "SQL error";
    } else {
        mysqli_stmt_bind_param($stmt, "s", $nama);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        $stmt->close();

        if (!$user || !password_verify($nik, $user['password'])) {
            $_SESSION['alert'] = "NIK atau Password salah";
            header("Location: ../LoginRTWARGA.php");
            exit();
        }
        $_SESSION['user_warga'] = [
            'nik_warga' => $user['nik_warga'],
            'nama_warga' => $user['nama_warga']
        ];
        header("Location: ../warga/sign-in_Warga.php");
        exit;
    }
}

?>