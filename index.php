<?php include 'partials/header.php'; ?>

<div class="main-content">

<?php
if (isset($_GET['success'])): ?>
<script>
$(document).ready(function(){
    toastr.options = { positionClass:'toast-top-right', timeOut:3500, progressBar:true, closeButton:true };
    toastr.success('<?php echo addslashes(strip_tags($_GET['success'])); ?>', '✅ Berhasil!');
});
</script>
<?php endif; if (isset($_GET['error'])): ?>
<script>
$(document).ready(function(){
    toastr.options = { positionClass:'toast-top-right', timeOut:4000, progressBar:true, closeButton:true };
    toastr.error('<?php echo addslashes(strip_tags($_GET['error'])); ?>', '❌ Gagal!');
});
</script>
<?php endif; ?>

<?php
include 'koneksi.php';
$total  = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM tb_paket_foto"))[0];
$aktif  = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM tb_paket_foto WHERE status='Tersedia'"))[0];
$habis  = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM tb_paket_foto WHERE status='Habis'"))[0];

$foto_result = mysqli_query($koneksi, "SELECT nama_paket, kategori, foto_paket, harga FROM tb_paket_foto WHERE foto_paket != '' ORDER BY tanggal_input DESC");
$fotos = [];
while ($f = mysqli_fetch_assoc($foto_result)) $fotos[] = $f;
?>

<div class="page-header" style="background: linear-gradient(135deg, white 60%, #FFF0F5 100%);">
    <div>
        <h1 style="margin:0 0 4px;">
            <span>Dashboard</span>
            <span style="font-size:0.9rem; font-weight:600; color:#C084FC; margin-left:8px;">SnapStudio</span>
        </h1>
        <p style="margin:0; font-size:0.85rem; color:#aaa;">
            <?php echo date('l, d F Y'); ?> &nbsp;·&nbsp; Selamat datang, <strong style="color:#FF6B9D;"><?php echo htmlspecialchars($_SESSION['username']); ?></strong>! 🎉
        </p>
    </div>
    <a href="paket.php" class="btn-snap-primary"><i class="fas fa-camera-retro"></i> Kelola Paket</a>
</div>

<div class="row g-3 mb-4">

    <!-- Total Paket -->
    <div class="col-md-4 col-12">
        <div class="stat-card-new" style="background:linear-gradient(135deg,#FF6B9D 0%,#FF8C69 100%);">
            <div class="stat-card-inner">
                <div class="stat-card-icon-wrap">
                    <i class="fas fa-camera-retro"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-num"><?php echo $total; ?></div>
                    <div class="stat-lbl">Total Paket Foto</div>
                </div>
            </div>
            <div class="stat-card-bar">
                <div class="stat-bar-fill" style="width:100%; background:rgba(255,255,255,0.4);"></div>
            </div>
            <a href="paket.php" class="stat-card-link">Lihat semua paket <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>

    <!-- Tersedia -->
    <div class="col-md-4 col-12">
        <div class="stat-card-new" style="background:linear-gradient(135deg,#06B6D4 0%,#0891B2 100%);">
            <div class="stat-card-inner">
                <div class="stat-card-icon-wrap">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-num"><?php echo $aktif; ?></div>
                    <div class="stat-lbl">Paket Tersedia</div>
                </div>
            </div>
            <div class="stat-card-bar">
                <?php $pct = $total > 0 ? ($aktif/$total)*100 : 0; ?>
                <div class="stat-bar-fill" style="width:<?php echo $pct; ?>%; background:rgba(255,255,255,0.4);"></div>
            </div>
            <a href="paket.php" class="stat-card-link"><?php echo round($pct); ?>% dari total paket <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>

    <div class="col-md-4 col-12">
        <div class="stat-card-new" style="background:linear-gradient(135deg,#C084FC 0%,#7C3AED 100%);">
            <div class="stat-card-inner">
                <div class="stat-card-icon-wrap">
                    <i class="fas fa-pause-circle"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-num"><?php echo $habis; ?></div>
                    <div class="stat-lbl">Paket Habis / Nonaktif</div>
                </div>
            </div>
            <div class="stat-card-bar">
                <?php $pct2 = $total > 0 ? ($habis/$total)*100 : 0; ?>
                <div class="stat-bar-fill" style="width:<?php echo $pct2; ?>%; background:rgba(255,255,255,0.4);"></div>
            </div>
            <a href="paket.php" class="stat-card-link">Perlu perhatian <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>

</div>

