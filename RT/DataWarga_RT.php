<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['user_rt'])) {
    echo "<script>
        alert('Silahkan login terlebih dahulu!');
        window.location.href='../LoginRTWARGA.php';
    </script>";
    exit;
}

$validasi_RT = $_SESSION['user_rt']['sk_rt'];

// Cek status welcome animation
$show_welcome = false;
if (!isset($_SESSION['welcome_shown'])) {
    $show_welcome = true;
    $_SESSION['welcome_shown'] = true;
}

$result = mysqli_query($koneksi, "SELECT * FROM user_warga WHERE rt = '$validasi_RT'");
$warga_list = [];
while ($row = mysqli_fetch_assoc($result)) {
    $warga_list[] = $row;
}



if (isset($_POST['verifikasi'])) {
    $nik = $_POST['nik_warga'];
    $pass = $_POST['pass'];
    $sk = $_SESSION['user_rt']['sk_rt'];

    // cek password admin RT
    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM user_rt WHERE sk_rt='$sk' AND password ='$pass'"
    );

    if (mysqli_num_rows($query) > 0) {

        // hapus warga
        mysqli_query($koneksi, "DELETE FROM user_warga WHERE nik_warga='$nik'");

        echo "<script>
            alert('Data warga berhasil dihapus!');
            window.location.href='DataWarga_RT.php';
        </script>";
    } else {
        echo "<script>
            alert('Password salah, gagal menghapus data');
            window.location.href='DataWarga_RT.php';
        </script>";
    }
}

