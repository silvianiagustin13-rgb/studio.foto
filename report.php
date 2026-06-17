<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php"); exit();
}

require_once('vendor/autoload.php');
include 'koneksi.php';

$pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf->SetCreator('SnapStudio');
$pdf->SetAuthor('SnapStudio Admin');
$pdf->SetTitle('Laporan Data Paket Foto - SnapStudio');
$pdf->SetSubject('Laporan Paket Foto');

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(12, 14, 12);
$pdf->AddPage();

// Header laporan
$html  = '<table cellpadding="4" cellspacing="0" style="width:100%; margin-bottom:12px;">';
$html .= '<tr>';
$html .= '<td style="width:60px; vertical-align:middle; text-align:center; font-size:36px;">📸</td>';
$html .= '<td style="vertical-align:middle;">';
$html .= '<p style="margin:0; font-size:18pt; font-weight:bold; color:#FF6B9D; font-family:helvetica;">SnapStudio</p>';
$html .= '<p style="margin:0; font-size:9pt; color:#555;">Studio Foto Management System — Laporan Data Paket Foto</p>';
$html .= '</td>';
$html .= '<td style="vertical-align:middle; text-align:right; font-size:8pt; color:#777;">';
$html .= 'Dicetak: ' . date('d/m/Y H:i') . '<br>Oleh: ' . $_SESSION['username'];
$html .= '</td>';
$html .= '</tr></table>';
$html .= '<hr style="border:2px solid #FF6B9D; margin-bottom:10px;">';

// Judul
$html .= '<h2 style="text-align:center; color:#2D1B4E; font-family:helvetica; font-size:13pt; margin:0 0 12px 0;">LAPORAN DATA PAKET FOTO STUDIO</h2>';

// Tabel data
$html .= '<table border="1" cellpadding="6" cellspacing="0" style="width:100%; border-collapse:collapse; font-size:8.5pt;">';
$html .= '<thead>';
$html .= '<tr style="background-color:#FF6B9D; color:white; font-weight:bold; text-align:center;">';
$html .= '<th style="width:30px;">No</th>';
$html .= '<th style="width:55px;">Foto</th>';
$html .= '<th style="width:55px;">Kode</th>';
$html .= '<th style="width:120px;">Nama Paket</th>';
$html .= '<th style="width:70px;">Kategori</th>';
$html .= '<th style="width:55px;">Durasi</th>';
$html .= '<th style="width:80px;">Harga</th>';
$html .= '<th style="width:55px;">Jml Foto</th>';
$html .= '<th style="width:70px;">Tgl Input</th>';
$html .= '<th style="width:50px;">Status</th>';
$html .= '<th>Deskripsi</th>';
$html .= '</tr>';
$html .= '</thead><tbody>';

$query  = "SELECT * FROM tb_paket_foto ORDER BY tanggal_input DESC";
$result = mysqli_query($koneksi, $query);
$no     = 1;
$total_harga = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $bg = ($no % 2 == 0) ? '#FFF0F5' : '#FFFFFF';
    $total_harga += $row['harga'];

    // Foto embed
    $foto_cell = '—';
    $foto_path = 'uploads/' . $row['foto_paket'];
    if ($row['foto_paket'] && file_exists($foto_path)) {
        $ext  = strtolower(pathinfo($foto_path, PATHINFO_EXTENSION));
        $mime = ($ext == 'png') ? 'png' : 'jpeg';
        $b64  = base64_encode(file_get_contents($foto_path));
        $foto_cell = '<img src="data:image/' . $mime . ';base64,' . $b64 . '" width="48" height="40" style="border-radius:6px;">';
    }

    $html .= '<tr style="background-color:' . $bg . ';">';
    $html .= '<td style="text-align:center;">' . $no++ . '</td>';
    $html .= '<td style="text-align:center;">' . $foto_cell . '</td>';
    $html .= '<td style="text-align:center; color:#FF6B9D; font-weight:bold;">' . htmlspecialchars($row['id_paket']) . '</td>';
    $html .= '<td><b>' . htmlspecialchars($row['nama_paket']) . '</b></td>';
    $html .= '<td style="text-align:center;">' . htmlspecialchars($row['kategori']) . '</td>';
    $html .= '<td style="text-align:center;">' . $row['durasi_sesi'] . ' mnt</td>';
    $html .= '<td style="text-align:right;">Rp' . number_format($row['harga'], 0, ',', '.') . '</td>';
    $html .= '<td style="text-align:center;">' . $row['jumlah_foto'] . ' foto</td>';
    $html .= '<td style="text-align:center;">' . date('d/m/Y', strtotime($row['tanggal_input'])) . '</td>';
    $html .= '<td style="text-align:center;">' . htmlspecialchars($row['status']) . '</td>';
    $html .= '<td>' . htmlspecialchars(substr($row['deskripsi'] ?? '', 0, 80)) . (strlen($row['deskripsi'] ?? '') > 80 ? '...' : '') . '</td>';
    $html .= '</tr>';
}

// Footer row
$html .= '<tr style="background:#2D1B4E; color:white; font-weight:bold;">';
$html .= '<td colspan="6" style="text-align:right; padding:8px;">TOTAL NILAI SELURUH PAKET:</td>';
$html .= '<td style="text-align:right; color:#FFD93D;">Rp' . number_format($total_harga, 0, ',', '.') . '</td>';
$html .= '<td colspan="4"></td>';
$html .= '</tr>';

$html .= '</tbody></table>';

$html .= '<p style="font-size:7.5pt; color:#999; margin-top:10px; text-align:right;">* Laporan ini dibuat otomatis oleh sistem SnapStudio Studio Management</p>';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('laporan_paket_foto_snapstudio.pdf', 'I');
?>
