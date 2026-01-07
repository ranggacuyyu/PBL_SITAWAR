<?php 
session_start();
// Pastikan file koneksi.php berada di folder yang sama
require_once '../koneksi.php';
require_once '../db_helper.php';

if (!isset($_SESSION['user_warga'])) {
    header("Location: sign-in_Warga.php");
    exit();
}
$warga = $_SESSION['user_warga']['nik_warga'];
$data = db_select_single($koneksi, "SELECT no_rt, no_rw, kecamatan, kelurahan, nama_warga, tempat_lahir, tanggal_lahir, jenis_kelamin, pekerjaan, agama, status_kawin FROM user_warga WHERE nik_warga=?", "s", [$warga])
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    body {
        padding: 0;
        max-width: 210mm;
        max-height: 297mm;
        margin: auto;
        background-color: rgb(238, 237, 237);
    }

    .bott {
        background-color: #fff;
        width: 210mm;
        height: 270mm;
        align-items: center;
        margin: auto;
        font-family: 'Gill Sans', sans-serif;
        padding: 20px 40px 40px 40px;
    }

    .head {
        display: flex;
        width: 100%;
        padding-top: 20px;
        border-bottom: 3px solid black;
    }

    .hading {
        text-align: center;
        width: 100%;
        gap: 0;
        padding-bottom: 20px;
    }

    .hading h2 {
        margin: 10px 10px 0px 10px;  
        
    }

    .head img {
        width: 100px;
        height: 120px;
        position: absolute;
        padding-top: 10px;
        padding-left: 10px;
        align-items: center;
        justify-content: center;
    }

    /* ---------------------------------- */
    .content-head {
        text-align: center;
        margin-top: 40px;
    }

    .content-head h3 {
        text-decoration: underline;
        margin: 0;
        padding-bottom: 1px
    }

    .content-head p {
        margin-top: 0;
    }

    .katapengantar {
        margin-top: 30px;
        text-align: justify;
        line-height: 1.6;
    }

    .tables {
        margin-top: 40px;
        margin-bottom: 40px;
    }

    table {

        border-collapse: collapse;
    }

    td {
        padding: 8px 15px 8px 30px;
        vertical-align: top;
    }

    .kata-penutup {
        margin-top: 20px;
        text-align: justify;
        line-height: 1.6;
    }
</style>

<body>
    <div class="bott">
        <div class="head" >
            <img src="image222.png" alt="" srcset="">
            <div class="hading">
                <h2>PEMERINTAH PROVINSI <span>KEPULAUAN RIAU</span></h2>
                <h2>KETUA RT <span><?php echo htmlspecialchars($data['no_rt']); ?></span> RW <span><?php echo htmlspecialchars($data['no_rw']); ?></span> DESA <span><?php echo htmlspecialchars($data['kelurahan']); ?></span></h2>
                <h2>KECAMATAN <span><?php echo htmlspecialchars($data['kecamatan']); ?></span></h2>
            </div>
        </div>
        <div class="content-head">
            <h3>SURAT KETERANGAN DOMISILI</h3>
            <p>Nomor : ....../....../.....</p>
        </div>
        <div class="katapengantar">
            <p>&nbsp;&nbsp;&nbsp;&nbsp;Yang bertanda tangan di bawah ini Ketua RT <span><?php echo htmlspecialchars($data['no_rt']); ?></span> RW <span><?php echo htmlspecialchars($data['no_rw']); ?></span>
                Desa <span><?php echo htmlspecialchars($data['kelurahan']); ?></</span>
                Kecamatan
                <span><?php echo htmlspecialchars($data['kecamatan']); ?></span> Kota <span>BATAM</span> Dengan ini menerangkan bahwa:
            </p>
        </div>
        <div class="tables">
            <table>
                <tr>
                    <td>Nama Lengkap</td>
                    <td>:</td>
                    <td>
                        <?php echo htmlspecialchars($data['nama_warga']); ?>
                    </td>
                </tr>
                <tr>
                    <td>Tempat lahir,Tgl Lahir</td>
                    <td>:</td>
                    <td>
                        <?php echo htmlspecialchars($data['tempat_lahir']); ?> / <?php echo htmlspecialchars($data['tanggal_lahir']); ?>
                    </td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>
                        <?php echo htmlspecialchars($data['jenis_kelamin']); ?>
                    </td>
                </tr>
                <tr>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td>
                        <?php echo htmlspecialchars($data['pekerjaan']); ?>
                    </td>
                </tr>
                <tr>
                    <td>Agama</td>
                    <td>:</td>
                    <td>
                        <?php echo htmlspecialchars($data['agama']); ?>
                    </td>
                </tr>
                <tr>
                    <td>Status Perkawinan</td>
                    <td>:</td>
                    <td>
                        <?php echo htmlspecialchars($data['status_kawin']); ?>
                    </td>
                </tr>
                <tr>
                    <td>Kewarganegaraan</td>
                    <td>:</td>
                    <td>
                        Warga Negara Indonesia
                    </td>
                </tr>
            </table>
        </div>
        <div class="kata-penutup">
            <p>orang tersebut diatas, adalah benar benar warga kami dan berdomisili di RT <?php echo htmlspecialchars($data['no_rt']); ?></span> RW <span><?php echo htmlspecialchars($data['no_rw']); ?></span>
                Desa <span><?php echo htmlspecialchars($data['kelurahan']); ?></span> Kecamatan <span><?php echo htmlspecialchars($data['kecamatan']); ?> Kota <span>BATAM</span>. Surat keterangan ini dibuat
                sebagai kelengkapan pengurusan surat <span>Keterangan Domisili</span><br><br> &nbsp;&nbsp;&nbsp;&nbsp;demikian surat
                keterangan ini kami buat, untuk
                dapat digunakan sebagaimana semestinya.
            </p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>