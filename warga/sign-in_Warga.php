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
    <div class="card-baru">Silakan isi formulir data diri warga dengan lengkap dan benar...</div>
  </div>
  
  <div class="container">
    <div class="form-kiri">
      <h2>DAFTARKAN AKUN ANDA</h2>
      
      <form action="proses_pendaftaran.php" method="POST">
      
        <div class="kotak-form">
          <h3>Masukkan data diri anda</h3>

          <p>Nama (sesuai NIK)</p>
          <input type="text" id="namaInput" name="namaInput" required>

          <p>NIK pengguna</p>
          <input type="text" id="NIKInput" name="NIKInput" required>

          <p>No. KK</p>
          <input type="text" id="Nokkinput" name="Nokkinput" required>

          <p>Tempat/Tanggal Lahir</p>
          <input type="text" id="tempatlahir" name="tempatlahir" required>
          <input type="date" id="tanggallahir" name="tanggallahir" required>

          <p>Alamat (sesuai KTP)</p>
          <input type="text" id="alamatinput" name="alamatinput">

          <p>Agama</p>
          <select id="pilihan" name="pilihan" required>
            <option disabled selected hidden>- Pilih -</option>
            <option value="Islam">Islam</option>
            <option value="Kristen">Kristen</option>
            <option value="Katolik">Katolik</option>
            <option value="Hindu">Hindu</option>
            <option value="Buddha">Buddha</option>
            <option value="Konghucu">Konghucu</option>
          </select>

          <p>Nomor SK RT</p>
          <input type="text" id="anggotaRT" name="anggotaRT" placeholder="Contoh: RT01" required>
          
          <p>RW</p>
          <input type="text" id="anggotaRW" name="anggotaRW" placeholder="Contoh: RW01" required>
        </div>

        <div class="kotak-form">
          <h3>Masukkan data profil</h3>

          <p>Nama panggilan</p>
          <input type="text" id="panggilan" name="panggilan">

          <p>Email</p>
          <input type="email" id="emaill" name="emaill">

          <p>Jenis Kelamin:</p>
          <label><input type="radio" name="jk" value="Laki-laki" required> Laki-laki</label>
          <label><input type="radio" name="jk" value="Perempuan" required> Perempuan</label>

          <p>Status Pekerjaan</p>
          <select id="inputpekerjaan" name="inputpekerjaan">
            <option disabled selected hidden>- Pilih -</option>
            <option value="Swasta">Swasta</option>
            <option value="BUMN">BUMN</option>
            <option value="PNS">PNS</option>
            <option value="Wirausaha">Wirausaha</option>
            <option value="Tidak-Bekerja">Tidak Bekerja</option>
          </select>

          <p>Status Perkawinan:</p>
          <select id="pilihkawin" name="pilihkawin" required>
            <option disabled selected hidden>- Pilih -</option>
            <option value="kawin">Sudah Kawin</option> 
            <option value="belum-kawin">Belum Kawin</option>
            <option value="cerai-hidup">Cerai Hidup</option>
            <option value="cerai-mati">Cerai Mati</option>
          </select>

          <p>Pendidikan Terakhir:</p>
          <select id="pilihpendidikan" name="pilihpendidikan">
            <option disabled selected hidden>- Pilih -</option>
            <option value="Tidak-Bersekolah">Tidak Bersekolah</option>
            <option value="SD">SD</option>
            <option value="SMP">SMP</option>
            <option value="SMA/SMK/MA">SMA/SMK/MA</option>
            <option value="D3/D2">D3/D2</option>
            <option value="S1/D4">S1/D4</option>
            <option value="S2">S2</option>
            <option value="S3">S3</option>
          </select>

          <p>Provinsi</p>
          <input type="text" id="inputprovinsi" name="inputprovinsi" placeholder="kepulauan Riau">

          <p>Kota/Kabupaten</p>
          <input type="text" id="inputkota" name="inputkota" placeholder="Batam">

          <p>Kecamatan</p>
          <input type="text" id="inputKecamatan" name="inputKecamatan" placeholder="kecamatan">

          <p>Kelurahan</p>
          <input type="text" id="inputkelurahan" name="inputkelurahan" placeholder="kelurahan">

          <p>No Handphone</p>
          <input type="text" id="inputnomorhp" name="inputnomorhp" placeholder="08-****-****-****">

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