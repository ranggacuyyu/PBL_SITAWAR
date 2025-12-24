<?php
session_start();
include "../koneksi.php";

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sk        = trim($_POST['sk_rt']);
    $password  = trim($_POST['password']);

    if(empty($sk) ||  empty($password)) {
        $_SESSION['alertrt'] = "SK atau Password tidak boleh kosong";
        header("Location: ../LoginRTWARGA.php");
        exit();
    }

    $sql = "SELECT * FROM user_rt WHERE sk_rt = ?";
    $stmt = mysqli_stmt_init($koneksi);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "SQL error";
    } else {
        mysqli_stmt_bind_param($stmt, "s", $sk);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        $stmt->close();
        
        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['alertrt'] = "SK atau Password salah";
            header("Location: ../LoginRTWARGA.php");
            exit();
        }
        $_SESSION["login"] = "Selamat Datang";
        $_SESSION['user_rt'] = [
            'sk_rt' => $user['sk_rt'],
            'no_rt' => $user['no_rt'],
            'no_rw' => $user['no_rw'],
            'nama_rt' => $user['nama_rt']
        ];
        header("Location: ../RT/Dashboard_RT.php");
        exit;
    }
}


?>