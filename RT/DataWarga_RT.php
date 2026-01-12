<?php
session_start();
require_once '../koneksi.php';
require_once '../db_helper.php';

if (!isset($_SESSION['user_rt'])) {
    echo "<script>
        alert('Silahkan login terlebih dahulu!');
        window.location.href='../LoginRTWARGA.php';
    </script>";
    exit;
}

$notif = $_SESSION['notif'] ?? null;
unset($_SESSION['notif']);

$validasi_RT = $_SESSION['user_rt']['sk_rt'];
$result = db_select_no_assoc($koneksi, "SELECT * FROM user_warga WHERE rt=?", "s", [$validasi_RT]);

if (isset($_POST['nik_warga'], $_POST['pass'])) {
    $nik = $_POST['nik_warga'];
    $pass = $_POST['pass'];
    $sk = $_SESSION['user_rt']['sk_rt'];

    $query = db_select_single($koneksi, "SELECT sk_rt, password FROM user_rt WHERE sk_rt=?", "s", [$sk]);
    if (!$query || !password_verify($pass, $query['password'])) {
        $_SESSION['notif'] = "Password salah, gagal menghapus data";

        echo "<script>
            window.location.href='DataWarga_RT.php';
        </script>";
        exit;
    } else {
        db_delete($koneksi, "DELETE FROM user_warga WHERE nik_warga = ?", "s", [$nik]);
        $_SESSION['notif'] = "Data warga berhasil dihapus.";

        echo "<script>
            window.location.href='DataWarga_RT.php';
        </script>";
        exit;
    }
}

if (isset($_POST['nik_edit'], $_POST['kolom_edit'])) {
    $nik = $_POST['nik_edit'];
    $kolom = $_POST['kolom_edit'];
    $nilai_baru = $_POST['nilai_baru'];
    $pass = $_POST['pass_edit'];
    $sk = $_SESSION['user_rt']['sk_rt'];

    // Validasi password RT
    $cekPass = db_select_single($koneksi, "SELECT password FROM user_rt WHERE sk_rt=?", "s", [$sk]);

    if ($cekPass && password_verify($pass, $cekPass['password'])) {
        if ($kolom === "NIK") {
            $cekNik = db_select_no_assoc(
                $koneksi,
                "SELECT nik_warga FROM user_warga WHERE nik_warga=?",
                "s",
                [$nilai_baru]
            );

            if (mysqli_num_rows($cekNik) > 0) {
                $_SESSION['notif'] = "NIK sudah terdaftar, silahkan gunakan NIK lain.";
                echo "<script>
                    window.location.href='DataWarga_RT.php';
                </script>";
                exit;
            }
        }

        // Map kolom dropdown → nama kolom di database
        $mapKolom = [
            "Nama" => "nama_warga",
            "NIK" => "nik_warga",
            "Tanggal_lahir" => "tanggal_lahir",
            "Tempat_lahir" => "tempat_lahir",
            "Agama" => "agama",
            "Status Keluarga" => "keluarga",
            "Jenis Kelamin" => "jenis_kelamin",
            "NO KK" => "no_kk",
            "Alamat" => "alamat",
            "Pekerjaan" => "pekerjaan",
            "Pendidikan" => "pendidikan",
            "Status Perkawinan" => "status_kawin"
        ];

        $kolom_db = $mapKolom[$kolom];
        db_update($koneksi, "UPDATE user_warga SET $kolom_db='$nilai_baru' WHERE nik_warga=?", "s", [$nik]);
        $_SESSION['notif'] = "Data warga berhasil diperbarui.";
        echo "<script>
                window.location.href='DataWarga_RT.php';
              </script>";
    } else {
        $_SESSION['notif'] = "Password salah, gagal memperbarui data.";
        echo "<script>
                window.location.href='DataWarga_RT.php';
              </script>";
        exit;
    }
}


?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Warga RT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="DataWarga_RT.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../notif.css">
    <!-- SheetJS untuk Excel -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>

    <!-- jsPDF & AutoTable untuk PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
</head>

