<?php
session_start();
include "../koneksi.php";
if (!isset($_SESSION['user_rt'])) {
    header('location:../LoginRTWARGA.php');
    exit();
}

$sk_rt = $_SESSION['user_rt']['sk_rt'];
$dataDiriRT = "SELECT * FROM user_rt WHERE sk_rt =?";
$stmt = mysqli_stmt_init($koneksi);
if (!mysqli_stmt_prepare($stmt, "$dataDiriRT")) {
    echo "error";
} else {
    mysqli_stmt_bind_param($stmt, "s", $sk_rt);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dataku = mysqli_fetch_assoc($result);
}

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

$query = "SELECT COUNT(*) as user_warga FROM user_warga WHERE rt =?";
if (!mysqli_stmt_prepare($stmt, "$query")) {
    echo "error";
} else {
    mysqli_stmt_bind_param($stmt, "s", $sk_rt);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
}

$periksa_warga = "SELECT * FROM user_warga WHERE rt =?";
if (!mysqli_stmt_prepare($stmt, "$periksa_warga")) {
    echo "error";
} else {
    mysqli_stmt_bind_param($stmt, "s", $sk_rt);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $hasil_periksa = mysqli_fetch_assoc($result);
}

$query1 = "SELECT COUNT(*) as hamil FROM laporan WHERE jenis_laporan = 'ibu-hamil' AND nik_pelapor IN (SELECT nik_warga FROM user_warga WHERE rt = ?)";
if (mysqli_stmt_prepare($stmt, $query1)) {
    mysqli_stmt_bind_param($stmt, "s", $sk_rt);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user1 = mysqli_fetch_assoc($result);
}


$query2 = "SELECT COUNT(*) as kepala_keluarga FROM user_warga WHERE rt = ? AND keluarga = ?";
if (!mysqli_stmt_prepare($stmt, "$query2")) {
    echo "error";
} else {
    $status_keluarga = "kepala keluarga";
    mysqli_stmt_bind_param($stmt, "ss", $sk_rt, $status_keluarga);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user2 = mysqli_fetch_assoc($result);
}

$query3 = "SELECT COUNT(*) as balita FROM user_warga WHERE rt = ? and TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) < 5";
if (!mysqli_stmt_prepare($stmt, "$query3")) {
    echo "error";
} else {
    mysqli_stmt_bind_param($stmt, "s", $sk_rt);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user3 = mysqli_fetch_assoc($result);
}

$data_per_bulan = array_fill(1, 12, 0); // buat 12 bulan isi 0

$query4 = "SELECT MONTH(tanggal_input) AS bulan, COUNT(*) AS total
    FROM user_warga
    WHERE YEAR(tanggal_input) = YEAR(CURDATE()) AND rt = ?
    GROUP BY MONTH(tanggal_input)";