<div class="snap-card mb-4">
    <div class="card-body">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:10px;">
            <div>
                <h5 style="font-weight:800; color:#2D1B4E; margin:0 0 4px;">Portofolio Studio</h5>
                <p style="margin:0; font-size:0.82rem; color:#aaa;">Preview foto dari setiap paket yang tersedia</p>
            </div>
            <a href="tambah.php" class="btn-snap-primary" style="font-size:0.82rem;">
                <i class="fas fa-plus"></i> Tambah Paket
            </a>
        </div>

        <?php if (empty($fotos)): ?>
        <div style="text-align:center; padding:48px 20px; color:#ccc;">
            <div style="font-size:3rem; margin-bottom:12px;"></div>
            <p style="font-weight:700; color:#ddd; margin:0;">Belum ada foto paket</p>
            <p style="font-size:0.82rem; margin:4px 0 16px;">Tambahkan paket foto pertama kamu!</p>
            <a href="tambah.php" class="btn-snap-primary">Tambah Sekarang</a>
        </div>
        <?php else: ?>

        <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:20px;" id="filter-tabs">
            <button class="filter-tab active" data-cat="semua" onclick="filterPortfolio('semua')">Semua</button>
            <?php
            $cats = array_unique(array_column($fotos, 'kategori'));
            foreach ($cats as $cat):
            ?>
            <button class="filter-tab" data-cat="<?php echo $cat; ?>" onclick="filterPortfolio('<?php echo $cat; ?>')">
                <?php echo $cat; ?>
            </button>
            <?php endforeach; ?>
        </div>

        <!-- Grid Foto -->
        <div class="portfolio-grid" id="portfolio-grid">
            <?php foreach ($fotos as $i => $foto):
                $img_path = 'uploads/' . $foto['foto_paket'];
                $has_img  = !empty($foto['foto_paket']) && file_exists($img_path);
                $cat_colors = [
                    'Pernikahan'=>'#FF6B9D','Maternity'=>'#C084FC','Newborn'=>'#06B6D4',
                    'Keluarga'=>'#FFD93D','Wisuda'=>'#FF8C69','Komersial'=>'#16A34A','Portrait'=>'#7C3AED'
                ];
                $color = $cat_colors[$foto['kategori']] ?? '#FF6B9D';
            ?>
            <div class="portfolio-item" data-cat="<?php echo $foto['kategori']; ?>">
                <div class="portfolio-img-wrap">
                    <?php if ($has_img): ?>
                        <img src="<?php echo $img_path; ?>" alt="<?php echo htmlspecialchars($foto['nama_paket']); ?>" class="portfolio-img">
                    <?php else: ?>
                        <div class="portfolio-placeholder" style="background:linear-gradient(135deg, <?php echo $color; ?>33, <?php echo $color; ?>66);">
                            <i class="fas fa-camera" style="font-size:2rem; color:<?php echo $color; ?>;"></i>
                        </div>
                    <?php endif; ?>
                    <div class="portfolio-overlay">
                        <span class="portfolio-cat" style="background:<?php echo $color; ?>;"><?php echo $foto['kategori']; ?></span>
                        <p class="portfolio-name"><?php echo htmlspecialchars($foto['nama_paket']); ?></p>
                        <p class="portfolio-price">Rp<?php echo number_format($foto['harga'], 0, ',', '.'); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php endif; ?>
    </div>
</div>

<div class="snap-card">
    <div class="card-body">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
            <h5 style="font-weight:800; color:#2D1B4E; margin:0;">Paket Terbaru</h5>
            <a href="paket.php" class="btn-snap-primary" style="font-size:0.82rem;"><i class="fas fa-list"></i> Lihat Semua</a>
        </div>
        <div style="overflow-x:auto;">
        <table class="snap-table w-100">
            <thead>
                <tr><th>Kode</th><th>Nama Paket</th><th>Kategori</th><th>Harga</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            <?php
            $badge     = ['Tersedia'=>'badge-green','Habis'=>'badge-red','Nonaktif'=>'badge-yellow'];
            $cat_color = ['Pernikahan'=>'badge-pink','Maternity'=>'badge-purple','Newborn'=>'badge-teal','Keluarga'=>'badge-yellow','Wisuda'=>'badge-pink','Komersial'=>'badge-teal','Portrait'=>'badge-purple'];
            $q = "SELECT * FROM tb_paket_foto ORDER BY tanggal_input DESC LIMIT 5";
            $r = mysqli_query($koneksi, $q);
            while ($row = mysqli_fetch_assoc($r)):
            ?>
            <tr>
                <td><strong style="color:#FF6B9D;"><?php echo $row['id_paket']; ?></strong></td>
                <td><?php echo htmlspecialchars($row['nama_paket']); ?></td>
                <td><span class="badge-snap <?php echo $cat_color[$row['kategori']] ?? 'badge-pink'; ?>"><?php echo $row['kategori']; ?></span></td>
                <td><strong>Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?></strong></td>
                <td><span class="badge-snap <?php echo $badge[$row['status']] ?? 'badge-yellow'; ?>"><?php echo $row['status']; ?></span></td>
                <td>
                    <a href="edit.php?id=<?php echo $row['id_paket']; ?>" class="btn-action btn-edit"><i class="fas fa-edit"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

