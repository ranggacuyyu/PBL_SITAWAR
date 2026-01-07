<?php
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';

if (!isset($_SESSION['admin_user'])) {
    header('location:login_admin.php');
    exit();
}
$notif = $_SESSION['notif'] ?? null;
unset($_SESSION['notif']);

$ambilData = $_SESSION['admin_user']['id_admin'];
$query = db_select_no_assoc($koneksi, "SELECT * FROM user_rt WHERE admin=? ", "i", [$ambilData]);

if (isset($_POST['update'])) {
    $nama    = trim($_POST['nama_rt']);
    $nik     = trim($_POST['nik_rt']);
    $nohp    = trim($_POST['nohp_rt']);
    $no_rw   = trim($_POST['no_rw']);
    $sk_baru = trim($_POST['sk_rt']);
    $no_rt   = trim($_POST["no_rt"]);
    $sk_lama = trim($_POST['sk_rt_lama']);

    if ($nama == '' || $nik == '' || $nohp == '' || $no_rw == '' || $sk_baru == '' || $no_rt == '') {
        $_SESSION['notif'] = "Data tidak boleh ada yang kosong";
        header("Location: dashborad_admin.php");
        exit();
    } elseif (strlen($nik) !== 16 || !ctype_digit($nik)) {
        $_SESSION['notif'] = "NIK harus 16 digit angka";
        header("Location: dashborad_admin.php");
        exit();
    } elseif (strlen($nohp) <= 10 or strlen($nohp) >= 13 || !ctype_digit($nohp)) {
        $_SESSION['notif'] = "No HP harus 10 hingga 13 angka";
        header("Location: dashborad_admin.php");
        exit();
    } else {
        $update = db_update(
            $koneksi,
            "UPDATE user_rt 
            SET nama_rt=?, nik_rt=?, no_rt=?, no_rw=?, nohp_rt=?, sk_rt=?   
            WHERE sk_rt=?",
            "sssssss",
            [$nama, $nik, $no_rt, $no_rw, $nohp, $sk_baru, $sk_lama]
        );

        if ($update) {
            $_SESSION['notif'] = "Data berhasil diupdate";
            header("Location: dashborad_admin.php");
            exit();
        } else {
            $_SESSION['notif'] = "Data gagal diupdate";
            header("Location: dashborad_admin.php");
            exit();
        }
    }
}

$per_page = 2; 
$current_page = isset($_GET['hal']) ? (int)$_GET['hal'] : 1;

$current_page = max(1, $current_page);
$offset = ($current_page - 1) * $per_page;

$data_total = db_select_single($koneksi, 
"SELECT COUNT(*) AS total FROM user_rt WHERE admin=?", 
"i", 
[$ambilData]);

$total_data = (int)$data_total['total'];

$query = db_select_no_assoc(
    $koneksi,
    "SELECT * FROM user_rt 
     WHERE admin=? 
     ORDER BY nama_rt ASC 
     LIMIT $per_page OFFSET $offset",
    "i",
    [$ambilData]
);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman_admin</title>
    <link rel="stylesheet" href="dashboard_admin.css?v=<?php echo time(); ?>">
    <style>
        /* ===== Pagination Bootstrap-like ===== */
        .pagination {
            display: flex;
            list-style: none;
            padding-left: 0;
            border-radius: 0.375rem;
            gap: 4px;
        }

        .pagination .page-item {
            display: inline;
        }

        .pagination .page-link {
            position: relative;
            display: block;
            padding: 6px 12px;
            font-size: 14px;
            color: #6d993aff;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        /* Hover */
        .pagination .page-link:hover {
            color: #475f30ff;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        /* Active page */
        .pagination .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #668a37ff;
            border-color: #668a37ff;
            cursor: default;
        }

        /* Disabled */
        .pagination .page-item.disabled .page-link,
        .pagination .page-item.disabled span {
            color: #6c757d;
            pointer-events: none;
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }

        /* Ellipsis (...) */
        .pagination .page-item.disabled span {
            padding: 6px 12px;
            border-radius: 4px;
        }

        /* Small size (pagination-sm) */
        .pagination-sm .page-link {
            padding: 4px 10px;
            font-size: 13px;
        }

        /* Alignment */
        .justify-content-end {
            justify-content: flex-end;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .pagination {
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>
    <div class="notifikasi">
        <?php if ($notif): ?>
            <div id="notif" class="notif">
                <?= htmlspecialchars($notif) ?>
            </div>
        <?php endif; ?>
    </div>
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
                                <input type="text" id="nik_rt" name="nik_rt">

                                <label>NO RT</label>
                                <input type="text" id="no_rt" name="no_rt">

                                <label>NO RW</label>
                                <input type="text" id="no_rw" name="no_rw">

                                <label>No HP</label>
                                <input type="text" id="nohp_rt" name="nohp_rt">

                                <label>No SK</label>
                                <input type="text" id="sk_rt" name="sk_rt">

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
                            <button class="alur" style="height: 50px; font-size:20px;" onclick="return confirm('Yakin ingin keluar?')">Keluar</button>
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
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = $offset + 1;
                            while ($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($data['nama_rt']); ?></td>
                                    <td><?= htmlspecialchars($data['nik_rt']); ?></td>
                                    <td><?= htmlspecialchars($data['no_rt']); ?></td>
                                    <td><?= htmlspecialchars($data['no_rw']); ?></td>
                                    <td><?= htmlspecialchars($data['nohp_rt']); ?></td>
                                    <td><?= htmlspecialchars($data['sk_rt']); ?></td>
                                    <td style="display: flex;">
                                        <form action="hapus_admin.php" method="POST" style="padding: 0; width:auto" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            <input type="hidden" name="sk_rt" value="<?= htmlspecialchars($data['sk_rt']) ?>">
                                            <button type="submit" class="hapus">HAPUS</button>
                                        </form>

                                        <button class="update" onclick="openModal(
                                            '<?= htmlspecialchars($data['nama_rt']); ?>',
                                            '<?= htmlspecialchars($data['nik_rt']); ?>',
                                            '<?= htmlspecialchars($data['no_rt']); ?>',
                                            '<?= htmlspecialchars($data['no_rw']); ?>',
                                            '<?= htmlspecialchars($data['nohp_rt']); ?>',
                                            '<?= htmlspecialchars($data['sk_rt']); ?>')">
                                            UPDATE
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="mt-3">
                        <?php
                        echo db_pagination_links(
                            $total_data,
                            $per_page,
                            $current_page,
                            $_SERVER['REQUEST_URI']
                        );
                        ?>
                    </div>
                </div>
            </section>
        </main>
    </div>
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
        function openModal(nama, nik, no_rt, no_rw, nohp, sk, wilayah) {
            document.getElementById("modalUpdate").style.display = "flex";

            document.getElementById("nama_rt").value = nama;
            document.getElementById("nik_rt").value = nik;
            document.getElementById("no_rt").value = no_rt;
            document.getElementById("no_rw").value = no_rw;
            document.getElementById("nohp_rt").value = nohp;
            document.getElementById("sk_rt").value = sk;

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
                let nik = row.children[2].innerText.toLowerCase();

                if (
                    nama.includes(keyword) ||
                    sk.includes(keyword) ||
                    nik.includes(keyword) ||
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
</body>

</html>