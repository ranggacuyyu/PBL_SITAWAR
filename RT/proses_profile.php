<?php
session_start();
include "../koneksi.php";

$nik = $_POST['nik'];
$hp = $_POST['hp'];
$email = $_POST['email'];
$pekerjaan = $_POST['pekerjaan'];
$pass1 = $_POST['password'];
$pass2 = $_POST['password2'];

/* UPLOAD FOTO */
if (!empty($_FILES['foto']['name'])) {
    $namaFile = time() . $_FILES['foto']['name'];
    move_uploaded_file($_FILES['foto']['tmp_name'], "../image/" . $namaFile);

    mysqli_query($koneksi, "UPDATE user_warga SET foto='$namaFile' WHERE nik_warga='$nik'");
}

/* UPDATE PASSWORD */
if (!empty($pass1)) {
    if ($pass1 != $pass2) {
        echo "Password tidak sama!";
        exit;
    }

    $hash = password_hash($pass1, PASSWORD_DEFAULT);
    mysqli_query($koneksi, "UPDATE user_warga SET password='$hash' WHERE nik_warga='$nik'");
}

/* UPDATE DATA */
$query = mysqli_query($koneksi, "
    UPDATE user_warga SET
    hp='$hp',
    email='$email',
    pekerjaan='$pekerjaan'
    WHERE nik_warga='$nik'
");

echo $query ? "Profile berhasil diperbarui!" : "Gagal menyimpan!";
