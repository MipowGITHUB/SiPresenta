<?php //import data dari database
    // Import koneksi database dari file connection.php
    include 'connection.php';

    //menangkap id yang dikirim dari page sebelumnya
    if (isset($_GET['id'])) {
        $id_kelas = $_GET['id'];
    } else {
        die("ID tidak ditemukan!");
    }

    // Query untuk mengambil data kelas tertentu
    $sql_kelas = "SELECT * FROM kelas WHERE id_kelas = ?";
    $stmt_kelas = $conn->prepare($sql_kelas);
    $stmt_kelas->bind_param("i", $id_kelas);
    $stmt_kelas->execute();
    $result_kelas = $stmt_kelas->get_result();
    $stmt_kelas->close(); // Tutup statement
    $row_kelas = $result_kelas->fetch_assoc();

    //query untuk mengambil data asisten dari kelas tertentu
    $sql_kelas_asisten =
            "SELECT kelas_asisten.*, asisten.*
            FROM kelas_asisten
            JOIN asisten ON kelas_asisten.id_asisten = asisten.id_asisten
            WHERE kelas_asisten.id_kelas = ?";

    $stmt_kelas_asisten = $conn->prepare($sql_kelas_asisten);
    $stmt_kelas_asisten->bind_param("i", $id_kelas);
    $stmt_kelas_asisten->execute();
    $result_kelas_asisten = $stmt_kelas_asisten->get_result();

    //query untuk mengambil data praktikan dari kelas tertentu
    $sql_kelas_praktikan =
            "SELECT kelas_praktikan.*, praktikan.*
            FROM kelas_praktikan
            JOIN praktikan ON kelas_praktikan.id_praktikan = praktikan.id_praktikan
            WHERE kelas_praktikan.id_kelas = ?";

    $stmt_kelas_praktikan = $conn->prepare($sql_kelas_praktikan);
    $stmt_kelas_praktikan->bind_param("i", $id_kelas);
    $stmt_kelas_praktikan->execute();
    $result_kelas_praktikan = $stmt_kelas_praktikan->get_result();
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Lebih Detail</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #1A3A63 0%, #0C233B 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 30px;
            padding-bottom: 50px;
        }
        .container {
            width: 80%;
            max-width: 1200px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            padding: 30px;
            margin-bottom: 30px;
            backdrop-filter: blur(4px);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1 {
            color: #fff;
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            font-size: 24px;
        }
        .table-container {
            width: 100%;
            margin: 15px 0 30px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
            border: none;
        }
        th {
            background-color: rgba(0, 0, 0, 0.3);
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
        }
        tr {
            background-color: rgba(255, 255, 255, 0.1);
        }
        tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.05);
        }
        tr:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .btn-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
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
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        .btn.kehadiran {
            background-color: #27ae60;
        }
        .btn.kehadiran:hover {
            background-color: #219653;
        }
        .btn.kembali {
            background-color: #7f8c8d;
        }
        .btn.kembali:hover {
            background-color: #6c7a7d;
        }
        .section-title {
            width: 100%;
            padding: 10px 0;
            margin: 20px 0 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            font-weight: 600;
            font-size: 22px;
        }
        .matkul-info {
            font-size: 18px;
            color: #3DA5D9;
            margin-bottom: 10px;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Data Detail Kelas</h1>
        <div class="matkul-info">
            <?php echo htmlspecialchars("{$row_kelas['matkul']} {$row_kelas['kelas']}"); ?>
        </div>
        
        <div class="section-title">Daftar Asisten</div>
        <div class="table-container">
            <table>
                <tr>
                    <th>Nama</th>
                    <th>NIM</th>
                </tr>
                <?php
                    if ($result_kelas_asisten->num_rows > 0) {
                        while ($row_kelas_asisten = $result_kelas_asisten->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row_kelas_asisten['nama']) . "</td>
                                    <td>" . htmlspecialchars($row_kelas_asisten['nim']) . "</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2' class='no-data'>Tidak ada data</td></tr>";
                    }
                ?>
            </table>
        </div>
        
        <div class="section-title">Daftar Praktikan</div>
        <div class="table-container">
            <table>
                <tr>
                    <th>Nama</th>
                    <th>NIM</th>
                </tr>
                <?php
                    if ($result_kelas_praktikan->num_rows > 0) {
                        while ($row_kelas_praktikan = $result_kelas_praktikan->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row_kelas_praktikan['nama']) . "</td>
                                    <td>" . htmlspecialchars($row_kelas_praktikan['nim']) . "</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2' class='no-data'>Tidak ada data</td></tr>";
                    }
                ?>
            </table>
        </div>
        
        <div class="btn-container">
            <a href="lihat_kehadiran_praktikum.php?id=<?php echo htmlspecialchars($row_kelas['id_kelas']); ?>" class="btn kehadiran">Lihat Kehadiran Praktikum</a>
            <a href="lihat_data_kelas.php" class="btn kembali">Kembali ke Data Kelas</a>
        </div>
    </div>
</body>
</html>

<?php
    $conn->close();
?>