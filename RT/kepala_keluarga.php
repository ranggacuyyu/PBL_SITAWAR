<?php
session_start();
require_once "../koneksi.php";
require_once "../db_helper.php";

if (!isset($_SESSION['user_rt'])) {
    header("Location: ../LoginRTWARGA.php");
    exit;
}

$notif = $_SESSION['notif'] ?? null;
unset($_SESSION['notif']);

$sk_rt = $_SESSION['user_rt']['sk_rt'];

$halaman_aktif = (isset($_GET['hal'])) ? (int) $_GET['hal'] : 1;
$limit = 10;
$offset = ($halaman_aktif - 1) * $limit;

$count_query = "SELECT COUNT(*) FROM user_warga WHERE keluarga = 'kepala keluarga' AND rt = ?";
$total_data = db_count($koneksi, $count_query, "s", [$sk_rt]);

// Ambil data kepala keluarga dengan Pagination
$query_kk = "SELECT * FROM user_warga 
    WHERE keluarga = 'kepala keluarga' AND rt = ? 
    LIMIT ? OFFSET ?";
$kk = db_select_no_assoc($koneksi, $query_kk, "sii", [$sk_rt, $limit, $offset]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kepala Keluarga - Sitawar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="kepala_keluarga.css">
    <link rel="stylesheet" href="../notif.css">
</head>

<body>
    <div class="notifikasi">
        <?php if ($notif): ?>
            <div id="notif" class="notif">
                <?= htmlspecialchars($notif) ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="bungkusluar"></div>
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

    <div class="container content-animate" style=" margin-left: 250px; padding: 30px; background-color: #88976cce;">
        <h2 class="fw-bold mb-4 text-center" style="color: #f5f5f5f5;">Daftar Kepala Keluarga - Sitawar</h2>
        <div class="card-home">
            <h3>RT 01 RW 02</h3>
            <h5>Kelurahan Sukamaju, Kecamatan Cilandak, Kota Jakarta Selatan</h5>
            <p>halaman pengelolaan data warga dapat melakukan pencarian cepat data warga berdasarkan Nama dan NIK,
                memberikan statistik jumlah warga berdasarkan usia dan dapat menghapus data warga beserta mengupdate
                data warga</p>
        </div>

        <div class="card p-4 shadow">
            <table class="table table-hover align-middle">
                <thead class="table">
                    <tr>
                        <th style="background-color: #6b7a59; color:white;">No KK</th>
                        <th style="background-color: #6b7a59; color:white;">Nama</th>
                        <th style="background-color: #6b7a59; color:white;">NIK</th>
                        <th style="background-color: #6b7a59; color:white;">Jumlah Anggota</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($kk): ?>
                        <?php while ($row = mysqli_fetch_assoc($kk)):
                            $no_kk = $row['no_kk'];
                            $count_anggota_query = "SELECT COUNT(*) AS total FROM user_warga WHERE no_kk=?";
                            $count = db_select_single($koneksi, $count_anggota_query, "s", [$no_kk]);
                            $jumlah = $count['total'];
                        ?>
                            <tr onclick='openModal(
                            <?= json_encode($row["no_kk"], JSON_HEX_QUOT | JSON_HEX_APOS) ?>,
                            <?= json_encode($row["nik_warga"], JSON_HEX_QUOT | JSON_HEX_APOS) ?>,
                            <?= json_encode($row["nama_warga"], JSON_HEX_QUOT | JSON_HEX_APOS) ?>,
                            <?= json_encode($jumlah, JSON_HEX_QUOT | JSON_HEX_APOS) ?>,
                            <?= json_encode($row["keluarga"], JSON_HEX_QUOT | JSON_HEX_APOS) ?>,
                            <?= json_encode($row["foto_profile"], JSON_HEX_QUOT | JSON_HEX_APOS) ?> 
                        )'>

                                <td><?= htmlspecialchars($row['no_kk']) ?></td>
                                <td><?= htmlspecialchars($row['nama_warga']) ?></td>
                                <td><?= htmlspecialchars($row['nik_warga']) ?></td>
                                <td><?= $jumlah ?> Orang</td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>

                </tbody>
            </table>

            <!-- PAGINATION LINKS -->
            <div class="mt-3">
                <?= db_pagination_links($total_data, $limit, $halaman_aktif, 'kepala_keluarga.php'); ?>
            </div>
        </div>
    </div>

    <!-- ========================================= -->
    <!-- =============== MODAL MODERN ============ -->
    <!-- ========================================= -->

    <div class="modal fade" id="profileModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content p-3">

                <div class="text-center">
                    <img id="modalFoto" class="profile-img mb-3">
                    <h4 class="fw-bold" id="modalNoKK"></h4>
                </div>

                <div class="row g-3 mt-2 p-3">

                    <div class="col-md-6">
                        <div class="card card-custom p-3">
                            <h6 class="fw-bold">NIK</h6>
                            <p id="modalNIK"></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-custom p-3">
                            <h6 class="fw-bold">Nama</h6>
                            <p id="modalNama"></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-custom p-3">
                            <h6 class="fw-bold">Jumlah Dalam Keluarga</h6>
                            <p id="modalJumlah"></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-custom p-3">
                            <h6 class="fw-bold">Anggota Keluarga</h6>
                            <div id="modalAnggota"></div>
                        </div>
                    </div>

                </div>

                <div class="text-center mt-3 d-flex justify-content-center gap-3">

                    <button id="btnWafat" class="btn btn-danger px-3" onclick="openWafatModal()">
                        Kepala Keluarga Wafat
                    </button>

                    <button id="btnGanti" class="btn btn-primary px-3" onclick="openGantiModal()">
                        Ganti Kepala Keluarga
                    </button>

                    <button class="btn btn-success px-4" data-bs-dismiss="modal">Tutup</button>
                </div>


            </div>
        </div>
    </div>
    <!-- Modal Kepala Keluarga Wafat -->
    <div class="modal fade" id="modalWafat" tabindex="-1">
        <form action="update_wafat.php" method="post">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-3">
                    <input type="hidden" name="no_kk" id="inputNoKK">
                    <input type="hidden" name="nik_wafat" id="inputNIK">

                    <h5 class="fw-bold text-center">Konfirmasi Kepala Keluarga Wafat</h5>
                    <p class="text-center">Apakah Anda yakin kepala keluarga ini telah wafat?</p>

                    <div class="mb-3">
                        <label class="form-label">Anda wajib memilih kepala keluarga baru saat ada kepala keluarga yang wafat</label>
                        <select id="dropdownwafat" name="nik_baru" class="form-select">
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Masukkan Password RT</label>
                        <input type="password" name="password_wafat" id="passwordWafat" class="form-control" placeholder="Password wajib diisi">
                    </div>

                    <div class="text-center d-flex justify-content-end gap-2">
                        <div class="btn btn-secondary" data-bs-dismiss="modal">Batal</div>
                        <button class="btn btn-danger" type="submit">Konfirmasi</button>
                    </div>

                </div>
            </div>
        </form>
    </div>
    <!-- Modal Ganti Kepala Keluarga -->
    <div class="modal fade" id="modalGanti" tabindex="-1">
        <form action="ganti_kepala_keluarga.php" method="POST">
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content p-3">
                    <input type="hidden" name="no_kk1" id="inputNoKKGanti">

                    <h5 class="fw-bold text-center">Ganti Kepala Keluarga</h5>

                    <div class="mb-3">
                        <label class="form-label">Pilih Anggota</label>
                        <select id="dropdownAnggota" class="form-select" name="nik_baru1">
                        </select>
                    </div>

                    <div class="text-end d-flex justify-content-end gap-2">
                        <div class="btn btn-secondary" data-bs-dismiss="modal">Batal</div>
                        <button type="submit" class="btn btn-primary">Simpan</button>

                    </div>

                </div>

            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
    <script>
        function openModal(no_kk, nik, nama, jumlah, status, foto_profile) {

            document.getElementById('modalFoto').src = foto_profile ? '../warga/profile/' + foto_profile : '../warga/profile/default.jpg';

            document.getElementById('modalNoKK').innerHTML = "No KK: " + no_kk;
            document.getElementById('inputNoKKGanti').value = no_kk;
            document.getElementById('modalNIK').innerHTML = nik;
            document.getElementById('modalNama').innerHTML = nama;
            document.getElementById('modalJumlah').innerHTML = jumlah + " Orang";
            document.getElementById('inputNoKK').value = no_kk;
            document.getElementById('inputNIK').value = nik;

            // Atur status tombol
            if (status === "wafat") {
                document.getElementById('btnWafat').disabled = true;
                document.getElementById('btnGanti').disabled = false;
            } else {
                document.getElementById('btnWafat').disabled = false;
                document.getElementById('btnGanti').disabled = false;
            }

            // Simpan data global
            window.selectedKK = no_kk;

            fetch('ambil_anggota.php?no_kk=' + no_kk)
                .then(res => res.text())
                .then(data => document.getElementById('modalAnggota').innerHTML = data);

            var modal = new bootstrap.Modal(document.getElementById('profileModal'));
            modal.show();
        }

        function openWafatModal() {
            var m = new bootstrap.Modal(document.getElementById('modalWafat'));
            m.show();

            fetch('ambil_dropdown_anggota.php?no_kk=' + window.selectedKK)
                .then(res => res.text())
                .then(optionHtml => {
                    document.getElementById('dropdownwafat').innerHTML = optionHtml;


                });
        }

        function openGantiModal() {
            fetch('ambil_dropdown_anggota.php?no_kk=' + window.selectedKK)
                .then(res => res.text())
                .then(optionHtml => {
                    document.getElementById('dropdownAnggota').innerHTML = optionHtml;

                    var g = new bootstrap.Modal(document.getElementById('modalGanti'));
                    g.show();
                });
        }

        function bukaModalGanti(kk) {
            document.getElementById("inputNoKK").value = kk;

            fetch("get_anggota_kk.php?no_kk=" + kk)
                .then(res => res.json())
                .then(data => {
                    let dropdown = document.getElementById("dropdownAnggota");
                    dropdown.innerHTML = "";

                    data.forEach(warga => {
                        let opt = document.createElement("option");
                        opt.value = warga.nik_warga;
                        opt.textContent = warga.nama_warga + " (" + warga.nik_warga + ")";
                        dropdown.appendChild(opt);
                    });
                });

            let modal = new bootstrap.Modal(document.getElementById("modalGantiKK"));
            modal.show();
        }
    </script>

</body>

</html>