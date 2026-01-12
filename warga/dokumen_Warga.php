<?php
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';

if (!isset($_SESSION['user_warga'])) {
  header("Location: ../LoginRTWARGA.php");
  exit();
}

$nik_login = $_SESSION['user_warga']['nik_warga'];
$row = db_select_single($koneksi, 
"SELECT nama_warga, nik_warga, rt, hp, tanggal_lahir, alamat, agama, jenis_kelamin, status_kawin, no_kk, no_rt, no_rw, kelurahan, kecamatan, dokumen FROM user_warga WHERE nik_warga =?", 
"s", 
[$nik_login]);

$nama           = $row['nama_warga'];
$nik            = $row['nik_warga'];
$rt             = $row['rt'];
$nohp           = $row['hp'];
$tanggal_lahir  = $row['tanggal_lahir'];
$alamat         = $row['alamat'];
$agama          = $row['agama'];
$jenis_kelamin  = $row['jenis_kelamin'];
$status_kawin   = $row['status_kawin'];
$no_kk          = $row['no_kk'];
$no_rt          = $row['no_rt'];
$no_rw          = $row['no_rw'];
$kelurahan      = $row['kelurahan'];
$kecamatan      = $row['kecamatan'];
$kota           = "BATAM";
$jenis_dokumen  = $row['dokumen'];


// Gabungkan RT dan RW
$rt_rw = "";
if ($no_rt && $no_rw) {
  $rt_rw = $no_rt . "/" . $no_rw;
}

