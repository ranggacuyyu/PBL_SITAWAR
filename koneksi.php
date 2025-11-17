<?php 
$host = "localhost";
$user = "root";
$pass ="";
$database ="pbl";

$koneksi = mysqli_connect($host, $user, $pass, $database);
if(!$koneksi){
    die("tidak dapat terkoneksi");
}
?>