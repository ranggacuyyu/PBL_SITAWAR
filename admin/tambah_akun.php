<?php
session_start();
include "../koneksi.php";
mysqli_report(MYSQLI_REPORT_OFF);

$nik_rt      = "";
$nama_rt     = "";
$nohp_rt     = "";
$sk_rt       = "";
$alamat_rt   = "";
$password_rt = "";
$error       = "";
$sukses      = "";

$id_admin = $_SESSION['admin_user']['id_admin'];

if (isset($_POST["submit"])) {
    $nik    = $_POST["nik"];
    $pass   = $_POST["pass"];
    $nama   = $_POST["nama"];
    $nohp   = $_POST["nohp"];
    $sk     = $_POST["sk"];
    $alamat = $_POST["alamat"];

    if ($nik && $pass && $nama && $nohp && $sk && $alamat) {
        $sql1 = "insert into user_rt(sk_rt,nik_rt,nama_rt,nohp_rt,alamat_rt,password,admin) values ('$sk','$nik','$nama','$nohp','$alamat','$pass',$id_admin)";
        $q1 = mysqli_query($koneksi, $sql1);
        if ($q1) {
            $sukses = "berhasil";
        } else {
            $error = "gagal";
        }
    } else{
        $error = "silahkan masukkan semua data";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="tambahAkun.css">
</head>

<body>
    <main style="height: 100vh;">
        <aside>
            <h2>SITAWAR ADMIN</h2>
            <ul>
                <li onclick="window.location.href='dashborad_admin.php'">Daftar RT</li>
                <li>Tambah RT</li>
            </ul>
        </aside>
        <section>
            <div id="tambahRT" class="section hidden">
                <form id="formTambahRT" method="POST">
                    <?php
                    if ($error) {
                    ?>
                        <div style="width: 100%; height:50px; background-color:tomato;" role="alert">
                            <?php echo $error ?>
                        </div>
                    <?php
                    }
                    ?>
                    <?php

                    if ($sukses) {
                    ?>
                        <div style="width: 100%; height:50px; background-color:#404739; color:white; font-size:20px;" role="alert">
                            <?php echo $sukses ?>
                        </div>
                    <?php
                    }
                    ?>
                    <h3>Tambah Akun RT</h3>
                    <input type="number" id="nik" placeholder="NIK (16 digit)" maxlength="16" required name="nik" value="<?php echo $nik_rt ?>">
                    <input type="password" id="pass" placeholder="Password" maxlength="16" required name="pass" value="<?php echo $password_rt ?>">
                    <input type="text" id="nama" placeholder="Nama Lengkap" required name="nama" value="<?php echo $nama_rt ?>">
                    <input type="text" id="nohp" placeholder="Nomor HP" maxlength="13" required name="nohp" value="<?php echo $nohp_rt ?>">
                    <input type="text" id="sk" placeholder="Nomor SK Pengangkatan" required name="sk" value="<?php echo $sk_rt ?>">
                    <input type="text" id="alamat" placeholder="Alamat" required name="alamat" value="<?php echo $alamat_rt ?>">
                    <button type="submit" name="submit">Tambah RT</button>
                </form>
                <div id="pesan"></div>
            </div>
        </section>
    </main>
</body>

</html>