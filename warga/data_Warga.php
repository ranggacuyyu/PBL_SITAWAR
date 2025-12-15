<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['user_warga']['nik_warga'])) {
    header("Location: ../loginRTWARGA.php");
    exit();
}

$nik = $_SESSION['user_warga']['nik_warga'];

$query = mysqli_query($koneksi, "SELECT * FROM user_warga WHERE nik_warga='$nik'");
$data = mysqli_fetch_assoc($query);
$skRT = $data['rt'];

$detail_rt = mysqli_query($koneksi, "SELECT * FROM user_rt WHERE sk_rt = '$skRT'");
$detail_rt1 = mysqli_query($koneksi, "SELECT * FROM user_rt WHERE sk_rt = '$skRT'");
$info_rt = mysqli_fetch_assoc($detail_rt);
$nama_rt = $info_rt['nama_rt'];
$info_rt1 = mysqli_fetch_assoc($detail_rt1);
$nama_rt1 = $info_rt1['nohp_rt'];


if (!$data) {
    echo "Data tidak ditemukan.";
    exit();
}

if (!empty($_SESSION['flash'])) {
    echo "<script>alert('$_SESSION[flash]');</script>";
    unset($_SESSION['flash']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pribadi - SITAWAR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="data_Warga.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>

<body>
    <header class="head">
        <h1 class="logo">SITAWAR</h1>
        <div class="waktu-uuu">
            <span id="clock"></span>
        </div>
        <div class="modal fade" id="ubahModal" tabindex="-1" aria-labelledby="ubahModalLabel" aria-hidden="true">
        </div>
    </header>

    <nav class="navbar">
        <div class="navigasi-navbar">
            <div class="list-navbar">
                <a href="#">
                    <h2>Data Pribadi</h2>
                </a>
                <a href="dokumen_Warga.php">
                    <h2>Dokumen</h2>
                </a>
                <a href="laporan_warga.php">
                    <h2>Laporan</h2>
                </a>
            </div>
        </div>
        <!-- KONTEN UTAMA -->
        <div class="konten" style="width: 100%; padding:40px 20px 20px 20px;">
            <!-- PROFILE CARD -->
            <div class="card shadow profile-card p-3" style="width: 30%;">
                <div class="gambar" align="center">
                    <img src="../image/download.jpg" alt="Avatar" class="mb-3">
                </div>
                <h4>
                    <?php echo htmlspecialchars($data['nama_warga']); ?>
                </h4>
                <p class="text-muted">NIK: <?php echo htmlspecialchars($data['nik_warga']); ?></p>
                <hr>
                <div class="text-start">
                    <p><i class="fas fa-calendar"></i> Tgl Lahir: <?php echo htmlspecialchars($data['tanggal_lahir']); ?></p>
                    <p><i class="fas fa-venus-mars"></i> Jenis Kelamin: <?php echo htmlspecialchars($data['jenis_kelamin']); ?></p>
                    <p><i class="fas fa-pray"></i> Agama: <?php echo htmlspecialchars($data['agama']); ?></p>
                    <p>Status Kawin: <span class="badge bg-success badge-custom"><?php echo htmlspecialchars($data['status_kawin']); ?></span></p>
                    <p>Pendidikan: <span class="badge bg-primary badge-custom"><?php echo htmlspecialchars($data['pendidikan']); ?></span></p>
                    <p>Pekerjaan: <span class="badge bg-info text-dark badge-custom"><?php echo htmlspecialchars($data['pekerjaan']); ?></span></p>
                    <hr>
                    <!-- Upload KTP & KK -->
                    <div>
                        <form action="proses_upload.php" method="POST" enctype="multipart/form-data">

                            <!-- Upload KK -->
                            <div class="mb-2">
                                <label id="label_kk" for="foto_kk" class="btn btn-outline-secondary upload-label w-100">
                                    <i class="fas fa-upload"></i> Upload KK
                                </label>
                                <input type="file" id="foto_kk" name="foto_kk" accept="image/*" hidden required>
                            </div>

                            <!-- Upload KTP -->
                            <div class="mb-2">
                                <label id="label_ktp" for="foto_ktp" class="btn btn-outline-secondary upload-label w-100">
                                    <i class="fas fa-upload"></i> Upload KTP
                                </label>
                                <input type="file" id="foto_ktp" name="foto_ktp" accept="image/*" hidden required>
                            </div>

                            <!-- Panduan -->
                            <div class="alert alert-info mt-3">
                                <strong>Syarat Upload Dokumen:</strong>
                                <ul class="mb-0">
                                    <li>Format file <b>JPG / PNG</b></li>
                                    <li>Ukuran maksimal <b>2 MB</b></li>
                                    <li>Foto harus <b>jelas, tidak buram, tidak terpotong</b></li>
                                    <li>Data harus <b>sesuai dengan biodata warga</b></li>
                                </ul>
                            </div>

                            <button type="submit" class="btn btn-success w-100 mt-2" id="kirimDokumenBtn">
                                <i class="fas fa-paper-plane"></i> Kirim Dokumen
                            </button>

                        </form>


                    </div>

                </div>
            </div>

            <!-- DETAIL DATA -->
            <div class="card shadow p-3 flex-grow-1" style="width: 70%;">
                <h4>Detail Informasi</h4>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover">
                        <tbody>
                            <tr>
                                <th>No KK</th>
                                <td><?php echo htmlspecialchars($data['no_kk']); ?></td>
                            </tr>
                            <tr>
                                <th>Tempat Tanggal Lahir</th>
                                <td><?php echo htmlspecialchars($data['tempat_lahir']); ?> / <?php echo htmlspecialchars($data['tanggal_lahir']); ?></td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td><?php echo htmlspecialchars($data['alamat']); ?></td>
                            </tr>
                            <tr>
                                <th>Status Dalam Keluarga</th>
                                <td><?php echo htmlspecialchars($data['keluarga']); ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?php echo htmlspecialchars($data['email']); ?></td>
                            </tr>
                            <tr>
                                <th>No HP</th>
                                <td><?php echo htmlspecialchars($data['hp']); ?></td>
                            </tr>
                            <tr>
                                <th>Kecamatan</th>
                                <td><?php echo htmlspecialchars($data['kecamatan']); ?></td>
                            </tr>
                            <tr>
                                <th>Kelurahan</th>
                                <td><?php echo htmlspecialchars($data['kelurahan']); ?></td>
                            </tr>
                            <tr>
                                <th>Domisili</th>
                                <td>BATAM</td>
                            </tr>
                            <tr>
                                <th>RT/RW</th>
                                <td><?php echo htmlspecialchars($data['no_rt']); ?> / <?php echo htmlspecialchars($data['no_rw']); ?></td>
                            </tr>
                            <tr>
                                <th>Nama RT</th>
                                <td><?php echo $nama_rt; ?> </td>
                            </tr>
                            <tr>
                                <th>No HP RT</th>
                                <td><?php echo $nama_rt1; ?> </td>
                            </tr>

                            <tr>
                                <th>Catatan</th>
                                <td>Warga aktif</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalProfil"><i class="fas fa-edit"></i> Ubah Data</button>
                    <button class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </div>
            </div>
        </div>
    </nav>

    <div class="modal fade" id="modalProfil" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Ubah Data Diri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="formProfil">
                        <input type="hidden" name="nik" value="<?php echo $data['nik_warga']; ?>">

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($data['nama_warga']); ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">NIK</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($data['nik_warga']); ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor HP</label>
                            <input type="text" class="form-control" name="hp" value="<?php echo htmlspecialchars($data['hp']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($data['email']); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" class="form-control" name="password">
                            <small class="text-muted">Kosongkan jika tidak ingin ganti password</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pekerjaan</label>
                            <input type="text" class="form-control" name="pekerjaan" value="<?php echo htmlspecialchars($data['pekerjaan']); ?>">
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            Simpan Perubahan
                        </button>
                    </form>

                </div>

            </div>
        </div>
    </div>






    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // ... (Kode JavaScript untuk Modal Anda di sini) ...
        });

        function updateClock() {
            const now = new Date();
            document.getElementById('clock').textContent =
                now.toLocaleTimeString('id-ID', {
                    hour12: false
                });
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
    <script>
        document.getElementById("foto_kk").addEventListener("change", function() {
            if (this.files.length > 0) {
                document.getElementById("label_kk").innerHTML =
                    '<i class="fas fa-check-circle text-success"></i> ' + this.files[0].name;
                document.getElementById("label_kk").classList.remove("btn-outline-secondary");
                document.getElementById("label_kk").classList.add("btn-outline-success");
            }
        });

        document.getElementById("foto_ktp").addEventListener("change", function() {
            if (this.files.length > 0) {
                document.getElementById("label_ktp").innerHTML =
                    '<i class="fas fa-check-circle text-success"></i> ' + this.files[0].name;
                document.getElementById("label_ktp").classList.remove("btn-outline-secondary");
                document.getElementById("label_ktp").classList.add("btn-outline-success");
            }
        });

        document.getElementById("kirimDokumenBtn").addEventListener("click", function(e) {
            if (!document.getElementById("foto_kk").files.length ||
                !document.getElementById("foto_ktp").files.length) {

                alert("Silakan pilih file KK dan KTP sebelum mengirim.");
                e.preventDefault(); // ✅ BENAR
                return;
            }
        });
        document.getElementById("formProfil").addEventListener("submit", function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            fetch("proses_ubah_profil.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.text())
                .then(data => {
                    alert(data);

                    let modal = bootstrap.Modal.getInstance(document.getElementById('modalProfil'));
                    modal.hide();

                    location.reload(); // refresh data baru
                });
        });
    </script>

</body>

</html>