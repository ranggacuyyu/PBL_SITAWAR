<?php
session_start();
include "../koneksi.php";
mysqli_report(MYSQLI_REPORT_OFF);

$nik_rt      = "";
$nama_rt     = "";
$no_rt       = "";
$nohp_rt     = "";
$sk_rt       = "";
$alamat_rt   = "";
$pass        = "";
$error       = "";
$sukses      = "";

$id_admin = $_SESSION['admin_user']['id_admin'];

if (isset($_POST["submit"])) {  
    $nik    = $_POST["nik"];
    $pass   = $_POST["pass"];
    $no_rt   = $_POST["no_rt"];
    $nama   = $_POST["nama"];
    $nohp   = $_POST["nohp"];
    $sk     = $_POST["sk"];
    $alamat = $_POST["alamat"];

    if ($nik && $pass && $no_rt && $nama && $nohp && $sk && $alamat) {
        $sql1 = "INSERT INTO user_rt( sk_rt, nik_rt, no_rt, nama_rt, nohp_rt, alamat_rt, password, admin) 
                 VALUES ('$sk', '$nik', '$no_rt', '$nama', '$nohp', '$alamat', '$pass', '$id_admin')";
        $q1 = mysqli_query($koneksi, $sql1);

        if ($q1) {
            $sukses = "Data berhasil ditambahkan!";
        } else {
            $error = "Data gagal ditambahkan!";
        }
    } else {
        $error = "Silakan masukkan semua data.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="tambah_akun.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <main style="height: 100vh;">
        <aside>
            <h2 style="font-size: 24px; font-weight: 700; margin-bottom:0;">SITAWAR ADMIN</h2>
            <ul style="padding: 0;">
                <li onclick="window.location.href='dashborad_admin.php'">Daftar RT</li>
                <li>Tambah RT</li>
            </ul>
        </aside>

        <section>
            <div id="tambahRT" class="section hidden">
                <form id="formTambahRT" method="POST" class="d-flex flex-column gap-3">

                    <!-- ALERT ERROR -->
                    <?php if ($error) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><?php echo $error ?></strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php } ?>

                    <!-- ALERT SUKSES -->
                    <?php if ($sukses) { ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong><?php echo $sukses ?></strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php } ?>

                    <h3>Tambah Akun RT</h3>

                    <div class="form-floating">
                        <input type="text" class="form-control" placeholder="nama" required name="nama">
                        <label for="email">Nama Akun RT</label>
                    </div>

                    <div class="form-floating">
                        <input type="text" class="form-control" placeholder="sk" required name="sk">
                        <label for="sk">SK Akun RT</label>
                    </div>

                    <div class="form-floating">
                        <input type="text" class="form-control" placeholder="Nomor RT" required name="no_rt">
                        <label for="no_rt">No RT</label>
                    </div>

                    <div class="form-floating">
                        <input type="text" class="form-control" placeholder="nik" required name="nik">
                        <label for="nik">NIK AKUN RT</label>
                    </div>

                    <div class="form-floating">
                        <input type="text" class="form-control" placeholder="hp" required name="nohp">
                        <label for="hp">No HP Akun RT</label>
                    </div>

                    <div class="form-floating">
                        <input type="text" class="form-control" placeholder="pass" required name="pass">
                        <label for="pass">Password Akun RT</label>
                    </div>

                    <div class="form-floating">
                        <input type="text" class="form-control" placeholder="alamat" required name="alamat">
                        <label for="alamat">Alamat Akun RT</label>
                    </div>

                    <button type="submit" name="submit" class="btn btn-success w-100 btn-modern">Tambah RT</button>
                </form>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ALERT AUTO CLOSE -->
    <script>
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 3000); // auto close 3 detik
    </script>

</body>
</html>
