<?php
    include 'connection.php';
    if (isset($_GET['id_pertemuan'])) {
        $id_pertemuan = $_GET['id_pertemuan'];
    } else {
        echo "ID Pertemuan tidak ditemukan.";
        exit;
    } 

    // Query untuk mengambil data kehadiran asisten berdasarkan id_pertemuan
    $sql_kehadiran_asisten = "
        SELECT ka.*, a.nama 
        FROM kehadiran_asisten ka
        JOIN asisten a ON ka.id_asisten = a.id_asisten
        WHERE ka.id_pertemuan = ?
    ";
    $stmt_asisten = $conn->prepare($sql_kehadiran_asisten);
    $stmt_asisten->bind_param("i", $id_pertemuan);
    $stmt_asisten->execute();
    $result_asisten = $stmt_asisten->get_result();

    // Query untuk mengambil data kehadiran praktikan berdasarkan id_pertemuan
    $sql_kehadiran_praktikan = "
        SELECT kp.*, p.nama 
        FROM kehadiran_praktikan kp
        JOIN praktikan p ON kp.id_praktikan = p.id_praktikan
        WHERE kp.id_pertemuan = ?
    ";
    $stmt_praktikan = $conn->prepare($sql_kehadiran_praktikan);
    $stmt_praktikan->bind_param("i", $id_pertemuan);
    $stmt_praktikan->execute();
    $result_praktikan = $stmt_praktikan->get_result();

    // Query untuk mendapatkan id_kelas dari id_pertemuan
    $sql_get_kelas = "SELECT id_kelas FROM pertemuan WHERE id_pertemuan = ?";
    $stmt_get_kelas = $conn->prepare($sql_get_kelas);
    $stmt_get_kelas->bind_param("i", $id_pertemuan);
    $stmt_get_kelas->execute();
    $result_kelas = $stmt_get_kelas->get_result();
    
    // Set default value jika tidak ditemukan
    $id_kelas = 0;
    
    if ($result_kelas->num_rows > 0) {
        $row_kelas = $result_kelas->fetch_assoc();
        $id_kelas = $row_kelas['id_kelas'];
    }
    $stmt_get_kelas->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Data Kehadiran Praktikan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 50px;
            background: linear-gradient(135deg, #1A3A63 0%, #0C233B 100%);
            background-color: #f5f5f5;
            color: white;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 1200px;
            margin-bottom: 50px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.4);
            text-decoration: none;
            color: white;
            text-align: center;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 30px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            overflow: hidden;
        }
        th, td {
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: rgba(0, 0, 0, 0.2);
        }
        h1 {
            color: white;
            text-align: center;
            margin-bottom: 20px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        }
        tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        .section {
            margin-bottom: 30px;
            width: 100%;
        }
        @media (max-width: 768px) {
            .container {
                width: 95%;
                padding: 15px;
            }
            th, td {
                padding: 8px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Data Kehadiran Praktikum</h1>
        
        <div class="section">
            <h2>List Asisten</h2>
            <table>
                <tr>
                    <th>Id Asisten</th>
                    <th>Nama</th>
                    <th>Keterangan</th>
                    <th>Waktu Masuk</th>
                    <th>Waktu Keluar</th>
                </tr>
                <?php
                    if ($result_asisten->num_rows > 0) {
                        while ($row_asisten = $result_asisten->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row_asisten['id_asisten']}</td>
                                    <td>{$row_asisten['nama']}</td>
                                    <td>{$row_asisten['keterangan']}</td>
                                    <td>{$row_asisten['waktu_masuk']}</td>
                                    <td>{$row_asisten['waktu_keluar']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Tidak ada data kehadiran asisten.</td></tr>";
                    }
                ?>
            </table>
        </div>

        <div class="section">
            <h2>List Praktikan</h2>
            <table>
                <tr>
                    <th>Id Praktikan</th>
                    <th>Nama</th>
                    <th>Keterangan</th>
                    <th>Waktu Masuk</th>
                    <th>Waktu Keluar</th>
                </tr>
                <?php
                    if ($result_praktikan->num_rows > 0) {
                        while ($row_praktikan = $result_praktikan->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row_praktikan['id_praktikan']}</td>
                                    <td>{$row_praktikan['nama']}</td>
                                    <td>{$row_praktikan['keterangan']}</td>
                                    <td>{$row_praktikan['waktu_masuk']}</td>
                                    <td>{$row_praktikan['waktu_keluar']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Tidak ada data kehadiran praktikan.</td></tr>";
                    }
                ?>
            </table>
        </div>

        <a href="lihat_kehadiran_praktikum.php?id=<?php echo urlencode($id_kelas); ?>" class="btn">Kembali ke Lihat Pertemuan</a>
    </div>
</body>
</html>

<?php
    // Tutup koneksi dan statement
    $stmt_asisten->close();
    $stmt_praktikan->close();
    $conn->close();
?>