<?php
include '../koneksi.php';
if ($_POST) {
    $id       = mysqli_real_escape_string($koneksi, $_POST['user_id']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $query    = "UPDATE tb_users SET username='$username', password='$password' WHERE id='$id'";
    $result   = mysqli_query($koneksi, $query);
    echo $result ? "ok" : "error";
}
