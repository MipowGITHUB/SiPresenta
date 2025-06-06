<?php
include 'connection.php';
session_start();

date_default_timezone_set('Asia/Jakarta');
$current_date = date('Y-m-d');
$current_time = date('H:i:s');

// Konversi nama hari dari Bahasa Inggris ke Bahasa Indonesia
$days = [
    "Sunday" => "Minggu",
    "Monday" => "Senin",
    "Tuesday" => "Selasa",
    "Wednesday" => "Rabu",
    "Thursday" => "Kamis",
    "Friday" => "Jumat",
    "Saturday" => "Sabtu"
];
$current_day = $days[date('l')];

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_SESSION['rfid']) || isset($_SESSION['rfid1'])) {
    if (isset($_POST['rfid']) || isset($_POST['rfid1'])) {
        $_SESSION['rfid'] = $_POST['rfid'] ?? null;
        $_SESSION['rfid1'] = $_POST['rfid1'] ?? null;
    }
    $rfid = $_SESSION['rfid'] ?? null;
    $rfid1 = $_SESSION['rfid1'] ?? null;

    $asisten_id = $rfid ?? $rfid1;

    if (!$asisten_id) {
        echo "<script>alert('RFID tidak ditemukan.'); window.location.href='dashboard.php';</script>";
        exit;
    }

    // =====================================
    // STEP 1: GET ASISTEN'S CLASSES (CONCURRENT SUPPORT)
    // =====================================
    
    // FIX: Ambil kelas yang ASISTEN INI handle di hari ini, bukan semua kelas aktif
    $sql_asisten_classes = "SELECT ka.id_kelas, CONCAT(k.matkul, ' ', k.kelas) AS nama_kelas,
                                   k.status_presensi, k.status_checkout
                            FROM kelas_asisten ka
                            JOIN kelas k ON ka.id_kelas = k.id_kelas
                            WHERE ka.id_asisten = ? AND k.hari = ?";
    
    $stmt_classes = $conn->prepare($sql_asisten_classes);
    $stmt_classes->bind_param('is', $asisten_id, $current_day);
    $stmt_classes->execute();
    $asisten_classes = $stmt_classes->get_result();

    if ($asisten_classes->num_rows == 0) {
        echo "<script>alert('Asisten tidak terdaftar di kelas manapun untuk hari $current_day.'); window.location.href='dashboard.php';</script>";
        exit;
    }

    // =====================================
    // STEP 2: PRIORITY - CHECK PENDING CHECKOUTS FIRST
    // =====================================
    
    $pending_checkout_found = false;
    
    $asisten_classes->data_seek(0); // Reset pointer
    while ($class = $asisten_classes->fetch_assoc()) {
        // Skip kalau checkout tidak dibuka untuk kelas ini
        if ($class['status_checkout'] != 1) continue;
        
        // Cek pending checkout untuk kelas ini
        $sql_check_pending = "SELECT ka.*, p.id_kelas, p.id_pertemuan
                             FROM kehadiran_asisten ka
                             JOIN pertemuan p ON ka.id_pertemuan = p.id_pertemuan
                             WHERE ka.id_asisten = ? AND p.tanggal = ? AND p.id_kelas = ? AND ka.waktu_keluar IS NULL";
        
        $stmt_check = $conn->prepare($sql_check_pending);
        $stmt_check->bind_param('isi', $asisten_id, $current_date, $class['id_kelas']);
        $stmt_check->execute();
        $pending_checkout = $stmt_check->get_result();

        if ($pending_checkout->num_rows > 0) {
            // FOUND PENDING CHECKOUT - Process it
            $checkout_data = $pending_checkout->fetch_assoc();
            $id_pertemuan = $checkout_data['id_pertemuan'];
            $nama_kelas = $class['nama_kelas'];

            // Process checkout
            $sql_update = "UPDATE kehadiran_asisten 
                          SET waktu_keluar = ?, keterangan = 'Hadir' 
                          WHERE id_asisten = ? AND id_pertemuan = ? AND waktu_keluar IS NULL";

            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param('sii', $current_time, $asisten_id, $id_pertemuan);
            $stmt_update->execute();

            echo "<script>alert('Check-out berhasil dari kelas $nama_kelas!'); window.location.href='dashboard.php';</script>";
            $pending_checkout_found = true;
            break; // Exit loop setelah process checkout
        }
    }

    // =====================================
    // STEP 3: IF NO PENDING CHECKOUT, PROCESS CHECK-IN
    // =====================================
    
    if (!$pending_checkout_found) {
        
        // Reset pointer untuk check-in logic
        $asisten_classes->data_seek(0);
        $checkin_processed = false;
        
        while ($class = $asisten_classes->fetch_assoc()) {
            // Skip kalau presensi tidak dibuka untuk kelas ini
            if ($class['status_presensi'] != 1) continue;
            
            $id_kelas = $class['id_kelas'];
            $nama_kelas = $class['nama_kelas'];

            // Check if pertemuan exists untuk kelas ini hari ini
            $sql = "SELECT id_pertemuan FROM pertemuan WHERE id_kelas = ? AND tanggal = ? LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('is', $id_kelas, $current_date);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $id_pertemuan = $row['id_pertemuan'];

                // Check if asisten sudah presensi di pertemuan ini
                $sql = "SELECT * FROM kehadiran_asisten WHERE id_asisten = ? AND id_pertemuan = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ii', $asisten_id, $id_pertemuan);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) { 
                    echo "<script>alert('Anda sudah melakukan presensi untuk kelas $nama_kelas hari ini.'); window.location.href='dashboard.php';</script>";
                } else {
                    // Add attendance record
                    $sql = "INSERT INTO kehadiran_asisten (id_asisten, id_pertemuan, keterangan, waktu_masuk, waktu_keluar) 
                            VALUES (?, ?, 'Masuk', ?, NULL)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('iis', $asisten_id, $id_pertemuan, $current_time);
                    $stmt->execute();

                    echo "<script>alert('Check-in berhasil ke kelas $nama_kelas!'); window.location.href='dashboard.php';</script>";
                }
                $checkin_processed = true;
                break; // Exit setelah process check-in pertama yang valid
                
            } else {
                // No pertemuan exists, create one untuk kelas ini
                echo "<form id='redirectForm' action='tambah_data_pertemuan.php' method='POST'>
                          <input type='hidden' name='rfid' value='$asisten_id'>
                          <input type='hidden' name='target_class_id' value='$id_kelas'>
                      </form>
                      <script>document.getElementById('redirectForm').submit();</script>";
                $checkin_processed = true;
                break;
            }
        }
        
        // Kalau tidak ada kelas yang bisa di-check-in
        if (!$checkin_processed) {
            echo "<script>alert('Tidak ada kelas yang aktif untuk check-in pada hari $current_day.'); window.location.href='dashboard.php';</script>";
        }
    }

    $stmt_classes->close();
    if(isset($stmt_check)) $stmt_check->close();
    if(isset($stmt)) $stmt->close();
    if(isset($stmt_update)) $stmt_update->close();
} else {
    echo "<script>alert('Metode pengiriman tidak valid.'); window.location.href='dashboard.php';</script>";
}

