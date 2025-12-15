<?php
session_start();
include "../koneksi.php";

$sk_rt = $_SESSION['user_rt']['sk_rt'];

// Ambil semua kepala keluarga
$kk = mysqli_query($koneksi, "SELECT * FROM user_warga 
    WHERE keluarga = 'kepala keluarga' and rt = $sk_rt");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kepala Keluarga - Sitawar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", sans-serif;
        }

        body {
            display: flex;
            background-image: url(download.jpg);
            background-size: cover;
            background-position: fixed;
            color: #333;
            min-height: 100vh;
        }

        .table-hover tbody tr:hover {
            background-color: #e8f0fe;
            cursor: pointer;
        }

        .profile-img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            border: none;
        }

        .card-custom {
            border-radius: 18px;
            box-shadow: 0px 2px 12px rgba(0, 0, 0, 0.1);
        }


        /* SIDEBAR */
        .sidebar {
            width: 250px;
            background-color: #8da070;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
            height: 100%;
            left: 0;
            top: 0;
        }

        .sidebar-header {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar-header h1 {
            font-size: 24px;
            margin-bottom: 4px;
        }

        .sidebar-header p {
            font-size: 12px;
            color: #4b5c30;
            font-weight: bold;
        }

        .nav {
            padding: 20px;
        }

        .nav a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: background 0.2s;
            font-size: 1.35em;
        }

        .nav a:hover {
            background-color: #6b7a59;
        }

        .sidebar-footer {
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #ffffff;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>

</head>

<body>
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

    <div class="container " style="margin-left: 250px;padding-top:30px; background-color:#88976cce;">
        <h2 class="fw-bold mb-4 text-center">Daftar Kepala Keluarga - Sitawar</h2>

        <div class="card p-4 shadow">
            <table class="table table-hover align-middle" >
                <thead class="table-success">
                    <tr>
                        <th>No KK</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Jumlah Anggota</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($kk)):
                        $no_kk = $row['no_kk'];

                        // Hitung jumlah anggota keluarga (Count yang benar)
                        $anggota = mysqli_query(
                            $koneksi,
                            "SELECT COUNT(*) AS total FROM user_warga WHERE no_kk='$no_kk'"
                        );
                        $count = mysqli_fetch_assoc($anggota);
                        $jumlah = $count['total'];
                    ?>
                        <tr onclick="openModal(
                            '<?= $row['no_kk'] ?>',
                            '<?= $row['nik_warga'] ?>',
                            '<?= $row['nama_warga'] ?>',
                            '<?= $jumlah ?>',
                            '<?= $row['keluarga'] ?>'
                        )">

                            <td><?= $row['no_kk'] ?></td>
                            <td><?= $row['nama_warga'] ?></td>
                            <td><?= $row['nik_warga'] ?></td>
                            <td><?= $jumlah ?> Orang</td>
                        </tr>

                    <?php endwhile; ?>

                </tbody>
            </table>
        </div>
    </div>

    <!-- ========================================= -->
    <!-- =============== MODAL MODERN ============ -->
    <!-- ========================================= -->

    <div class="modal fade" id="profileModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content p-3">

                <div class="text-center">
                    <img id="modalFoto" class="profile-img mb-3" src="../img/default-profile.png">

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
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">

                <h5 class="fw-bold text-center">Konfirmasi Kepala Keluarga Wafat</h5>
                <p class="text-center">Apakah Anda yakin kepala keluarga ini telah wafat?</p>

                <div class="mb-3">
                    <label class="form-label">Masukkan Password RT</label>
                    <input type="password" id="passwordWafat" class="form-control" placeholder="Password wajib diisi">
                </div>

                <div class="text-center d-flex justify-content-end gap-2">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-danger" onclick="submitWafat()">Konfirmasi</button>
                </div>

            </div>
        </div>
    </div>
    <!-- Modal Ganti Kepala Keluarga -->
    <div class="modal fade" id="modalGanti" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">

                <h5 class="fw-bold text-center">Ganti Kepala Keluarga</h5>

                <div class="mb-3">
                    <label class="form-label">Pilih Anggota</label>
                    <select id="dropdownAnggota" class="form-select">
                    </select>
                </div>

                <div class="text-end d-flex justify-content-end gap-2">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" onclick="submitGanti()">Simpan</button>
                </div>

            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Fungsi buka modal
        function openModal(no_kk, nik, nama, jumlah, status) {

            document.getElementById('modalFoto').src = "../img/default-profile.png";

            document.getElementById('modalNoKK').innerHTML = "No KK: " + no_kk;
            document.getElementById('modalNIK').innerHTML = nik;
            document.getElementById('modalNama').innerHTML = nama;
            document.getElementById('modalJumlah').innerHTML = jumlah + " Orang";

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
        }

        function submitWafat() {
            let pass = document.getElementById('passwordWafat').value;

            if (pass.trim() === "") {
                alert("Password wajib diisi!");
                return;
            }

            fetch('update_wafat.php?no_kk=' + window.selectedKK + "&pass=" + pass)
                .then(res => res.text())
                .then(response => {
                    alert(response);
                    location.reload();
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

        function submitGanti() {
            let nikBaru = document.getElementById('dropdownAnggota').value;

            fetch('ganti_kepala_keluarga.php?no_kk=' + window.selectedKK + "&nik=" + nikBaru)
                .then(res => res.text())
                .then(response => {
                    alert(response);
                    location.reload();
                });
        }
    </script>

</body>

</html>