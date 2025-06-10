<?php
include 'connection.php';

if (isset($_GET['id'])) {
    $id_kelas = $_GET['id'];
} else {
    die("ID Kelas tidak ditemukan!");
}

// Query untuk mengambil semua data dari tabel pertemuan
$sql = "SELECT * FROM pertemuan WHERE id_kelas = ? ORDER BY pertemuan_ke ASC";
$stmt_pertemuan = $conn->prepare($sql);
$stmt_pertemuan->bind_param("i", $id_kelas);
$stmt_pertemuan->execute();
$result = $stmt_pertemuan->get_result();

// Query untuk mengambil data kelas tertentu
$sql_kelas = "SELECT * FROM kelas WHERE id_kelas = ?";
$stmt_kelas = $conn->prepare($sql_kelas);
$stmt_kelas->bind_param("i", $id_kelas);
$stmt_kelas->execute();
$result_kelas = $stmt_kelas->get_result();
$row_kelas = $result_kelas->fetch_assoc();

if (!$row_kelas) {
    die("Kelas tidak ditemukan!");
}

// Function to determine pertemuan type untuk color coding
function getPertemuanType($minggu) {
    if ($minggu % 2 == 1 && $minggu < 13) {
        return "Teori";
    } elseif ($minggu % 2 == 0 && $minggu < 13) {
        return "Demo";
    } else {
        return "UAP";
    }
}

// Count existing pertemuan
$total_pertemuan = $result->num_rows;

