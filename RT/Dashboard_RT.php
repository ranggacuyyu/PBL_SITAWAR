<?php 
session_start();
if(!isset($_SESSION['user_rt'])){
    header('location:login_admin.php');
}
    
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
                <a href="DataWarga_RT.html">Data Warga</a>
                <a href="Dokumen_RT.html">Dokumen</a>
                <a href="Laporan_ RT.html">Laporan</a>
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
                    <p class="blue">120</p>
                </div>
                <div class="card">
                    <h3>Warga Hamil</h3>
                    <p class="pink">7</p>
                </div>
                <div class="card">
                    <h3>Kepala Keluarga</h3>
                    <p class="green">40</p>
                </div>
                <div class="card">
                    <h3>Balita</h3>
                    <p class="yellow">15</p>
                </div>
            </section>

            <!-- Grafik -->
            <section class="chart-section">
                <h3>Grafik Perkembangan Data Warga</h3>
                <canvas id="grafikWarga" height="100"></canvas>
            </section>
        </main>
    </div>

    <!-- Modal Tambah Warga -->
    <div id="modalTambah" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="closeModal">&times;</span>
            <h3>Tambah Data Warga</h3>
            <label for="nama">Nama</label>
            <input type="text" id="nama" placeholder="Masukkan nama lengkap">

            <label for="nik">NIK</label>
            <input type="text" id="nik" placeholder="Masukkan NIK">

            <label for="hp">Nomor HP</label>
            <input type="text" id="hp" placeholder="Masukkan nomor HP">

            <button onclick="simpanWarga()">Simpan</button>
        </div>
    </div>

    <script>
        // Modal logika
        const modal = document.getElementById("modalTambah");
        const btnTambah = document.getElementById("btnTambah");
        const closeModal = document.getElementById("closeModal");

        btnTambah.onclick = () => modal.style.display = "flex";
        closeModal.onclick = () => modal.style.display = "none";
        window.onclick = (e) => { if (e.target === modal) modal.style.display = "none"; };

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
        const ctx = document.getElementById('grafikWarga').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt'],
                datasets: [{
                    label: 'Penambahan Warga',
                    data: [2, 5, 3, 6, 8, 10, 7, 9, 5, 11],
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.2)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'top' } } }
        });
    </script>
</body>

</html>