<?php
session_start();
include '../koneksi.php'; 

$tanggallahir = mysqli_real_escape_string($koneksi, $_POST['tanggallahir']); 
$tempatlahir = mysqli_real_escape_string($koneksi, $_POST['tempatlahir']); 
$alamat = mysqli_real_escape_string($koneksi, $_POST['alamatinput']);

$agama = mysqli_real_escape_string($koneksi, $_POST['pilihan']);
$email = mysqli_real_escape_string($koneksi, $_POST['emaill']);
$gender = mysqli_real_escape_string($koneksi, $_POST['jk']);

$pekerjaan = mysqli_real_escape_string($koneksi, $_POST['inputpekerjaan']);
$kawin = mysqli_real_escape_string($koneksi, $_POST['pilihkawin']);
$rt = mysqli_real_escape_string($koneksi, $_POST['anggotaRT']);

$pendidikan = mysqli_real_escape_string($koneksi, $_POST['pilihpendidikan']);
$kelurahan = mysqli_real_escape_string($koneksi, $_POST['inputkelurahan']);
$kk = mysqli_real_escape_string($koneksi, $_POST['Nokkinput']);


// 3. Query INSERT data ke tabel user_warga
$sql_insert = "INSERT INTO user_warga 
               (no_kk, tanggal_lahir, tempat_lahir, alamat, agama, email, jenis_kelamin, pekerjaan, status_kawin, pendidikan, kelurahan)
               VALUES 
               ('$kk', '$tanggallahir', '$tempatlahir', '$alamat', '$agama', '$email', '$gender', '$pekerjaan', '$kawin', '$pendidikan', '$kelurahan')";

if (mysqli_query($koneksi, $sql_insert)) {
    // 4. Jika INSERT berhasil, simpan NIK ke SESSION
    $_SESSION['nik_warga'] = $nik; 
    $_SESSION['nama_warga'] = $nama;

    // 5. Redirect ke halaman data diri
    header("Location: data_Warga.php"); 
    exit();
} else {
    // Tampilkan pesan error jika query gagal
    echo "<h2>❌ Pendaftaran Gagal!</h2>";
    echo "Error: " . mysqli_error($koneksi);
    echo "<br>Kemungkinan penyebab: NIK atau RT belum terdaftar atau data tidak sesuai.";
}

// Tutup koneksi
mysqli_close($koneksi);
?>