<body>
    <div class="notifikasi">
        <?php if ($notif): ?>
            <div id="notif" class="notif">
                <?= htmlspecialchars($notif) ?>
            </div>
        <?php endif; ?>
    </div>
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
        <div class="sidebar-footer">
            © 2025 RT Smart System
        </div>
    </aside>
    <div class="home content-animate">
        <h2>Data Warga</h2>
        <div class="card-home">
            <h3>RT 01 RW 02</h3>
            <h5>Kelurahan Sukamaju, Kecamatan Cilandak, Kota Jakarta Selatan</h5>
            <p>halaman pengelolaan data warga dapat melakukan pencarian cepat data warga berdasarkan Nama dan NIK,
                memberikan statistik jumlah warga berdasarkan usia dan dapat menghapus data warga beserta mengupdate
                data warga</p>
        </div>
        <!-- BAGIAN EDIT PHP -->
        <div class="filter-bar">
            <input type="text" id="searchNama" placeholder="Cari Nama..." class="form-control" style="width: 215px;">
            <input type="text" id="searchKK" placeholder="Cari No KK..." class="form-control" style="width: 215px;">
            <select id="filterGender" class="form-select" style="width: 215px;">
                <option value="">Jenis Kelamin</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
            <button class="btn btn-green" onclick="applyFilter()">Filter</button>
            <button class="btn btn-green" onclick="resetFilter()">Reset</button>
            <button class="btn btn-green" onclick="tampilStatistik()">📊 Statistik</button>
            <button class="btn btn-green" data-bs-toggle="modal" data-bs-target="#modalExport">
                Export
            </button>
        </div>

        <table id="tabelWarga" class="tabelWarga">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIK</th>
                    <th>Status Keluarga</th>
                    <th>Jenis Kelamin</th>
                    <th>No KK</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="kolom">
                <?php
                $no = 1;
                while ($data = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $data['nama_warga']; ?></td>
                        <td><?php echo $data['nik_warga']; ?></td>
                        <td><?php echo $data['keluarga']; ?></td>
                        <td><?php echo $data['jenis_kelamin']; ?></td>
                        <td><?php echo $data['no_kk']; ?></td>
                        <td><button class="btn btn-green btn-sm"
                                onclick="lihatDetail('<?php echo $data['nik_warga']; ?>')">Kelola Data</button></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <!-- PAGINATION CONTROLS -->
        <div id="paginationContainer" class="mt-3"></div>

        <!-- Modal Detail -->
        <div class="modal fade" id="modalDetail" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Data Warga</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <tbody id="detailBody"></tbody>
                        </table>
                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <button class="btn btn-danger" id="btnHapus">Hapus Data</button>
                            <button class="btn btn-green" id="btnPerbarui">Perbarui Data</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Password -->
        <div class="modal fade" id="modalPassword" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="POST">
                        <input type="hidden" name="nik_warga" id="nikHidden">

                        <div class="modal-header">
                            <h5 class="modal-title">Verifikasi Password</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Masukkan password untuk melanjutkan:</p>
                            <input type="text" name="pass" id="inputPassword" class="form-control"
                                placeholder="Password...">
                        </div>
                        <div class="modal-footer">
                            <div class="btn btn-secondary" data-bs-dismiss="modal">Batal</div>
                            <button class="btn btn-green" id="btnValidasi" name="verifikasi">Verifikasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Perbarui Data -->
        <div class="modal fade" id="modalEdit" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <input type="hidden" name="nik_edit" id="nikEditHidden">

                        <div class="modal-header">
                            <h5 class="modal-title">Perbarui Data Warga</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <div class="mb-3">
                                <label>Pilih Kolom:</label>
                                <select id="selectKolom" name="kolom_edit" class="form-select">
                                    <option>Nama</option>
                                    <option>NIK</option>
                                    <option>Tanggal_lahir</option>
                                    <option>Tempat_lahir</option>
                                    <option>Agama</option>
                                    <option>Status Keluarga</option>
                                    <option>Jenis Kelamin</option>
                                    <option>NO KK</option>
                                    <option>Alamat</option>
                                    <option>Pekerjaan</option>
                                    <option>Pendidikan</option>
                                    <option>Status Perkawinan</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Nilai Baru</label>
                                <div id="fieldContainer">
                                    <input type="text" class="form-control" name="nilai_baru" id="inputNilaiBaru">
                                </div>
                            </div>


                            <div class="mb-3">
                                <label>Password Validasi:</label>
                                <input type="text" name="pass_edit" id="inputPasswordEdit" class="form-control">
                            </div>

                        </div>

                        <div class="modal-footer">
                            <div class="btn btn-secondary" data-bs-dismiss="modal">Batal</div>
                            <button type="submit" name="update" class="btn btn-green" id="btnSimpanEdit">
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>


        <!-- Modal Statistik -->
        <div class="modal fade" id="modalStatistik" tabindex="1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Statistik Berdasarkan Usia</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <!-- tempat simpan -->
                    <div class="modal-body"><canvas id="chartUsia"></canvas></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalExport" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Pilih Kolom Export</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label><input type="checkbox" class="kolomExport" value="nama_warga" checked> Nama</label><br>
                    <label><input type="checkbox" class="kolomExport" value="nik_warga" checked> NIK</label><br>
                    <label><input type="checkbox" class="kolomExport" value="keluarga" checked> Status
                        Keluarga</label><br>
                    <label><input type="checkbox" class="kolomExport" value="jenis_kelamin" checked> Jenis
                        Kelamin</label><br>
                    <label><input type="checkbox" class="kolomExport" value="no_kk" checked> No KK</label><br>
                    <label><input type="checkbox" class="kolomExport" value="alamat"> Alamat</label><br>
                    <label><input type="checkbox" class="kolomExport" value="usia"> Usia</label><br>
                    <label><input type="checkbox" class="kolomExport" value="agama"> Agama</label><br>
                    <label><input type="checkbox" class="kolomExport" value="ibu_hamil"> Ibu Hamil</label><br>
                    <label><input type="checkbox" class="kolomExport" value="warga_wafat"> Warga Wafat</label><br>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success" onclick="exportExcel()">Excel</button>
                    <button class="btn btn-danger" onclick="exportPDF()">PDF</button>
                </div>

            </div>
        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let warga = [
            <?php
            $laporan = [];
            $qLap = mysqli_query($koneksi, "SELECT nik_pelapor, jenis_laporan FROM laporan");
            while ($l = mysqli_fetch_assoc($qLap)) {
                $laporan[$l['nik_pelapor']][$l['jenis_laporan']] = true;
            }

            mysqli_data_seek($result, 0);
            while ($data = mysqli_fetch_assoc($result)) {
                echo "{ 
                    nama_warga  : " . json_encode($data['nama_warga'] ?: null) . ",
                    nik_warga   : " . json_encode($data['nik_warga'] ?: null) . ",
                    keluarga    : " . json_encode($data['keluarga'] ?: null) . ",
                    jenis_kelamin : " . json_encode($data['jenis_kelamin'] ?: null) . ",
                    no_kk       : " . json_encode($data['no_kk'] ?: null) . ",
                    tanggal_lahir : " . json_encode($data['tanggal_lahir'] ?: null) . ",
                    usia         : hitungUsia(" . json_encode($data['tanggal_lahir']) . "),
                    tempat_lahir : " . json_encode($data['tempat_lahir'] ?: null) . ",
                    agama        : " . json_encode($data['agama'] ?: null) . ",
                    status_kawin : " . json_encode($data['status_kawin'] ?: null) . ",
                    alamat       : " . json_encode($data['alamat'] ?: null) . ",
                    email        : " . json_encode($data['email'] ?: null) . ", 
                    pekerjaan    : " . json_encode($data['pekerjaan'] ?: null) . ",
                    pendidikan   : " . json_encode($data['pendidikan'] ?: null) . ", 
                    hp           : " . json_encode($data['hp'] ?: null) . ",
                    ibu_hamil    : " . json_encode(isset($laporan[$data['nik_warga']]['ibu-hamil']) ? 'Hamil' : '-') . ",
                    warga_wafat  : " . json_encode(strtolower($data['keluarga']) === 'wafat' ? 'Telah Wafat' : 'Hidup') . "
                },";
            }
            ?>
        ];
        let selectedNik = null;
        let modeAksi = ""; // "hapus" atau "edit"

        function tampilkan(val) {
            if (val === null || val === undefined || val === '' || Number.isNaN(val)) {
                return '-';
            }
            return val;
        }


        function lihatDetail(nik) {
            selectedNik = nik;

            const data = warga.find(w => w.nik_warga === nik);
            if (!data) return alert("Data tidak ditemukan!");

            const detailBody = document.getElementById("detailBody");
            detailBody.innerHTML = `
                <tr><th>Nama</th><td>${tampilkan(data.nama_warga)}</td></tr>
                <tr><th>NIK</th><td>${tampilkan(data.nik_warga)}</td></tr>
                <tr><th>Tempat/Tanggal Lahir</th><td>${tampilkan(data.tempat_lahir)}/${tampilkan(data.tanggal_lahir)}</td></tr>
                <tr><th>Agama</th><td>${tampilkan(data.agama)}</td></tr>
                <tr><th>Status keluarga</th><td>${tampilkan(data.keluarga)}</td></tr>
                <tr><th>Status Kawin</th><td>${tampilkan(data.status_kawin)}</td></tr>
                <tr><th>Jenis Kelamin</th><td>${tampilkan(data.jenis_kelamin)}</td></tr>
                <tr><th>No KK</th><td>${tampilkan(data.no_kk)}</td></tr>
                <tr><th>Alamat</th><td>${tampilkan(data.alamat)}</td></tr>
                <tr><th>Pekerjaan</th><td>${tampilkan(data.pekerjaan)}</td></tr>
                <tr><th>Pendidikan</th><td>${tampilkan(data.pendidikan)}</td></tr>
                <tr><th>NO TELEPON</th><td>${tampilkan(data.hp)}</td></tr>
            `;

            new bootstrap.Modal('#modalDetail').show();
        }

        let currentPage = 1;
        const rowsPerPage = 10;
        let filteredData = []; // Menyimpan data hasil filter untuk pagination

        function tampilkanData(dataArray) {
            filteredData = dataArray; // Update data yang sedang aktif (bisa full / hasil filter)
            const kolom = document.getElementById("kolom");
            kolom.innerHTML = "";

            // Hitung Pagination
            const totalRows = filteredData.length;
            const totalPages = Math.ceil(totalRows / rowsPerPage);

            // Validasi currentPage
            if (currentPage < 1) currentPage = 1;
            if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;

            // Slice data untuk halaman ini
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const paginatedData = filteredData.slice(start, end);

            // Render Tabel
            if (totalRows === 0) {
                kolom.innerHTML = "<tr><td colspan='7' class='text-center'>Tidak ada data ditemukan</td></tr>";
            } else {
                paginatedData.forEach((data, index) => {
                    // index + 1 + start agar nomor urut berlanjut antar halaman
                    kolom.innerHTML += `
                        <tr>
                            <td>${start + index + 1}</td>
                            <td>${tampilkan(data.nama_warga)}</td>
                            <td>${tampilkan(data.nik_warga)}</td>
                            <td>${tampilkan(data.keluarga)}</td>
                            <td>${tampilkan(data.jenis_kelamin)}</td>
                            <td>${tampilkan(data.no_kk)}</td>
                            <td><button class="btn btn-green btn-sm" onclick="lihatDetail('${data.nik_warga}')">Kelola Data</button></td>
                        </tr>
                    `;
                });
            }

            renderPaginationControls(totalPages);
        }

        function renderPaginationControls(totalPages) {
            let container = document.getElementById("paginationContainer");
            if (!container) return;

            if (totalPages <= 1) {
                container.innerHTML = "";
                return;
            }

            let html = '<nav><ul class="pagination pagination-sm justify-content-end">';

            // Prev Button
            let prevDisabled = (currentPage === 1) ? "disabled" : "";
            html += `<li class="page-item ${prevDisabled}"><a class="page-link" href="#" onclick="gantiHalaman(${currentPage - 1}); return false;">Previous</a></li>`;

            // Page Numbers (Simple Logic: show all or simplified range)
            // Untuk simplifikasi, kita tampilkan max 5 page di sekitar current page
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, currentPage + 2);

            if (startPage > 1) {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="gantiHalaman(1); return false;">1</a></li>`;
                if (startPage > 2) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }

            for (let i = startPage; i <= endPage; i++) {
                let active = (i === currentPage) ? "active" : "";
                html += `<li class="page-item ${active}"><a class="page-link" href="#" onclick="gantiHalaman(${i}); return false;">${i}</a></li>`;
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                html += `<li class="page-item"><a class="page-link" href="#" onclick="gantiHalaman(${totalPages}); return false;">${totalPages}</a></li>`;
            }

            // Next Button
            let nextDisabled = (currentPage === totalPages) ? "disabled" : "";
            html += `<li class="page-item ${nextDisabled}"><a class="page-link" href="#" onclick="gantiHalaman(${currentPage + 1}); return false;">Next</a></li>`;

            html += '</ul></nav>';
            container.innerHTML = html;
        }

        function gantiHalaman(page) {
            currentPage = page;
            tampilkanData(filteredData); // Re-render dengan data yang sama (filteredData sudah tersimpan di scope global)
        }
        // ==== Aksi Tombol ====
        document.getElementById("btnHapus").onclick = function() {
            modeAksi = "hapus";

            // masukkan nik warga ke hidden input
            document.getElementById("nikHidden").value = selectedNik;

            new bootstrap.Modal('#modalPassword').show();
        };


        document.getElementById("btnPerbarui").onclick = function() {
            document.getElementById("nikEditHidden").value = selectedNik;
            new bootstrap.Modal('#modalEdit').show();
        };

        document.getElementById("selectKolom").addEventListener("change", function() {
            let kolom = this.value;
            let container = document.getElementById("fieldContainer");

            // Reset ke input text
            container.innerHTML = `
        <input type="text" class="form-control" name="nilai_baru" id="inputNilaiBaru">
    `;

            if (kolom === "Agama") {
                container.innerHTML = `
            <select class="form-select" name="nilai_baru">
                <option>Islam</option>
                <option>Kristen</option>
                <option>Katolik</option>
                <option>Hindu</option>
                <option>Buddha</option>
                <option>Konghucu</option>
            </select>
        `;
            }

            if (kolom === "Pendidikan") {
                container.innerHTML = `
            <select class="form-select" name="nilai_baru">
                <option>SD</option>
                <option>SMP</option>
                <option>SMA</option>
                <option>SMK</option>
                <option>D1</option>
                <option>D2</option>
                <option>D3</option>
                <option>D4</option>
                <option>S1</option>
                <option>S2</option>
                <option>S3</option>
            </select>
        `;
            }

            if (kolom === "Tanggal_lahir") {
                container.innerHTML = `
            <input type="date" class="form-control" name="nilai_baru" id="inputNilaiBaru">
        `;
            }

            if (kolom === "Status Keluarga") {
                container.innerHTML = `
            <select class="form-select" name="nilai_baru">
                <option>kepala keluarga</option>
                <option>anggota keluarga</option>
            </select>
        `;
            }

            if (kolom === "Jenis Kelamin") {
                container.innerHTML = `
            <select class="form-select" name="nilai_baru">
                <option>Laki-laki</option>
                <option>Perempuan</option>
            </select>
        `;
            }

            if (kolom === "Status Perkawinan") {
                container.innerHTML = `
            <select class="form-select" name="nilai_baru">
                <option>Belum Kawin</option>
                <option>Kawin</option>
                <option>Cerai Hidup</option>
                <option>Cerai Mati</option>
            </select>
        `;
            }

            if (kolom === "Pekerjaan") {
                container.innerHTML = `
            <select class="form-select" name="nilai_baru">
                <option>Pelajar / Mahasiswa</option>
                <option>Swasta</option>
                <option>BUMN</option>
                <option>Wirausaha</option>
                <option>PNS</option>
                <option>Tidak bekerja</option>
            </select>
        `;
            }
        });

        // ==== Filter dan Statistik ====
        function applyFilter() {
            const nama = document.getElementById("searchNama").value.toLowerCase();
            const kk = document.getElementById("searchKK").value.toLowerCase();
            const gender = document.getElementById("filterGender").value.toLowerCase();

            const hasil = warga.filter(w =>
                (w.nama_warga || "").toLowerCase().includes(nama) &&
                (w.no_kk || "").toLowerCase().includes(kk) &&
                (gender === "" || (w.jenis_kelamin || "").toLowerCase() === gender)
            );

            currentPage = 1;
            tampilkanData(hasil);
        }



        function resetFilter() {
            document.getElementById("searchNama").value = "";
            document.getElementById("searchKK").value = "";
            document.getElementById("filterGender").value = "";
            tampilkanData(warga);
        }

        function hitungUsia(tanggal) {
            // Jika tanggal lahir tidak ada atau kosong, return null
            if (!tanggal || tanggal === '' || tanggal === null || tanggal === undefined) {
                return null;
            }

            const tgl = new Date(tanggal);

            // Cek apakah tanggal valid
            if (isNaN(tgl.getTime())) {
                return null;
            }

            const today = new Date();
            let usia = today.getFullYear() - tgl.getFullYear();
            const bulan = today.getMonth() - tgl.getMonth();

            if (bulan < 0 || (bulan === 0 && today.getDate() < tgl.getDate())) {
                usia--;
            }
            return usia;
        }

        let chartUsia = null;

        function tampilStatistik() {

            // hitung data
            const balita = warga.filter(w => w.usia <= 6).length;
            const remaja = warga.filter(w => w.usia >= 7 && w.usia <= 17).length;
            const dewasa = warga.filter(w => w.usia >= 18 && w.usia <= 59).length;
            const lansia = warga.filter(w => w.usia >= 60).length;
            const wafat = warga.filter(w => w.keluarga === 'wafat').length;

            const dataStat = [balita, remaja, dewasa, lansia, wafat];
            const maxData = Math.max(...dataStat) + 1; // ⭐ batas atas

            const ctx = document.getElementById('chartUsia').getContext('2d');

            // hapus chart lama
            if (chartUsia) {
                chartUsia.destroy();
            }

            chartUsia = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Balita', 'Anak-anak', 'Dewasa', 'Lansia', 'Wafat'],
                    datasets: [{
                        label: 'Jumlah Warga',
                        data: dataStat,

                        // gaya modern
                        borderColor: '#4b5320',
                        backgroundColor: 'rgba(75, 83, 32, 0.15)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        pointBackgroundColor: '#4b5320',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: maxData, // ⭐ batas atas = nilai terbesar + 1
                            ticks: {
                                precision: 0 // ⛔ tanpa desimal
                            }
                        }
                    }
                }
            });

            new bootstrap.Modal(
                document.getElementById('modalStatistik')
            ).show();
        }

        tampilkanData(warga);

        function exportExcel() {
            let kolomDipilih = [];
            document.querySelectorAll(".kolomExport:checked").forEach(cb => {
                kolomDipilih.push(cb.value);
            });

            let hasil = warga.map(row => {
                let obj = {};
                kolomDipilih.forEach(k => {
                    obj[k] = tampilkan(row[k]);
                });
                return obj;
            });

            let worksheet = XLSX.utils.json_to_sheet(hasil);
            let workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, "Data Warga");

            XLSX.writeFile(workbook, "data_warga_rt.xlsx");
        }

        function exportPDF() {
            const {
                jsPDF
            } = window.jspdf;
            let doc = new jsPDF();

            let kolomDipilih = [];
            document.querySelectorAll(".kolomExport:checked").forEach(cb => {
                kolomDipilih.push(cb.value);
            });

            let head = [kolomDipilih.map(k => k.toUpperCase())];

            let body = warga.map(row => {
                return kolomDipilih.map(k => tampilkan(row[k]));
            });

            doc.text("DATA WARGA RT", 14, 15);

            doc.autoTable({
                startY: 20,
                head: head,
                body: body
            });

            doc.save("data_warga_rt.pdf");
        }

        // Initialize view
        tampilkanData(warga);
    </script>
</body>

</html>