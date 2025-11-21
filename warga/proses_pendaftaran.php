<?php
session_start(); // Wajib: Untuk memulai session dan menyimpan NIK

// 1. Sertakan file koneksi database
include 'koneksi.php'; // Menggunakan file koneksi.php Anda

// 2. Ambil data dari formulir menggunakan $_POST
// Data diambil dan diamankan (sanitasi) untuk mencegah SQL Injection
$nama = mysqli_real_escape_string($koneksi, $_POST['namaInput']);
$nik = mysqli_real_escape_string($koneksi, $_POST['NIKInput']);
$kk = mysqli_real_escape_string($koneksi, $_POST['Nokkinput']);

// Perhatian: Di database user_warga hanya ada kolom 'tanggal_lahir' (tipe DATE)
$tanggallahir = mysqli_real_escape_string($koneksi, $_POST['tanggallahir']); 

$agama = mysqli_real_escape_string($koneksi, $_POST['pilihan']);
$gender = mysqli_real_escape_string($koneksi, $_POST['jk']); // name="jk" dari radio button
$kawin = mysqli_real_escape_string($koneksi, $_POST['pilihkawin']);
$rt = mysqli_real_escape_string($koneksi, $_POST['anggotaRT']);

// Gabungkan data domisili (sesuai batasan 100 karakter di tabel user_warga)
$provinsi = mysqli_real_escape_string($koneksi, $_POST['inputprovinsi']);
$kota = mysqli_real_escape_string($koneksi, $_POST['inputkota']);
$kecamatan = mysqli_real_escape_string($koneksi, $_POST['inputKecamatan']);
$kelurahan = mysqli_real_escape_string($koneksi, $_POST['inputkelurahan']);
$domisili_gabung = $provinsi . ", " . $kota . ", " . $kecamatan . ", " . $kelurahan;


// Kolom NOT NULL yang harus diisi di tabel user_warga
$dokumen_default = 0; 
$laporan_default = 0; 


// 3. Query INSERT data ke tabel user_warga
$sql_insert = "INSERT INTO user_warga 
               (nik_warga, dokumen, laporan, nama_warga, tanggal_lahir, jenis_kelamin, agama, domisili, status_kawin, `no-kk`, rt)
               VALUES 
               ('$nik', '$dokumen_default', '$laporan_default', '$nama', '$tanggallahir', '$gender', '$agama', '$domisili_gabung', '$kawin', '$kk', '$rt')";

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