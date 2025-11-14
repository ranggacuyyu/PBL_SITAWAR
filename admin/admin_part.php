<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login & Dashboard Admin</title>
  <link rel="stylesheet" href="Dashboard_Admin.css">
</head>

<body>
  <!-- LOGIN PAGE -->
  <div class="login-container" >
    <h2>Login Admin</h2>
    <form id="loginForm" method="post" action="login.php">
      <label>Username</label>
      <input type="text" id="username" placeholder="Masukkan username" required name="username">
      <label>Kata Sandi</label>
      <input type="password" id="password" placeholder="Masukkan kata sandi" required name="password">
      <button type="submit">Masuk</button>
    </form>
    <div class="error" id="errorMsg"></div>
  </div>


  <!-- DASHBOARD PAGE -->
  <div class="dashboard" id="dashboardPage">
    <main>
      <aside>
        <h2>SITAWAR ADMIN</h2>
        <ul>
          <li class="active" data-section="daftarRT">Daftar RT</li>
          <li data-section="tambahRT">Tambah RT</li>
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
        <!-- DATAR PAGE -->
        <div id="tambahRT" class="section hidden">
          <form id="formTambahRT">
            <h3>Tambah Akun RT</h3>
            <input type="text" id="nik" placeholder="NIK (16 digit)" maxlength="16" required>
            <input type="text" id="nama" placeholder="Nama Lengkap" required>
            <input type="text" id="nohp" placeholder="Nomor HP" maxlength="13" required>
            <input type="text" id="sk" placeholder="Nomor SK Pengangkatan" required>
            <button type="submit">Tambah RT</button>
          </form>
          <div id="pesan"></div>
        </div>
      </section>
    </main>
  </div>

  <script>

    loginForm.addEventListener("submit", (e) => {
      e.preventDefault();
      let user = document.getElementById("username").value.trim();
      let pass = document.getElementById("password").value.trim();

      fetch("login.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          },
          body: `username=${user}&password=${pass}`
        })
        .then(res => res.text())
        .then(data => {
          if (data === "sukses") {
            window.location.href = "dashboard.php";
          } else if (data === "password_salah") {
            errorMsg.textContent = "❌ Kata sandi salah!";
          } else if (data === "user_tidak_ada") {
            errorMsg.textContent = "❌ Username tidak ditemukan!";
          } else {
            errorMsg.textContent = "❌ Terjadi kesalahan!";
          }
        });
    });
    function renderRT() {
      const tbody = document.getElementById("rtBody");
      tbody.innerHTML = daftarRT.map(rt => `
        <tr>
          <td>${rt.id}</td>
          <td>${rt.nama}</td>
          <td>${rt.nik}</td>
          <td>${rt.nohp}</td>
          <td>${rt.sk}</td>
          <td>${rt.tanggal}</td>
          <td><button onclick="hapusRT(${rt.id})">Hapus</button></td>
        </tr>
      `).join("");
    }

    function hapusRT(id) {
      if (confirm("Hapus akun RT ini?")) {
        daftarRT = daftarRT.filter(rt => rt.id !== id);
        renderRT();
      }
    }

    // =============== TAMBAH RT BARU ===============
    document.getElementById("formTambahRT").addEventListener("submit", e => {
      e.preventDefault();
      const nik = document.getElementById("nik").value.trim();
      const nama = document.getElementById("nama").value.trim();
      const nohp = document.getElementById("nohp").value.trim();
      const sk = document.getElementById("sk").value.trim();

      if (nik.length !== 16) {
        alert("NIK harus 16 digit!");
        return;
      }

      daftarRT.push({
        id: daftarRT.length + 1,
        nik,
        nama,
        nohp,
        sk,
        tanggal: new Date().toISOString().split("T")[0]
      });

      document.getElementById("pesan").textContent = "✅ Akun RT berhasil ditambahkan!";
      e.target.reset();
      renderRT();
    });

    // =============== MENU SWITCH ===============
    document.querySelectorAll("aside li").forEach(li => {
      li.addEventListener("click", () => {
        document.querySelectorAll("aside li").forEach(x => x.classList.remove("active"));
        li.classList.add("active");
        document.querySelectorAll(".section").forEach(sec => sec.classList.add("hidden"));
        document.getElementById(li.dataset.section).classList.remove("hidden");
      });
    });

    // =============== LOGOUT ===============
    document.getElementById("logoutBtn").addEventListener("click", () => {
      localStorage.removeItem("isSuperAdmin");
      dashboardPage.style.display = "none";
      loginPage.style.display = "block";
      document.getElementById("username").value = "";
      document.getElementById("password").value = "";
    });

    // =============== CEK LOGIN STATUS ===============
    if (localStorage.getItem("isSuperAdmin")) {
      loginPage.style.display = "none";
      dashboardPage.style.display = "flex";
      renderRT();
    }
  </script>
</body>

</html>