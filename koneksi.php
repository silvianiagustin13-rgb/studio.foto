<?php
$server   = "localhost";
$username = "root";
$password = "";
$db       = "db_studio_foto";

$koneksi = mysqli_connect($server, $username, $password, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

mysqli_set_charset($koneksi, "utf8");
?>
