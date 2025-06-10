<?php
include 'connection.php';

date_default_timezone_set('Asia/Jakarta');
$current_date = date('Y-m-d');

if ($_SERVER["REQUEST_METHOD"] == "POST") { //tangkap variabel
    $fingerprint_id = $_POST['fingerprint_id'] ?? null;
    $rfid = $_POST['rfid'] ?? null;

    if(!$fingerprint_id && !$rfid){
        echo "apaan dah";
        exit();
    }

    // Cek apakah praktikan terdaftar di kelas yang aktif (status_presensi = 1)
    $sql = "SELECT kp.id_kelas, kp.id_praktikan
            FROM kelas_praktikan kp
            JOIN kelas k ON kp.id_kelas = k.id_kelas
            WHERE kp.id_praktikan = ? AND k.status_presensi = 1";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $fingerprint_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $sql1 = "SELECT ka.id_kelas, ka.id_asisten
            FROM kelas_asisten ka
            JOIN kelas k ON ka.id_kelas = k.id_kelas
            WHERE ka.id_asisten = ? AND k.status_presensi = 1";

    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param('i', $rfid);
    $stmt1->execute();
    $result1 = $stmt1->get_result();

    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_kelas = $row['id_kelas'];

        // Insert data baru ke tabel pertemuan
        $sql = "INSERT INTO pertemuan (id_kelas, tanggal) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('is', $id_kelas, $current_date);
        $stmt->execute();
        $id_pertemuan = $conn->insert_id; // Mendapatkan id_pertemuan yang baru dibuat  
    } else if($result1->num_rows > 0){
        $row1 = $result1->fetch_assoc();
        $id_kelas = $row1['id_kelas'];

        // Insert data baru ke tabel pertemuan
        $sql1 = "INSERT INTO pertemuan (id_kelas, tanggal) VALUES (?, ?)";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param('is', $id_kelas, $current_date);
        $stmt1->execute();
        $id_pertemuan = $conn->insert_id; // Mendapatkan id_pertemuan yang baru dibuat  

        echo "  <form id='redirectForm' action='tambah_data_pertemuan_asisten.php' method='POST'>
                    <input type='hidden' name='rfid' value='$rfid'>
                </form>
                <script>
                    alert('Data pertemuan berhasil dibuat.');
                    document.getElementById('redirectForm').submit();
                </script>";
    } else {
        echo "<script>alert('Tidak ada kelas yang aktif untuk iad ini.$fingerprint_id'); window.location.href='dashboard.php';</script>";
    }

    $stmt->close();
}
$conn->close();

?>
