<?php
session_start();
include "../koneksi.php";
if (!isset($_SESSION['user_rt'])) {
    header('location:../LoginRTWARGA.php');
}

$sk_rt = $_SESSION['user_rt']['sk_rt'];
$rt    = $_SESSION['user_rt']['no_rt'];
$rw    = $_SESSION['user_rt']['no_rw'];

$query = mysqli_query($koneksi, "SELECT COUNT(*) as user_warga FROM user_warga");
$user  = mysqli_fetch_assoc($query);

$query1 = mysqli_query($koneksi, "SELECT COUNT(ibu_hamil) as hamil FROM user_warga");
$user1  = mysqli_fetch_assoc($query1);

$query2 = mysqli_query($koneksi, "SELECT COUNT(keluarga) as kepala_keluarga FROM user_warga");
$user2  = mysqli_fetch_assoc($query2);

$query3 = mysqli_query($koneksi, "SELECT COUNT(*) as balita FROM user_warga WHERE kategori='Balita' and rt = $sk_rt");
$user3  = mysqli_fetch_assoc($query3);


if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $nik  = $_POST['nik'];
    $hp   = $_POST['hp'];

    $query = "INSERT INTO user_warga (nama_warga, nik_warga, hp, no_rt, no_rw, rt)
    VALUES ('$nama', '$nik', '$hp', '$rt', '$rw', '$sk_rt')";
}

$data_per_bulan = array_fill(1, 12, 0); // buat 12 bulan isi 0

$query4 = mysqli_query($koneksi, "SELECT MONTH(tanggal_input) AS bulan, COUNT(*) AS total
    FROM user_rt
    WHERE YEAR(tanggal_input) = YEAR(CURDATE())
    GROUP BY MONTH(tanggal_input)");

while ($row = mysqli_fetch_assoc($query4)) {
    $bulan = (int)$row['bulan'];
    $data_per_bulan[$bulan] = $row['total'];
}

$chart_data = json_encode(array_values($data_per_bulan));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SITAWAR - Sistem Informasi Tata Warga</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="Dashboar_RT.css">
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
                <a href="#">Dashboard</a>
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
                <h2>Selamat Datang di Dashboard RT</h2>
                <p>Pantau data dan aktivitas warga secara real-time.</p>
            </section>

            <!-- Tambahan dua card -->
            <section class="info-section">
                <div class="info-card">
                    <h3>🧾 Cara Menambahkan Data Warga</h3>
                    <p>
                        Untuk menambahkan warga baru, klik tombol <b>"Tambah Warga"</b> di sebelah kanan.
                        Isi data lengkap seperti nama, NIK, dan nomor HP sesuai identitas.
                        Setelah selesai, klik <b>"Simpan"</b> untuk menambah ke database.
                    </p>
                </div>

                <div class="info-card">
                    <h3>➕ Tambahkan Warga Baru</h3>
                    <p>Gunakan tombol di bawah ini untuk memasukkan data warga baru ke sistem.</p>
                    <button id="btnTambah">Tambah Warga</button>
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

                <label for="hp">Nomor HP</label>
                <input type="text" id="hp" name="hp" placeholder="Masukkan nomor HP">

                <button type="submit" name="simpan" onclick="simpanWarga()">Simpan</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Modal logika
        const modal = document.getElementById("modalTambah");
        const btnTambah = document.getElementById("btnTambah");
        const closeModal = document.getElementById("closeModal");

        btnTambah.onclick = () => modal.style.display = "flex";
        closeModal.onclick = () => modal.style.display = "none";
        window.onclick = (e) => {
            if (e.target === modal) modal.style.display = "none";
        };

        // Simpan data (dummy alert)
        function simpanWarga() {
            const nama = document.getElementById("nama").value;
            const nik = document.getElementById("nik").value;
            const hp = document.getElementById("hp").value;
            if (!nama || !nik || !hp) {
                alert("Harap isi semua data terlebih dahulu!");
                return;
            }
            alert(`✅ Data warga baru ditambahkan:\nNama: ${nama}\nNIK: ${nik}\nNo HP: ${hp}`);
            modal.style.display = "none";
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
</body>

</html>