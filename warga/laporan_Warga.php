<?php

use LDAP\Result;

session_start();
include "../koneksi.php";

// 1. SET NIK MANUAL SESUAI KEINGINAN KAMU
if (!isset($_SESSION["user_warga"])) {
    header("location:../LoginRTWARGA.php");
}
$nik = $_SESSION["user_warga"]["nik_warga"];  


$usia = "SELECT TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) AS usia FROM user_warga WHERE nik_warga=?";
$stmt = mysqli_stmt_init($koneksi);
if(!mysqli_stmt_prepare($stmt, $usia)){
    echo "error";
}else{
    mysqli_stmt_bind_param($stmt, "s", $nik);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $usia_data = mysqli_fetch_assoc($result)['usia'];
}


$syarat = "SELECT * FROM user_warga WHERE keluarga = 'kepala keluarga' and nik_warga=?";
if(!mysqli_stmt_prepare($stmt, $syarat)){
    echo "error";
}else{
    mysqli_stmt_bind_param($stmt, "s", $nik);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $boleh = mysqli_num_rows($result) > 0;
}

$syarat_meninggal = mysqli_query($koneksi, "SELECT * FROM user_warga 
WHERE nik_warga='$nik' and  keluarga = 'kepala keluarga'");

$boleh_meninggal = mysqli_num_rows($syarat_meninggal) > 0;

// AMBIL DATA WARGA DARI DATABASE
$query = mysqli_query($koneksi, "SELECT * FROM user_warga WHERE nik_warga='$nik'");
$data = mysqli_fetch_assoc($query);

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

    <style>
        * {margin:0; padding:0; box-sizing:border-box;}
        body {
            background-image: url(../image/download.jpg);
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            background-size: cover;
            background-position: center;
            
        }
        header {
            background: linear-gradient(to right, #3f492d, #88976c);
            height: 13vh;
            align-items: center;
            display: flex;
            padding: 0 20px;
            color: white;
            position: fixed;
            width: 100%;
        }
        .bungkus {display: flex; background-color: #88976cce; padding-top: 13vh; }

        .sidebar {
            width: 220px; background-color: #879867;
            padding: 20px 10px; min-height: 100vh;
            position: fixed;
        }
        .menu-item {
            padding: 10px;  margin-bottom: 10px; color: white;
            cursor: pointer; border-radius: 4px; transition: .3s;
        }
        .menu-item:hover {background-color:white; color:#738B67; }
        .content {flex: 1; padding: 20px 30px 0 250px; min-height: 87vh;}

        .panduan {
            background-color: #879867ab; border: 2px solid #8A9A7A;
            padding: 20px; margin-bottom: 20px;
        }
        .panduan h3 {margin-bottom: 15px;}
        .panduan li {margin-bottom: 8px; font-size: 16px;}

        .form-container {
            background-color: #879867ab; padding: 30px; border-radius: 5px;
        }
        .form-group {display:flex; align-items:center; margin-bottom:15px;}
        .form-group label {width:200px; font-size:18px; font-weight:bold;}
        .form-group input, .form-group select {
            flex:1; padding:10px; font-size:16px; border:1px solid #ccc;
            background-color:white;
        }

        .jenis-buttons {display:flex; gap:10px;}
        .jenis-btn {padding:8px 20px; background: #6B7D5F; color:white; border:none;}
        .jenis-btn:hover {background-color:#ffffffa9;}

        .submit-btn {
            background-color:#32CD32; color:white; padding:12px 40px;
            border:none; font-size:16px; font-weight:bold; cursor:pointer;
            float:right; margin-top:20px;
        }
        .submit-btn:hover {background:#28A428;}

        #dataIbuHamil, #dataWargaMeninggal {display:none;}
        .show {display:block !important;}
    </style>
</head>

<body>
<header>
    <h1>SITAWAR</h1>
</header>

<div class="bungkus">
    <div class="sidebar" style="font-weight: 900;">
        <div class="menu-item" onclick="window.location.href='data_Warga.php'"><h2>Data Pribadi</h2></div>
        <div class="menu-item" onclick="window.location.href='dokumen_Warga.php'"><h2>Dokumen</h2></div>
        <div class="menu-item"><h2>Laporan</h2></div>
    </div>

    <div class="content">

        <div class="panduan">
            <h3>Panduan:</h3>
            <ol>
                <li>Pastikan data dibawah sudah terisi dengan benar</li>
                <li>Isi data yang kosong sesuai dengan yang diminta</li>
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
                        <input type="text" id="namaPelapor" name="nama_pelapor" value="<?= $nama_pelapor ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>No Telephone </label>
                        <input type="number" id="noTelephone" name="nohp_pelapor" value="<?= $nohp_pelapor ?>" readonly>
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
                            <button type="button" class="jenis-btn" onclick="toggleJenis('selanjutnya')">Selanjutnya</button>
                            <button type="button" class="jenis-btn" onclick="toggleJenis('ulang')">Ulang</button>
                    </div>
                </div>

                <!-- Data Ibu Hamil -->
                <div class="form-section" id="dataIbuHamil">
                    <h2>Data Ibu Hamil</h2>

                    <div class="form-group">
                        <label>Nama Warga</label>
                        <input type="text" name="nama_subjek1">
                    </div>
                    
                    <div class="form-group">
                        <label>Umur</label>
                        <input type="number" name="umur_subjek1">
                    </div>
                    
                    <div class="form-group">
                        <label>Blok Rumah</label>
                        <input type="text" name="blok_subjek1">
                    </div>

                    <button type="submit" class="submit-btn">Ajukan</button>
                </div>

                <div class="form-section" id="dataWargaMeninggal">
                    <h2>Data Warga Meninggal</h2>

                    <div class="form-group">
                        <label>Nama Warga</label>
                        <input type="text" name="nama_subjek">
                    </div>
                    
                    <div class="form-group">
                        <label>Umur</label>
                        <input type="number" name="umur_subjek">
                    </div>

                    <div class="form-group">
                        <label>Tanggal Meninggal</label>
                        <input type="date" name="tanggal_meninggal">
                    </div>

                    <div class="form-group">
                        <label>Blok Rumah</label>
                        <input type="text" name="blok_subjek">
                    </div>
                    
                    <button type="submit" class="submit-btn">Ajukan</button>
                </div>

                </div>
            </form>

        </div>

    </div>
</div>

<script>
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
    }

    else if (action === 'ulang') {
        document.getElementById('mainForm').reset();
        dataIbuHamil.classList.remove('show');
        dataWargaMeninggal.classList.remove('show');
    }
}
</script>

</body>
</html>
