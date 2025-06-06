<?php
include 'connection.php';

// Get pertemuan ID dan return URL
$id_pertemuan = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$return_url = isset($_GET['return']) ? $_GET['return'] : 'dashboard.php';

if (!$id_pertemuan) {
    echo "<script>alert('ID pertemuan tidak valid!'); window.location.href='$return_url';</script>";
    exit;
}

// Get pertemuan data untuk konfirmasi
$sql_pertemuan = "SELECT p.*, k.matkul, k.kelas 
                  FROM pertemuan p 
                  JOIN kelas k ON p.id_kelas = k.id_kelas 
                  WHERE p.id_pertemuan = ?";
$stmt_pertemuan = $conn->prepare($sql_pertemuan);
$stmt_pertemuan->bind_param("i", $id_pertemuan);
$stmt_pertemuan->execute();
$result_pertemuan = $stmt_pertemuan->get_result();
$pertemuan_data = $result_pertemuan->fetch_assoc();

if (!$pertemuan_data) {
    echo "<script>alert('Pertemuan tidak ditemukan!'); window.location.href='$return_url';</script>";
    exit;
}

// Check berapa banyak kehadiran yang terkait
$sql_count = "SELECT 
                COUNT(*) as total_kehadiran,
                COUNT(CASE WHEN waktu_masuk IS NOT NULL THEN 1 END) as total_checkin,
                COUNT(CASE WHEN waktu_keluar IS NOT NULL THEN 1 END) as total_checkout
              FROM kehadiran_asisten 
              WHERE id_pertemuan = ?";
$stmt_count = $conn->prepare($sql_count);
$stmt_count->bind_param("i", $id_pertemuan);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$count_data = $result_count->fetch_assoc();

// Process konfirmasi delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_delete'])) {
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Delete kehadiran_asisten records first (foreign key constraint)
        $sql_delete_kehadiran = "DELETE FROM kehadiran_asisten WHERE id_pertemuan = ?";
        $stmt_delete_kehadiran = $conn->prepare($sql_delete_kehadiran);
        $stmt_delete_kehadiran->bind_param("i", $id_pertemuan);
        $stmt_delete_kehadiran->execute();
        
        // Delete pertemuan
        $sql_delete_pertemuan = "DELETE FROM pertemuan WHERE id_pertemuan = ?";
        $stmt_delete_pertemuan = $conn->prepare($sql_delete_pertemuan);
        $stmt_delete_pertemuan->bind_param("i", $id_pertemuan);
        $stmt_delete_pertemuan->execute();
        
        // Commit transaction
        $conn->commit();
        
        echo "<script>
                alert('Pertemuan dan " . $count_data['total_kehadiran'] . " data kehadiran berhasil dihapus!');
                window.location.href='$return_url';
              </script>";
        exit;
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $error_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Hapus Pertemuan - SIPRESENTA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1A3A63 0%, #0C233B 100%);
            color: white;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .container {
            max-width: 600px;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        
        h1 {
            color: #e74c3c;
            margin-bottom: 30px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .warning-icon {
            font-size: 64px;
            color: #e74c3c;
            margin-bottom: 20px;
        }
        
        .pertemuan-info {
            background-color: rgba(231, 76, 60, 0.2);
            border: 2px solid #e74c3c;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .pertemuan-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #ecf0f1;
        }
        
        .pertemuan-details {
            text-align: left;
            margin-bottom: 20px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .detail-label {
            font-weight: bold;
            color: #bdc3c7;
        }
        
        .detail-value {
            color: #ecf0f1;
        }
        
        .impact-info {
            background-color: rgba(255, 193, 7, 0.2);
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .impact-title {
            color: #ffc107;
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 16px;
        }
        
        .impact-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .stat-item {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #ffc107;
        }
        
        .stat-label {
            font-size: 12px;
            color: #bdc3c7;
            margin-top: 5px;
        }
        
        .button-group {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 30px;
        }
        
        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s ease;
            font-size: 16px;
        }
        
        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: #95a5a6;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #7f8c8d;
            transform: translateY(-2px);
        }
        
        .error-message {
            background-color: rgba(244, 67, 54, 0.2);
            border: 1px solid #f44336;
            color: #f44336;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .button-group {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 200px;
            }
            
            .impact-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="warning-icon">⚠️</div>
        <h1>Konfirmasi Hapus Pertemuan</h1>
        
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <div class="pertemuan-info">
            <div class="pertemuan-title">
                <?php echo htmlspecialchars($pertemuan_data['matkul'] . ' ' . $pertemuan_data['kelas']); ?>
            </div>
            
            <div class="pertemuan-details">
                <div class="detail-row">
                    <span class="detail-label">Pertemuan Ke:</span>
                    <span class="detail-value"><?php echo $pertemuan_data['pertemuan_ke']; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Modul:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($pertemuan_data['modul']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Kegiatan:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($pertemuan_data['kegiatan']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tanggal:</span>
                    <span class="detail-value"><?php echo date('d/m/Y', strtotime($pertemuan_data['tanggal'])); ?></span>
                </div>
            </div>
        </div>
        
        <div class="impact-info">
            <div class="impact-title">Data yang akan terhapus:</div>
            <p>Menghapus pertemuan ini akan menghapus SEMUA data kehadiran yang terkait dan tidak dapat dikembalikan!</p>
            
            <div class="impact-stats">
                <div class="stat-item">
                    <div class="stat-number"><?php echo $count_data['total_kehadiran']; ?></div>
                    <div class="stat-label">Total Records</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $count_data['total_checkin']; ?></div>
                    <div class="stat-label">Check-ins</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $count_data['total_checkout']; ?></div>
                    <div class="stat-label">Check-outs</div>
                </div>
            </div>
        </div>
        
        <form method="POST">
            <div class="button-group">
                <button type="submit" name="confirm_delete" class="btn btn-danger" onclick="return finalConfirm()">
                    Ya, Hapus Pertemuan
                </button>
                <a href="<?php echo htmlspecialchars($return_url); ?>" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
    
    <script>
        function finalConfirm() {
            return confirm('KONFIRMASI TERAKHIR:\n\nApakah Anda BENAR-BENAR yakin ingin menghapus pertemuan ini?\n\n<?php echo $count_data['total_kehadiran']; ?> data kehadiran akan hilang PERMANEN!');
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>