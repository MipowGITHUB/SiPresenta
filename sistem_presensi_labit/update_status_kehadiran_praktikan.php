<?php
include 'connection.php';

date_default_timezone_set('Asia/Jakarta');
$now = date('Y-m-d H:i:s');
$today = date('Y-m-d');
$current_day = date('l'); // 'Monday', 'Tuesday', etc

// Ambil semua kelas yang jadwalnya hari ini dan waktu_selesai sudah lewat
$sql = "SELECT k.id_kelas, k.waktu_selesai, r.id_presensi, r.keterangan 
        FROM kelas k 
        JOIN praktikan p ON k.kelas = p.kelas
        JOIN rekap_praktikan r ON r.id_praktikan = p.id_praktikan
        WHERE k.hari = DAYNAME(CURDATE())
          AND DATE(r.waktu_checkin) = CURDATE()
          AND r.keterangan = 'Masuk'
          AND k.waktu_selesai < CURTIME()
          AND r.waktu_checkout IS NULL";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id_presensi = $row['id_presensi'];
        $update = "UPDATE rekap_praktikan SET keterangan = 'Tidak Hadir' WHERE id_presensi = $id_presensi";
        $conn->query($update);
    }
}

$conn->close();
