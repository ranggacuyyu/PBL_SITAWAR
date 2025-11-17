<?php 
session_start();
if(!isset($_SESSION['admin_user'])){
    header('location:login_admin.php');
}
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman_admin</title>
    <link rel="stylesheet" href="yangbaru.css">
</head>

<body>
    <!-- DASHBOARD PAGE -->
    <div class="dashboard" id="dashboardPage">
        <main>
            <aside>
                <h2>SITAWAR ADMIN</h2>
                <ul>
                    <li >Daftar RT</li>
                    <li onclick="window.location.href=('tambah_akun.php')">Tambah RT</li>
                </ul>
            </aside>
            <section>
                <div id="daftarRT" class="section">
                    <div class="torik">
                        <div>
                            <h2>DASHBOARD ADMIN</h2>
                            <p>Bagian pendataan akun RT</p>
                        </div>
                        <button id="logoutBtn" class="btn-close">Keluar</button>
                    </div>
                    <hr>
                    <h3>Daftar RT Terdaftar</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>No HP</th>
                                <th>Nomor SK</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="rtBody"></tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>

</html>