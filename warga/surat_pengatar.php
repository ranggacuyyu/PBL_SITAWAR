<?php 
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';

if (!isset($_SESSION['user_warga'])) {
    header("Location: sign-in_Warga.php");
    exit();
}

$warga = $_SESSION['user_warga']['nik_warga'];

$data = db_select_single(
    $koneksi,
    "SELECT no_rt, no_rw, kecamatan, kelurahan, nama_warga, tempat_lahir, tanggal_lahir,
            jenis_kelamin, pekerjaan, agama, status_kawin
     FROM user_warga WHERE nik_warga=?",
    "s",
    [$warga]
);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Surat Pengantar RT</title>

<style>
body{
    background:#eee;
    font-family: Arial, sans-serif;
}
.sheet{
    width:210mm;
    min-height:297mm;
    background:#fff;
    margin:auto;
    padding:40px;
    color:#000;
}
.header{
    text-align:center;
    border-bottom:2px solid #000;
    padding-bottom:10px;
}
.header h3, .header h4{
    margin:3px 0;
}
.content{
    margin-top:30px;
    line-height:1.7;
}
table{
    margin-top:20px;
}
td{
    padding:5px 10px;
    vertical-align:top;
}
.footer{
    margin-top:50px;
    display:flex;
    justify-content:flex-end;
}
.ttd{
    text-align:center;
}
</style>
</head>

<body>
<div class="sheet">

    <div class="header">
        <h3>SURAT PENGANTAR</h3>
        <h4>RT <?= htmlspecialchars($data['no_rt']) ?> / RW <?= htmlspecialchars($data['no_rw']) ?></h4>
        <p>Kelurahan <?= htmlspecialchars($data['kelurahan']) ?>, Kecamatan <?= htmlspecialchars($data['kecamatan']) ?></p>
    </div>

    <div class="content">
        <p>
            Yang bertanda tangan di bawah ini Ketua RT 
            <?= htmlspecialchars($data['no_rt']) ?> RW 
            <?= htmlspecialchars($data['no_rw']) ?>,
            Kelurahan <?= htmlspecialchars($data['kelurahan']) ?>,
            Kecamatan <?= htmlspecialchars($data['kecamatan']) ?>,
            dengan ini menerangkan bahwa:
        </p>

        <table>
            <tr>
                <td>Nama Lengkap</td><td>:</td>
                <td><?= htmlspecialchars($data['nama_warga']) ?></td>
            </tr>
            <tr>
                <td>Tempat / Tanggal Lahir</td><td>:</td>
                <td><?= htmlspecialchars($data['tempat_lahir']) ?>, <?= htmlspecialchars($data['tanggal_lahir']) ?></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td><td>:</td>
                <td><?= htmlspecialchars($data['jenis_kelamin']) ?></td>
            </tr>
            <tr>
                <td>Pekerjaan</td><td>:</td>
                <td><?= htmlspecialchars($data['pekerjaan']) ?></td>
            </tr>
            <tr>
                <td>Agama</td><td>:</td>
                <td><?= htmlspecialchars($data['agama']) ?></td>
            </tr>
            <tr>
                <td>Status Perkawinan</td><td>:</td>
                <td><?= htmlspecialchars($data['status_kawin']) ?></td>
            </tr>
        </table>

        <p>
            Adalah benar warga yang berdomisili di lingkungan RT 
            <?= htmlspecialchars($data['no_rt']) ?> RW 
            <?= htmlspecialchars($data['no_rw']) ?>.
            Surat pengantar ini diberikan sebagai kelengkapan administrasi
            pengurusan dokumen ke tingkat selanjutnya.
        </p>
    </div>

    <div class="footer">
        <div class="ttd">
            <p>Batam, <?= date('d F Y') ?></p>
            <p>Ketua RT <?= htmlspecialchars($data['no_rt']) ?></p>
            <br><br><br>
            <p><u>____________________</u></p>
        </div>
    </div>

</div>

<script>
window.onload = () => window.print();
</script>

</body>
</html>
    