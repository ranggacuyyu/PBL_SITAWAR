<?php
include "../koneksi.php";

$no_kk = $_GET['no_kk'];

$data = mysqli_query($koneksi, 
    "SELECT nik_warga, nama_warga FROM user_warga 
     WHERE no_kk='$no_kk' AND keluarga='anggota keluarga'"
);

while ($row = mysqli_fetch_assoc($data)) {
    echo "<option value='{$row['nik_warga']}'>{$row['nama_warga']}</option>";
}
