<?php 
include "../koneksi.php";



if(isset($_POST['nama'])){
    $nama = trim($_POST['nama']);
    $pass = trim($_POST['password']);

    $password_hash = password_hash($pass, PASSWORD_DEFAULT);

    $query = mysqli_query($koneksi, "INSERT INTO admin_user(nama, password_admin) VALUES ('$nama', '$password_hash')");
    if($query){
        $alert = "halo";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Input Data</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #e8f5e9, #f1f8e9);
            min-height: 100vh;
        }
        .card {
            border-radius: 15px;
        }
    </style>
</head>
<body class="d-flex align-items-center">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <!-- CARD -->
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0">Form Input Data</h4>
                    <small>Contoh POST dengan Bootstrap</small>
                </div>

                <div class="card-body">
                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" placeholder="Masukkan nama" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="=text" name="password" class="form-control" placeholder="contoh@email.com" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" name="kirim" class="btn btn-success">
                                Kirim Data
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <!-- HASIL POST -->
            <?php if(isset($alert)): ?>
                <div class="alert alert-success mt-4 shadow">
                    <h5>✅ Data Berhasil Dikirim</h5>
                    <hr>
                    <p><strong>Nama:</strong> <?= htmlspecialchars($_POST['nama']) ?></p>
                    <p><strong>password:</strong> <?= htmlspecialchars($_POST['password']) ?></p>
                    
                </div>
            <?php endif;
             
            ?>

        </div>
    </div>
</div>

</body>
</html>
