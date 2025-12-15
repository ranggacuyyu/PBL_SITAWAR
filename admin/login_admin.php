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
      <input type="password" placeholder="Masukkan kata sandi"  name="password" required>
      <button type="submit" name="submit" value="login">Masuk</button>
    </form>
  </div>
</body>

</html>


<?php

?>