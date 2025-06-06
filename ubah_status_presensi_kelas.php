<?php
include 'connection.php'; // Koneksi database

date_default_timezone_set('Asia/Jakarta'); // Mengatur timezone ke WIB
$current_time = date("H:i:s"); // Waktu sekarang (HH:MM:SS)


// Konversi nama hari ke bahasa Indonesia
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

// Mengubah status_presensi dan status_checkout berdasarkan waktu_mulai dan waktu_selesai
$sql = "
    UPDATE kelas 
    SET 
        status_presensi = 
            CASE
                WHEN ('$current_time' BETWEEN SUBTIME(waktu_mulai, '00:15:00') AND ADDTIME(waktu_mulai, '00:15:00'))
                     AND hari = '$current_day' THEN 1
                ELSE 0
            END,
        status_checkout = 
            CASE
                WHEN ('$current_time' BETWEEN SUBTIME(waktu_selesai, '00:15:00') AND ADDTIME(waktu_selesai, '00:15:00'))
                     AND hari = '$current_day' THEN 1
                ELSE 0
            END
";

if ($conn->query($sql) === TRUE) {
    echo "Status presensi dan checkout berhasil diperbarui.";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
