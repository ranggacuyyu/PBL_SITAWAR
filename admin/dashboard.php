<?php
session_start();

// jika belum login
if (!isset($_SESSION['id_admin'])) {
    header("Location: index.html");
    exit;
}

echo "Selamat datang, " . $_SESSION['nama'];
?>