$conn->close();

// =====================================
// HELPER FUNCTIONS (untuk dashboard)
// =====================================

/**
 * Function untuk menentukan status kehadiran yang sederhana
 */
function getAttendanceStatus($keterangan, $waktu_masuk, $waktu_keluar, $tanggal) {
    $current_date = date('Y-m-d');
    $record_date = date('Y-m-d', strtotime($tanggal));
    
    // Jika tidak ada waktu masuk
    if (empty($waktu_masuk)) {
        return 'Tidak Hadir';
    }
    
    // Jika ada waktu masuk tapi tidak ada waktu keluar
    if (!empty($waktu_masuk) && empty($waktu_keluar)) {
        // Jika masih hari yang sama, status "Masuk" (sedang di lab)
        if ($record_date == $current_date) {
            return 'Masuk';
        } else {
            // Jika sudah hari berbeda dan belum check-out, dianggap tidak lengkap
            return 'Tidak Lengkap';
        }
    }
    
    // Jika ada waktu masuk DAN waktu keluar = "Hadir"
    if (!empty($waktu_masuk) && !empty($waktu_keluar)) {
        return 'Hadir';
    }
    
    return 'Tidak Hadir';
}

/**
 * Function untuk menghitung statistik kehadiran sederhana
 */
function getAccurateAttendanceStats($conn) {
    $stats = [
        'total_hadir' => 0,
        'hari_ini' => 0,
        'minggu_ini' => 0,
        'sedang_masuk' => 0
    ];
    
    // Total yang benar-benar hadir (ada check-in dan check-out)
    $sql_total = "SELECT COUNT(*) as total FROM kehadiran_asisten 
                  WHERE waktu_masuk IS NOT NULL 
                  AND waktu_keluar IS NOT NULL";
    $result = $conn->query($sql_total);
    if ($result && $row = $result->fetch_assoc()) {
        $stats['total_hadir'] = $row['total'];
    }
    
    // Hari ini yang benar-benar hadir
    $sql_today = "SELECT COUNT(*) as today FROM kehadiran_asisten ka
                  JOIN pertemuan p ON ka.id_pertemuan = p.id_pertemuan
                  WHERE DATE(p.tanggal) = CURDATE() 
                  AND ka.waktu_masuk IS NOT NULL 
                  AND ka.waktu_keluar IS NOT NULL";
    $result = $conn->query($sql_today);
    if ($result && $row = $result->fetch_assoc()) {
        $stats['hari_ini'] = $row['today'];
    }
    
    // Minggu ini yang benar-benar hadir
    $sql_week = "SELECT COUNT(*) as week FROM kehadiran_asisten ka
                 JOIN pertemuan p ON ka.id_pertemuan = p.id_pertemuan
                 WHERE WEEK(p.tanggal) = WEEK(CURDATE()) 
                 AND YEAR(p.tanggal) = YEAR(CURDATE())
                 AND ka.waktu_masuk IS NOT NULL 
                 AND ka.waktu_keluar IS NOT NULL";
    $result = $conn->query($sql_week);
    if ($result && $row = $result->fetch_assoc()) {
        $stats['minggu_ini'] = $row['week'];
    }
    
    // Sedang masuk (check-in tapi belum check-out hari ini)
    $sql_current = "SELECT COUNT(*) as current FROM kehadiran_asisten ka
                    JOIN pertemuan p ON ka.id_pertemuan = p.id_pertemuan
                    WHERE DATE(p.tanggal) = CURDATE() 
                    AND ka.waktu_masuk IS NOT NULL 
                    AND ka.waktu_keluar IS NULL";
    $result = $conn->query($sql_current);
    if ($result && $row = $result->fetch_assoc()) {
        $stats['sedang_masuk'] = $row['current'];
    }
    
    return $stats;
}
?>