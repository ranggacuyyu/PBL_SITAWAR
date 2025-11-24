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
    $no_rw   = $_POST['no_rw'];
    $sk_baru = $_POST['sk_rt'];
    $wilayah  = $_POST['wilayah'];
    $no_rt   = $_POST["no_rt"];
    $sk_lama = $_POST['sk_rt_lama'];

    $stmt = $koneksi->prepare("UPDATE user_rt 
            SET nama_rt=?, nik_rt=?, no_rt=?, no_rw=?, nohp_rt=?, sk_rt=?, wilayah_rt=? 
            WHERE sk_rt=?");

    $stmt->bind_param(
        "ssssssss",
        $nama,
        $nik,
        $no_rt,
        $no_rw,
        $nohp,
        $sk_baru,
        $wilayah,
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
<html  lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman_admin</title>
    <link rel="stylesheet" href="dashboard_admin.css?v=<?php echo time(); ?>">
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
                                
                                <label>NO RW</label>
                                <input type="text" id="no_rw" name="no_rw">

                                <label>No HP</label>
                                <input type="number" id="nohp_rt" name="nohp_rt">

                                <label>No SK</label>
                                <input type="text" id="sk_rt" name="sk_rt">

                                <label>Alamat</label>
                                <input type="text" id="alamat_rt" name="wilayah">

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
                        <input type="text" id="filterInput" placeholder="Cari Nama / NIK / SK / No RW.....">
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
                                <th>No RW</th>
                                <th>No HP</th>
                                <th>Nomor SK</th>
                                <th>Wilayah</th>
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
                                    <td><?= $data['no_rw']; ?></td>
                                    <td><?= $data['nohp_rt']; ?></td>
                                    <td><?= $data['sk_rt']; ?></td>
                                    <td><?= $data['wilayah_rt']; ?></td>
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
                                            '<?= $data['no_rw']; ?>',
                                            '<?= $data['nohp_rt']; ?>',
                                            '<?= $data['sk_rt']; ?>',
                                            '<?= $data['wilayah_rt']; ?>')">
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
    function openModal(nama, nik, no_rt, no_rw, nohp, sk, wilayah) {
        document.getElementById("modalUpdate").style.display = "flex";

        document.getElementById("nama_rt").value = nama;
        document.getElementById("nik_rt").value = nik;
        document.getElementById("no_rt").value = no_rt;
        document.getElementById("no_rw").value = no_rw;
        document.getElementById("nohp_rt").value = nohp;
        document.getElementById("sk_rt").value = sk;
        document.getElementById("alamat_rt").value = wilayah;

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
            let sk = row.children[6].innerText.toLowerCase();
            let no_rw = row.children[4].innerText.toLowerCase();
            let wilayah = row.children[7].innerText.toLowerCase();


            if (
                nama.includes(keyword) ||
                wilayah.includes(keyword) ||
                sk.includes(keyword) ||
                no_rw.includes(keyword)
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