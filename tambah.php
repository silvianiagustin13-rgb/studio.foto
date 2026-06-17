<?php
include 'partials/header.php';
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_paket     = trim($_POST['id_paket']);
    $nama_paket   = trim($_POST['nama_paket']);
    $kategori     = $_POST['kategori'];
    $durasi_sesi  = intval($_POST['durasi_sesi']);
    $harga        = floatval(str_replace(['.', ','], ['', '.'], $_POST['harga']));
    $jumlah_foto  = intval($_POST['jumlah_foto']);
    $deskripsi    = trim($_POST['deskripsi']);
    $tanggal_input = $_POST['tanggal_input'];
    $status       = $_POST['status'];

    // Cek duplikat ID
    $cek = mysqli_query($koneksi, "SELECT id_paket FROM tb_paket_foto WHERE id_paket='$id_paket'");
    if (mysqli_num_rows($cek) > 0) {
        header("Location: tambah.php?error=Kode+paket+sudah+digunakan!");
        exit();
    }

    // Upload foto
    $foto_paket = '';
    if (!empty($_FILES['foto_paket']['name'])) {
        $allowed = ['jpg','jpeg','png','webp','gif'];
        $ext     = strtolower(pathinfo($_FILES['foto_paket']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            header("Location: tambah.php?error=Format+foto+tidak+didukung!(jpg,png,webp)");
            exit();
        }
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $foto_paket = time() . '_' . basename($_FILES['foto_paket']['name']);
        if (!move_uploaded_file($_FILES['foto_paket']['tmp_name'], $target_dir . $foto_paket)) {
            header("Location: tambah.php?error=Gagal+mengupload+foto!");
            exit();
        }
    } else {
        header("Location: tambah.php?error=Foto+paket+wajib+diupload!");
        exit();
    }

    $query = "INSERT INTO tb_paket_foto 
              (id_paket, nama_paket, kategori, durasi_sesi, jumlah_foto, harga, deskripsi, tanggal_input, foto_paket, status)
              VALUES (?,?,?,?,?,?,?,?,?,?)";
    $stmt = mysqli_prepare($koneksi, $query);
    // sssiidssss: string,string,string,int,int,double,string,string,string,string
    mysqli_stmt_bind_param($stmt, "sssiidssss", $id_paket, $nama_paket, $kategori, $durasi_sesi, $jumlah_foto, $harga, $deskripsi, $tanggal_input, $foto_paket, $status);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: paket.php?success=Paket+foto+berhasil+ditambahkan!");
        exit();
    } else {
        header("Location: tambah.php?error=Gagal+menyimpan+data:+" . mysqli_error($koneksi));
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
    <h1><i class="fas fa-plus-circle me-2" style="color:#FF6B9D;"></i>Tambah <span>Paket Foto</span></h1>
    <a href="paket.php" style="color:#999; font-weight:700; text-decoration:none;">← Kembali ke daftar</a>
</div>

<div class="snap-card">
  <div class="card-body">
    <form action="" method="post" enctype="multipart/form-data">
      <div class="row g-3">

        <!-- Kiri -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-hashtag me-1" style="color:#FF6B9D;"></i>Kode Paket <span style="color:red">*</span></label>
            <input type="text" name="id_paket" class="form-control" placeholder="Contoh: PKT004" required>
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-tag me-1" style="color:#FF6B9D;"></i>Nama Paket <span style="color:red">*</span></label>
            <input type="text" name="nama_paket" class="form-control" placeholder="Contoh: Paket Sweet Newborn" required>
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-th-list me-1" style="color:#FF6B9D;"></i>Kategori <span style="color:red">*</span></label>
            <select name="kategori" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                <?php foreach(['Pernikahan','Maternity','Newborn','Keluarga','Wisuda','Komersial','Portrait'] as $k): ?>
                <option value="<?php echo $k; ?>"><?php echo $k; ?></option>
                <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-clock me-1" style="color:#FF6B9D;"></i>Durasi Sesi (menit) <span style="color:red">*</span></label>
            <input type="number" name="durasi_sesi" class="form-control" placeholder="120" min="30" max="600" required>
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-tag me-1" style="color:#FF6B9D;"></i>Status</label>
            <select name="status" class="form-control">
                <option value="Tersedia">Tersedia</option>
                <option value="Habis">Habis</option>
                <option value="Nonaktif">Nonaktif</option>
            </select>
          </div>
        </div>

        <!-- Kanan -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-money-bill-wave me-1" style="color:#FF6B9D;"></i>Harga (Rp) <span style="color:red">*</span></label>
            <input type="number" name="harga" class="form-control" placeholder="1500000" min="0" required>
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-images me-1" style="color:#FF6B9D;"></i>Jumlah Foto Termasuk <span style="color:red">*</span></label>
            <input type="number" name="jumlah_foto" class="form-control" placeholder="30" min="1" required>
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-calendar me-1" style="color:#FF6B9D;"></i>Tanggal Input <span style="color:red">*</span></label>
            <input type="date" name="tanggal_input" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-camera me-1" style="color:#FF6B9D;"></i>Foto Paket <span style="color:red">*</span></label>
            <input type="file" name="foto_paket" id="foto_paket" class="form-control" accept="image/*" required>
            <div class="img-preview-wrap mt-2" id="preview-wrap">
                <span style="color:#FFB3CE; font-size:0.85rem;">📷 Preview foto akan muncul di sini</span>
            </div>
          </div>
        </div>

        <!-- Deskripsi -->
        <div class="col-12">
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-align-left me-1" style="color:#FF6B9D;"></i>Deskripsi Paket</label>
            <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan detail paket foto ini..."></textarea>
          </div>
        </div>

        <!-- Submit -->
        <div class="col-12 d-flex gap-2 justify-content-end">
            <a href="paket.php" style="padding:10px 24px; border-radius:50px; background:#f0e0f0; color:#2D1B4E; font-weight:700; text-decoration:none;">
                <i class="fas fa-times me-1"></i> Batal
            </a>
            <button type="submit" class="btn-snap-primary">
                <i class="fas fa-save me-1"></i> Simpan Paket
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
                '<img src="' + ev.target.result + '" alt="Preview">';
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php include 'partials/footer.php'; ?>
