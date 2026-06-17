<?php
include '../koneksi.php';
if (isset($_GET['user_id'])) {
    $id     = mysqli_real_escape_string($koneksi, $_GET['user_id']);
    $query  = "SELECT * FROM tb_users WHERE id='$id'";
    $result = mysqli_query($koneksi, $query);
    $user   = mysqli_fetch_assoc($result);
    echo json_encode($user);
}
