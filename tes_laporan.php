<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pelaporan Warga - SITAWAR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

    <style>
        body {
            background: linear-gradient(135deg, #2e3a23, #4b5f3a);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            color: #fff;
        }

        .glass {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,.3);
        }

        .badge-status {
            background: #8bc34a;
        }

        .form-control, .form-select {
            background: rgba(255,255,255,.15);
            border: none;
            color: #fff;
        }

        .form-control::placeholder {
            color: #ddd;
        }

        .btn-kirim {
            background: linear-gradient(135deg, #9ccc65, #558b2f);
            border: none;
            font-weight: bold;
        }

        .btn-kirim:hover {
            opacity: .9;
        }

        .card-laporan {
            transition: transform .3s;
        }

        .card-laporan:hover {
            transform: translateY(-8px);
        }
    </style>
</head>
<body>

<div class="container py-5">

    <!-- HEADER -->
    <div class="text-center mb-5" id="hero">
        <h1 class="fw-bold">📢 Pelaporan Warga</h1>
        <p class="text-light">Laporkan kejadian di lingkungan Anda secara cepat & aman</p>
    </div>

    <div class="row g-4">

        <!-- FORM LAPORAN -->
        <div class="col-lg-6">
            <div class="glass p-4 h-100" id="formBox">
                <h4 class="mb-3"><i class="fa-solid fa-file-lines"></i> Form Laporan</h4>

                <form id="laporanForm">
                    <div class="mb-3">
                        <label class="form-label">Judul Laporan</label>
                        <input type="text" class="form-control" placeholder="Contoh: Lampu Jalan Mati">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select class="form-select">
                            <option>Keamanan</option>
                            <option>Kebersihan</option>
                            <option>Infrastruktur</option>
                            <option>Sosial</option>
                            <option>Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi Kejadian</label>
                        <input type="text" class="form-control" placeholder="RT / RW / Jalan">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" rows="4" placeholder="Jelaskan detail kejadian..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lampiran Foto (opsional)</label>
                        <input type="file" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-kirim w-100 mt-3">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Laporan
                    </button>
                </form>
            </div>
        </div>

        <!-- RIWAYAT LAPORAN (DUMMY UI) -->
        <div class="col-lg-6">
            <div class="glass p-4 h-100" id="historyBox">
                <h4 class="mb-3"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Laporan</h4>

                <div class="card glass p-3 mb-3 card-laporan">
                    <h6>Lampu Jalan Mati</h6>
                    <span class="badge badge-status mb-2">Diproses</span>
                    <p class="small mb-0">RT 02 / RW 05</p>
                </div>

                <div class="card glass p-3 mb-3 card-laporan">
                    <h6>Sampah Menumpuk</h6>
                    <span class="badge bg-warning mb-2">Menunggu</span>
                    <p class="small mb-0">RT 01 / RW 03</p>
                </div>

                <div class="card glass p-3 card-laporan">
                    <h6>Parkir Liar</h6>
                    <span class="badge bg-success mb-2">Selesai</span>
                    <p class="small mb-0">RT 04 / RW 02</p>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // GSAP Animations
    gsap.from("#hero", {
        opacity: 0,
        y: -40,
        duration: 1,
        ease: "power3.out"
    });

    gsap.from("#formBox", {
        opacity: 0,
        x: -50,
        duration: 1,
        delay: .3,
        ease: "power3.out"
    });

    gsap.from("#historyBox", {
        opacity: 0,
        x: 50,
        duration: 1,
        delay: .5,
        ease: "power3.out"
    });

    gsap.from(".card-laporan", {
        opacity: 0,
        y: 30,
        duration: .6,
        stagger: .2,
        delay: .8
    });

    // Dummy submit
    document.getElementById("laporanForm").addEventListener("submit", function(e){
        e.preventDefault();
        gsap.to(this, { scale: 0.97, yoyo: true, repeat: 1, duration: .15 });
        alert("Laporan berhasil dikirim (demo UI)");
    });
</script>

</body>
</html>
