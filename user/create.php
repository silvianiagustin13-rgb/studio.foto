<?php
include '../koneksi.php';
if ($_POST) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $query  = "INSERT INTO tb_users (username, password) VALUES ('$username', '$password')";
    $result = mysqli_query($koneksi, $query);
    echo $result ? "ok" : "error";
}
