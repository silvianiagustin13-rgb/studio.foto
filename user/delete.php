<?php
include '../koneksi.php';
if ($_POST) {
    $id     = mysqli_real_escape_string($koneksi, $_POST['user_id']);
    $query  = "DELETE FROM tb_users WHERE id='$id'";
    $result = mysqli_query($koneksi, $query);
    echo $result ? "ok" : "error";
}
