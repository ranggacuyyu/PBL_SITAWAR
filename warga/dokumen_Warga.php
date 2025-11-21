<?php
// dokumen_Warga.php
session_start();

// Koneksi ke database
$host = "localhost";
$username = "root";
$password = "";
$database = "pbl";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Ambil NIK dari session (sesuaikan dengan sistem login Anda)
$nik_login = $_SESSION['nik'] ?? '12345'; // ganti dengan session login Anda

// Query untuk mengambil data dari tabel user_warga
$query = "SELECT * FROM user_warga WHERE nik_warga = '$nik_login' LIMIT 1";
$result = mysqli_query($conn, $query);

// Inisialisasi variabel untuk auto-fill form
$nama = "";
$nik = "";
$rt = "";
$nohp = "";
$tanggal_lahir = "";
$domisili_alamat = "";
$agama = "";
$jenis_kelamin = "";
$status_kawin = "";
$no_kk = "";

// Jika data ditemukan, mapping ke variabel
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    
    // Field yang ADA di database user_warga
    $nama = $row['nama_warga'] ?? "";
    $nik = $row['nik_warga'] ?? "";
    $rt = $row['rt'] ?? ""; // RT/RW
    $nohp = $row['hp_warga'] ?? "";
    $tanggal_lahir = $row['tanggal_lahir'] ?? "";
    $domisili_alamat = $row['domisili'] ?? ""; // alamat dari field domisili

    $agama = $row['agama'] ?? "";
    $jenis_kelamin = $row['jenis_kelamin'] ?? "";
    $status_kawin = $row['status_kawin'] ?? "";
    $no_kk = $row['no_kk'] ?? "";
}

