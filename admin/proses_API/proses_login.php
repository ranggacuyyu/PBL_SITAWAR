  <?php
  session_start();
  require_once '../../koneksi.php';
  require_once '../../db_helper.php';
  
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim(($_POST['password']));

    if (empty($username) || empty($password)) {
      $_SESSION['notif'] = "Nama atau password tidak boleh diisi kosong";
      header("Location: ../login_admin.php");
      exit;
    }

    $query = db_select_single(
      $koneksi,
      "SELECT id_admin, password_admin FROM admin_user where nama =?",
      "s",
      [$username]
    );

    if (!$query || !password_verify($password, $query['password_admin'])) {
      $_SESSION['notif'] = "Nama atau password salah";
      header("Location: ../login_admin.php");
      exit;
    } else {
      $_SESSION['admin_user'] = $query;
      $_SESSION['notif'] = "Login berhasil Selamat datang, $username!";
      echo
      '<script>window.location.href="../dashborad_admin.php";</script>';
    }
  }
  ?>