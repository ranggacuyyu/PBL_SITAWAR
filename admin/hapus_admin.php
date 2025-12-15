<?php
session_start();
include "../koneksi.php";

if (isset($_GET['sk_rt'])) {
    $sk_rt = $_GET['sk_rt'];
    $id_admin = $_SESSION['admin_user']['id_admin'];

    $query = "DELETE FROM user_rt WHERE sk_rt = ? AND admin = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ss", $sk_rt, $id_admin);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['alert'] = 'Data berhasil dihapus';
        header("Location: dashborad_admin.php");
        exit();
    } else {
        $_SESSION['alert'] = 'Gagal menghapus data';
        header("Location: dashborad_admin.php");
        exit() ;
    }
} else {
    header("Location: dashborad_admin.php");
}
?>
