<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['user_rt'])) {
    header('location:login_admin.php');
    exit;
}

$nik      = $_GET['nik_warga'];
$pass     = $_GET['password'];
$id_admin = $_SESSION['admin_user']['id_admin'];

$cekAdmin = mysqli_query($koneksi, "SELECT password FROM admin WHERE id_admin='$id_admin'");
$admin = mysqli_fetch_assoc($cekAdmin);

// Cek password
if (!password_verify($pass, $admin['password'])) {
    echo "<script>
        alert('Password salah!');
        window.location.href='DataWarga_RT.php';
    </script>";
    exit;
}

// Hapus data warga
mysqli_query($koneksi, "DELETE FROM user_warga WHERE nik_warga='$nik'");

echo "<script>
    alert('Data warga berhasil dihapus!');
    window.location.href='DataWarga_RT.php';
</script>";
exit;
?>