if (isset($_POST['update'])) {

    $nik = $_POST['nik_edit'];
    $kolom = $_POST['kolom_edit'];
    $nilai_baru = $_POST['nilai_baru'];
    $pass = $_POST['pass_edit'];
    $sk = $_SESSION['user_rt']['sk_rt'];

    // Validasi password RT
    $cekPass = mysqli_query(
        $koneksi,
        "SELECT * FROM user_rt WHERE sk_rt='$sk' AND password='$pass'"
    );

    if (mysqli_num_rows($cekPass) > 0) {

        // Jika user mengganti NIK → cek apakah NIK sudah dipakai
        if ($kolom === "NIK") {
            $cekNik = mysqli_query(
                $koneksi,
                "SELECT * FROM user_warga WHERE nik_warga='$nilai_baru'"
            );

            if (mysqli_num_rows($cekNik) > 0) {
                echo "<script>
                        alert('NIK sudah digunakan warga lain! Update dibatalkan.');
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

        // Query update
        mysqli_query(
            $koneksi,
            "UPDATE user_warga SET $kolom_db='$nilai_baru' WHERE nik_warga='$nik'"
        );

        echo "<script>
                alert('Data berhasil diperbarui!');
                window.location.href='DataWarga_RT.php';
              </script>";
    } else {
        echo "<script>
                alert('Password salah!');
                window.location.href='DataWarga_RT.php';
              </script>";
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
    <!-- SheetJS untuk Excel -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>

    <!-- jsPDF & AutoTable untuk PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

    <style>
        @keyframes contentSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-home {
            animation: contentSlideUp 2.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .filter-bar {
            animation: contentSlideUp 2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        table {
            animation: contentSlideUp 1.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;

        }

        .content-animate {
            padding-left: 0;
            margin-left: 250px;
        }
    </style>
</head>

<body>
    <?php if ($show_welcome): ?>
        <!-- Welcome Overlay -->
        <div id="welcome-overlay">
            <div class="welcome-content">
                <h1 class="welcome-title">Selamat Datang, <?php echo htmlspecialchars($_SESSION['user_rt']['nama_rt']); ?>
                </h1>
                <div class="welcome-divider"></div>
                <p class="welcome-subtitle">Sistem Informasi Tata Warga (SITAWAR)</p>
            </div>
        </div>
    <?php endif; ?>

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
                            <input type="password" name="pass" id="inputPassword" class="form-control"
                                placeholder="Password...">
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
                                <input type="password" name="pass_edit" id="inputPasswordEdit" class="form-control">
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
                    nama_warga  : '" . $data['nama_warga'] . "',
                    nik_warga   : '" . $data['nik_warga'] . "',
                    keluarga    : '" . $data['keluarga'] . "',
                    jenis_kelamin : '" . $data['jenis_kelamin'] . "',
                    no_kk       : '" . $data['no_kk'] . "',
                    tanggal_lahir : '" . $data['tanggal_lahir'] . "',
                    usia         : hitungUsia('" . $data['tanggal_lahir'] . "'),
                    tempat_lahir : '" . $data['tempat_lahir'] . "',
                    agama        : '" . $data['agama'] . "',
                    status_kawin : '" . $data['status_kawin'] . "',
                    alamat       : '" . $data['alamat'] . "',
                    email        : '" . $data['email'] . "', 
                    pekerjaan    : '" . $data['pekerjaan'] . "',
                    pendidikan   : '" . $data['pendidikan'] . "', 
                    hp           : '" . $data['hp'] . "',
                    'ibu_hamil' : '" . (isset($laporan[$data['nik_warga']]['ibu-hamil']) ? 1 : 0) . "',
                    'warga_wafat' : '" . (isset($laporan[$data['nik_warga']]['warga-meninggal']) ? 1 : 0) . "'
                },";
            }
            ?>
        ];
        let selectedNik = null;
        let modeAksi = ""; // "hapus" atau "edit"

        function lihatDetail(nik) {
            selectedNik = nik;

            const data = warga.find(w => w.nik_warga === nik);
            if (!data) return alert("Data tidak ditemukan!");

            const detailBody = document.getElementById("detailBody");
            detailBody.innerHTML = `
                <tr><th>Nama</th><td>${data.nama_warga}</td></tr>
                <tr><th>NIK</th><td>${data.nik_warga}</td></tr>
                <tr><th>Tempat/Tanggal Lahir</th><td>${data.tempat_lahir}/${data.tanggal_lahir}</td></tr>
                <tr><th>Agama</th><td>${data.agama}</td></tr>
                <tr><th>Status keluarga</th><td>${data.keluarga}</td></tr>
                <tr><th>Status Kawin</th><td>${data.status_kawin}</td></tr>
                <tr><th>Jenis Kelamin</th><td>${data.jenis_kelamin}</td></tr>
                <tr><th>No KK</th><td>${data.no_kk}</td></tr>
                <tr><th>Alamat</th><td>${data.alamat}</td></tr>
                <tr><th>Pekerjaan</th><td>${data.pekerjaan}</td></tr>
                <tr><th>Pendidikan</th><td>${data.pendidikan}</td></tr>
                <tr><th>NO TELEPON</th><td>${data.hp}</td></tr>
            `;

            new bootstrap.Modal('#modalDetail').show();
        }

        function tampilkanData(dataArray) {
            const kolom = document.getElementById("kolom");
            kolom.innerHTML = "";
            dataArray.forEach((data, index) => {
                kolom.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${data.nama_warga}</td>
                        <td>${data.nik_warga}</td>
                        <td>${data.keluarga}</td>
                        <td>${data.jenis_kelamin}</td>
                        <td>${data.no_kk}</td>
                        <td><button class="btn btn-green btn-sm" onclick="lihatDetail('${data.nik_warga}')">Kelola Data</button></td>
                    </tr>
                `;
            });
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

            if (kolom === "Pekerjaan") {
                container.innerHTML = `
            <select class="form-select" name="nilai_baru">
                <option>Pelajar / Mahasiswa</option>
                <option>Karyawan Swasta</option>
                <option>Wiraswasta</option>
                <option>Buruh</option>
                <option>PNS</option>
                <option>TNI</option>
                <option>Polri</option>
                <option>Guru</option>
                <option>Perawat</option>
                <option>Petani</option>
                <option>Nelayan</option>
                <option>Dokter</option>
                <option>Ibu Rumah Tangga</option>
                <option>Pensiunan</option>
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
                w.nama_warga.toLowerCase().includes(nama) &&
                w.no_kk.toLowerCase().includes(kk) &&
                (gender === "" || w.jenis_kelamin.toLowerCase() === gender)
            );


            tampilkanData(hasil);
        }


        function resetFilter() {
            document.getElementById("searchNama").value = "";
            document.getElementById("searchKK").value = "";
            document.getElementById("filterGender").value = "";
            tampilkanData(warga);
        }

        function hitungUsia(tanggal) {
            const tgl = new Date(tanggal);
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
            // hitung usia
            const balita = warga.filter(w => w.usia <= 6).length;
            const remaja = warga.filter(w => w.usia >= 7 && w.usia <= 17).length;
            const dewasa = warga.filter(w => w.usia >= 18 && w.usia <= 59).length;
            const lansia = warga.filter(w => w.usia >= 60).length;

            const ctx = document.getElementById('chartUsia').getContext('2d');

            // 🧨 Hapus chart lama jika ada
            if (chartUsia !== null) {
                chartUsia.destroy();
            }

            // buat chart baru
            chartUsia = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Balita', 'Anak-anak', 'Dewasa', 'Lansia'],
                    datasets: [{
                        label: 'Jumlah Warga',
                        data: [balita, remaja, dewasa, lansia],
                        backgroundColor: ['#a2b17c', '#7b865a', '#5f6842', '#404733']
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            new bootstrap.Modal('#modalStatistik').show();
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
                    let value = row[k];

                    // KHUSUS kolom ibu_hamil
                    if (k === 'ibu_hamil') {
                        obj[k] = value == 1 ? 'Hamil' : 'Tidak Hamil';
                    } else if (k === 'warga_wafat') {
                        obj[k] = value == 1 ? 'Wafat' : 'Hidup';
                    } else if (k == 'usia') {
                        if (value === null || value === '' || Number.isNaN(value)) {
                            obj[k] = '-';
                        } else {
                            obj[k] = value;
                        }
                    } else {
                        // default kolom lain
                        obj[k] = (value === null || value === '' || value === 0) ?
                            '-' :
                            value;
                    }
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
                return kolomDipilih.map(k => row[k]);
            });

            doc.text("DATA WARGA RT", 14, 15);

            doc.autoTable({
                startY: 20,
                head: head,
                body: body
            });

            doc.save("data_warga_rt.pdf");
        }
    </script>
</body>

</html>