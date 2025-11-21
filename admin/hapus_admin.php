<?php
include "../koneksi.php";

if (isset($_GET['sk_rt'])) {
    $sk_rt = $_GET['sk_rt'];

    $query = "DELETE FROM user_rt WHERE sk_rt = '$sk_rt'";
    $hapus = mysqli_query($koneksi, $query);

    if ($hapus) {
        echo "<script>
                alert('Data berhasil dihapus!');
                window.location='dashborad_admin.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data!');
                window.location='dashborad_admin.php';
              </script>";
    }
} else {
    header("Location: dashborad_admin.php");
}
?>
