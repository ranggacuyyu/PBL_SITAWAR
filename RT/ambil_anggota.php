<?php  
include "../koneksi.php";
$no_kk = $_GET['no_kk'];

$data = mysqli_query($koneksi, "SELECT nama_warga FROM user_warga WHERE no_kk = '$no_kk'");

while($d = mysqli_fetch_assoc($data)){
    echo "<span class='badge bg-success me-1 mb-1'>".$d['nama_warga']."</span>";
}
?>
