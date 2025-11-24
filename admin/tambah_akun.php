<?php
session_start();
include "../koneksi.php";
mysqli_report(MYSQLI_REPORT_OFF);

$nik_rt      = "";
$nama_rt     = "";
$no_rt       = "";
$no_rw       = "";
$nohp_rt     = "";
$sk_rt       = "";
$wilayah     = "";
$error       = "";
$sukses      = "";

$id_admin = $_SESSION['admin_user']['id_admin'];

if (isset($_POST["submit"])) {
    $nik_rt     = $_POST["nik"];
    $no_rt      = $_POST["no_rt"];
    $no_rw      = $_POST["no_rw"];
    $nama_rt    = $_POST["nama"];
    $nohp_rt    = $_POST["nohp"];
    $sk_rt      = $_POST["sk"];
    $wilayah    = $_POST["wilayah"];

    if ($nik_rt && $no_rt && $no_rw && $nama_rt && $nohp_rt && $sk_rt && $wilayah) {
        $sql1 = "INSERT INTO user_rt( sk_rt, nik_rt, no_rt, no_rw, nama_rt, nohp_rt, wilayah_rt, admin) 
                 VALUES ('$sk_rt', '$nik_rt', '$no_rt', '$no_rw', '$nama_rt', '$nohp_rt', '$wilayah', '$id_admin')";
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
    <style>
        .form-floating>.form-control.small {
            height: 10px !important;
            /* kecilkan tinggi */
            padding: 0.25rem 0.5rem !important;
            /* kecilkan padding */
        }
    </style>
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
                    <!-- CARD PETUNJUK PENGISIAN FORM (WARNA MATCHA) -->
                    <div class="card mb-3" style="border: 2px solid #92B79F; border-radius: 10px; overflow: hidden;">
                        <div class="card-header" style="background-color: #A8C4AC; color: #2E4D3D; font-weight: 600;">
                            Petunjuk Pengisian Form Akun RT
                        </div>
                        <div class="card-body" style="background-color: #EEF4EF; color: #2E4D3D;">
                            <ul class="mb-0" style="font-size: 15px; padding-left: 18px;">
                                <li><strong>Nama Akun RT:</strong> Isi dengan nama lengkap ketua RT.</li>
                                <li><strong>SK Akun RT:</strong> Isi nomor SK resmi ketua RT.</li>
                                <li><strong>No RT & No RW:</strong> Masukkan angka saja tanpa titik.</li>
                                <li><strong>NIK Akun RT:</strong> Wajib 16 digit angka.</li>
                                <li><strong>No HP:</strong> Isi dengan nomor aktif (contoh: 08123456789).</li>
                                <li><strong>Password:</strong> Minimal 6 karakter (huruf/angka).</li>
                                <li><strong>Alamat Akun RT:</strong> Tulis alamat lengkap.</li>
                                <li><strong>Semua kolom wajib diisi</strong> sebelum menekan tombol <em>Tambah RT</em>.</li>
                            </ul>
                        </div>
                    </div>


                    <div class="form-floating">
                        <input type="text" class="form-control" placeholder="nama" required name="nama">
                        <label for="nama">Nama Akun RT</label>
                    </div>

                    <div class="form-floating">
                        <input type="text" class="form-control" placeholder="sk" required name="sk">
                        <label for="sk">SK Akun RT</label>
                    </div>

                    <div class="form-floating ">
                        <input type="number" class="form-control" placeholder="Nomor RT" required name="no_rt">
                        <label for="no_rt">No RT</label>
                    </div>

                    <div class="form-floating ">
                        <input type="number" class="form-control" placeholder="Nomor RW" required name="no_rw">
                        <label for="no_rw">No RW</label>
                    </div>

                    <div class="form-floating">
                        <input type="number" class="form-control" placeholder="nik" required name="nik">
                        <label for="nik">NIK AKUN RT</label>
                    </div>

                    <div class="form-floating">
                        <input type="number" class="form-control" placeholder="hp" required name="nohp">
                        <label for="hp">No HP Akun RT</label>
                    </div>

                    <div class="form-floating">
                        <input type="text" class="form-control" placeholder="alamat" required name="wilayah">
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