// Field yang TIDAK ada di database user_warga (harus diisi manual)
$kelurahan = ""; // tidak ada di user_warga
$kecamatan = ""; // tidak ada di user_warga
$kota = ""; // tidak ada di user_warga

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>SITAWAR - Dokumen Warga</title>

  <style>
    body {
      margin: 0;
      font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
      background-image: url(download.jpg);
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }

    a {
      text-decoration: none;
    }

    .kepala {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .kepala span {
      cursor: pointer;
      font-size: 20px;
    }

    .pop-up11, .pop-up-pengantar {
      display: none;
      position: fixed;
      z-index: 1000;
      width: 100%;
      height: 100vh;
    }

    .pop-up22 {
      width: 100%;
      height: 100vh;
      background-color: rgba(0, 0, 0, 0.8);
      align-items: center;
      justify-content: center;
      display: flex;
      padding: 20px;
      box-sizing: border-box;
    }

    .pop-up22 h3 {
      margin: 0;
      font-size: 20px;
      font-weight: bold;
      padding: 10px;
    }

    .modal {
      animation: fadeIn 0.3s ease-in-out;
      background-color: white;
      width: 100%;
      max-width: 500px;
      padding: 20px;
      border-radius: 8px;
      max-height: 90vh;
      overflow-y: auto;
    }

    .modal-form {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-top: 20px;
    }

    .modal-form label {
      font-size: 13px;
      color: #555;
      margin-bottom: -5px;
    }

    .modal-form input, .modal-form select {
      padding: 10px;
      border: 1px solid #bbbbbb;
      border-radius: 4px;
      font-size: 14px;
    }

    .modal-form input:read-only {
      background-color: #f0f0f0;
      cursor: not-allowed;
    }

    .modal-form input[type="submit"] {
      background-color: #4CAF50;
      padding: 12px;
      color: white;
      border: none;
      cursor: pointer;
      font-weight: bold;
      margin-top: 10px;
    }

    .modal-form input[type="submit"]:hover {
      background-color: #45a049;
    }

    .auto-fill-note {
      background-color: #e8f5e9;
      padding: 8px;
      border-radius: 4px;
      font-size: 12px;
      color: #2e7d32;
      margin-bottom: 10px;
    }

    @keyframes fadeIn {
      from {
        transform: scale(0.5);
        opacity: 0;
      }
      to {
        transform: scale(1);
        opacity: 1;
      }
    }

    header {
      background: linear-gradient(to right, #3f492d, #88976c);
      color: white;
      height: 13vh;
      display: flex;
      align-items: center;
      padding-left: 20px;
      font-weight: bold;
      position: fixed;
      width: 100%;
      top: 0;
      left: 0;
      z-index: 100;
    }

    nav {
      background-color: #899a68;
      color: white;
      font-weight: bolder;
      padding: 10px;
      width: 200px;
      flex-shrink: 0;
      position: fixed;
      height: 100%;
      top: 13vh;
      left: 0;
      z-index: 50;
      overflow-y: auto;
    }

    nav ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    nav ul h2 {
      padding: 10px;
      margin: 10px 0;
      color: white;
      cursor: pointer;
      transition: all 0.3s;
      border-radius: 4px;
    }

    nav ul h2:hover {
      background-color: white;
      color: #738B67;
      transform: translateY(3px);
    }

    main {
      background-color: #88976cce;
      width: 100%;
      padding: 20px;
      display: flex;
      flex-direction: column;
      padding-left: 240px;
      padding-top: calc(13vh + 1rem);
      box-sizing: border-box;
      min-height: 100vh;
    }

    .top-row {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-bottom: 20px;
    }

    .menu-dokumen {
      background-color: #728156;
      padding: 15px;
      border-radius: 10px;
      border: 1px solid #899a68;
    }

    .menu-dokumen h4 {
      margin-top: 5px;
      margin-bottom: 10px;
      color: white;
      text-align: center;
    }

    .dokumen-tombol {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-top: 10px;
    }

    .dokumen-tombol button {
      padding: 12px;
      background-color: #a2ad7d;
      border: none;
      cursor: pointer;
      font-weight: bold;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    .dokumen-tombol button:hover {
      background-color: #899a68;
      color: white;
    }

    .alert-box {
      background-color: #899a68;
      padding: 15px;
      border-radius: 5px;
      flex-grow: 1;
      color: rgb(3, 3, 3);
      font-size: 16px;
    }

    .riwayat {
      background-color: #899a68;
      padding: 15px;
      border: 1px solid #899a68;
      border-radius: 5px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }

    .riwayat-header-row {
      display: flex;
      justify-content: space-between;
      font-weight: bold;
      margin-bottom: 10px;
      flex-wrap: wrap;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #dbeeb4;
    }

    th, td {
      border: 1px solid #748156;
      padding: 8px;
      text-align: center;
      color: #222e08;
      font-size: 14px;
    }

    th {
      background-color: #f6fae9;
      font-weight: bold;
    }

    /* --- RESPONSIVE --- */
    @media (max-width: 992px) {
      nav {
        width: 100%;
        height: auto;
        position: relative;
        top: 0;
      }

      nav ul {
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
      }

      main {
        padding-left: 20px;
        padding-top: 20px;
      }

      header {
        position: relative;
        height: auto;
        padding: 15px;
      }
    }

    @media (max-width: 576px) {
      .menu-dokumen {
        width: 100%;
      }

      .alert-box {
        font-size: 14px;
      }

      table th, table td {
        font-size: 12px;
        padding: 5px;
      }

      .modal {
        padding: 15px;
      }
    }
  </style>
</head>

<body>
  <!-- Modal untuk Domisili -->
  <div class="pop-up11">
    <div class="pop-up22">
      <div class="modal">
        <div class="kepala">
          <h3>Form Pengajuan Domisili</h3>
          <span id="closeModalDomisili">✖️</span>
        </div>
        <hr>
        <div class="auto-fill-note">
          ✓ Data di bawah ini otomatis terisi dari database Anda. Silakan periksa dan lengkapi jika ada yang kosong.
        </div>
        <form action="proses_domisili.php" method="POST" class="modal-form">
          <label>Nama Lengkap *</label>
          <input type="text" name="nama" placeholder="Nama Lengkap" value="<?php echo htmlspecialchars($nama); ?>" required readonly>
          
          <label>NIK * (Otomatis dari database)</label>
          <input type="text" name="nik" placeholder="NIK" value="<?php echo htmlspecialchars($nik); ?>" required readonly>
          
          <label>Alamat Lengkap *</label>
          <input type="text" name="alamat" placeholder="Alamat Lengkap" value="<?php echo htmlspecialchars($domisili_alamat); ?>" required>
          
          <label>RT/RW *</label>
          <input type="text" name="rt" placeholder="Contoh: 001/002" value="<?php echo htmlspecialchars($rt); ?>" required readonly>
          
          <label>Kelurahan * </label>
          <input type="text" name="kelurahan" placeholder="Kelurahan" value="<?php echo htmlspecialchars($kelurahan); ?>" required>
          
          <label>Kecamatan * </label>
          <input type="text" name="kecamatan" placeholder="Kecamatan" value="<?php echo htmlspecialchars($kecamatan); ?>" required>
          
          <label>Kota/Kabupaten </label>
          <input type="text" name="kota" placeholder="Kota/Kabupaten" value="<?php echo htmlspecialchars($kota); ?>" required>
          
          <label>No Handphone *</label>
          <input type="text" name="nohp" placeholder="Contoh: 081234567890" value="<?php echo htmlspecialchars($nohp); ?>" required>
          
          <label>Tanggal Lahir *</label>
          <input type="date" name="tanggal" value="<?php echo htmlspecialchars($tanggal_lahir); ?>" required>
          
          <input type="submit" value="Ajukan Dokumen">
        </form>
      </div>
    </div>
  </div>

  <!-- Modal untuk Pengantar RT -->
  <div class="pop-up-pengantar">
    <div class="pop-up22">
      <div class="modal">
        <div class="kepala">
          <h3>Form Pengantar RT</h3>
          <span id="closeModalPengantar">✖️</span>
        </div>
        <hr>
        <div class="auto-fill-note">
          ✓ Data di bawah ini otomatis terisi dari database Anda. Silakan periksa dan lengkapi jika ada yang kosong.
        </div>
        <form action="proses_pengantar.php" method="POST" class="modal-form">
          <label>Nama Lengkap *</label>
          <input type="text" name="nama" placeholder="Nama Lengkap" value="<?php echo htmlspecialchars($nama); ?>" required readpnly>
          
          <label>NIK * </label>
          <input type="text" name="nik" placeholder="NIK" value="<?php echo htmlspecialchars($nik); ?>" required readonly>
          
          <label>Alamat Lengkap *</label>
          <input type="text" name="alamat" placeholder="Alamat Lengkap" value="<?php echo htmlspecialchars($domisili_alamat); ?>" required>
          
          <label>RT/RW *</label>
          <input type="text" name="rt" placeholder="Contoh: 001/002" value="<?php echo htmlspecialchars($rt); ?>" required>
          
          <label>Kelurahan * </label>
          <input type="text" name="kelurahan" placeholder="Kelurahan" value="<?php echo htmlspecialchars($kelurahan); ?>" required>
          
          <label>Kecamatan * </label>
          <input type="text" name="kecamatan" placeholder="Kecamatan" value="<?php echo htmlspecialchars($kecamatan); ?>" required>
          
          <label>Kota/Kabupaten * </label>
          <input type="text" name="kota" placeholder="Kota/Kabupaten" value="<?php echo htmlspecialchars($kota); ?>" required>
          
          <label>No Handphone *</label>
          <input type="text" name="nohp" placeholder="Contoh: 081234567890" value="<?php echo htmlspecialchars($nohp); ?>" required>
          
          <label>Tanggal Lahir *</label>
          <input type="date" name="tanggal" value="<?php echo htmlspecialchars($tanggal_lahir); ?>" required>
          
          <input type="submit" value="Ajukan Dokumen">
        </form>
      </div>
    </div>
  </div>

  <header>
    <h1>SITAWAR</h1>
  </header>

  <nav>
    <ul>
      <a href="datasiWarga.html">
        <h2>Data Pribadi</h2>
      </a>
      <h2>Dokumen</h2>
      <a href="Data Ibu Hamil.html">
        <h2>Laporan</h2>
      </a>
    </ul>
  </nav>

  <main>
    <div class="top-row">
      <div class="menu-dokumen">
        <h4>Jenis Dokumen</h4>
        <hr>
        <div class="dokumen-tombol">
          <button type="button" id="openDomisili">Pengantar Domisili</button>
          <button type="button" id="openPengantar">Pengantar RT</button>
        </div>
      </div>
      <div class="alert-box">
        <p><strong>Halaman Pengajuan Dokumen</strong></p>
        <p>Halaman ini adalah bagian untuk membuat sebuah dokumen yang akan diajukan kepada RT, dengan tata cara sebagai berikut:</p>
        <ol>
          <li>Pilih jenis dokumen yang ingin diajukan pada menu jenis dokumen yang berada di samping kiri.</li>
          <li>Form akan otomatis terisi dengan data Anda dari database.</li>
          <li>Periksa dan lengkapi data yang masih kosong (seperti Kelurahan, Kecamatan, Kota).</li>
          <li>Klik tombol "Ajukan Dokumen" untuk mengirim permohonan.</li>
          <li>Tunggu konfirmasi dari RT mengenai status pengajuan dokumen Anda.</li>
        </ol>
        <p><strong>Catatan:</strong> Data NIK, Nama, RT, No HP, dan Tanggal Lahir akan otomatis terisi dari profil Anda.</p>
      </div>
    </div>

    <div class="riwayat">
      <div class="riwayat-header-row">
        <div>Riwayat Pelaporan</div>
        <div class="tampil-10">Tampilkan 10</div>
      </div>
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>No</th>
              <th>Jenis</th>
              <th>Tanggal Pembuatan</th>
              <th>Status</th>
              <th>Validasi</th>
              <th>Cetak</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>Domisili</td>
              <td>01/10/2025</td>
              <td>Selesai</td>
              <td class="validasi">Valid</td>
              <td><button onclick="cekValidasi(this)">Cetak</button></td>
            </tr>
            <tr>
              <td>2</td>
              <td>Pengantar RT</td>
              <td>02/10/2025</td>
              <td>Diproses</td>
              <td class="validasi">-</td>
              <td><button onclick="cekValidasi(this)">Cetak</button></td>
            </tr>
            <tr>
              <td>3</td>
              <td>Pengantar RT</td>
              <td>03/10/2025</td>
              <td>Selesai</td>
              <td class="validasi">Valid</td>
              <td><button onclick="cekValidasi(this)">Cetak</button></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <script>
    const openModalDomisili = document.getElementById('openDomisili');
    const openModalPengantar = document.getElementById('openPengantar');
    const popUpDomisili = document.querySelector('.pop-up11');
    const popUpPengantar = document.querySelector('.pop-up-pengantar');
    const closeModalDomisili = document.getElementById('closeModalDomisili');
    const closeModalPengantar = document.getElementById('closeModalPengantar');

    openModalDomisili.onclick = () => {
      popUpDomisili.style.display = 'flex';
    };

    openModalPengantar.onclick = () => {
      popUpPengantar.style.display = 'flex';
    };

    closeModalDomisili.onclick = () => {
      popUpDomisili.style.display = 'none';
    };

    closeModalPengantar.onclick = () => {
      popUpPengantar.style.display = 'none';
    };

    popUpDomisili.onclick = (e) => {
      if (e.target === popUpDomisili) {
        popUpDomisili.style.display = 'none';
      }
    };

    popUpPengantar.onclick = (e) => {
      if (e.target === popUpPengantar) {
        popUpPengantar.style.display = 'none';
      }
    };

    function cekValidasi(btn) {
      let validasi = btn.parentElement.parentElement.querySelector(".validasi").innerText.trim();

      if (validasi.toLowerCase() === "valid") {
        alert("Anda akan pergi ke halaman cetak dokumen");
        window.open("surat_domisili.html", "_blank");
      } else {
        alert("Dokumen belum divalidasi, tidak dapat mencetak!");
      }
    }
  </script>
</body>

</html>