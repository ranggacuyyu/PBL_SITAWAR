<?php
session_start();
$notif = $_SESSION['notif'] ?? null;
unset($_SESSION['notif']);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin</title>
  <link rel="stylesheet" href="login_admin.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
  <style>
    @keyframes fadeDown {
      from {
        opacity: 0;
        transform: translateY(-15px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeUp {
      from {
        opacity: 1;
        transform: translateY(0);
      }

      to {
        opacity: 0;
        transform: translateY(-25px);
      }
    }

    .notif {
      background: #efffe5ff;
      color: #3e5f20ff;
      padding: 10px 12px;
      margin-top: 10px;
      border-radius: 5px;
      border-left: 5px solid #66b46bff;
      opacity: 0;
      transform: translateY(-15px);

      /* animasi */
      animation: fadeDown 0.6s ease forwards;
    }

    .notif.hide {
      opacity: 0;
      transform: translateY(-10px);
      animation: fadeUp 0.8s ease forwards;
    }

    .notifikasi {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 10;
    }
  </style>
</head>

<body>
  <!-- LOGIN PAGE -->
  <div class="notifikasi">
    <?php if ($notif): ?>
      <div id="notif" class="notif">
        <?= htmlspecialchars($notif) ?>
      </div>
    <?php endif; ?>
  </div>
  <div class="login-container">
    <h2>Login Admin</h2>
    <form id="loginForm" method="POST" action="proses_API/proses_login.php">
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