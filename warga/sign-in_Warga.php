<?php
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';

// Pastikan login
if (!isset($_SESSION['user_warga']['nik_warga'])) {
  header("Location: data_Warga.php");
  exit();
}

$nik = $_SESSION['user_warga']['nik_warga'];

// Cek apakah sudah mengisi
$data = db_select_single($koneksi, "SELECT sudah_lengkap FROM user_warga WHERE nik_warga=?", "s", [$nik]);
if ((int)$data['sudah_lengkap'] === 1) {
  header("Location: data_Warga.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIGN IN SITAWAR</title>
  <link rel="stylesheet" href="sign-in_Warga.css">
</head>

<body>
  <h1 class="judul">SITAWAR</h1>
  <div class="background">
    <div class="card-baru">
      <div class="panduan-form" style="color: #333; padding-left: 40px;">
        <h3> Panduan Pengisian Data</h3>
        <ul>
          <li>Pastikan seluruh data yang diisi <b>sesuai dengan KTP dan KK</b>.</li>
          <li>Data ini digunakan <b>khusus untuk administrasi RT</b>.</li>
          <li><b>Hanya Ketua RT dan pengurus resmi</b> di komunitas Anda yang dapat melihat data ini.</li>
          <li>Data Anda <b>tidak akan disebarluaskan</b> ke pihak luar.</li>
          <li>Kolom bertanda (*) <b>wajib diisi</b>.</li>
        </ul>
      </div>

    </div>
  </div>

  <div class="container">
    <div class="form-kiri">
      <h2>DAFTARKAN AKUN ANDA</h2>

      <form action="aksi/proses_pendaftaran.php" method="POST">

        <div class="kotak-form">
          <h3>Masukkan data diri anda</h3>

          <label for="Nokkinput">No. KK*</label>
          <input type="text" id="Nokkinput" name="Nokkinput" required>

          <label for="tempatlahir">Tempat/Tanggal Lahir*</label>
          <input type="text" id="tempatlahir" name="tempatlahir" required>
          <input type="date" id="tanggallahir" name="tanggallahir" required>

          <p>Alamat (sesuai KTP)*</p>
          <input type="text" id="alamatinput" name="alamatinput">

          <p>Agama*</p>
          <select id="pilihan" name="pilihan" required>
            <option disabled selected hidden>- Pilih -</option>
            <option value="Islam">Islam</option>
            <option value="Kristen">Kristen</option>
            <option value="Katolik">Katolik</option>
            <option value="Hindu">Hindu</option>
            <option value="Buddha">Buddha</option>
            <option value="Konghucu">Konghucu</option>
          </select>

          <p>Email*</p>
          <input type="email" id="emaill" name="emaill">

          <p>NO HP*</p>
          <input type="text" id="nohp" name="nohp" required>

          <p>Jenis Kelamin*</p>
          <select name="jk">
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
          </select>

          <p>Status Pekerjaan*</p>
          <select id="inputpekerjaan" name="inputpekerjaan" required>
            <option disabled selected hidden>- Pilih -</option>
            <option value="Pelajar/Mahasiswa">Pelajar/Mahasiswa</option>
            <option value="Swasta">Swasta</option>
            <option value="BUMN">BUMN</option>
            <option value="PNS">PNS</option>
            <option value="Wirausaha">Wirausaha</option>
            <option value="Tidak Bekerja">Tidak Bekerja</option>
          </select>

          <p>Status Perkawinan*</p>
          <select id="pilihkawin" name="pilihkawin" required>
            <option disabled selected hidden>- Pilih -</option>
            <option value="Kawin">Sudah Kawin</option>
            <option value="belum-Kawin">Belum Kawin</option>
            <option value="cerai-hidup">Cerai Hidup</option>
            <option value="cerai-mati">Cerai Mati</option>
          </select>

          <p>Pendidikan Terakhir*</p>
          <select id="pilihpendidikan" name="pilihpendidikan" required>
            <option disabled selected hidden>- Pilih -</option>
            <option value="Tidak Bersekolah">Tidak Bersekolah</option>
            <option value="SD">SD</option>
            <option value="SMP">SMP</option>
            <option value="SMA/SMK/MA">SMA/SMK/MA</option>
            <option value="D3/D2">D3/D2</option>
            <option value="S1/D4">S1/D4</option>
            <option value="S2">S2</option>
            <option value="S3">S3</option>
          </select>

          <p>Kecamatan*</p>
          <input type="text" id="inputkelurahan" name="inputkecamatan" placeholder="kelurahan" required>

          <p>Kelurahan*</p>
          <input type="text" id="inputkelurahan" name="inputkelurahan" placeholder="kelurahan" required>

          <input type="checkbox" required> Saya menyatakan bahwa data yang saya isi adalah benar dan setuju jika RT dapat melihat data saya

          <div class="tombol">
            <button type="reset">Reset</button>
            <button type="submit">Submit</button>
          </div>
        </div>

      </form>
    </div>

    <div class="foto-kanan">
    </div>
  </div>

  <script>
    // **Pastikan tidak ada kode JavaScript yang mengarahkan (redirect) ke halaman lain di sini.**
    // Anda hanya perlu menyisakan kode untuk fungsi UI lain (jika ada).
  </script>
</body>

</html>