if (!mysqli_stmt_prepare($stmt, $query4)) {
    echo "error";
} else {
    mysqli_stmt_bind_param($stmt, "s", $sk_rt);
    mysqli_stmt_execute($stmt);
    $result4 = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result4)) {
        $bulan = (int) $row['bulan'];
        $data_per_bulan[$bulan] = $row['total'];
    }
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
    <title>SITAWAR - Sistem Informasi Tata Warga</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="Dashboar_RT.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <!-- SIDEBAR -->
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

    <!-- MAIN -->
    <div class="utama">
        <main class="main">
            <section class="welcome">
                <div>
                    <h2>Selamat Datang di Dashboard RT</h2>
                    <p>Pantau data dan aktivitas warga secara real-time.</p>
                </div>
                <div style="display: flex; align-items: center;justify-content: center;">
                    <button onclick="cihuy()"><i class="fas fa-user-circle me-3" style="font-size: 35px;"></i></button>
                    <button class="tombol"
                        style="margin-left:10px; background-color: #495336; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
                        <i class="fas fa-sign-out-alt me-3"></i>
                    </button>
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
                    <p class="blue"><?= $user['user_warga']; ?></p>
                </div>
                <div class="card">
                    <h3>Warga Hamil</h3>
                    <p class="pink"><?= $user1['hamil']; ?></p>
                </div>
                <div class="card">
                    <h3>Kepala Keluarga</h3>
                    <p class="green"><?= $user2['kepala_keluarga']; ?></p>
                </div>
                <div class="card">
                    <h3>Balita</h3>
                    <p class="yellow"><?= $user3['balita']; ?></p>
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

                <button type="submit" name="simpan" onclick="simpanWarga()">Simpan</button>
            </form>
        </div>
    </div>

    <div id="modalDokumen" class="modal">
        <div class="modal-content" style="width: 80%;">
            <span class="close-btn" id="closeDokumen">&times;</span>
            <h3>📄 Dokumen Warga RT</h3>
            <table border="1" width="100%" cellpadding="8" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama Warga</th>
                        <th>KK</th>
                        <th>KTP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($d = mysqli_fetch_assoc($data_dokumen)) { ?>
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
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="profileRTcihuy" class="overlay">
        <div class="modal_profile">
            <div class="keluar-button">
                <div style="font-weight: bold; padding:10px;">Profil Pengguna</div>
                <button id="profileRTtutup" onclick="cihuytutup()"
                    style="border: none; background-color:#495336; color:white; padding:5px 8px 5px 8px">✕</button>
            </div>


            <div class="modal-body">
                <!-- FOTO -->
                <div class="modal-left">
                    <div class="avatar-large">
                        <img id="largeAvatar"
                            src="../image/unnamed.jpg"
                            alt="Foto Profil">
                    </div>
                    <label for="fileAvatar" style="color:white;">Ganti Foto</label>
                    <input id="fileAvatar" type="file" accept="image/*" hidden>
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
                    <?php

                    if (isset($_POST['passwordsaatini'])) {
                        $passini = $_POST['passwordsaatini'];
                        $passbaru = $_POST['passwordbaru'];
                        $passtes = $_POST['passwordtes'];
                        $password_hash = password_hash($passbaru, PASSWORD_DEFAULT);

                        $q = "SELECT password FROM user_rt WHERE sk_rt='$sk_rt'";
                        if (!mysqli_stmt_prepare($stmt, $q)) {
                            echo "error";
                        } else {
                            mysqli_stmt_bind_param($stmt, "s", $sk_rt);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            $datartku = mysqli_fetch_assoc($result);
                            $password_lama = $datartku['password'];
                        }

                        // 1. Cek password lama
                        if (!password_verify($passini, $password_lama)) {
                            echo "<script>alert('Password saat ini salah');</script>";
                            exit();
                        }

                        if ($passbaru !== $passtes) {
                            echo "<script>alert('Password baru dan konfirmasi tidak sama');</script>";
                            exit();
                        }

                        $update_password = "UPDATE user_rt SET password=? WHERE sk_rt=?";
                        if (!mysqli_stmt_prepare($stmt, $update_password)) {
                            echo "error";
                        } else {
                            mysqli_stmt_bind_param($stmt, "ss", $password_hash, $sk_rt);
                            mysqli_stmt_execute($stmt);
                        }
                        echo "<script>alert('Password berhasil diperbarui');</script>";
                    }

                    ?>
                    <form id="pwdForm" method="POST">
                        <div class="field">
                            <label>Password Saat Ini</label>
                            <input id="currentPwd" type="password" name="passwordsaatini">
                        </div>
                        <div class="field">
                            <label>Password Baru</label>
                            <input id="newPwd" type="password" name="passwordbaru">
                        </div>
                        <div class="field">
                            <label>Konfirmasi Password</label>
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

        // Simpan data (dummy alert)
        function simpanWarga() {
            const nama = document.getElementById("nama").value;
            const nik = document.getElementById("nik").value;
            if (!nama || !nik) {
                alert("Harap isi semua data terlebih dahulu!");
                return;
            }
            alert(`✅ Data warga baru ditambahkan:\nNama: ${nama}\nNIK: ${nik}`);
            modalTambah.style.display = "none";
        }

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
                }
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</body>

</html>