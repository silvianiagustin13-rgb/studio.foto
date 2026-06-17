<?php include 'partials/header.php'; ?>

<div class="main-content">

<script>
$(document).ready(function(){
    toastr.options = {
        positionClass: 'toast-top-right',
        timeOut: 3500,
        progressBar: true,
        closeButton: true,
        showDuration: 300,
        hideDuration: 300
    };
    <?php if (isset($_GET['success'])): ?>
        toastr.success('<?php echo addslashes(strip_tags($_GET['success'])); ?>', '✅ Berhasil!');
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        toastr.error('<?php echo addslashes(strip_tags($_GET['error'])); ?>', '❌ Gagal!');
    <?php endif; ?>
});
</script>

<div class="page-header">
    <h1><i class="fas fa-camera-retro me-2" style="color:#FF6B9D;"></i>Data <span>Paket Foto</span></h1>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <a href="tambah.php" class="btn-snap-primary"><i class="fas fa-plus"></i> Tambah Paket</a>
        <a href="report.php" class="btn-snap-danger" target="_blank"><i class="fas fa-file-pdf"></i> Cetak PDF</a>
    </div>
</div>

<!-- Table Card -->
<div class="snap-card">
    <div class="card-body">
        <div style="overflow-x:auto;">
        <table class="snap-table w-100" id="tabel_paket">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Kode</th>
                    <th>Nama Paket</th>
                    <th>Kategori</th>
                    <th>Durasi</th>
                    <th>Harga</th>
                    <th>Jumlah Foto</th>
                    <th>Tanggal Input</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            include 'koneksi.php';
            $query  = "SELECT * FROM tb_paket_foto ORDER BY tanggal_input DESC";
            $result = mysqli_query($koneksi, $query);
            $no = 1;
            $badge_map = ['Tersedia'=>'badge-green','Habis'=>'badge-red','Nonaktif'=>'badge-yellow'];
            $cat_map   = ['Pernikahan'=>'badge-pink','Maternity'=>'badge-purple','Newborn'=>'badge-teal','Keluarga'=>'badge-yellow','Wisuda'=>'badge-pink','Komersial'=>'badge-teal','Portrait'=>'badge-purple'];
            while ($row = mysqli_fetch_assoc($result)):
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td>
                    <?php if ($row['foto_paket'] && file_exists('uploads/' . $row['foto_paket'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($row['foto_paket']); ?>"
                             alt="foto"
                             style="width:64px;height:56px;object-fit:cover;border-radius:10px;border:2px solid #FFB3CE;">
                    <?php else: ?>
                        <div style="width:64px;height:56px;background:linear-gradient(135deg,#FFB3CE,#C084FC);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">📷</div>
                    <?php endif; ?>
                </td>
                <td><strong style="color:#FF6B9D;"><?php echo htmlspecialchars($row['id_paket']); ?></strong></td>
                <td style="max-width:160px;">
                    <strong><?php echo htmlspecialchars($row['nama_paket']); ?></strong>
                    <?php if ($row['deskripsi']): ?>
                        <br><small style="color:#999;font-size:0.75rem;"><?php echo htmlspecialchars(substr($row['deskripsi'], 0, 50)); ?>...</small>
                    <?php endif; ?>
                </td>
                <td><span class="badge-snap <?php echo $cat_map[$row['kategori']] ?? 'badge-pink'; ?>"><?php echo $row['kategori']; ?></span></td>
                <td><?php echo $row['durasi_sesi']; ?> mnt</td>
                <td><strong>Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?></strong></td>
                <td><?php echo $row['jumlah_foto']; ?> foto</td>
                <td><?php echo date('d/m/Y', strtotime($row['tanggal_input'])); ?></td>
                <td><span class="badge-snap <?php echo $badge_map[$row['status']] ?? 'badge-yellow'; ?>"><?php echo $row['status']; ?></span></td>
                <td>
                    <a href="edit.php?id=<?php echo $row['id_paket']; ?>" class="btn-action btn-edit">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="hapus.php?id=<?php echo $row['id_paket']; ?>"
                       class="btn-action btn-delete"
                       onclick="return confirmDelete(this, '<?php echo htmlspecialchars($row['nama_paket']); ?>')">
                        <i class="fas fa-trash"></i> Hapus
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

</div>

<script>
$(document).ready(function() {
    $('#tabel_paket').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.3/i18n/id.json'
        },
        order: [[8, 'desc']],
        pageLength: 10,
    });
});

function confirmDelete(el, name) {
    if (confirm('🗑️ Hapus paket "' + name + '"?\n\nData tidak dapat dikembalikan!')) {
        return true;
    }
    return false;
}
</script>

<?php include 'partials/footer.php'; ?>
