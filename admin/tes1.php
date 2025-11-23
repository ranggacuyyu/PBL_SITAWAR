<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_siswa");

$id     = $_POST['id'];
$nama   = $_POST['nama'];
$kelas  = $_POST['kelas'];
$nik    = $_POST['nik'];
$nim    = $_POST['nim'];

$update = mysqli_query($koneksi, "UPDATE siswa SET
    nama='$nama',
    kelas='$kelas',
    nik='$nik',
    nim='$nim'
    WHERE id='$id'
");

if ($update) echo "OK";
else echo "ERROR";
?>
