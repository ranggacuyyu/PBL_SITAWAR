    <?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['user_rt'])) {
    die("Akses ditolak!");
}

$id = $_GET['id'];
$jenis = $_GET['jenis'];

$q = mysqli_query($koneksi, "SELECT foto_kk, foto_ktp FROM dokumen_wargart WHERE id_dokumen='$id'");
$data = mysqli_fetch_assoc($q);

if (!$data) {
    die("Dokumen tidak ditemukan.");
}

$file = ($jenis == 'kk') ? $data['foto_kk'] : $data['foto_ktp'];
$folder = ($jenis == 'kk') ? "../uploads/kk/" : "../uploads/ktp/";
$path = $folder . $file;

if (!file_exists($path)) {
    die("File tidak tersedia.");
}

// MODE DOWNLOAD
if (isset($_GET['download'])) {
    header("Content-Disposition: attachment; filename=$file");
}

$mime = mime_content_type($path);
header("Content-Type: $mime");
readfile($path);
exit;
?>