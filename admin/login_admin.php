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
    if(isset($_POST['username'])){
      $username = trim($_POST['username']);
      $password = trim(($_POST['password']));

      $sql = "SELECT*FROM admin_user where nama =? and password_admin =?";
      $stmt = mysqli_prepare($koneksi, $sql);
      mysqli_stmt_bind_param($stmt, "ss", $username, $password);
      mysqli_stmt_execute($stmt);
      $query = mysqli_stmt_get_result($stmt);
      
      if(mysqli_num_rows($query) > 0){
        $data = mysqli_fetch_array($query);
        $_SESSION['admin_user'] = $data;
        echo 
        '<script> alert("selamat datang");
         window.location.href="dashborad_admin.php";
        </script>';
      } else{
        echo '<script>alert("gagal login")</script>';
      }
    
    }
  ?>
  <!-- LOGIN PAGE -->
  <div class="login-container">
    <h2>Login Admin</h2>
    <form id="loginForm" method="post">
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
