<?php
include 'connection.php';

// Get pertemuan ID
$id_pertemuan = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id_pertemuan) {
    die("ID pertemuan tidak valid!");
}

// Get pertemuan data
$sql_pertemuan = "SELECT p.*, k.matkul, k.kelas, k.hari 
                  FROM pertemuan p 
                  JOIN kelas k ON p.id_kelas = k.id_kelas 
                  WHERE p.id_pertemuan = ?";
$stmt_pertemuan = $conn->prepare($sql_pertemuan);
$stmt_pertemuan->bind_param("i", $id_pertemuan);
$stmt_pertemuan->execute();
$result_pertemuan = $stmt_pertemuan->get_result();
$pertemuan_data = $result_pertemuan->fetch_assoc();

if (!$pertemuan_data) {
    die("Pertemuan tidak ditemukan!");
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pertemuan_ke = $_POST['pertemuan_ke'];
    $modul = $_POST['modul'];
    $kegiatan = $_POST['kegiatan'];
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];
    
    // Update pertemuan
    $sql_update = "UPDATE pertemuan 
                   SET pertemuan_ke = ?, modul = ?, kegiatan = ?, tanggal = ?, keterangan = ?
                   WHERE id_pertemuan = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("issssi", $pertemuan_ke, $modul, $kegiatan, $tanggal, $keterangan, $id_pertemuan);
    
    if ($stmt_update->execute()) {
        $success_message = "Pertemuan berhasil diupdate!";
        
        // Refresh data
        $stmt_pertemuan->execute();
        $result_pertemuan = $stmt_pertemuan->get_result();
        $pertemuan_data = $result_pertemuan->fetch_assoc();
    } else {
        $error_message = "Error: " . $stmt_update->error;
    }
}

// Function to determine pertemuan type
function getPertemuanType($minggu) {
    if ($minggu % 2 == 1 && $minggu < 13) {
        return "Teori";
    } elseif ($minggu % 2 == 0 && $minggu < 13) {
        return "Demo";
    } else {
        return "UAP";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pertemuan - SIPRESENTA</title>
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
            max-width: 800px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }
        
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #f39c12;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .class-info {
            background: linear-gradient(45deg, #f39c12, #e67e22);
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-section {
            background-color: rgba(255, 255, 255, 0.05);
            padding: 25px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 30px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #ecf0f1;
        }
        
        input, textarea, select {
            width: 100%;
            padding: 12px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.9);
            color: #333;
            box-sizing: border-box;
            font-size: 14px;
        }
        
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        .jenis-info {
            background-color: rgba(52, 152, 219, 0.2);
            border: 1px solid #3498db;
            border-radius: 5px;
            padding: 10px;
            margin-top: 5px;
            font-size: 12px;
        }
        
        .jenis-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            margin-left: 10px;
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
        
        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: #f39c12;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #e67e22;
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
        
        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }
        
        .success-message {
            background-color: rgba(76, 175, 80, 0.2);
            border: 1px solid #4CAF50;
            color: #4CAF50;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .error-message {
            background-color: rgba(244, 67, 54, 0.2);
            border: 1px solid #f44336;
            color: #f44336;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .warning-box {
            background-color: rgba(255, 193, 7, 0.2);
            border: 1px solid #ffc107;
            color: #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .warning-box h4 {
            margin-top: 0;
            color: #ffc107;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .button-group {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Pertemuan</h1>
        
        <div class="class-info">
            <strong><?php echo htmlspecialchars($pertemuan_data['matkul'] . ' ' . $pertemuan_data['kelas']); ?></strong> - 
            <?php echo htmlspecialchars($pertemuan_data['hari']); ?>
        </div>
        
        <?php if (isset($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <div class="warning-box">
            <h4>Peringatan!</h4>
            <p>Mengubah data pertemuan akan mempengaruhi semua data kehadiran yang terkait. Pastikan perubahan yang Anda lakukan sudah benar.</p>
        </div>
        
        <div class="form-section">
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="pertemuan_ke">Pertemuan Ke:</label>
                        <input type="number" id="pertemuan_ke" name="pertemuan_ke" 
                               value="<?php echo $pertemuan_data['pertemuan_ke']; ?>" 
                               min="1" max="20" required onchange="updateJenisInfo()">
                        <div class="jenis-info" id="jenisInfo">
                            <?php 
                            $jenis = getPertemuanType($pertemuan_data['pertemuan_ke']);
                            $badge_class = '';
                            if ($jenis == 'Teori') $badge_class = 'badge-teori';
                            elseif ($jenis == 'Demo') $badge_class = 'badge-demo';
                            elseif ($jenis == 'UAP') $badge_class = 'badge-uap';
                            ?>
                            Jenis: <span class="jenis-badge <?php echo $badge_class; ?>" id="jenisBadge"><?php echo $jenis; ?></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="tanggal">Tanggal:</label>
                        <input type="date" id="tanggal" name="tanggal" 
                               value="<?php echo $pertemuan_data['tanggal']; ?>" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="modul">Modul:</label>
                        <input type="text" id="modul" name="modul" 
                               value="<?php echo htmlspecialchars($pertemuan_data['modul']); ?>" 
                               placeholder="Contoh: Modul 1 - Pengenalan HTML" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="kegiatan">Kegiatan:</label>
                        <input type="text" id="kegiatan" name="kegiatan" 
                               value="<?php echo htmlspecialchars($pertemuan_data['kegiatan']); ?>" 
                               placeholder="Contoh: Pembelajaran HTML Dasar" required>
                    </div>
                </div>
                
                <div class="form-group full-width">
                    <label for="keterangan">Keterangan:</label>
                    <textarea id="keterangan" name="keterangan" 
                              placeholder="Catatan tambahan..."><?php echo htmlspecialchars($pertemuan_data['keterangan']); ?></textarea>
                </div>
                
                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Update Pertemuan</button>
                    <a href="lihat_kehadiran_praktikum.php?id=<?php echo $pertemuan_data['id_kelas']; ?>" class="btn btn-secondary">Batal</a>
                    <a href="#" onclick="confirmDelete()" class="btn btn-danger">Delete Pertemuan</a>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function updateJenisInfo() {
            const pertemuanKe = document.getElementById('pertemuan_ke').value;
            const jenisInfo = document.getElementById('jenisInfo');
            const jenisBadge = document.getElementById('jenisBadge');
            
            let jenis = '';
            let badgeClass = '';
            
            if (pertemuanKe % 2 == 1 && pertemuanKe < 13) {
                jenis = 'Teori';
                badgeClass = 'badge-teori';
            } else if (pertemuanKe % 2 == 0 && pertemuanKe < 13) {
                jenis = 'Demo';
                badgeClass = 'badge-demo';
            } else {
                jenis = 'UAP';
                badgeClass = 'badge-uap';
            }
            
            jenisBadge.textContent = jenis;
            jenisBadge.className = 'jenis-badge ' + badgeClass;
        }
        
        function confirmDelete() {
            if (confirm('Apakah Anda yakin ingin menghapus pertemuan ini?\n\nPeringatan: Semua data kehadiran yang terkait akan ikut terhapus!')) {
                window.location.href = 'delete_pertemuan.php?id=<?php echo $id_pertemuan; ?>&return=' + encodeURIComponent('lihat_kehadiran_praktikum.php?id=<?php echo $pertemuan_data['id_kelas']; ?>');
            }
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>