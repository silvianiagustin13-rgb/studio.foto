<?php
ob_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_paket     = $_POST['id_paket'];
    $nama_paket   = trim($_POST['nama_paket']);
    $kategori     = $_POST['kategori'];
    $durasi_sesi  = intval($_POST['durasi_sesi']);
    $harga        = floatval($_POST['harga']);
    $jumlah_foto  = intval($_POST['jumlah_foto']);
    $deskripsi    = trim($_POST['deskripsi']);
    $tanggal_input = $_POST['tanggal_input'];
    $status       = $_POST['status'];
    $foto_lama    = $_POST['foto_lama'];

    $foto_paket = $foto_lama;

    
    if (!empty($_FILES['foto_paket']['name'])) {
        $allowed = ['jpg','jpeg','png','webp','gif'];
        $ext     = strtolower(pathinfo($_FILES['foto_paket']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            header("Location: edit.php?id=$id_paket&error=Format+foto+tidak+didukung!");
            exit();
        }
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $foto_paket = time() . '_' . basename($_FILES['foto_paket']['name']);
        if (!move_uploaded_file($_FILES['foto_paket']['tmp_name'], $target_dir . $foto_paket)) {
            header("Location: edit.php?id=$id_paket&error=Gagal+upload+foto!");
            exit();
        }
        
        if ($foto_lama && file_exists("uploads/$foto_lama")) {
            unlink("uploads/$foto_lama");
        }
    }

    $query = "UPDATE tb_paket_foto SET 
              nama_paket=?, kategori=?, durasi_sesi=?, harga=?, jumlah_foto=?,
              deskripsi=?, tanggal_input=?, foto_paket=?, status=?
              WHERE id_paket=?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ssididisss", $nama_paket, $kategori, $durasi_sesi, $harga, $jumlah_foto, $deskripsi, $tanggal_input, $foto_paket, $status, $id_paket);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: paket.php?success=Paket+foto+berhasil+diperbarui!");
        exit();
    } else {
        header("Location: edit.php?id=$id_paket&error=Gagal+menyimpan+perubahan!");
        exit();
    }
}

include 'partials/header.php';

$row = null;
if (isset($_GET['id'])) {
    $id  = mysqli_real_escape_string($koneksi, $_GET['id']);
    $res = mysqli_query($koneksi, "SELECT * FROM tb_paket_foto WHERE id_paket='$id'");
    $row = mysqli_fetch_assoc($res);
    if (!$row) {
        header("Location: paket.php?error=Data+tidak+ditemukan!");
        exit();
    }
}
?>

<div class="main-content">

<script>
$(document).ready(function(){
    toastr.options = { positionClass:'toast-top-right', timeOut:4000, progressBar:true, closeButton:true };
    <?php if (isset($_GET['error'])): ?>
        toastr.error('<?php echo htmlspecialchars($_GET['error']); ?>', '❌ Gagal!');
    <?php endif; ?>
});
</script>

<div class="page-header">
    <h1><i class="fas fa-edit me-2" style="color:#FFD93D;"></i>Edit <span>Paket Foto</span></h1>
    <a href="paket.php" style="color:#999; font-weight:700; text-decoration:none;">← Kembali ke daftar</a>
</div>

<div class="snap-card">
  <div class="card-body">
    <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="id_paket" value="<?php echo htmlspecialchars($row['id_paket']); ?>">
      <input type="hidden" name="foto_lama" value="<?php echo htmlspecialchars($row['foto_paket']); ?>">

      <div class="row g-3">
        <!-- Kiri -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-hashtag me-1" style="color:#FFD93D;"></i>Kode Paket</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['id_paket']); ?>" disabled
                   style="background:#f9f9f9; color:#999;">
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-tag me-1" style="color:#FFD93D;"></i>Nama Paket <span style="color:red">*</span></label>
            <input type="text" name="nama_paket" class="form-control"
                   value="<?php echo htmlspecialchars($row['nama_paket']); ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-th-list me-1" style="color:#FFD93D;"></i>Kategori <span style="color:red">*</span></label>
            <select name="kategori" class="form-control" required>
                <?php foreach(['Pernikahan','Maternity','Newborn','Keluarga','Wisuda','Komersial','Portrait'] as $k): ?>
                <option value="<?php echo $k; ?>" <?php echo ($row['kategori']==$k)?'selected':''; ?>><?php echo $k; ?></option>
                <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-clock me-1" style="color:#FFD93D;"></i>Durasi Sesi (menit)</label>
            <input type="number" name="durasi_sesi" class="form-control"
                   value="<?php echo $row['durasi_sesi']; ?>" min="30" required>
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-toggle-on me-1" style="color:#FFD93D;"></i>Status</label>
            <select name="status" class="form-control">
                <?php foreach(['Tersedia','Habis','Nonaktif'] as $s): ?>
                <option value="<?php echo $s; ?>" <?php echo ($row['status']==$s)?'selected':''; ?>><?php echo $s; ?></option>
                <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-money-bill-wave me-1" style="color:#FFD93D;"></i>Harga (Rp)</label>
            <input type="number" name="harga" class="form-control"
                   value="<?php echo $row['harga']; ?>" min="0" required>
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-images me-1" style="color:#FFD93D;"></i>Jumlah Foto Termasuk</label>
            <input type="number" name="jumlah_foto" class="form-control"
                   value="<?php echo $row['jumlah_foto']; ?>" min="1" required>
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-calendar me-1" style="color:#FFD93D;"></i>Tanggal Input</label>
            <input type="date" name="tanggal_input" class="form-control"
                   value="<?php echo $row['tanggal_input']; ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-camera me-1" style="color:#FFD93D;"></i>Foto Paket
              <small style="color:#999;">(kosongkan jika tidak ingin mengubah)</small>
            </label>
            <input type="file" name="foto_paket" id="foto_paket" class="form-control" accept="image/*">
            <div class="img-preview-wrap mt-2" id="preview-wrap">
                <?php if ($row['foto_paket'] && file_exists('uploads/'.$row['foto_paket'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($row['foto_paket']); ?>"
                         alt="foto" id="preview-img">
                <?php else: ?>
                    <span style="color:#FFB3CE; font-size:0.85rem;">📷 Tidak ada foto</span>
                <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="col-12">
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-align-left me-1" style="color:#FFD93D;"></i>Deskripsi Paket</label>
            <textarea name="deskripsi" class="form-control" rows="4"><?php echo htmlspecialchars($row['deskripsi']); ?></textarea>
          </div>
        </div>

        <div class="col-12 d-flex gap-2 justify-content-end">
            <a href="paket.php" style="padding:10px 24px; border-radius:50px; background:#f0e0f0; color:#2D1B4E; font-weight:700; text-decoration:none;">
                <i class="fas fa-times me-1"></i> 
            </a>
            <button type="submit" style="background:linear-gradient(135deg,#FFD93D,#FF8C69); color:white; border:none; padding:10px 24px; border-radius:50px; font-weight:700; cursor:pointer; font-family:'Nunito',sans-serif;">
                <i class="fas fa-save me-1"></i> 
            </button>
        </div>
      </div>
    </form>
  </div>
</div>

</div>

<script>
document.getElementById('foto_paket').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('preview-wrap').innerHTML =
                '<img src="' + ev.target.result + '" alt="Preview" id="preview-img">';
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php include 'partials/footer.php'; ?>
