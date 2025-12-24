<?php
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';

// 1. SET NIK MANUAL SESUAI KEINGINAN KAMU
if (!isset($_SESSION["user_warga"])) {
    header("location:../LoginRTWARGA.php");
}
$nik = $_SESSION["user_warga"]["nik_warga"];
$usia_data = db_select_single($koneksi, "SELECT TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) AS usia FROM user_warga WHERE nik_warga=?", "s", [$nik]);

$boleh = db_select_single($koneksi, "SELECT nama_warga FROM user_warga WHERE keluarga = 'kepala keluarga' and nik_warga=?", "s", [$nik]);
$boleh_meninggal = db_select_single($koneksi, "SELECT nama_warga FROM user_warga WHERE nik_warga=? and  keluarga = 'kepala keluarga'", "s", [$nik]);

/* ambil data */
$data = db_select_single($koneksi, "SELECT nama_warga, hp FROM user_warga WHERE nik_warga=?", "s", [$nik]);
if (!$data) {
    die("Data warga tidak ditemukan");
}

$nama_pelapor = $data['nama_warga'];
$nohp_pelapor = $data['hp'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SITAWAR - Sistem Informasi</title>
    <link rel="stylesheet" href="laporan_warga.css">
</head>

<body>
    <header>
        <h1>SITAWAR</h1>
    </header>

    <div class="bungkus">
        <div class="sidebar" style="font-weight: 900;">
            <div class="menu-item" onclick="window.location.href='data_Warga.php'">
                <h2>Data Pribadi</h2>
            </div>
            <div class="menu-item" onclick="window.location.href='dokumen_Warga.php'">
                <h2>Dokumen</h2>
            </div>
            <div class="menu-item">
                <h2>Laporan</h2>
            </div>
        </div>

        <div class="content">

            <div class="panduan">
                <h3>Panduan:</h3>
                <ol>
                    <li style="font-size: 18px;">Pastikan data dibawah sudah terisi dengan benar dan mengisi data sesuai
                        dengan yang diminta</li>
                    <li style="font-size: 18px;">Hanya pengguna dengan status dalam keluarga adalah kepala keluarga yang
                        dapat membuat laporan</li>
                    <li style="font-size: 18px;">Untuk pelaporan ibu Hamil hanya dapat melaporkan ibu hamil yang dalam
                        satu keluarga atau satu No kk</li>
                    <li style="font-size: 18px;">Untuk pelaporan warga wafat hanya dapat melaporkan warga wafat yang
                        dalam satu keluarga atau satu No kk</li>
                    <li style="font-size: 18px;">jika dalam keluarga seorang kepala keluarga wafat maka dapat menelpon
                        RT untuk mendafatarkan beliau wafat</li>
                    <li style="font-size: 18px;">nomor HP RT dapat diambil dari halaman DATA PRIBADI</li>
                </ol>
            </div>

            <div class="form-container">
                <form id="mainForm" method="POST" action="proses_laporan.php">
                    <!-- Data Pelapor -->
                    <div class="form-section">
                        <h2>Data Pelapor</h2>

                        <input type="hidden" name="nik_pelapor" value="<?= $nik ?>">

                        <div class="form-group">
                            <label>Nama </label>
                            <input type="text" id="namaPelapor" name="nama_pelapor" value="<?= $nama_pelapor ?>"
                                readonly>
                        </div>

                        <div class="form-group">
                            <label>No Telephone </label>
                            <input type="number" id="noTelephone" name="nohp_pelapor" value="<?= $nohp_pelapor ?>"
                                readonly>
                        </div>

                        <div class="form-group">
                            <label>Blok Rumah </label>
                            <input type="text" id="blokRumahPelapor" name="blok_pelapor" required>
                        </div>

                        <div class="form-group">
                            <label>Jenis </label>
                            <select id="jenisSelect" name="jenis_laporan">
                                <option value="ibu-hamil">Ibu Hamil</option>
                                <option value="warga-meninggal">Warga Meninggal</option>
                            </select>
                        </div>

                        <div class="jenis-buttons" style="margin-top:10px;">
                            <button type="button" class="jenis-btn"
                                onclick="toggleJenis('selanjutnya')">Selanjutnya</button>
                            <button type="button" class="jenis-btn" onclick="toggleJenis('ulang')">Ulang</button>
                        </div>
                    </div>

                    <!-- Data Ibu Hamil -->
                    <div class="form-section" id="dataIbuHamil">
                        <h2>Data Ibu Hamil</h2>

                        <div class="form-group">
                            <label>Nama Warga (Ibu Hamil)</label>
                            <select id="namaIbuHamil" name="nama_subjek1" required>
                                <option value="">Pilih Nama Warga...</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Umur</label>
                            <input type="number" id="umurIbuHamil" name="umur_subjek1" readonly required>
                        </div>

                        <div class="form-group">
                            <label>Blok Rumah</label>
                            <input type="text" name="blok_subjek1" required>
                        </div>

                        <button type="submit" class="submit-btn">Ajukan</button>
                    </div>

                    <div class="form-section" id="dataWargaMeninggal">
                        <h2>Data Warga Meninggal</h2>

                        <div class="form-group">
                            <label>Nama Warga</label>
                            <select id="namaWargaMeninggal" name="nama_subjek" required>
                                <option value="">Pilih Nama Warga...</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Umur</label>
                            <input type="number" id="umurWargaMeninggal" name="umur_subjek" readonly required>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Meninggal</label>
                            <input type="date" name="tanggal_meninggal" required>
                        </div>

                        <div class="form-group">
                            <label>Blok Rumah</label>
                            <input type="text" name="blok_subjek" required>
                        </div>

                        <button type="submit" class="submit-btn">Ajukan</button>
                    </div>

            </div>
            </form>

        </div>

    </div>
    </div>

    <script>
        // Data anggota keluarga untuk dropdown
        let familyMembers = [];

        // Load data anggota keluarga saat halaman dimuat
        window.addEventListener('DOMContentLoaded', function() {
            loadFamilyMembers();
        });

        // Fungsi untuk load data anggota keluarga via AJAX
        function loadFamilyMembers() {
            fetch('get_family_members.php')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error:', data.error);
                        return;
                    }

                    familyMembers = data;
                    populateDropdowns();
                })
                .catch(error => {
                    console.error('Error fetching family members:', error);
                });
        }

        // Fungsi untuk mengisi dropdown dengan data anggota keluarga
        function populateDropdowns() {
            const selectIbuHamil = document.getElementById('namaIbuHamil');
            const selectWargaMeninggal = document.getElementById('namaWargaMeninggal');

            // Clear existing options except the first one
            selectIbuHamil.innerHTML = '<option value="">Pilih Nama Warga...</option>';
            selectWargaMeninggal.innerHTML = '<option value="">Pilih Nama Warga...</option>';

            // Populate dropdown ibu hamil (hanya perempuan)
            familyMembers.forEach(member => {
                if (member.jenis_kelamin === 'Perempuan') {
                    const option = document.createElement('option');
                    option.value = member.nama_warga;
                    option.setAttribute('data-umur', member.umur);
                    option.setAttribute('data-nik', member.nik_warga);
                    option.textContent = member.nama_warga;
                    selectIbuHamil.appendChild(option);
                }
            });

            // Populate dropdown warga meninggal (semua jenis kelamin)
            familyMembers.forEach(member => {
                if (member.keluarga === 'anggota keluarga') {
                    const option = document.createElement('option');
                    option.value = member.nama_warga;
                    option.setAttribute('data-umur', member.umur);
                    option.setAttribute('data-nik', member.nik_warga);
                    option.textContent = member.nama_warga;
                    selectWargaMeninggal.appendChild(option);
                }
            });
        }

        // Event listener untuk auto-fill umur pada dropdown ibu hamil
        document.addEventListener('DOMContentLoaded', function() {
            const selectIbuHamil = document.getElementById('namaIbuHamil');
            const umurIbuHamil = document.getElementById('umurIbuHamil');

            selectIbuHamil.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value) {
                    const umur = selectedOption.getAttribute('data-umur');
                    umurIbuHamil.value = umur;
                } else {
                    umurIbuHamil.value = '';
                }
            });
        });

        // Event listener untuk auto-fill umur pada dropdown warga meninggal
        document.addEventListener('DOMContentLoaded', function() {
            const selectWargaMeninggal = document.getElementById('namaWargaMeninggal');
            const umurWargaMeninggal = document.getElementById('umurWargaMeninggal');

            selectWargaMeninggal.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value) {
                    const umur = selectedOption.getAttribute('data-umur');
                    umurWargaMeninggal.value = umur;
                } else {
                    umurWargaMeninggal.value = '';
                }
            });
        });

        function toggleJenis(action) {
            const dataIbuHamil = document.getElementById('dataIbuHamil');
            const dataWargaMeninggal = document.getElementById('dataWargaMeninggal');
            const namaPelapor = document.getElementById('namaPelapor').value.trim();
            const noTelephone = document.getElementById('noTelephone').value.trim();
            const blokPelapor = document.getElementById('blokRumahPelapor').value.trim();

            var dataWarga = <?= json_encode($boleh); ?>;
            var WargaMeninggal = <?= json_encode($boleh_meninggal); ?>;

            if (!dataWarga && document.getElementById('jenisSelect').value === 'ibu-hamil') {
                alert('Maaf, Anda tidak memenuhi syarat untuk melaporkan Ibu Hamil.');
                return;
            } else if (!WargaMeninggal && document.getElementById('jenisSelect').value === 'warga-meninggal') {
                alert('Maaf, Anda tidak memenuhi syarat untuk melaporkan Warga Meninggal.');
                return;
            }

            if (action === 'selanjutnya') {
                if (namaPelapor === '' || noTelephone === '' || blokPelapor === '') {
                    alert('Harap lengkapi data pelapor terlebih dahulu.');
                    return;
                }

                dataIbuHamil.classList.remove('show');
                dataWargaMeninggal.classList.remove('show');

                const jenis = document.getElementById('jenisSelect').value;
                if (jenis === 'ibu-hamil') {
                    dataIbuHamil.classList.add('show');
                } else {
                    dataWargaMeninggal.classList.add('show');
                }
            } else if (action === 'ulang') {
                document.getElementById('mainForm').reset();
                dataIbuHamil.classList.remove('show');
                dataWargaMeninggal.classList.remove('show');
                // Reset umur fields
                document.getElementById('umurIbuHamil').value = '';
                document.getElementById('umurWargaMeninggal').value = '';
            }
        }
    </script>

</body>

</html>