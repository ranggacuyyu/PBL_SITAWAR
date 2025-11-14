<?php
include "koneksi.php";

$result = mysqli_query($conn, "SELECT * FROM rt_user ORDER BY id_rt DESC");

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
?>
