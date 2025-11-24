<?php 
session_start();
include "../koneksi.php";

$nama = $_POST['nama'];
$nik = $_POST['nik'];
$hp = $_POST['hp'];

$id_rt = $_SESSION['user_rt']['sk_rt'];

$input = mysqli_query($koneksi, "INSERT INTO user_warga (nama_warga, nik_warga, hp, rt) VALUES ('$nama', '$nik', '$hp', '$id_rt');") or die (mysqli_error($koneksi)); 

if ($input){
    echo "<script>
    alert('data berhasil disimpan');
    window.location.href='Dashboard_RT.php';
    </script>";
} else {
    echo "<script>
    alert('gagal menyimpan data')
    window.location.href='Dashboard_RT.php'
    </script>";
}
?>