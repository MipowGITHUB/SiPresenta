<?php
include 'connection.php';

// Helper function untuk status kehadiran
function getAttendanceStatus($keterangan, $waktu_masuk, $waktu_keluar, $tanggal) {
    $current_date = date('Y-m-d');
    $record_date = date('Y-m-d', strtotime($tanggal));
    
    if (empty($waktu_masuk)) {
        return 'Tidak Hadir';
    }
    
    if (!empty($waktu_masuk) && empty($waktu_keluar)) {
        if ($record_date == $current_date) {
            return 'Masuk';
        } else {
            return 'Tidak Lengkap';
        }
    }
    
    if (!empty($waktu_masuk) && !empty($waktu_keluar)) {
        return 'Hadir';
    }
    
    return 'Tidak Hadir';
}

// Menangkap ID pertemuan dari parameter
if (isset($_GET['id'])) {
    $id_pertemuan = $_GET['id'];
} else {
    die("ID pertemuan tidak ditemukan!");
}

// Query untuk mengambil data pertemuan dan kelas
$sql_pertemuan = "SELECT p.*, k.* FROM pertemuan p 
                  JOIN kelas k ON p.id_kelas = k.id_kelas 
                  WHERE p.id_pertemuan = ?";
$stmt_pertemuan = $conn->prepare($sql_pertemuan);
$stmt_pertemuan->bind_param("i", $id_pertemuan);
$stmt_pertemuan->execute();
$result_pertemuan = $stmt_pertemuan->get_result();

if ($result_pertemuan->num_rows == 0) {
    die("Pertemuan tidak ditemukan!");
}

$pertemuan_data = $result_pertemuan->fetch_assoc();
$stmt_pertemuan->close();

// Query untuk mengambil daftar asisten yang terdaftar di kelas ini
$sql_asisten = "SELECT a.id_asisten, a.nama, a.nim 
                FROM kelas_asisten ka 
                JOIN asisten a ON ka.id_asisten = a.id_asisten 
                WHERE ka.id_kelas = ? 
                ORDER BY a.nama";
$stmt_asisten = $conn->prepare($sql_asisten);
$stmt_asisten->bind_param("i", $pertemuan_data['id_kelas']);
$stmt_asisten->execute();
$result_asisten = $stmt_asisten->get_result();

$asisten_list = [];
while ($row = $result_asisten->fetch_assoc()) {
    $asisten_list[] = $row;
}

// Query untuk mengambil daftar praktikan yang terdaftar di kelas ini
$sql_praktikan = "SELECT p.id_praktikan, p.nama, p.nim 
                  FROM kelas_praktikan kp 
                  JOIN praktikan p ON kp.id_praktikan = p.id_praktikan 
                  WHERE kp.id_kelas = ? 
                  ORDER BY p.nama";
$stmt_praktikan = $conn->prepare($sql_praktikan);
$stmt_praktikan->bind_param("i", $pertemuan_data['id_kelas']);
$stmt_praktikan->execute();
$result_praktikan = $stmt_praktikan->get_result();

