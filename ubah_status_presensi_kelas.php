<?php
include 'connection.php';

date_default_timezone_set('Asia/Jakarta');
$current_time = date("H:i:s");

$hari_inggris = date('l');
$hari_indonesia = [
    'Sunday' => 'Minggu',
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu'
];
$current_day = $hari_indonesia[$hari_inggris];

$sql = "
UPDATE kelas
SET 
    status_presensi = CASE
        WHEN (TIME('$current_time') BETWEEN SUBTIME(waktu_mulai, '00:15:00') AND ADDTIME(waktu_mulai, '00:15:00'))
        THEN 1 ELSE 0 END,
    status_checkout = CASE
        WHEN (TIME('$current_time') BETWEEN SUBTIME(waktu_selesai, '00:15:00') AND ADDTIME(waktu_selesai, '00:15:00'))
        THEN 1 ELSE 0 END
WHERE hari = '$current_day'
";

if ($conn->query($sql) === TRUE) {
    echo "Status presensi dan checkout berhasil diperbarui.";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
