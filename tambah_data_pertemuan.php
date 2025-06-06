<?php
include 'connection.php';

date_default_timezone_set('Asia/Jakarta');
$current_date = date('Y-m-d');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fingerprint_id = $_POST['fingerprint_id'] ?? null;
    $rfid = $_POST['rfid'] ?? null;

    if(!$fingerprint_id && !$rfid){
        echo "<script>alert('Parameter tidak valid.'); window.location.href='dashboard.php';</script>";
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

        // Hitung pertemuan_ke berdasarkan pertemuan yang sudah ada untuk kelas ini
        $sql_count = "SELECT COUNT(*) as total FROM pertemuan WHERE id_kelas = ?";
        $stmt_count = $conn->prepare($sql_count);
        $stmt_count->bind_param('i', $id_kelas);
        $stmt_count->execute();
        $result_count = $stmt_count->get_result();
        $count_row = $result_count->fetch_assoc();
        $pertemuan_ke = $count_row['total'] + 1;

        // Insert data baru ke tabel pertemuan - FIX: modul = INT, bukan string
        $sql = "INSERT INTO pertemuan (id_kelas, tanggal, pertemuan_ke, modul, kegiatan, keterangan) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $modul = $pertemuan_ke; // INT value, bukan string
        $kegiatan = "Praktikum Pertemuan " . $pertemuan_ke;
        $keterangan = "Praktikum  " . date('Y-m-d H:i:s');
        
        // FIX: bind_param 'isiiss' bukan 'isssss'
        $stmt->bind_param('isiiss', $id_kelas, $current_date, $pertemuan_ke, $modul, $kegiatan, $keterangan);
        $stmt->execute();
        $id_pertemuan = $conn->insert_id;
        
        echo "<script>alert('Data pertemuan praktikan berhasil dibuat.'); window.location.href='dashboard.php';</script>";
        
    } else if($result1->num_rows > 0){
        $row1 = $result1->fetch_assoc();
        $id_kelas = $row1['id_kelas'];

        // Hitung pertemuan_ke berdasarkan pertemuan yang sudah ada untuk kelas ini
        $sql_count = "SELECT COUNT(*) as total FROM pertemuan WHERE id_kelas = ?";
        $stmt_count = $conn->prepare($sql_count);
        $stmt_count->bind_param('i', $id_kelas);
        $stmt_count->execute();
        $result_count = $stmt_count->get_result();
        $count_row = $result_count->fetch_assoc();
        $pertemuan_ke = $count_row['total'] + 1;

        // Insert data baru ke tabel pertemuan - FIX: modul = INT, bukan string
        $sql1 = "INSERT INTO pertemuan (id_kelas, tanggal, pertemuan_ke, modul, kegiatan, keterangan) 
                 VALUES (?, ?, ?, ?, ?, ?)";
        $stmt1 = $conn->prepare($sql1);
        $modul = $pertemuan_ke; // INT value, bukan string
        $kegiatan = "Praktikum Pertemuan " . $pertemuan_ke;
        $keterangan = "Auto created - " . date('Y-m-d H:i:s');
        
        // FIX: bind_param 'isiiss' bukan 'isssss'
        $stmt1->bind_param('isiiss', $id_kelas, $current_date, $pertemuan_ke, $modul, $kegiatan, $keterangan);
        $stmt1->execute();
        $id_pertemuan = $conn->insert_id;

        echo "  <form id='redirectForm' action='tambah_data_pertemuan_asisten.php' method='POST'>
                    <input type='hidden' name='rfid' value='$rfid'>
                </form>
                <script>
                    alert('Data pertemuan berhasil dibuat.');
                    document.getElementById('redirectForm').submit();
                </script>";
    } else {
        echo "<script>alert('Tidak ada kelas yang aktif untuk ID ini: $fingerprint_id$rfid'); window.location.href='dashboard.php';</script>";
    }

    $stmt->close();
    if(isset($stmt1)) $stmt1->close();
}
$conn->close();
?>