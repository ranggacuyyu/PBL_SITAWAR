<?php
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';

if (!isset($_SESSION['user_warga']['nik_warga'])) {
    header("Location: ../loginRTWARGA.php");
    exit();
}

$show_welcome = false;
if (!isset($_SESSION['welcome_shown'])) {
    $show_welcome = true;
    $_SESSION['welcome_shown'] = true;
}
$nik = $_SESSION['user_warga']['nik_warga'];

$rowRT = db_select_single($koneksi, "SELECT rt FROM user_warga WHERE nik_warga=?", "s", [$nik]);
$skRT = $rowRT['rt'];
$rowrt = db_select_single($koneksi, "SELECT nama_rt,nohp_rt FROM user_rt WHERE sk_rt =?", "s", [$skRT]);
$data = db_select_single($koneksi, "SELECT 
nik_warga, nama_warga, tanggal_lahir, jenis_kelamin, agama, status_kawin, no_kk, tempat_lahir, alamat, email, pekerjaan, pendidikan, hp, no_rt, no_rw, kecamatan, kelurahan, keluarga, foto_profile
FROM user_warga WHERE nik_warga = ?", "s", [$nik]);

$cek_kk_ktp = db_select_single(
    $koneksi,
    "SELECT id_dokumen,foto_kk, foto_ktp FROM dokumen_wargart WHERE id_warga = ?",
    "s",
    [$nik]
);

$notif = $_SESSION['notif'] ?? null;
unset($_SESSION['notif']);

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
    <link rel="stylesheet" href="../notif_warga.css">
    <!-- GSAP CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        /* === MATCHA MODAL STYLE === */
        .matcha-modal .modal-content {
            background: linear-gradient(135deg, #f5f9ed 0%, #e7f0da 100%);
            border-radius: 18px;
            border: none;
            box-shadow: 0 25px 70px rgba(90, 120, 80, 0.35);
            animation: matchaZoom 0.4s ease;
        }

        /* Header */
        .matcha-modal .modal-header {
            background: rgba(255, 255, 255, 0.55);
            border-bottom: none;
            padding: 1rem 1.25rem;
        }

        /* Title */
        .matcha-modal .modal-title {
            font-weight: 700;
            color: #3f5532;
        }

        /* Body */
        .matcha-modal .modal-body {
            padding: 1.5rem;
        }

        /* Image Card */
        .matcha-doc {
            background: white;
            border-radius: 16px;
            padding: 10px;
            margin-bottom: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        .matcha-doc:hover {
            transform: scale(1.02);
        }

        .matcha-doc img {
            border-radius: 12px;
            max-height: 350px;
            object-fit: contain;
        }

        /* Footer */
        .matcha-modal .modal-footer {
            border-top: none;
            padding: 1rem 1.25rem;
        }

        /* Buttons */
        .btn-matcha {
            background: linear-gradient(135deg, #6a7c4f, #8fa876);
            color: white;
            border-radius: 12px;
            border: none;
            padding: 8px 16px;
        }

        .btn-matcha:hover {
            opacity: 0.9;
        }

        .btn-danger-soft {
            background: #ffe3e3;
            color: #c0392b;
            border-radius: 12px;
            border: none;
        }

        /* Animation */
        @keyframes matchaZoom {
            from {
                opacity: 0;
                transform: scale(0.85);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>

<body>
    <div class="notifikasi">
        <?php if ($notif): ?>
            <div id="notif" class="notif">
                <?= htmlspecialchars($notif) ?>
            </div>
        <?php endif; ?>
    </div>
    <?php if ($show_welcome): ?>
        <!-- Welcome Overlay -->
        <div id="welcome-overlay">
            <div class="welcome-content">
                <h1 class="welcome-title">Selamat Datang, <?php echo htmlspecialchars($data['nama_warga']); ?></h1>
                <div class="welcome-divider"></div>
                <p class="welcome-subtitle">Sistem Terpadu Administrasi Warga (SITAWAR)</p>
            </div>
        </div>
    <?php endif; ?>

    <header class="head">
        <h1 class="logo">SITAWAR</h1>
        <div class="waktu-uuu">
            <span id="clock"></span>
        </div>
        <div class="modal fade" id="ubahModal" tabindex="-1" aria-labelledby="ubahModalLabel" aria-hidden="true">
        </div>
    </header>

    <nav class="navbar">
        <div class="navigasi-navbar" style="position: fixed;">
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
                    <?php
                    $foto_path_warga = !empty($data['foto_profile']) ? 'profile/' . $data['foto_profile'] : 'profile/default.jpg';
                    ?>
                    <img id="fotoProfilWarga" src="<?php echo $foto_path_warga; ?>" alt="Avatar" class="mb-3">

                    <!-- Form Upload Foto Profil -->
                    <form id="formUploadFotoWarga" enctype="multipart/form-data" class="mb-3">
                        <label for="fileAvatarWarga" class="btn btn-sm btn-outline-primary w-75">
                            <i class="fas fa-camera"></i> Ganti Foto Profil
                        </label>
                        <input id="fileAvatarWarga" type="file" name="foto_profile"
                            accept="image/jpeg,image/jpg,image/png,image/gif" hidden>
                        <div id="uploadStatusWarga" style="margin-top: 8px; font-size: 11px;"></div>
                    </form>
                </div>
                <h4>
                    <?php echo htmlspecialchars($data['nama_warga']); ?>
                </h4>
                <p class="text-muted">NIK: <?php echo htmlspecialchars($data['nik_warga']); ?></p>
                <hr>
                <div class="text-start">
                    <p><i class="fas fa-calendar"></i> Tgl Lahir:
                        <?php echo htmlspecialchars($data['tanggal_lahir']); ?>
                    </p>
                    <p><i class="fas fa-venus-mars"></i> Jenis Kelamin:
                        <?php echo htmlspecialchars($data['jenis_kelamin']); ?>
                    </p>
                    <p><i class="fas fa-pray"></i> Agama: <?php echo htmlspecialchars($data['agama']); ?></p>
                    <p>Status Kawin: <span
                            class="badge bg-success badge-custom"><?php echo htmlspecialchars($data['status_kawin']); ?></span>
                    </p>
                    <p>Pendidikan: <span
                            class="badge bg-primary badge-custom"><?php echo htmlspecialchars($data['pendidikan']); ?></span>
                    </p>
                    <p>Pekerjaan: <span
                            class="badge bg-info text-dark badge-custom"><?php echo htmlspecialchars($data['pekerjaan']); ?></span>
                    </p>
                    <hr>
                    <!-- Upload KTP & KK -->
                    <div>
                        <?php if ($cek_kk_ktp) { ?>
                            <div class="alert alert-info mt-3">
                                    <strong>Anda telah mengunggah foto KK dan KTP:</strong>
                                    <ul class="mb-0">
                                        <li>tekan tombol untuk melihat dan memperbarui foto KK dan KTP</li>
                                    </ul>
                                </div>
                            <button class="btn btn-matcha w-100" data-bs-toggle="modal" data-bs-target="#modalDokumen">
                                <i class="fas fa-eye"></i> Lihat Dokumen
                            </button>

                        <?php } elseif (!$cek_kk_ktp) { ?>
                            <form action="aksi_datawarga/proses_upload.php" method="POST" enctype="multipart/form-data">
                                <!-- Upload KK -->
                                <div class="mb-2">
                                    <label id="label_kk" for="foto_kk" class="btn btn-outline-secondary upload-label w-100">
                                        <i class="fas fa-upload"></i> Upload KK
                                    </label>
                                    <input type="file" id="foto_kk" name="foto_kk" accept="image/*" hidden required>
                                </div>

                                <!-- Upload KTP -->
                                <div class="mb-2">
                                    <label id="label_ktp" for="foto_ktp"
                                        class="btn btn-outline-secondary upload-label w-100">
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
                        <?php } ?>

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
                                <td><?php echo htmlspecialchars($data['tempat_lahir']); ?> /
                                    <?php echo htmlspecialchars($data['tanggal_lahir']); ?>
                                </td>
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
                                <td><?php echo htmlspecialchars($data['no_rt']); ?> /
                                    <?php echo htmlspecialchars($data['no_rw']); ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Nama RT</th>
                                <td><?php echo htmlspecialchars($rowrt['nama_rt']); ?></td>
                            </tr>
                            <tr>
                                <th>No HP RT</th>
                                <td><?php echo htmlspecialchars($rowrt['nohp_rt']); ?></td>
                            </tr>

                            <tr>
                                <th>Catatan</th>
                                <td>Warga aktif</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalProfil"><i
                            class="fas fa-edit"></i> Ubah Data</button>
                    <form action="logout_warga.php" method="post">
                        <button class="btn btn-danger" onclick="return confirm('Yakin ingin keluar?')"><i
                                class="fas fa-sign-out-alt"></i> Logout</button>
                    </form>
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
                    <form action="proses_ubah_profil.php" method="POST">
                        <input type="hidden" name="nik" value="<?php echo $data['nik_warga']; ?>">

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control"
                                value="<?php echo htmlspecialchars($data['nama_warga']); ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">NIK</label>
                            <input type="text" class="form-control"
                                value="<?php echo htmlspecialchars($data['nik_warga']); ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor HP</label>
                            <input type="text" class="form-control" name="hp"
                                value="<?php echo htmlspecialchars($data['hp']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email"
                                value="<?php echo htmlspecialchars($data['email']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" class="form-control" name="password">
                            <small class="text-muted">Kosongkan jika tidak ingin ganti password</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pekerjaan</label>
                            <input type="text" class="form-control" name="pekerjaan"
                                value="<?php echo htmlspecialchars($data['pekerjaan']); ?>" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            Simpan Perubahan
                        </button>
                    </form>

                </div>

            </div>
        </div>
    </div>
    <div class="modal fade matcha-modal" id="modalDokumen" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-file-image"></i> Foto KK & KTP anda
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <!-- KK -->
                    <div class="matcha-doc">
                        <p class="fw-bold text-success text-center mb-2">
                            Kartu Keluarga
                        </p>
                        <img src="view_file_warga.php?id=<?= $cek_kk_ktp['id_dokumen']; ?>&type=kk"
                            class="img-fluid w-100" alt="Foto KK">
                    </div>

                    <!-- KTP -->
                    <div class="matcha-doc">
                        <p class="fw-bold text-success text-center mb-2">
                            KTP
                        </p>
                        <img src="view_file_warga.php?id=<?= $cek_kk_ktp['id_dokumen']; ?>&type=ktp"
                            class="img-fluid w-100" alt="Foto KTP">
                    </div>

                </div>

                <div class="modal-footer d-flex justify-content-between">

                    <form action="aksi_datawarga/hapus_dokumen.php" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                        <input type="hidden" name="id_warga" value="<?= $data['nik_warga']; ?>">
                        <button type="submit" class="btn btn-danger-soft">
                            <i class="fas fa-trash"></i> Hapus Dokumen
                        </button>
                    </form>

                    <button type="button" class="btn btn-matcha" data-bs-dismiss="modal">
                        Tutup
                    </button>

                </div>

            </div>
        </div>
    </div>


    <?php if ($notif): ?>
        <script>
            // Hilangkan notifikasi otomatis setelah 4 detik
            setTimeout(() => {
                const notif = document.getElementById('notif');
                if (notif) {
                    notif.classList.add('hide');
                    setTimeout(() => notif.remove(), 500);
                }
            }, 4000);
        </script>
    <?php endif; ?>

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


        // Upload Foto Profile Warga
        const fileAvatarWarga = document.getElementById('fileAvatarWarga');
        const fotoProfilWarga = document.getElementById('fotoProfilWarga');
        const uploadStatusWarga = document.getElementById('uploadStatusWarga');

        fileAvatarWarga.addEventListener('change', function(e) {
            const file = this.files[0];
            if (!file) return;

            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                uploadStatusWarga.style.color = '#dc3545';
                uploadStatusWarga.textContent = 'Tipe file tidak valid. Gunakan JPG, PNG, atau GIF';
                this.value = '';
                return;
            }

            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                uploadStatusWarga.style.color = '#dc3545';
                uploadStatusWarga.textContent = 'Ukuran file terlalu besar. Maksimal 2MB';
                this.value = '';
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function(event) {
                fotoProfilWarga.src = event.target.result;
            };
            reader.readAsDataURL(file);

            // Upload file via AJAX
            const formData = new FormData();
            formData.append('foto_profile', file);

            uploadStatusWarga.style.color = '#0d6efd';
            uploadStatusWarga.textContent = 'Mengupload...';

            fetch('upload_foto_profile_warga.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        uploadStatusWarga.style.color = '#198754';
                        uploadStatusWarga.textContent = 'Foto berhasil diupdate!';
                        // Update image source to new file
                        fotoProfilWarga.src = 'profile/' + data.filename;
                        setTimeout(() => {
                            uploadStatusWarga.textContent = '';
                        }, 3000);
                    } else {
                        uploadStatusWarga.style.color = '#dc3545';
                        uploadStatusWarga.textContent = 'Error: ' + data.message;
                        // Revert to old image
                        fotoProfilWarga.src = '<?php echo $foto_path_warga; ?>';
                    }
                })
                .catch(error => {
                    uploadStatusWarga.style.color = '#dc3545';
                    uploadStatusWarga.textContent = 'Gagal mengupload foto';
                    console.error('Upload error:', error);
                    // Revert to old image
                    fotoProfilWarga.src = '<?php echo $foto_path_warga; ?>';
                });
        });


        // GSAP Animation Logic
        <?php if ($show_welcome): ?>
            document.addEventListener("DOMContentLoaded", () => {
                const overlay = document.getElementById('welcome-overlay');
                // Make visible immediately for animation
                gsap.set(overlay, {
                    autoAlpha: 1
                });

                const tl = gsap.timeline({
                    onComplete: () => {
                        gsap.to(overlay, {
                            duration: 0.8,
                            yPercent: -100,
                            ease: "power2.inOut",
                            onComplete: () => {
                                overlay.style.display = 'none';
                            }
                        });

                        // Animate page elements in
                        gsap.from(".head", {
                            duration: 1,
                            y: -50,
                            opacity: 0,
                            ease: "power2.out",
                            delay: 0.2
                        });
                        gsap.from(".navbar", {
                            duration: 1,
                            x: -50,
                            opacity: 0,
                            ease: "power2.out",
                            delay: 0.3
                        });
                        gsap.from(".konten", {
                            duration: 0.8,
                            y: 30,
                            opacity: 0,
                            ease: "power2.out",
                            delay: 0.5
                        });
                    }
                });

                tl.to(".welcome-title", {
                        duration: 1,
                        y: 0,
                        opacity: 1,
                        ease: "back.out(1.7)"
                    })
                    .to(".welcome-divider", {
                        duration: 0.8,
                        width: "50%",
                        ease: "power2.out"
                    }, "-=0.5")
                    .to(".welcome-subtitle", {
                        duration: 0.8,
                        y: 0,
                        opacity: 1,
                        ease: "power2.out"
                    }, "-=0.6")
                    .to({}, {
                        duration: 2
                    }); // Pause for reading
            });
        <?php else: ?>
            // Simple entrance animation for non-first visits
            document.addEventListener("DOMContentLoaded", () => {
                // Optional GSAP entrance
                gsap.from(".konten", {
                    duration: 0.6,
                    y: 20,
                    opacity: 0,
                    ease: "power1.out"
                });
            });
        <?php endif; ?>
    </script>

</body>

</html>