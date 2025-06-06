<?php
include 'connection.php';

date_default_timezone_set('Asia/Jakarta');
$current_date = date('Y-m-d');
$current_time = date('H:i:s');

// Konversi nama hari dari Bahasa Inggris ke Bahasa Indonesia
$days = [
    "Sunday" => "Minggu",
    "Monday" => "Senin",
    "Tuesday" => "Rabu",
    "Wednesday" => "Rabu",
    "Thursday" => "Kamis",
    "Friday" => "Jumat",
    "Saturday" => "Sabtu"
];
$current_day = $days[date('l')]; // Nama hari dalam Bahasa Indonesia

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_praktikan = $_POST['fingerprint_id'] ?? null;
    $id_praktikan1 = $_POST['fingerprint_id1'] ?? null;

    if (!$id_praktikan && !$id_praktikan1) {
        echo "<script>alert('fingerprint_id tidsfdak ditemukan.'); window.location.href='dashboard.php';</script>";
        exit;
    } 

    // Cek apakah praktikan terdaftar di kelas yang aktif dan sesuai dengan hari ini
    $sql = "SELECT kp.id_kelas 
            FROM kelas_praktikan kp
            JOIN kelas k ON kp.id_kelas = k.id_kelas
            WHERE kp.id_praktikan = ? AND k.status_presensi = 1 AND k.hari = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $id_praktikan, $current_day);
    $stmt->execute();
    $result = $stmt->get_result();

    $sql1 = "SELECT kp.id_kelas 
            FROM kelas_praktikan kp
            JOIN kelas k ON kp.id_kelas = k.id_kelas
            WHERE kp.id_praktikan = ? AND k.status_checkout = 1 AND k.hari = ?";
            
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param('is', $id_praktikan1, $current_day);
    $stmt1->execute();
    $result1 = $stmt1->get_result();

    if ($result->num_rows > 0) { //checkin
        echo "<p>Query kelas aktif berhasil!</p>";
        $row = $result->fetch_assoc();
        $id_kelas = $row['id_kelas'];
        echo "<p>Ditemukan kelas aktif dengan ID: $id_kelas</p>";

        // Cek apakah sudah ada pertemuan hari ini untuk kelas tersebut
        $sql = "SELECT id_pertemuan FROM pertemuan WHERE id_kelas = ? AND tanggal = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('is', $id_kelas, $current_date);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_pertemuan = $row['id_pertemuan'];
            echo "<p>Id pertemuan ditemukan: $id_pertemuan</p>";

            // Cek apakah praktikan sudah pernah presensi di pertemuan ini hari ini
            $sql = "SELECT 1 FROM kehadiran_praktikan WHERE id_praktikan = ? AND id_pertemuan = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ii', $id_praktikan, $id_pertemuan);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<script>alert('Anda sudah melakukan presensi untuk kelas ini hari ini.'); window.location.href='dashboard.php';</script>";
            } else {
                $sql = "INSERT INTO kehadiran_praktikan (id_praktikan, id_pertemuan, keterangan, waktu_masuk, waktu_keluar) 
                        VALUES (?, ?, 'Hadir', ?, NULL)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('iis', $id_praktikan, $id_pertemuan, $current_time);
                $stmt->execute();

                echo "<script>alert('Data kehadiran berhasil disimpan.'); window.location.href='dashboard.php';</script>";
            }
        } else {
            echo "<p>Tidak ada pertemuan ditemukan untuk hari ini. Redirect ke tambah_data_pertemuan.php.</p>";
            //exit();
            echo "<form id='redirectForm' action='tambah_data_pertemuan.php' method='POST'>
                    <input type='hidden' name='fingerprint_id' value='$id_praktikan'>
                  </form>
                  <script>document.getElementById('redirectForm').submit();</script>";
        }
    } else if ($result1->num_rows > 0) { //checkout
        $row1 = $result1->fetch_assoc();
        $id_kelas1 = $row1['id_kelas'];
    
        // Cek apakah sudah ada pertemuan hari ini untuk kelas tersebut
        $sql1 = "SELECT id_pertemuan FROM pertemuan WHERE id_kelas = ? AND tanggal = ? LIMIT 1";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param('is', $id_kelas1, $current_date);
        $stmt1->execute();
        $result1 = $stmt1->get_result();
    
        if ($result1->num_rows > 0) { //ada pertemuan
            $row1 = $result1->fetch_assoc();
            $id_pertemuan = $row1['id_pertemuan'];
    
            // Cek apakah praktikan sudah pernah melakukan checkout di pertemuan ini
            $sql1 = "   SELECT kp.* 
                        FROM kehadiran_praktikan kp
                        JOIN pertemuan p ON kp.id_pertemuan = p.id_pertemuan
                        JOIN kelas k ON p.id_kelas = k.id_kelas
                        WHERE kp.id_praktikan = ? AND kp.id_pertemuan = ? AND k.status_checkout IS NULL";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bind_param('ii', $id_praktikan1, $id_pertemuan);
            $stmt1->execute();
            $result1 = $stmt1->get_result();
    
            if ($result1->num_rows > 0) {
                echo "<script>alert('Anda belum melakukan Check-In untuk kelas ini hari ini.'); window.location.href='dashboard.php';</script>";
            } else {
                // Update waktu_keluar untuk Checkout
                $sql1 = "UPDATE kehadiran_praktikan SET waktu_keluar = ? WHERE id_praktikan = ? AND id_pertemuan = ?";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->bind_param('sii', $current_time, $id_praktikan1, $id_pertemuan);
                $stmt1->execute();
    
                echo "<script>alert('Checkout berhasil.'); window.location.href='dashboard.php';</script>";
            }
        } else {
            echo "<script>alert('Tidak ada pertemuan ditemukan untuk hari ini.'); window.location.href='dashboard.php';</script>";
        }
    }
     else {
        echo "<p>Query gagal! Tidak ada kelas aktif untuk praktikan dengan ID: $id_praktikan1 pada hari: $current_day</p>";
        echo "<p>SQL Query: $sql1</p>";
        echo "<p>praktikan ID: $id_praktikan1</p>";
        echo "<p>Hari: $current_day</p>";
    }

    $stmt->close();
    $stmt1->close();
} else {
    echo "<script>alert('Metode pengiriman tidak valid.'); window.location.href='dashboard.php';</script>";
}

$conn->close();
?>