$praktikan_list = [];
while ($row = $result_praktikan->fetch_assoc()) {
    $praktikan_list[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kehadiran - Pertemuan <?php echo htmlspecialchars($pertemuan_data['pertemuan_ke']); ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #1A3A63 0%, #0C233B 100%);
            color: #fff;
            padding: 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            padding: 30px;
            backdrop-filter: blur(4px);
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .pertemuan-info {
            background: linear-gradient(45deg, #3498db, #2ecc71);
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        
        .pertemuan-title {
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 10px 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .pertemuan-details {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stats-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .stats-number {
            font-size: 2.2em;
            font-weight: bold;
            color: #4CAF50;
            margin-bottom: 5px;
        }
        
        .stats-label {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .tab-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
            gap: 10px;
        }
        
        .tab-btn {
            padding: 12px 24px;
            background-color: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 16px;
        }
        
        .tab-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
        }
        
        .tab-btn.active {
            background-color: #3498db;
            border-color: #2980b9;
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
        }
        
        .content-section {
            display: none;
        }
        
        .content-section.active {
            display: block;
        }
        
        .section-header {
            background: linear-gradient(45deg, #3498db, #2ecc71);
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        
        .section-title {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .section-subtitle {
            font-size: 14px;
            margin: 5px 0 0 0;
            opacity: 0.9;
        }
        
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        
        .attendance-table th,
        .attendance-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .attendance-table th {
            background-color: rgba(0, 0, 0, 0.3);
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
        }
        
        .attendance-table tbody tr {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .attendance-table tbody tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .attendance-table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        .status-hadir {
            background-color: #4CAF50;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-masuk {
            background-color: #FF9800;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-tidak-hadir {
            background-color: #f44336;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-tidak-lengkap {
            background-color: #9C27B0;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .btn-container {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3498db;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        
        .btn.kembali {
            background-color: #7f8c8d;
        }
        
        .btn.kembali:hover {
            background-color: #6c7a7d;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            font-style: italic;
            color: #ddd;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Detail Kehadiran Pertemuan</h1>
        </div>

        <div class="pertemuan-info">
            <div class="pertemuan-title">
                Pertemuan <?php echo htmlspecialchars($pertemuan_data['pertemuan_ke']); ?> - 
                <?php echo htmlspecialchars($pertemuan_data['modul']); ?>
            </div>
            <div class="pertemuan-details">
                <?php echo htmlspecialchars($pertemuan_data['matkul'] . ' ' . $pertemuan_data['kelas']); ?> | 
                <?php echo date('d F Y', strtotime($pertemuan_data['tanggal'])); ?> | 
                <?php echo htmlspecialchars($pertemuan_data['hari']); ?><br>
                <strong>Kegiatan:</strong> <?php echo htmlspecialchars($pertemuan_data['kegiatan']); ?>
            </div>
        </div>

        <?php
        // Hitung statistik kehadiran untuk pertemuan ini
        
        // Asisten stats
        $sql_asisten_hadir = "SELECT COUNT(*) as hadir FROM kehadiran_asisten 
                             WHERE id_pertemuan = ? AND waktu_masuk IS NOT NULL AND waktu_keluar IS NOT NULL";
        $stmt_asisten_hadir = $conn->prepare($sql_asisten_hadir);
        $stmt_asisten_hadir->bind_param("i", $id_pertemuan);
        $stmt_asisten_hadir->execute();
        $asisten_hadir = $stmt_asisten_hadir->get_result()->fetch_assoc()['hadir'];
        
        $sql_asisten_masuk = "SELECT COUNT(*) as masuk FROM kehadiran_asisten 
                             WHERE id_pertemuan = ? AND waktu_masuk IS NOT NULL AND waktu_keluar IS NULL";
        $stmt_asisten_masuk = $conn->prepare($sql_asisten_masuk);
        $stmt_asisten_masuk->bind_param("i", $id_pertemuan);
        $stmt_asisten_masuk->execute();
        $asisten_masuk = $stmt_asisten_masuk->get_result()->fetch_assoc()['masuk'];
        
        // Praktikan stats (placeholder - akan diisi ketika implementasi fingerprint)
        $praktikan_hadir = 0;
        $praktikan_masuk = 0;
        ?>

        <div class="stats-container">
            <div class="stats-card">
                <div class="stats-number"><?php echo count($asisten_list); ?></div>
                <div class="stats-label">Total Asisten</div>
            </div>
            <div class="stats-card">
                <div class="stats-number"><?php echo $asisten_hadir; ?></div>
                <div class="stats-label">Asisten Hadir</div>
            </div>
            <div class="stats-card">
                <div class="stats-number" style="color: #FF9800;"><?php echo $asisten_masuk; ?></div>
                <div class="stats-label">Asisten Masuk</div>
            </div>
            <div class="stats-card">
                <div class="stats-number"><?php echo count($praktikan_list); ?></div>
                <div class="stats-label">Total Praktikan</div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="tab-container">
            <button class="tab-btn active" onclick="switchTab('asisten')">
                Kehadiran Asisten (RFID)
            </button>
            <button class="tab-btn" onclick="switchTab('praktikan')">
                Kehadiran Praktikan (Fingerprint)
            </button>
        </div>

        <!-- ASISTEN CONTENT (RFID) -->
        <div id="asisten-content" class="content-section active">
            <div class="section-header">
                <div class="section-title">Kehadiran Asisten (RFID)</div>
                <div class="section-subtitle">Sistem presensi berbasis scan kartu RFID</div>
            </div>

            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Asisten</th>
                        <th>NIM</th>
                        <th>Waktu Masuk</th>
                        <th>Waktu Keluar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($asisten_list as $asisten) {
                        // Query kehadiran untuk asisten ini di pertemuan ini
                        $sql_kehadiran = "SELECT * FROM kehadiran_asisten 
                                        WHERE id_asisten = ? AND id_pertemuan = ?";
                        $stmt_kehadiran = $conn->prepare($sql_kehadiran);
                        $stmt_kehadiran->bind_param("ii", $asisten['id_asisten'], $id_pertemuan);
                        $stmt_kehadiran->execute();
                        $result_kehadiran = $stmt_kehadiran->get_result();
                        
                        if ($result_kehadiran->num_rows > 0) {
                            $kehadiran = $result_kehadiran->fetch_assoc();
                            $status = getAttendanceStatus(
                                $kehadiran['keterangan'], 
                                $kehadiran['waktu_masuk'], 
                                $kehadiran['waktu_keluar'], 
                                $pertemuan_data['tanggal']
                            );
                            
                            $waktu_masuk = $kehadiran['waktu_masuk'] ? date('H:i', strtotime($kehadiran['waktu_masuk'])) : '-';
                            $waktu_keluar = $kehadiran['waktu_keluar'] ? date('H:i', strtotime($kehadiran['waktu_keluar'])) : '-';
                        } else {
                            $status = 'Tidak Hadir';
                            $waktu_masuk = '-';
                            $waktu_keluar = '-';
                        }
                        
                        // CSS class untuk status
                        $status_class = '';
                        switch($status) {
                            case 'Hadir': $status_class = 'status-hadir'; break;
                            case 'Masuk': $status_class = 'status-masuk'; break;
                            case 'Tidak Lengkap': $status_class = 'status-tidak-lengkap'; break;
                            default: $status_class = 'status-tidak-hadir';
                        }
                        
                        echo "<tr>";
                        echo "<td>{$no}</td>";
                        echo "<td>" . htmlspecialchars($asisten['nama']) . "</td>";
                        echo "<td>" . htmlspecialchars($asisten['nim']) . "</td>";
                        echo "<td>{$waktu_masuk}</td>";
                        echo "<td>{$waktu_keluar}</td>";
                        echo "<td><span class='{$status_class}'>{$status}</span></td>";
                        echo "</tr>";
                        
                        $no++;
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- PRAKTIKAN CONTENT (FINGERPRINT) -->
        <div id="praktikan-content" class="content-section">
            <div class="section-header">
                <div class="section-title">Kehadiran Praktikan (Fingerprint)</div>
                <div class="section-subtitle">Sistem presensi berbasis scan sidik jari</div>
            </div>

            <div class="no-data">
                <h3 style="color: #FF9800; margin-bottom: 15px;">Under Development</h3>
                <p>Fitur kehadiran praktikan dengan fingerprint sedang dalam pengembangan.</p>
                <p>Akan terintegrasi dengan sistem RFID asisten untuk memberikan</p>
                <p>laporan kehadiran yang comprehensive untuk pertemuan ini.</p>
                
                <div style="margin-top: 20px; padding: 15px; background: rgba(255, 152, 0, 0.1); border-radius: 8px; border: 1px solid rgba(255, 152, 0, 0.3);">
                    <strong>Struktur Data Praktikan Ready:</strong><br>
                    • Total praktikan terdaftar: <?php echo count($praktikan_list); ?> orang<br>
                    • Database schema sudah prepared<br>
                    • Tab system sudah configured<br>
                    • Tinggal implementasi fingerprint hardware<br><br>
                    
                    <strong>Daftar Praktikan Terdaftar:</strong><br>
                    <?php foreach (array_slice($praktikan_list, 0, 5) as $p): ?>
                        • <?php echo htmlspecialchars($p['nama']); ?> (<?php echo htmlspecialchars($p['nim']); ?>)<br>
                    <?php endforeach; ?>
                    <?php if (count($praktikan_list) > 5): ?>
                        ... dan <?php echo count($praktikan_list) - 5; ?> praktikan lainnya
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="btn-container">
            <a href="lihat_kehadiran_praktikum.php?id=<?php echo $pertemuan_data['id_kelas']; ?>" class="btn kembali">Kembali ke Daftar Pertemuan</a>
            <a href="lihat_lebih_detail.php?id=<?php echo $pertemuan_data['id_kelas']; ?>" class="btn">Kembali ke Detail Kelas</a>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all content sections
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.remove('active');
            });
            
            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected content section
            document.getElementById(tabName + '-content').classList.add('active');
            
            // Add active class to clicked tab button
            event.target.classList.add('active');
        }
        
        // Optional: Add smooth transition effect
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('.content-section');
            sections.forEach(section => {
                section.style.transition = 'opacity 0.3s ease-in-out';
            });
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>