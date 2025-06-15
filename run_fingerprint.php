<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'connection.php';

$current_day_en = date('l');
$hari_indonesia = [
    'Sunday'    => 'Minggu',
    'Monday'    => 'Senin',
    'Tuesday'   => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday'  => 'Kamis',
    'Friday'    => 'Jumat',
    'Saturday'  => 'Sabtu'
];
$current_day = $hari_indonesia[$current_day_en];

$sql = "SELECT COUNT(*) as count FROM kelas WHERE (status_presensi = 1 OR status_checkout = 1) AND hari = '$current_day'";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    
    if ($row['count'] > 0) {
        $python = 'C:\\Users\\WINDOWS 11\\AppData\\Local\\Programs\\Python\\Python312\\python.exe';
        $script = 'C:\\xampp\\htdocs\\SiPresenta\\sistem_presensi_labit\\fingerprint.py';

        if (file_exists($script)) {
            exec("\"$python\" \"$script\"", $output, $return_var);
            if ($return_var !== 0) {
                error_log("Gagal menjalankan fingerprint.py. Error code: $return_var");
            }
        } else {
            error_log("Script Python tidak ditemukan: $script");
        }
    }
} else {
    error_log("Query gagal: " . $conn->error);
}

$conn->close();
