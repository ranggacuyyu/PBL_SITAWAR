<?php
session_start();
include '../koneksi.php';
if (!isset($_SESSION['admin_user'])) {
    header('location:login_admin.php');
}
$query = mysqli_query($koneksi, "SELECT * FROM user_rt");

if (isset($_POST['update'])) {
    $nama    = $_POST['nama_rt'];
    $nik     = $_POST['nik_rt'];
    $nohp    = $_POST['nohp_rt'];
    $sk_baru = $_POST['sk_rt'];
    $alamat  = $_POST['alamat_rt'];
    $pass    = $_POST['password_rt'];
    $no_rt   = $_POST["no_rt"];
    $sk_lama = $_POST['sk_rt_lama'];

    $stmt = $koneksi->prepare("UPDATE user_rt 
            SET nama_rt=?, nik_rt=?,no_rt=?, nohp_rt=?, sk_rt=?, alamat_rt=?, password=? 
            WHERE sk_rt=?");

    $stmt->bind_param(
        "ssssssss",
        $nama,
        $nik,
        $no_rt,
        $nohp,
        $sk_baru,
        $alamat,
        $pass,
        $sk_lama
    );

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diupdate'); window.location='dashborad_admin.php';</script>";
    } else {
        echo "Gagal update: " . $stmt->error;
    }

    $stmt->close();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman_admin</title>
    <link rel="stylesheet" href="dashboard_admin.css">
    <style>
        /* Background gelap modal */
        .modal-bg {
            display: none;
            position: fixed;
            z-index: 100;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
        }

        /* Box modal */
        .modal-box {
            background: white;
            width: 350px;
            padding: 20px;
            border-radius: 10px;
            animation: fadeIn 0.3s;
        }

        /* Animasi */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hapus {
            background-color: #a02f2fff;
            list-style: none;
            text-decoration: none;
            color: #f5f5f5f5;
            padding: 5px;
            margin-right: 10px;
            border-radius: 5px;
        }

        .hapus:hover {
            background-color: #f5f5f5f5;
            color: #678f3bff;
        }

        .update {
            background-color: #678f3bff;
            list-style: none;
            text-decoration: none;
            color: #f5f5f5f5;
            padding: 5px;
            font-size: 16px;
            margin-right: 10px;
            border-radius: 5px;
        }

        .update:hover {
            background-color: #f5f5f5f5;
            color: #678f3bff;
        }

        .close-btn {
            float: right;
            cursor: pointer;
            font-weight: bold;
        }

        .modal-box label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        .modal-box input {
            width: 100%;
            padding: 7px;
            border: 1px solid #aaa;
            border-radius: 5px;
            margin-top: 3px;
        }

        .modal-box button {
            margin-top: 15px;
            padding: 10px;
            width: 100%;
            background: #679e3af5;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
        }

        .filter-box {
            margin: 20px 0;
            display: flex;
            gap: 10px;
        }

        .filter-box input {
            padding: 8px;
            width: 250px;
            border: 1px solid #aaa;
            border-radius: 5px;
        }

        .filter-box button {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        #btnFilter {
            background: #679e3af5;
            color: white;
        }

        #btnReset {
            background: #888;
            color: white;
        }
    </style>
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
                    <form method="post" style="padding: 0;">
                        <div class="modal-bg" id="modalUpdate">
                            <div class="modal-box">

                                <span class="close-btn" onclick="closeModal()">X</span>

                                <h3>Update Data</h3>
                                <input type="hidden" name="sk_rt_lama" id="sk_rt_lama">

                                <label>Nama</label>
                                <input type="text" id="nama_rt" name="nama_rt">

                                <label>NIK</label>
                                <input type="number" id="nik_rt" name="nik_rt">

                                <label>NO RT</label>
                                <input type="text" id="no_rt" name="no_rt">

                                <label>No HP</label>
                                <input type="number" id="nohp_rt" name="nohp_rt">

                                <label>No SK</label>
                                <input type="text" id="sk_rt" name="sk_rt">

                                <label>Alamat</label>
                                <input type="text" id="alamat_rt" name="alamat_rt">

                                <label>Password</label>
                                <input type="text" id="password_rt" name="password_rt">

                                <button type="submit" name="update">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                    <div class="torik">
                        <div>
                            <h2>DASHBOARD ADMIN</h2>
                            <p>Bagian pendataan akun RT</p>
                        </div>
                        <form action="logout.php" method="post" style="padding: 0; width:80px; height:70px">
                            <button class="alur" style="height: 50px; font-size:20px;">Keluar</button>
                        </form>
                    </div>
                    <hr>
                    <h3>Daftar RT Terdaftar</h3>
                    <div class="filter-box">
                        <input type="text" id="filterInput" placeholder="Cari Nama / NIK / SK / NoRT.....">
                        <button id="btnFilter">Filter</button>
                        <button id="btnReset">Reset</button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>No RT</th>
                                <th>No HP</th>
                                <th>Nomor SK</th>
                                <th>Alamat</th>
                                <th>Password</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $data['nama_rt']; ?></td>
                                    <td><?= $data['nik_rt']; ?></td>
                                    <td><?= $data['no_rt']; ?></td>
                                    <td><?= $data['nohp_rt']; ?></td>
                                    <td><?= $data['sk_rt']; ?></td>
                                    <td><?= $data['alamat_rt']; ?></td>
                                    <td><?= $data['password']; ?></td>
                                    <td>
                                        <a href="hapus_admin.php?sk_rt=<?= $data['sk_rt']; ?>"
                                            class="hapus"
                                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                                            HAPUS
                                        </a>
                                        <button class="update" onclick="openModal(
                                            '<?= $data['nama_rt']; ?>',
                                            '<?= $data['nik_rt']; ?>',
                                            '<?= $data['no_rt']; ?>',
                                            '<?= $data['nohp_rt']; ?>',
                                            '<?= $data['sk_rt']; ?>',
                                            '<?= $data['alamat_rt']; ?>',
                                            '<?= $data['password']; ?>')">
                                            UPDATE
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
<script>
    function openModal(nama, nik, no_rt, nohp, sk, alamat, pass) {
        document.getElementById("modalUpdate").style.display = "flex";

        document.getElementById("nama_rt").value = nama;
        document.getElementById("nik_rt").value = nik;
        document.getElementById("no_rt").value = no_rt;
        document.getElementById("nohp_rt").value = nohp;
        document.getElementById("sk_rt").value = sk;
        document.getElementById("alamat_rt").value = alamat;
        document.getElementById("password_rt").value = pass;

        // simpan sk lama
        document.getElementById("sk_rt_lama").value = sk;
    }

    function closeModal() {
        document.getElementById("modalUpdate").style.display = "none";
    }

    document.getElementById("btnFilter").addEventListener("click", function() {
        let keyword = document.getElementById("filterInput").value.toLowerCase();
        let rows = document.querySelectorAll("tbody tr");

        rows.forEach(row => {
            let nama = row.children[1].innerText.toLowerCase();
            let nik = row.children[2].innerText.toLowerCase();
            let sk = row.children[5].innerText.toLowerCase();
            let no_rt = row.children[3].innerText.toLowerCase();


            if (
                nama.includes(keyword) ||
                nik.includes(keyword) ||
                sk.includes(keyword) ||
                no_rt.includes(keyword)
            ) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });

    document.getElementById("btnReset").addEventListener("click", function() {
        document.getElementById("filterInput").value = "";
        let rows = document.querySelectorAll("tbody tr");
        rows.forEach(row => (row.style.display = ""));
    });
</script>

</html>