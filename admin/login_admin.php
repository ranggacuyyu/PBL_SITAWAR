<?php
session_start();
include "../koneksi.php";
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin</title>
  <link rel="stylesheet" href="login_admin.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>

<body>
  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim(($_POST['password']));

    if (empty($username) || empty($password)) {
      echo '<script>alert("Nama atau password tidak boleh diisi kosong")</script>';
    }

    $sql = "SELECT id_admin, password_admin FROM admin_user where nama =?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username, );
    mysqli_stmt_execute($stmt);
    $dataadmin = mysqli_stmt_get_result($stmt);
    $query = mysqli_fetch_assoc($dataadmin);

    if (!$query || !password_verify($password, $query['password_admin'])) {
      echo '<script>alert("gagal login")</script>';
    } else {
      $_SESSION['admin_user'] = $query;
      echo
        '<script> alert("selamat datang");
         window.location.href="dashborad_admin.php";
        </script>';
    }
  }
  ?>
  <!-- LOGIN PAGE -->
  <div class="login-container">
    <h2>Login Admin</h2>
    <form id="loginForm" method="POST">
      <label>Username</label>
      <input type="text" placeholder="Masukkan username" name="username" required>
      <label>Kata Sandi</label>
      <div class="password-wrapper">
        <input type="password" placeholder="Masukkan kata sandi" name="password" class="password" required>
        <i class="fa-solid fa-eye toggle-eye"></i>
      </div>
      <button type="submit" name="submit" value="login">Masuk</button>
    </form>
  </div>

  <script>
    //fungsi tombol mata
    document.querySelectorAll(".toggle-eye").forEach(eye => {
      eye.addEventListener("click", () => {
        const input = eye.parentElement.querySelector(".password");

        if (input.type === "password") {
          input.type = "text";
          eye.classList.replace("fa-eye", "fa-eye-slash");
        } else {
          input.type = "password";
          eye.classList.replace("fa-eye-slash", "fa-eye");
        }
      });
    });
  </script>

</body>

</html>