<?php
session_start();
include "koneksi.php";

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasboard SITAWAR</title>
    <link rel="stylesheet" href="LoginRTWARGA.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>

<body class="terang">
    <header>
        <nav class="navbar">
            <a href="#">
                <h1>SITAWAR</h1>
            </a>
            <ul class="nav-menu">
                <li class="list-navbar">
                    <a href="#home">Home</a>
                </li>
                <li class="list-navbar">
                    <a href="#tentanggg">Tentang</a>
                </li>
                <li class="list-navbar">
                    <a href="#layanannn">Layanan</a>
                </li>
                <li class="list-navbar">
                    <a href="#konntak">Kontak</a>
                </li>
                <li class="list-navbar">
                    <button type="button" class="btnLogin-popup" id="tombol-login" onclick="warna()">Login</button>
                </li>
            </ul>
        </nav>
    </header>


    <div class="pop-up">
        <div class="wrapper">
            <div class="icon-close">
                <i class="fa-solid fa-xmark"></i>
            </div>

            <div class="form-box-login">
                <h2 style="padding-bottom: 20px;">LOGIN WARGA</h2>
                <form method="POST" action="aset_login/login_warga.php">

                    <!-- ALERT -->
                    <?php if (isset($_SESSION['alert'])): ?>
                        <div class="alert-box">
                            <?= htmlspecialchars($_SESSION['alert']) ?>
                        </div>
                        <?php
                        echo '<script>alert("' . $_SESSION['alert'] . '")</script>';
                        unset($_SESSION['alert']);
                        ?>
                    <?php endif; ?>

                    <div class="input-box">
                        <input type="text" id="userName" required name="nama">
                        <label>NIK</label>
                    </div>

                    <div class="input-box">
                        <input type="text" id="password" required name="sandi">
                        <label>Password</label>
                    </div>

                    <footer style="font-size:15.5px; margin-top:10px; color:#495336" align="center">
                        Gunakan NIK sebagai password Anda saat pertama kali Login
                    </footer>

                    <button type="submit" class="btn-forgot" name="submit" value="login">
                        Login
                    </button>

                    <div class="login-register">
                        <p>ATAU <a href="#" class="login-link">login Sebagai RT?</a></p>
                    </div>

                </form>

            </div>

            <div class="daftar-regis">
                <div class="form-box-login">
                    <h2 style="padding-bottom: 20px;">LOGIN RT</h2>
                    <form method="POST" action="aset_login/login-rt.php">
                        <?php if (isset($_SESSION['alertrt'])): ?>
                            <div class="alert-box">
                                <?= htmlspecialchars($_SESSION['alertrt']) ?>
                            </div>
                            <?php
                            echo '<script>alert("' . $_SESSION['alertrt'] . '")</script>';
                            unset($_SESSION['alertrt']);
                            ?>
                        <?php endif; ?>
                        <div class="input-box">
                            <input type="text" required id="userName1" name="name">
                            <label for="">Nomor SK_RT</label>
                        </div>
                        <div class="input-box">
                            <input type="text" required name="sk">
                            <label for="">Password</label>
                        </div>
                        <footer style="font-size:15.5px; margin-top:10px; color:#495336" align="center"> Gunakan SK_RT
                            sebagai password Anda saat pertama kali Login
                        </footer>
                        <div class="remember-forgot">
                            <a href=""></a>
                        </div>
                        <button type="submit" class="btn-forgot" id="daftar23" name="submit">Login</button>

                        <div class="login-register">
                            <p>KEMBALI <a href="#" class="register-link">Login Sebagai Warga?</a></p>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- bagian foto -->
    <section id="home">
        <div class="foto">
            <img src="image/apa.png" alt="" class="foto-img" style="border-radius: 30px;">
        </div>
    </section>

    <!-- bagian about -->
    <section class="induk" id="tentanggg">
        <div class="section-content">
            <div class="hero-details">
                <h2 class="title"><i>SITAWAR</i></h2>
                <div class="lane">
                    <h3 class="sub-title">Sistem Terpadu Administrasi warga</h3>
                    <p class="deskripsi">Sitawar merupakan sebuah aplikasi perangkat lunak berbasis web yang diciptakan
                        untuk membantu pengurus RT/RW dalam mencatat dan mengelola data warga secara rapi, mulai
                        dari
                        data
                        keluarga, perubahan status, hingga pengajuan layanan berupa pembuatan surat Dokumen hingga
                        pembuata pelaporan</p>
                </div>
                <div class="section-btn">
                    <button class="btn"
                        onclick="window.location.href='https:www.instagram.com/sitaw_ar?igsh=MWJ1MTY2YTZjejNpNg=='">Instagram
                        Kami</button>
                    <button class="btn"
                        onclick="window.location.href='https:www.youtube.com/@komikrangga?si=SZfd34UySTgiy5KO'">Youtube
                        Kami</button>
                </div>
            </div>
            <img src="image/apas.png" alt="" class="imagea">
        </div>
    </section>

    <!-- bagian layanan -->
    <section id="layanannn">
        <div class="layanan">
            <div class="list-layanan">
                <div class="penjelasanCard" onmouseover="tombolLayanan1()" onmouseout="kembali()">
                    <img src="image/OIP.jpg" alt="" id="foto1">
                    <h3>Pengelolaan data</h3>
                </div>
                <div class="akhirnya" id="akhirnyaCard1">
                    <p>Fitur pengelolaan data warga pada web SITAWAR berfungsi untuk menyimpan, memperbarui, dan
                        mengatur informasi warga secara efisien guna mendukung kelancaran administrasi RT.</p>
                </div>
            </div>

            <div class="list-layanan">
                <div class="penjelasanCard" onmouseover="tombolLayanan2()" onmouseout="kembali()">
                    <img src="image/lapor.jpeg" alt="" id="foto2">
                    <h3> pengajuan pelaporan</h3>
                </div>
                <div class="akhirnya" id="akhirnyaCard2">
                    <p>Fitur Pelaporan Warga berfungsi untuk menyampaikan keluhan, usulan, atau informasi dari warga
                        secara langsung kepada pengurus RT melalui sistem SITAWAR.</p>
                </div>
            </div>

            <div class="list-layanan">
                <div class="penjelasanCard" onmouseover="tombolLayanan3()" onmouseout="kembali()">
                    <img src="image/dokumen.jpeg" alt="" id="foto3">
                    <h3>pembuatan dokumen</h3>
                </div>
                <div class="akhirnya" id="akhirnyaCard3">
                    <p>Fitur Pembuatan Dokumen memungkinkan warga untuk mengajukan dan mencetak dokumen administrasi,
                        seperti surat keterangan atau permohonan, secara cepat dan terdata otomatis dalam sistem.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- bagian footer -->
    <footer class="bawah" id="konntak">
        <div class="footer-bawah">
            <h2 class="bawah-">KONTAK</h2>
        </div>
        <div class="akhir">
            <div class="footer-list">
                <h4 class="list">
                    <i class="fa-solid fa-user"></i>
                    Politeknik Negeri Batam
                </h4>
                <h4 class="list">
                    <i class="fa-solid fa-user"></i>
                    sitaw_ar
                </h4>
                <h4 class="list">
                    <i class="fa-solid fa-phone"></i>
                    +62-878-1308-6814
                </h4>
                <h4 class="list">
                    <i class="fa-solid fa-hashtag"></i>
                    sitawar@gmail.com
                </h4>
                <h4 class="list">
                    <i class="fa-solid fa-envelope"></i>
                    www.sitiwar.com
                </h4>
            </div>
            <div class="akhir-form">
                <form action="" class="sudah">
                    <input type="text" name="" placeholder="nama anda">
                    <input type="email" name="" placeholder="email anda">
                    <textarea name="" placeholder="komentar"></textarea><br>
                    <button type="submit" class="kirimfooter">kirim</button>
                </form>
            </div>
        </div>
    </footer>

    <footer class="bagian">
        <div class="div-bagian">
            <h2></h2>
        </div>
    </footer>
    <div class="lari"></div>
    <div class="running-text">🌿 Selamat datang di SITAWAR - Sistem Terpadu Administrasi Warga © 2024 SITAWAR. All
        Rights Reserved.🌿</div>




    <script>
        const cardLayanan1 = document.getElementById('foto1')
        const cardLayanan2 = document.getElementById('foto2')
        const cardLayanan3 = document.getElementById('foto3')
        const akhirnya1 = document.getElementById('akhirnyaCard1')
        const akhirnya2 = document.getElementById('akhirnyaCard2')
        const akhirnya3 = document.getElementById('akhirnyaCard3')

        function tombolLayanan1() {
            cardLayanan1.style.scale = '1.2';
            cardLayanan1.style.transition = 'all 0.2s ease-in-out';
            akhirnya1.style.opacity = '1'
            akhirnya1.style.transition = 'all 0.4s ease-in-out';
        }

        function tombolLayanan2() {
            cardLayanan2.style.scale = '1.2'
            cardLayanan2.style.transition = 'all 0.2s ease-in-out';
            akhirnya2.style.opacity = '1'
            akhirnya2.style.transition = 'all 0.4s ease-in-out';
        }

        function tombolLayanan3() {
            cardLayanan3.style.scale = '1.2';
            cardLayanan3.style.transition = 'all 0.2s ease-in-out';
            akhirnya3.style.opacity = '1'
            akhirnya3.style.transition = 'all 0.4s ease-in-out';
        }

        function kembali() {
            cardLayanan1.style.removeProperty('scale')
            cardLayanan2.style.removeProperty('scale')
            cardLayanan3.style.removeProperty('scale')
            akhirnya1.style.opacity = '0'
            akhirnya2.style.opacity = '0'
            akhirnya3.style.opacity = '0'
        }



        const tombol = document.getElementById('tombol-login')

        function warna() {
            tombol.style.backgroundColor = '#ffffffc0'
            tombol.style.color = '#88976c'
        }

        const wrapper = document.querySelector('.wrapper');
        const loginLink = document.querySelector('.register-link');
        const registerLink = document.querySelector('.login-link');
        const btnPopup = document.querySelector('.btnLogin-popup');
        const iconClose = document.querySelector('.icon-close');


        registerLink.addEventListener('click', () => {
            wrapper.classList.add('active')
        });

        loginLink.addEventListener('click', () => {
            wrapper.classList.remove('active')
        });

        btnPopup.addEventListener('click', () => {
            wrapper.classList.add('active-popup')
        });

        iconClose.addEventListener('click', () => {
            wrapper.classList.remove('active-popup')

            tombol.style.removeProperty('background-color')
            tombol.style.removeProperty('color')
        });

        const menuToggle = document.querySelector('.menu-toggle');
        const navbar = document.querySelector('.navbar');

        menuToggle.addEventListener('click', () => {
            navbar.classList.toggle('active');
        });
    </script>
</body>


</html>