$result_dokumen    = db_select_no_assoc($koneksi, "SELECT * FROM dokumen WHERE warga=? ORDER BY tanggal DESC", "s", [$nik_login]);
$cekAktif_domisili = db_select_no_assoc($koneksi,"SELECT * FROM dokumen 
WHERE warga=? AND status IN ('pending','valid') AND jenis_dokumen='domisili'", "s", [$nik_login]);
$cekAktif_laporan  = db_select_no_assoc($koneksi, "SELECT * FROM dokumen 
WHERE warga=? AND status IN ('pending','valid') AND jenis_dokumen='Pengantar RT'", "s", [$nik_login]);

$adaPengajuan_domisili = mysqli_num_rows($cekAktif_domisili);
$adaPengajuan_laporan = mysqli_num_rows($cekAktif_laporan);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>SITAWAR - Dokumen Warga</title>
  <link rel="stylesheet" href="dokumen_Warga.css" />
  <style>
    .cetak_button {
      background-color: #687e4cff;
      border: 1px solid #94ac76ff;
      padding: 6px 16px;
      color: #efffd9ff;
      cursor: pointer;
      transition: background-color 0.3s ease; 
      color: 0.3s ease;
      transform: 0.3s ease;
    }

    .cetak_button:hover {
      background-color: #d0e7b1ff;
      color: #5a6947ff;
      transform: translateY(-1px);
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
          <input type="text" name="alamat" placeholder="Alamat Lengkap" value="<?php echo htmlspecialchars($alamat); ?>" required>

          <label>RT/RW *</label>
          <input type="text" name="rt" placeholder="Contoh: RT001/RW002" value="<?php echo htmlspecialchars($rt_rw); ?>" required readonly>

          <label>Kelurahan * </label>
          <input type="text" name="kelurahan" placeholder="Kelurahan" value="<?php echo htmlspecialchars($kelurahan); ?>" required>

          <label>Kecamatan * </label>
          <input type="text" name="kecamatan" placeholder="Kecamatan" value="<?php echo htmlspecialchars($kecamatan); ?>" required>

          <label>Kota/Kabupaten </label>
          <input type="text" name="kota" placeholder="Kota/Kabupaten" value="<?php echo htmlspecialchars($kota); ?>" required>

          <label>No Handphone *</label>
          <input type="text" name="nohp" placeholder="Contoh: 081234567890" value="<?php echo htmlspecialchars($nohp); ?>" required>

          <label>Tanggal Lahir *</label>
          <input type="date" name="tanggal" value="<?php echo htmlspecialchars($tanggal_lahir); ?>" required readonly>

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
          <input type="text" name="nama" placeholder="Nama Lengkap" value="<?php echo htmlspecialchars($nama); ?>" required readonly>

          <label>NIK * </label>
          <input type="text" name="nik" placeholder="NIK" value="<?php echo htmlspecialchars($nik); ?>" required readonly>

          <label>Alamat Lengkap *</label>
          <input type="text" name="alamat" placeholder="Alamat Lengkap" value="<?php echo htmlspecialchars($alamat); ?>" required>

          <label>RT/RW *</label>
          <input type="text" name="rt" placeholder="Contoh: RT001/RW002" value="<?php echo htmlspecialchars($rt_rw); ?>" required readonly>

          <label>Kelurahan * </label>
          <input type="text" name="kelurahan" placeholder="Kelurahan" value="<?php echo htmlspecialchars($kelurahan); ?>" required readonly>

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
      <a href="data_Warga.php">
        <h2>Data Pribadi</h2>
      </a>
      <h2>Dokumen</h2>
      <a href="laporan_Warga.php">
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
          <?php if ($adaPengajuan_laporan == 0 and $adaPengajuan_domisili == 0) { ?>
            <button type="button" id="openDomisili">Pemindahan Domisili</button>
            <button type="button" id="openPengantar">Pengantar RT</button>

          <?php } elseif ($adaPengajuan_laporan == 0 and $adaPengajuan_domisili == 1) { ?>
            <button disabled style="opacity:0.5">Pengajuan Sedang Diproses</button>
            <button type="button" id="openPengantar">Pengantar RT</button>

          <?php } elseif ($adaPengajuan_laporan == 1 and $adaPengajuan_domisili == 0) { ?>
            <button type="button" id="openDomisili">Pemindahan Domisili</button>
            <button disabled style="opacity:0.5">Pengajuan Sedang Diproses</button>

          <?php } else { ?>
            <button disabled style="opacity:0.5">Pengajuan Sedang Diproses</button>
            <button disabled style="opacity:0.5">Pengajuan Sedang Diproses</button>
          <?php } ?>

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
        <div>Riwayat Pembuatan Dokumen</div>
      </div>
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>No</th>
              <th>Jenis</th>
              <th>Tanggal Pengajuan</th>
              <th>Status</th>
              <th>Validasi</th>
              <th>Cetak</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($result_dokumen && mysqli_num_rows($result_dokumen) > 0) {
              $no = 1;
              while ($row_dok  = mysqli_fetch_assoc($result_dokumen)) {
                $nama_warga    = htmlspecialchars($row_dok['nama_warga'] ?? '-');
                $jenis_dokumen = htmlspecialchars($row_dok['jenis_dokumen'] ?? 'Surat Keterangan');
                $tanggal       = htmlspecialchars($row_dok['tanggal'] ?? '-');
                $id_dokumen    = htmlspecialchars($row_dok['id_dokumen'] ?? '');
                $status        = "Diproses"; // Default status
                $validasi      = "-"; // Default belum validasi

                if ($row_dok['status'] == 'setuju') {
                  $status   = "Selesai";
                  $validasi = "Valid";
                } elseif ($row_dok['status'] == 'pending') {
                  $status   = "Diproses";
                  $validasi = "Belum Validasi";
                } elseif ($row_dok['status'] == 'tolak') {
                  $status   = "Ditolak";
                  $validasi = "Tidak Valid";
                }

                echo "<tr>";
                echo "<td>$no</td>";
                echo "<td>$jenis_dokumen</td>";
                echo "<td>$tanggal</td>";
                echo "<td>$status</td>";
                echo "<td class='validasi'>$validasi</td>";
                echo "<td ><button class='cetak_button' onclick=\"cekValidasi(this, '$id_dokumen')\">Cetak</button></td>";
                echo "</tr>";
                $no++;
              }
            } else {
              echo "<tr><td colspan='6'>Belum ada riwayat dokumen</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <script>
    const openModalDomisili   = document.getElementById('openDomisili');
    const openModalPengantar  = document.getElementById('openPengantar');
    const popUpDomisili       = document.querySelector('.pop-up11');
    const popUpPengantar      = document.querySelector('.pop-up-pengantar');
    const closeModalDomisili  = document.getElementById('closeModalDomisili');
    const closeModalPengantar = document.getElementById('closeModalPengantar');

    // ✅ CEK DULU JIKA BUTTON ADA
    if (openModalDomisili) {
      openModalDomisili.onclick = () => {
        popUpDomisili.style.display = 'flex';
      };
    }

    if (openModalPengantar) {
      openModalPengantar.onclick = () => {
        popUpPengantar.style.display = 'flex';
      };
    }

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

    function cekValidasi(btn, idDokumen) {
      let validasi = btn.parentElement.parentElement.querySelector(".validasi").innerText.trim();

      if (validasi.toLowerCase() === "valid") {
        alert("Anda akan pergi ke halaman cetak dokumen");
        window.open("surat domisili.php?id=" + idDokumen, "_blank");
      } else {
        alert("Dokumen belum divalidasi, tidak dapat mencetak!");
      }
    }
  </script>

</body>

</html>
<?php
mysqli_close($koneksi);
?>