// Reset result pointer for table display
$result->data_seek(0);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Kehadiran Praktikum</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1A3A63 0%, #0C233B 100%);
            color: white;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }
        
        h1, h2 {
            text-align: center;
            margin-bottom: 20px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .class-info {
            background: linear-gradient(45deg, #3498db, #2ecc71);
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        
        .btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        
        .btn-success {
            background-color: #27ae60;
        }
        
        .btn-success:hover {
            background-color: #219a52;
        }
        
        .pertemuan-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .pertemuan-table th,
        .pertemuan-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .pertemuan-table th {
            background-color: rgba(0, 0, 0, 0.3);
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .pertemuan-table tr {
            background-color: rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }
        
        .pertemuan-table tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .pertemuan-table tr:hover {
            background-color: rgba(255, 255, 255, 0.15);
            transform: scale(1.01);
        }
        
        /* Color coding untuk jenis pertemuan */
        .type-teori {
            background-color: rgba(76, 175, 80, 0.6) !important;
            border-left: 4px solid #4CAF50;
        }
        
        .type-demo {
            background-color: rgba(255, 152, 0, 0.6) !important;
            border-left: 4px solid #FF9800;
        }
        
        .type-uap {
            background-color: rgba(244, 67, 54, 0.6) !important;
            border-left: 4px solid #f44336;
        }
        
        .jenis-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .badge-teori {
            background-color: #4CAF50;
            color: white;
        }
        
        .badge-demo {
            background-color: #FF9800;
            color: white;
        }
        
        .badge-uap {
            background-color: #f44336;
            color: white;
        }
        
        .action-buttons-cell {
            display: flex;
            gap: 5px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .action-btn {
            padding: 6px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 11px;
            font-weight: bold;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .detail-btn {
            background-color: #3498db;
            color: white;
        }
        
        .detail-btn:hover {
            background-color: #2980b9;
            transform: scale(1.05);
        }
        
        .edit-btn {
            background-color: #f39c12;
            color: white;
        }
        
        .edit-btn:hover {
            background-color: #e67e22;
            transform: scale(1.05);
        }
        
        .delete-btn {
            background-color: #e74c3c;
            color: white;
        }
        
        .delete-btn:hover {
            background-color: #c0392b;
            transform: scale(1.05);
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            margin: 30px 0;
        }
        
        .empty-state h3 {
            margin-bottom: 15px;
            color: #ecf0f1;
        }
        
        .empty-state p {
            color: #bdc3c7;
            margin-bottom: 25px;
        }
        
        .back-section {
            text-align: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .pertemuan-table {
                font-size: 14px;
            }
            
            .pertemuan-table th,
            .pertemuan-table td {
                padding: 8px 4px;
            }
            
            .action-buttons-cell {
                flex-direction: column;
                gap: 3px;
            }
            
            .action-btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Kehadiran Praktikum</h1>
        
        <div class="class-info">
            <strong><?php echo htmlspecialchars($row_kelas['matkul'] . ' ' . $row_kelas['kelas']); ?></strong> - 
            <?php echo htmlspecialchars($row_kelas['hari']); ?>
        </div>
        
        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="tambah_data_praktikum.php?id_kelas=<?php echo $id_kelas; ?>" class="btn btn-success">
                Tambah Pertemuan
            </a>
        </div>
        
        <!-- Pertemuan Table -->
        <?php if ($total_pertemuan > 0): ?>
            <h2>Daftar Pertemuan</h2>
            <table class="pertemuan-table">
                <thead>
                    <tr>
                        <th>Minggu</th>
                        <th>Jenis</th>
                        <th>Modul</th>
                        <th>Kegiatan</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        $minggu = $row['pertemuan_ke'];
                        $jenis = getPertemuanType($minggu);
                        $css_class = '';
                        $badge_class = '';
                        
                        if ($jenis == 'Teori') {
                            $css_class = 'type-teori';
                            $badge_class = 'badge-teori';
                        } elseif ($jenis == 'Demo') {
                            $css_class = 'type-demo';
                            $badge_class = 'badge-demo';
                        } elseif ($jenis == 'UAP') {
                            $css_class = 'type-uap';
                            $badge_class = 'badge-uap';
                        }
                        
                        echo "<tr class='{$css_class}'>
                                <td><strong>{$minggu}</strong></td>
                                <td><span class='jenis-badge {$badge_class}'>{$jenis}</span></td>
                                <td>" . htmlspecialchars($row['modul']) . "</td>
                                <td>" . htmlspecialchars($row['kegiatan']) . "</td>
                                <td>" . date('d/m/Y', strtotime($row['tanggal'])) . "</td>
                                <td>" . htmlspecialchars($row['keterangan']) . "</td>
                                <td>
                                    <div class='action-buttons-cell'>
                                        <a href='detail_kehadiran_pertemuan.php?id={$row['id_pertemuan']}' class='action-btn detail-btn'>
                                            Detail
                                        </a>
                                        <a href='edit_pertemuan.php?id={$row['id_pertemuan']}' class='action-btn edit-btn'>
                                            Edit
                                        </a>
                                        <a href='#' onclick='confirmDelete({$row['id_pertemuan']}, \"{$row['modul']}\")' class='action-btn delete-btn'>
                                            Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <h3>Belum Ada Pertemuan</h3>
                <p>Praktikum untuk kelas ini belum memiliki pertemuan yang dijadwalkan.</p>
                <a href="tambah_data_praktikum.php?id_kelas=<?php echo $id_kelas; ?>" class="btn btn-success">
                    Buat Pertemuan Pertama
                </a>
            </div>
        <?php endif; ?>
        
        <!-- Back Navigation -->
        <div class="back-section">
            <a href="lihat_lebih_detail.php?id=<?php echo $id_kelas; ?>" class="btn">
                Kembali ke Detail Kelas
            </a>
            <a href="dashboard.php" class="btn" style="margin-left: 10px;">
                Dashboard
            </a>
        </div>
    </div>
    
    <script>
        function confirmDelete(id, modul) {
            if (confirm('Apakah Anda yakin ingin menghapus pertemuan "' + modul + '"?\n\nPeringatan: Data kehadiran yang terkait juga akan terhapus!')) {
                window.location.href = 'delete_pertemuan.php?id=' + id + '&return=' + encodeURIComponent(window.location.href);
            }
        }
    </script>
</body>
</html>

<?php
// Tutup koneksi
$stmt_pertemuan->close();
$stmt_kelas->close();
$conn->close();
?>