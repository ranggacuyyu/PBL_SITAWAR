    <?php
    session_start();
    require_once '../koneksi.php';
    require_once '../db_helper.php';

    if (!isset($_SESSION['user_rt'])) {
        header('location:../LoginRTWARGA.php');
        exit();
    }
    $sk_rt = $_SESSION['user_rt']['sk_rt'];

    if (isset($_SESSION['alert'])) {
        echo "<script>alert('" . $_SESSION['alert'] . "');</script>";
        unset($_SESSION['alert']);
    }

    // Cek status welcome animation
    $show_welcome = false;
    if (!isset($_SESSION['welcome_shown'])) {
        $show_welcome = true;
        $_SESSION['welcome_shown'] = true;
    }

    $dataku = db_select_single($koneksi, "SELECT * FROM user_rt WHERE sk_rt =?", "s", [$sk_rt]);

    $stmt = mysqli_stmt_init($koneksi);
    $query_dokumen = "SELECT 
        w.nama_warga,
        d.id_dokumen,
        d.foto_kk,
        d.foto_ktp
    FROM user_warga w
    JOIN dokumen_wargart d ON w.nik_warga = d.id_warga
    WHERE w.rt = ?";
    if (!mysqli_stmt_prepare($stmt, $query_dokumen)) {
        echo "error";
    } else {
        mysqli_stmt_bind_param($stmt, "s", $sk_rt);
        mysqli_stmt_execute($stmt);
        $data_dokumen = mysqli_stmt_get_result($stmt);
    }

    $status_keluarga = "kepala keluarga";

    $user = db_select_single($koneksi, "SELECT COUNT(nik_warga) as user_warga FROM user_warga WHERE rt =?", "s", [$sk_rt]);
    $hasil_periksa = db_select_single($koneksi, "SELECT * FROM user_warga WHERE rt =?", "s", [$sk_rt]);

    $user1 = db_select_single($koneksi, "SELECT COUNT(*) as hamil FROM laporan WHERE jenis_laporan = 'ibu-hamil' AND nik_pelapor IN (SELECT nik_warga FROM user_warga WHERE rt = ?)", "s", [$sk_rt]);
    $user2 = db_select_single($koneksi, "SELECT COUNT(*) as kepala_keluarga FROM user_warga WHERE rt = ? AND keluarga = ?", "ss", [$sk_rt, $status_keluarga]);
    $user3 = db_select_single($koneksi, "SELECT COUNT(*) as balita FROM user_warga WHERE rt = ? and TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) < 5", "s", [$sk_rt]);

    $data_per_bulan = array_fill(1, 12, 0); // buat 12 bulan isi 0
    $result4 = db_select_no_assoc(
        $koneksi, 
        "SELECT MONTH(tanggal_input) AS bulan, COUNT(*) AS total
        FROM user_warga
        WHERE YEAR(tanggal_input) = YEAR(CURDATE()) AND rt = ?
        GROUP BY MONTH(tanggal_input)", 
        "s", 
        [$sk_rt]);

    while ($row = mysqli_fetch_assoc($result4)) {
        $bulan = (int) $row['bulan'];
        $data_per_bulan[$bulan] = $row['total'];
    }
    $chart_data = json_encode(array_values($data_per_bulan));
    if (isset($_SESSION['notif'])) {
        echo "<script>
            alert('" . $_SESSION['notif'] . "');        
        </script>";
        unset($_SESSION['notif']);
        unset($_SESSION['status']);
    }

    ?>
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SITAWAR - Sistem Terpadu Administrasi Warga</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link rel="stylesheet" href="Dashboar_RT.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <!-- GSAP CDN -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    </head>

    <body>
        <!-- SIDEBAR -->

        <?php if ($show_welcome): ?>
            <!-- Welcome Overlay -->
            <div id="welcome-overlay">
                <div class="welcome-content">
                    <h1 class="welcome-title">Selamat Datang, <?php echo htmlspecialchars($_SESSION['user_rt']['nama_rt']); ?>
                    </h1>
                    <div class="welcome-divider"></div>
                    <p class="welcome-subtitle">Sistem Terpadu Adminitrasi Warga ( SITAWAR )</p>
                </div>
            </div>
        <?php endif; ?>

        <aside class="sidebar">
            <div>
                <div class="sidebar-header">
                    <h1>SITAWAR</h1>
                    <p>Sistem Terpadu Administrasi Warga</p>
                </div>
                <nav class="nav">
                    <a href="Dashboard_RT.php">Dashboard</a>
                    <a href="kepala_keluarga.php">Kepala Keluarga</a>
                    <a href="DataWarga_RT.php">Data Warga</a>
                    <a href="Dokumen_RT.php">Dokumen</a>
                    <a href="Laporan_ RT.php">Laporan</a>
                </nav>
            </div>
            <div class="sidebar-footer">© 2025 RT Smart System</div>
        </aside>

        <!-- Welcome Overlay Structure ensures it is outside sidebar/main but inside body -->

        <!-- MAIN -->
        <div class="utama">
            <main class="main <?php echo !$show_welcome ? 'content-animate' : ''; ?>">
                <section class="welcome">
                    <div>
                        <h2>Selamat Datang <?php echo htmlspecialchars($_SESSION['user_rt']['nama_rt']); ?> di Dashboard RT
                        </h2>
                        <p>Pantau data dan aktivitas warga secara real-time.</p>
                    </div>
                    <div style="display: flex; align-items: center;justify-content: center;">

                        <button onclick="cihuy()"
                            style="background-color: #5c6846ff; padding: 8px 13px 8px 13px; border-radius: 5px; border: none; cursor: pointer; display:flex; align-items:center; justify-content:center; color: white;"
                            class="tombolprofil"><i class="fas fa-user-circle me-3"
                                style="font-size: 23px; padding-right:6px; color: white; background-color: #5c6846ff; border-radius: 5px; border: none; cursor: pointer;"></i>Profile
                        </button>
                        <form action="hapus_login.php" method="post">
                            <button onclick="return confirm('Yakin ingin keluar?')" class="tombol"
                                style="margin-left:10px; background-color: #495336; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; display:flex; align-items:center; justify-content:center">
                                <i class="fas fa-sign-out-alt me-3"
                                    style="display: flex; justify-content:center;padding-right:6px; align-items:center"></i>
                                Log Out
                            </button>
                        </form>
                    </div>
                </section>
                <!-- Tambahan dua card -->
                <section class="info-section">
                    <div class="info-card">
                        <h3>🧾 Cara Menambahkan Data Warga</h3>
                        <p>
                            Untuk menambahkan warga baru, klik tombol <b>"Tambah Warga"</b> di sebelah kanan.
                            Isi data lengkap seperti nama, NIK, dan Status warga dalam keluarga sesuai identitas.
                            pada status kelurga pilih <b>"Kepala Keluarga"</b> jika warga tersebut adalah kepala keluarga.
                            dan <b>"Anggota Keluarga"</b> jika bukan kepala keluarga.
                            Setelah selesai, klik <b>"Simpan"</b> untuk bergabung kekomunitas RT anda.
                        </p>
                    </div>

                    <div class="info-card">
                        <h3>➕ Tambahkan Warga Baru</h3>
                        <p>Gunakan tombol <b>"Tambah Warga"</b> di bawah ini untuk memasukkan data warga baru ke sistem, dan
                            Gunakan
                            tombol <b>"Lihat Dokumen Warga"</b> di bawah ini untuk melihat dan menyimpan dokumen seperti KK
                            dan KTP</p>
                        <button id="btnTambah">Tambah Warga</button>
                        <button id="btnLihatDokumen" class="btn btn-primary">
                            Lihat Dokumen Warga
                        </button>
                    </div>
                </section>

                <!-- Statistik -->
                <section class="stats">
                    <div class="card">
                        <h3>Jumlah Warga</h3>
                        <p class="blue"><?= htmlspecialchars($user['user_warga']); ?></p>
                    </div>
                    <div class="card">
                        <h3>Warga Hamil</h3>
                        <p class="pink"><?= htmlspecialchars($user1['hamil']); ?></p>
                    </div>
                    <div class="card">
                        <h3>Kepala Keluarga</h3>
                        <p class="green"><?= htmlspecialchars($user2['kepala_keluarga']); ?></p>
                    </div>
                    <div class="card">
                        <h3>Balita</h3>
                        <p class="yellow"><?= htmlspecialchars($user3['balita']); ?></p>
                    </div>
                </section>

                <!-- Grafik -->
                <section class="chart-section">
                    <h3>Grafik Perkembangan Data Warga</h3>
                    <canvas id="chartWarga" height="100"></canvas>
                </section>
            </main>
        </div>

        <!-- Modal Tambah Warga -->
        <div id="modalTambah" class="modal">
            <div class="modal-content">
                <span class="close-btn" id="closeModal">&times;</span>
                <h3>Tambah Data Warga</h3>
                <form action="tambah_warga.php" method="POST">
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap" required>

                    <label for="nik">NIK</label>
                    <input type="text" id="nik" name="nik" placeholder="Masukkan NIK">

                    <label for="keluarga">Status dalam keluarga</label>
                    <select name="keluarga" id="keluarga">
                        <option value="anggota keluarga" default>Anggota Keluarga</option>
                        <option value="kepala keluarga">Kepala Keluarga</option>
                    </select>

                    <button type="submit" name="simpan">Simpan</button>
                </form>
            </div>
        </div>

        <div id="modalDokumen" class="modal">
            <div class="modal-content" style="width: 80%;">
                <span class="close-btn" id="closeDokumen">&times;</span>
                <h3>📄 Dokumen Warga RT</h3>
                <table border="1" width="100%" cellpadding="8" cellspacing="0" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Warga</th>
                            <th>KK</th>
                            <th>KTP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Konfigurasi Pagination Modal
                        $hal_dok = (isset($_GET['hal_dok'])) ? (int) $_GET['hal_dok'] : 1;
                        $limit_dok = 5; // Limit lebih kecil untuk modal
                        $offset_dok = ($hal_dok - 1) * $limit_dok;

                        // Hitung total data dokumen
                        $count_dok_query = "SELECT COUNT(*) FROM user_warga w JOIN dokumen_wargart d ON w.nik_warga = d.id_warga WHERE w.rt = ?";
                        $total_dok = db_count($koneksi, $count_dok_query, "s", [$sk_rt]);

                        // Query Dokumen dengan Limit
                        $query_dokumen = "SELECT 
                            w.nama_warga,
                            d.id_dokumen,
                            d.foto_kk,
                            d.foto_ktp
                        FROM user_warga w
                        JOIN dokumen_wargart d ON w.nik_warga = d.id_warga
                        WHERE w.rt = ?
                        LIMIT ? OFFSET ?";

                        $stmt_dok = mysqli_stmt_init($koneksi);
                        if (mysqli_stmt_prepare($stmt_dok, $query_dokumen)) {
                            mysqli_stmt_bind_param($stmt_dok, "sii", $sk_rt, $limit_dok, $offset_dok);
                            mysqli_stmt_execute($stmt_dok);
                            $data_dokumen = mysqli_stmt_get_result($stmt_dok);

                            while ($d = mysqli_fetch_assoc($data_dokumen)) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($d['nama_warga']) ?></td>

                                    <td>
                                        <a href="view_file.php?id=<?= $d['id_dokumen']; ?>&type=kk" target="_blank"
                                            class="btn btn-info btn-sm">
                                            👁 Lihat KK
                                        </a>

                                        <a href="download_file.php?id=<?= $d['id_dokumen']; ?>&type=kk"
                                            class="btn btn-success btn-sm">
                                            ⬇ Download KK
                                        </a>
                                    </td>

                                    <td>
                                        <a href="view_file.php?id=<?= $d['id_dokumen']; ?>&type=ktp" target="_blank"
                                            class="btn btn-info btn-sm">
                                            👁 Lihat KTP
                                        </a>

                                        <a href="download_file.php?id=<?= $d['id_dokumen']; ?>&type=ktp"
                                            class="btn btn-success btn-sm">
                                            ⬇ Download KTP
                                        </a>
                                    </td>
                                </tr>
                            <?php }
                        } ?>
                    </tbody>
                </table>

                <!-- PAGINATION LINKS FOR MODAL -->
                <!-- Kita gunakan parameter 'hal_dok' agar tidak konflik jika nanti ada pagination lain di dashboard -->
                <div class="mt-3">
                    <?php
                    // Custom Pagination Link Generator untuk Modal
                    // Mengganti ?hal= dengan ?hal_dok=
                    $total_page_dok = ceil($total_dok / $limit_dok);
                    if ($total_page_dok > 1) {
                        echo '<nav><ul class="pagination pagination-sm justify-content-center">';
                        for ($i = 1; $i <= $total_page_dok; $i++) {
                            $active = ($i == $hal_dok) ? 'active' : '';
                            echo '<li class="page-item ' . $active . '"><a class="page-link" href="Dashboard_RT.php?hal_dok=' . $i . '">' . $i . '</a></li>';
                        }
                        echo '</ul></nav>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div id="profileRTcihuy" class="overlay">
            <div class="modal_profile">
                <div class="keluar-button">
                    <div style="font-weight: bold; padding:10px;">Profil Pengguna</div>
                    <button id="profileRTtutup" onclick="cihuytutup()"
                        style="border: none; background-color:#495336; color:white; padding:5px 8px 5px 8px; cursor: pointer;"><i
                            class="fas fa-times"></i></button>
                </div>


                <div class="modal-body">
                    <!-- FOTO -->
                    <div class="modal-left">
                        <div class="avatar-large">
                            <?php
                            $foto_path = !empty($dataku['foto_profile']) ? 'profile/' . $dataku['foto_profile'] : 'profile/default.jpg';
                            ?>
                            <img id="largeAvatar" src="<?php echo $foto_path; ?>" alt="Foto Profil">
                        </div>
                        <form id="formUploadFoto" enctype="multipart/form-data">
                            <label for="fileAvatar" class="btn-ganti-foto">Ganti Foto</label>
                            <input id="fileAvatar" type="file" name="foto_profile"
                                accept="image/jpeg,image/jpg,image/png,image/gif" hidden>
                            <div id="uploadStatus" style="margin-top: 10px; font-size: 12px;"></div>
                        </form>
                    </div>


                    <!-- DATA + GANTI PASSWORD -->
                    <div class="modal-right">
                        <div class="details">
                            <div class="row">
                                <div class="label">Nama</div>
                                <div class="value"><?php echo htmlspecialchars($dataku['nama_rt']); ?></div>
                            </div>
                            <div class="row">
                                <div class="label">NIK</div>
                                <div class="value"><?php echo htmlspecialchars($dataku['nik_rt']); ?></div>
                            </div>
                            <div class="row">
                                <div class="label">SK RT</div>
                                <div class="value"><?php echo htmlspecialchars($dataku['sk_rt']); ?></div>
                            </div>
                        </div>


                        <hr style="margin:14px 0 ; border:0; height:3px; background:rgba(255,255,255,0.05); width:100%">


                        <h3>Ganti Password</h3>
                        <form id="pwdForm" method="POST" action="ganti_pass.php">
                            <div class="field">
                                <label>Password Saat Ini</label>
                                <input id="currentPwd" type="password" name="passwordsaatini">
                            </div>
                            <div class="field">
                                <label>Password Baru</label>
                                <input id="newPwd" type="password" name="passwordbaru">
                            </div>
                            <div class="field">
                                <label>Konfirmasi Password Baru</label>
                                <input id="confirmPwd" type="password" name="passwordtes">
                            </div>
                            <button type="submit" class="btn_prumaary">Simpan</button>
                            <p id="pwdHelp"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            const profilertcihuy = document.getElementById("profileRTcihuy");
            const tutupprofilertcihuy = document.getElementById("profileRTtutup");

            const modalDokumen = document.getElementById("modalDokumen");
            const btnLihatDokumen = document.getElementById("btnLihatDokumen");
            const closeDokumen = document.getElementById("closeDokumen");

            const modalTambah = document.getElementById("modalTambah");
            const btnTambah = document.getElementById("btnTambah");
            const closeModal = document.getElementById("closeModal");

            function cihuy() {
                profilertcihuy.style.display = "flex";
            }

            function cihuytutup() {
                profilertcihuy.style.display = "none";
            }

            profileRTcihuy.onclick = (e) => {
                if (e.target === profileRTcihuy) cihuytutup();
            };

            // buka modal dokumen
            btnLihatDokumen.onclick = () => modalDokumen.style.display = "flex";
            closeDokumen.onclick = () => modalDokumen.style.display = "none";

            // buka modal tambah warga
            btnTambah.onclick = () => modalTambah.style.display = "flex";
            closeModal.onclick = () => modalTambah.style.display = "none";

            // Upload Foto Profile
            const fileAvatar = document.getElementById('fileAvatar');
            const largeAvatar = document.getElementById('largeAvatar');
            const uploadStatus = document.getElementById('uploadStatus');

            fileAvatar.addEventListener('change', function (e) {
                const file = this.files[0];
                if (!file) return;

                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    uploadStatus.style.color = '#ff6b6b';
                    uploadStatus.textContent = 'Tipe file tidak valid. Gunakan JPG, PNG, atau GIF';
                    this.value = '';
                    return;
                }

                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    uploadStatus.style.color = '#ff6b6b';
                    uploadStatus.textContent = 'Ukuran file terlalu besar. Maksimal 2MB';
                    this.value = '';
                    return;
                }

                // Show preview
                const reader = new FileReader();
                reader.onload = function (event) {
                    largeAvatar.src = event.target.result;
                };
                reader.readAsDataURL(file);

                // Upload file via AJAX
                const formData = new FormData();
                formData.append('foto_profile', file);

                uploadStatus.style.color = '#4dabf7';
                uploadStatus.textContent = 'Mengupload...';

                fetch('upload_foto_profile.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            uploadStatus.style.color = '#51cf66';
                            uploadStatus.textContent = 'Foto berhasil diupdate!';
                            // Update image source to new file
                            largeAvatar.src = 'profile/' + data.filename;
                            setTimeout(() => {
                                uploadStatus.textContent = '';
                            }, 3000);
                        } else {
                            uploadStatus.style.color = '#ff6b6b';
                            uploadStatus.textContent = 'Error: ' + data.message;
                            // Revert to old image
                            largeAvatar.src = '<?php echo $foto_path; ?>';
                        }
                    })
                    .catch(error => {
                        uploadStatus.style.color = '#ff6b6b';
                        uploadStatus.textContent = 'Gagal mengupload foto';
                        console.error('Upload error:', error);
                        // Revert to old image
                        largeAvatar.src = '<?php echo $foto_path; ?>';
                    });
            });

            // == LOGIC UNTUK RETAIN MODAL OPEN SAAT PAGINATION ==
            document.addEventListener("DOMContentLoaded", () => {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('hal_dok')) {
                    modalDokumen.style.display = "flex";
                    // Scroll ke modal agar terlihat
                    modalDokumen.scrollIntoView({ behavior: 'smooth' });
                }
            });

            // Grafik chart
            const dataWarga = <?= $chart_data ?>;
            const ctx = document.getElementById('chartWarga').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [
                        'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                        'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
                    ],
                    datasets: [{
                        label: 'Penambahan Warga per Bulan',
                        data: dataWarga,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    }
                }
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

                            // Animate dashboard elements in
                            gsap.from(".sidebar", {
                                duration: 1,
                                x: -50,
                                opacity: 0,
                                ease: "power2.out",
                                delay: 0.2
                            });
                            gsap.from(".main > section", {
                                duration: 0.8,
                                y: 30,
                                opacity: 0,
                                stagger: 0.1,
                                ease: "power2.out",
                                delay: 0.4
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
                            duration: 1.5
                        }); // Pause for reading
                });
            <?php endif; ?>
        </script>
    </body>

    </html>