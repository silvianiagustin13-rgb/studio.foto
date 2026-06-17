<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Ambil nama & foto untuk feedback & cleanup
    $res = mysqli_query($koneksi, "SELECT nama_paket, foto_paket FROM tb_paket_foto WHERE id_paket='$id'");
    $row = mysqli_fetch_assoc($res);

    if (!$row) {
        header("Location: paket.php?error=Data+tidak+ditemukan!");
        exit();
    }

    $query = "DELETE FROM tb_paket_foto WHERE id_paket='$id'";
    if (mysqli_query($koneksi, $query)) {
        // Hapus file foto
        if ($row['foto_paket'] && file_exists("uploads/" . $row['foto_paket'])) {
            unlink("uploads/" . $row['foto_paket']);
        }
        $nama = urlencode('Paket "' . $row['nama_paket'] . '" berhasil dihapus!');
        header("Location: paket.php?success=$nama");
        exit();
    } else {
        header("Location: paket.php?error=Gagal+menghapus+data!");
        exit();
    }
} else {
    header("Location: paket.php?error=ID+tidak+valid!");
    exit();
}
?>
