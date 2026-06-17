<?php
include '../koneksi.php';
$query  = "SELECT * FROM tb_users";
$result = mysqli_query($koneksi, $query);
$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $no++ . "</td>";
    echo "<td><strong>" . htmlspecialchars($row['username']) . "</strong></td>";
    echo "<td><span style='letter-spacing:2px;'>••••••••</span></td>";
    echo "<td>";
    echo "<button data-id='" . $row['id'] . "' class='btn-action btn-edit' id='btnEdit'><i class='fas fa-edit'></i> Edit</button> ";
    echo "<button data-id='" . $row['id'] . "' class='btn-action btn-delete' id='btnHapus'><i class='fas fa-trash'></i> Hapus</button>";
    echo "</td>";
    echo "</tr>";
}
