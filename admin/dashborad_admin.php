<?php
session_start();
if (!isset($_SESSION['admin_user'])) {
    header('location:login_admin.php');
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman_admin</title>
    <link rel="stylesheet" href="dashboard_admin.css">
</head>

<body>
    <!-- DASHBOARD PAGE -->
    <div class="dashboard" id="dashboardPage">
        <main>
            <aside>
                <h2>SITAWAR ADMIN</h2>
                <ul>
                    <li>Daftar RT</li>
                    <li onclick="window.location.href=('tambah_akun.php')">Tambah RT</li>
                </ul>
            </aside>
            <section>
                <div id="daftarRT" class="section">
                    <div class="span">
                        <p>Hello World</p>
                    </div>
                    <div class="torik">
                        <div>
                            <h2>DASHBOARD ADMIN</h2>
                            <p>Bagian pendataan akun RT</p>
                        </div>
                        <button class="alur">Keluar</button>
                    </div>
                    <hr>
                    <h3>Daftar RT Terdaftar</h3>
                    <table>
                        <thead>git add .

                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>No HP</th>
                                <th>Nomor SK</th>
                                <th>Alamat</th>
                                <th>Password</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include '../koneksi.php';
                            $query = mysqli_query($koneksi, "SELECT * FROM user_rt");

                            while ($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?= $data['admin']; ?></td>
                                    <td><?= $data['nama_rt']; ?></td>
                                    <td><?= $data['nik_rt']; ?></td>  
                                    <td><?= $data['nohp_rt']; ?></td>
                                    <td><?= $data['sk_rt']; ?></td>
                                    <td><?= $data['alamat_rt']; ?></td>
                                    <td><?= $data['password']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>

</html>