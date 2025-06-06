<?php
include 'connection.php';

// Get id_kelas from URL parameter
$id_kelas = isset($_GET['id_kelas']) ? $_GET['id_kelas'] : null;

if (!$id_kelas) {
    die("ID Kelas tidak ditemukan!");
}

// Get kelas info
$sql_kelas = "SELECT * FROM kelas WHERE id_kelas = ?";
$stmt_kelas = $conn->prepare($sql_kelas);
$stmt_kelas->bind_param("i", $id_kelas);
$stmt_kelas->execute();
$result_kelas = $stmt_kelas->get_result();
$kelas_data = $result_kelas->fetch_assoc();

if (!$kelas_data) {
    die("Kelas tidak ditemukan!");
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['single_pertemuan'])) {
        // Add single pertemuan
        $pertemuan_ke = $_POST['pertemuan_ke'];
        $modul = $_POST['modul'];
        $kegiatan = $_POST['kegiatan'];
        $tanggal = $_POST['tanggal'];
        $keterangan = $_POST['keterangan'] ?? '';

        $sql = "INSERT INTO pertemuan (pertemuan_ke, modul, kegiatan, tanggal, keterangan, id_kelas) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssi", $pertemuan_ke, $modul, $kegiatan, $tanggal, $keterangan, $id_kelas);
        
        if ($stmt->execute()) {
            $success_message = "Pertemuan berhasil ditambahkan!";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    }
}

// Get existing pertemuan for this class
$sql_existing = "SELECT * FROM pertemuan WHERE id_kelas = ? ORDER BY pertemuan_ke ASC";
$stmt_existing = $conn->prepare($sql_existing);
$stmt_existing->bind_param("i", $id_kelas);
$stmt_existing->execute();
$existing_pertemuan = $stmt_existing->get_result();

// Get next pertemuan number
$sql_next = "SELECT MAX(pertemuan_ke) as max_pertemuan FROM pertemuan WHERE id_kelas = ?";
$stmt_next = $conn->prepare($sql_next);
$stmt_next->bind_param("i", $id_kelas);
$stmt_next->execute();
$next_result = $stmt_next->get_result();
$next_pertemuan = ($next_result->fetch_assoc()['max_pertemuan'] ?? 0) + 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Praktikum</title>
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
            max-width: 1000px;
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
        
        .form-section {
            background-color: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input, textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.9);
            color: #333;
            box-sizing: border-box;
        }
        
        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: #45a049;
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
        
        .existing-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .existing-table th,
        .existing-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .existing-table th {
            background-color: rgba(0, 0, 0, 0.3);
        }
        
        .existing-table tr {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .existing-table tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .existing-table tr:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        .btn-container {
            text-align: center;
            margin-top: 30px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin: 0 5px;
        }
        
        .btn:hover {
            background-color: #2980b9;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .empty-state h3 {
            margin-bottom: 15px;
            color: #ecf0f1;
        }
        
        .empty-state p {
            color: #bdc3c7;
            margin-bottom: 0;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            .existing-table {
                font-size: 14px;
            }
            
            .existing-table th,
            .existing-table td {
                padding: 8px 4px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tambah Data Praktikum</h1>
        
        <div class="class-info">
            <strong><?php echo htmlspecialchars($kelas_data['matkul'] . ' ' . $kelas_data['kelas']); ?></strong> - 
            <?php echo htmlspecialchars($kelas_data['hari']); ?>
        </div>
        
        <?php if (isset($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <!-- Single Pertemuan Form -->
        <div class="form-section">
            <h2>Tambah Pertemuan</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="pertemuan_ke">Pertemuan Ke:</label>
                    <input type="number" id="pertemuan_ke" name="pertemuan_ke" 
                           value="<?php echo $next_pertemuan; ?>" min="1" required>
                </div>
                
                <div class="form-group">
                    <label for="modul">Modul:</label>
                    <input type="text" id="modul" name="modul" 
                           placeholder="Contoh: Modul 1 - Pengenalan HTML" required>
                </div>
                
                <div class="form-group">
                    <label for="kegiatan">Kegiatan:</label>
                    <input type="text" id="kegiatan" name="kegiatan" 
                           placeholder="Contoh: Pembelajaran HTML Dasar" required>
                </div>
                
                <div class="form-group">
                    <label for="tanggal">Tanggal:</label>
                    <input type="date" id="tanggal" name="tanggal" required>
                </div>
                
                <div class="form-group">
                    <label for="keterangan">Keterangan (Opsional):</label>
                    <textarea id="keterangan" name="keterangan" rows="3" 
                              placeholder="Catatan tambahan..."></textarea>
                </div>
                
                <button type="submit" name="single_pertemuan">Tambah Pertemuan</button>
            </form>
        </div>
        
        <!-- Existing Pertemuan Table -->
        <?php if ($existing_pertemuan->num_rows > 0): ?>
            <h2>Pertemuan yang Sudah Ada</h2>
            <table class="existing-table">
                <thead>
                    <tr>
                        <th>Pertemuan Ke</th>
                        <th>Modul</th>
                        <th>Kegiatan</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $existing_pertemuan->data_seek(0); // Reset pointer
                    while ($row = $existing_pertemuan->fetch_assoc()): 
                    ?>
                        <tr>
                            <td><strong><?php echo $row['pertemuan_ke']; ?></strong></td>
                            <td><?php echo htmlspecialchars($row['modul']); ?></td>
                            <td><?php echo htmlspecialchars($row['kegiatan']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                            <td><?php echo htmlspecialchars($row['keterangan']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <h3>Belum Ada Pertemuan</h3>
                <p>Silakan tambah pertemuan menggunakan form di atas.</p>
            </div>
        <?php endif; ?>
        
        <div class="btn-container">
            <a href="lihat_kehadiran_praktikum.php?id=<?php echo $id_kelas; ?>" class="btn">Kembali ke Daftar Pertemuan</a>
            <a href="lihat_lebih_detail.php?id=<?php echo $id_kelas; ?>" class="btn">Kembali ke Detail Kelas</a>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>