</div>

<style>
.stat-card-new {
    border-radius: 20px;
    padding: 22px 22px 14px;
    color: white;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    transition: transform 0.25s, box-shadow 0.25s;
    cursor: default;
}
.stat-card-new:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 32px rgba(0,0,0,0.18);
}
.stat-card-new::before {
    content: '';
    position: absolute; top: -40px; right: -40px;
    width: 120px; height: 120px;
    background: rgba(255,255,255,0.12);
    border-radius: 50%;
}
.stat-card-inner {
    display: flex; align-items: center; gap: 16px;
    margin-bottom: 16px;
}
.stat-card-icon-wrap {
    width: 54px; height: 54px;
    background: rgba(255,255,255,0.25);
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}
.stat-card-info { flex: 1; }
.stat-num {
    font-size: 2.2rem; font-weight: 800;
    line-height: 1; margin-bottom: 4px;
}
.stat-lbl {
    font-size: 0.82rem; font-weight: 600;
    opacity: 0.85;
}
.stat-card-bar {
    height: 4px; background: rgba(255,255,255,0.2);
    border-radius: 99px; margin-bottom: 12px;
    overflow: hidden;
}
.stat-bar-fill {
    height: 100%; border-radius: 99px;
    transition: width 1s ease;
}
.stat-card-link {
    font-size: 0.78rem; font-weight: 700;
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    display: flex; align-items: center; gap: 6px;
    transition: color 0.2s;
}
.stat-card-link:hover { color: white; }

.filter-tab {
    padding: 6px 16px;
    border-radius: 50px;
    border: 2px solid #F0D0E8;
    background: white;
    color: #999;
    font-family: 'Nunito', sans-serif;
    font-size: 0.8rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
}
.filter-tab:hover { border-color: #FF6B9D; color: #FF6B9D; }
.filter-tab.active {
    background: linear-gradient(135deg, #FF6B9D, #C084FC);
    border-color: transparent;
    color: white;
}

.portfolio-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 16px;
}
.portfolio-item {
    border-radius: 16px;
    overflow: hidden;
    transition: transform 0.25s;
}
.portfolio-item:hover { transform: scale(1.03); }
.portfolio-item.hidden { display: none; }

.portfolio-img-wrap {
    position: relative;
    aspect-ratio: 4/3;
    overflow: hidden;
    border-radius: 16px;
    background: #f5f0f8;
}
.portfolio-img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.4s;
}
.portfolio-item:hover .portfolio-img { transform: scale(1.08); }
.portfolio-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
}
.portfolio-overlay {
    position: absolute; bottom: 0; left: 0; right: 0;
    background: linear-gradient(to top, rgba(30,10,50,0.85) 0%, transparent 100%);
    padding: 28px 12px 12px;
    transform: translateY(30%);
    transition: transform 0.3s;
}
.portfolio-item:hover .portfolio-overlay { transform: translateY(0); }
.portfolio-cat {
    display: inline-block;
    padding: 2px 10px; border-radius: 50px;
    font-size: 0.7rem; font-weight: 800;
    color: white; margin-bottom: 4px;
}
.portfolio-name {
    color: white; font-size: 0.82rem; font-weight: 700;
    margin: 0 0 2px; line-height: 1.3;
}
.portfolio-price {
    color: #FFD93D; font-size: 0.78rem; font-weight: 700; margin: 0;
}
</style>

<script>
function filterPortfolio(cat) {
    document.querySelectorAll('.filter-tab').forEach(t => {
        t.classList.toggle('active', t.dataset.cat === cat);
    });
    document.querySelectorAll('.portfolio-item').forEach(item => {
        if (cat === 'semua' || item.dataset.cat === cat) {
            item.classList.remove('hidden');
        } else {
            item.classList.add('hidden');
        }
    });
}
</script>

<?php include 'partials/footer.php'; ?>
