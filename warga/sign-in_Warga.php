



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
    <div class="card-baru">Silakan isi formulir data diri warga dengan lengkap dan benar pada setiap kolom yang
      tersedia,
      dimulai dari informasi dasar seperti nama lengkap, nomor induk kependudukan (NIK), tempat dan tanggal lahir,
      alamat
      lengkap sesuai kartu keluarga (KK), serta informasi kontak yang dapat dihubungi. Pastikan Anda memeriksa kembali
      setiap data yang telah diinput agar tidak terjadi kesalahan penulisan atau kekeliruan informasi. Apabila Anda
      ingin
      menghapus seluruh data yang telah dimasukkan dan kembali ke kondisi awal, gunakan tombol Reset untuk membersihkan
      form secara otomatis sebelum mengisi ulang. Setelah seluruh data dipastikan benar dan sesuai, tekan tombol Submit
      untuk mengirimkan data ke sistem pendataan warga. Dengan menekan tombol Submit, Anda menyetujui bahwa data yang
      dikirimkan adalah akurat dan siap diproses oleh petugas administrasi yaitu RT. Pastikan koneksi internet stabil
      saat
      melakukan proses pengiriman data, dan tunggu hingga muncul notifikasi bahwa proses pengisian dan pengiriman data
      berhasil dilakukan.</div>
  </div>
  
  <div class="container">
    <!-- BAGIAN KIRI -->
    <form id="form-kiri" class="form-kiri">
      <h2>DAFTARKAN AKUN ANDA</h2>

      <div class="kotak-form">
        <h3>Masukkan data diri anda</h3>

        <p>Nama (sesuai NIK)</p>
        <input type="text" id="namaInput">

        <p>NIK pengguna</p>
        <input type="text" id="NIKInput">

        <p>No. KK</p>
        <input type="text" id="Nokkinput">

        <p>Tempat/Tanggal Lahir</p>
        <input type="text" id="tempatlahir">
        <input type="date" id="tanggallahir">

        <p>Alamat (sesuai KTP)</p>
        <input type="text" id="alamatinput">

        <p>Agama</p>
        <select id="pilihan">
          <option disabled selected hidden>- Pilih -</option>
          <option value="islam">Islam</option>
          <option value="kristen">Kristen</option>
          <option value="katolik">Katolik</option>
          <option value="hindu">Hindu</option>
          <option value="buddha">Buddha</option>
          <option value="konghuchu">Konghucu</option>
        </select>

        <p>Nomor ketua RT?</p>
        <input type="text" id="anggotaRT" placeholder="Ketua RT01">
        <p>RW</p>
        <input type="text" id="anggotaRW" placeholder="RW 01">
      </div>

      <div class="kotak-form">
        <h3>Masukkan data profil</h3>

        <p>Nama panggilan</p>
        <input type="text" id="panggilan">

        <p>Email</p>
        <input type="email" id="emaill">

        <p>Jenis Kelamin:</p>
        <label><input type="radio" name="jk" value="Laki-laki"> Laki-laki</label>
        <label><input type="radio" name="jk" value="Perempuan"> Perempuan</label>

        <p>Status Pekerjaan</p>
        <select id="inputpekerjaan">
          <option disabled selected hidden>- Pilih -</option>
          <option value="Swasta">Swasta</option>
          <option value="BUMN">BUMN</option>
          <option value="PNS">PNS</option>
          <option value="Wirausaha">Wirausaha</option>
          <option value="Tidak Bekerja">Tidak Bekerja</option>
        </select>

        <p>Status Perkawinan:</p>
        <select id="pilihkawin">
          <option disabled selected hidden>- Pilih -</option>
          <option value="Sudah Kawin">Sudah Kawin</option>
          <option value="Belum Kawin">Belum Kawin</option>
          <option value="Cerai Hidup">Cerai Hidup</option>
          <option value="Cerai Mati">Cerai Mati</option>
        </select>

        <p>Pendidikan Terakhir:</p>
        <select id="pilihpendidikan">
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

        <p>Provinsi</p>
        <input type="text" id="inputprovinsi" placeholder="kepulauan Riau">

        <p>Kota/Kabupaten</p>
        <input type="text" id="inputkota" placeholder="Batam">

        <p>Kecamatan</p>
        <input type="text" id="inputKecamatan" placeholder="kecamatan">

        <p>Kelurahan</p>
        <input type="text" id="inputkelurahan" placeholder="kelurahan">

        <p>No Handphone</p>
        <input type="text" id="inputnomorhp" placeholder="08-****-****-****">

        <div class="tombol">
          <button type="reset">Reset</button>
          <button type="submit" id="kirimBtn">Submit</button>
        </div>
      </div>
    </form>

    <!-- BAGIAN KANAN -->
    <div class="foto-kanan">
      <div class="kotak-foto">
        <div class="ikon">👤</div>
        <p>Unggah Foto Profil</p>
        <input type="file">
      </div>
    </div>
  </div>


  <script>
    document.getElementById('kirimBtn').addEventListener('click', function () {
      const kirimnama = document.getElementById('namaInput').value;
      const kirimnik = document.getElementById('NIKInput').value;
      const kirimkk = document.getElementById('Nokkinput').value;
      const kirimlahir = document.getElementById('tempatlahir').value;
      const kirimlahirtgl = document.getElementById('tanggallahir').value;
      const kirimalamat = document.getElementById('alamatinput').value;
      const kirimagama = document.getElementById('pilihan').value;
      const kirimgender = document.querySelector('input[name="jk"]:checked')?.value || "";
      const kirimkota = document.getElementById('inputkota').value;
      const kirimkecamatan = document.getElementById('inputKecamatan').value;
      const kirimkelurahan = document.getElementById('inputkelurahan').value;
      const kirimkawin = document.getElementById('pilihkawin').value;
      const kirimpekerjaan = document.getElementById('inputpekerjaan').value;
      const kirimpendidikan = document.getElementById('pilihpendidikan').value;
      const kirimnomor = document.getElementById('inputnomorhp').value;
      const kirimprovinsi = document.getElementById('inputprovinsi').value;
      const kirimRT = document.getElementById('anggotaRT').value;
      const kirimRW = document.getElementById('anggotaRW').value;
      const katakata = document.getElementById('panggilan').value;

      localStorage.setItem('nama', kirimnama);
      localStorage.setItem('nik', kirimnik);
      localStorage.setItem('kk', kirimkk);
      localStorage.setItem('lahir1', kirimlahir);
      localStorage.setItem('lahir2', kirimlahirtgl);
      localStorage.setItem('agama', kirimagama);
      localStorage.setItem('alamat', kirimalamat);
      localStorage.setItem('kelamin', kirimgender);
      localStorage.setItem('kawinn', kirimkawin);
      localStorage.setItem('domisili', `${kirimprovinsi} / ${kirimkota} / ${kirimkecamatan} / ${kirimkelurahan}`);
      localStorage.setItem('pekerjaan', kirimpekerjaan);
      localStorage.setItem('pendidikan', kirimpendidikan);
      localStorage.setItem('nomor', kirimnomor);
      localStorage.setItem('kata', `${katakata} ${kirimRT}`);
      localStorage.setItem('RTRW', `${kirimRT} / ${kirimRW}`);
      alert('Apakah anda sudah yakin dalam mengisi formulir?');
      window.location.href = 'data_Warga.php';
    });
  </script>
</body>

</html>