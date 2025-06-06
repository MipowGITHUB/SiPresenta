<?php
    include 'connection.php'; // Koneksi ke database

    // Cek apakah ada parameter ID di URL
    if (isset($_GET['id'])) {
        $id_praktikan = $_GET['id'];
    } else {
        die("ID tidak ditemukan!");
    }

    // Query untuk mengambil nama praktikan
    $sql_praktikan = "SELECT nama FROM praktikan WHERE id_praktikan = ?";
    $stmt_praktikan = $conn->prepare($sql_praktikan);
    $stmt_praktikan->bind_param("i", $id_praktikan);
    $stmt_praktikan->execute();
    $result_praktikan = $stmt_praktikan->get_result();
    $stmt_praktikan->close(); // Tutup statement

    $nama_praktikan = "Tidak Diketahui"; // Default jika tidak ditemukan
    if ($row_praktikan = $result_praktikan->fetch_assoc()) {
        $nama_praktikan = $row_praktikan['nama'];
    }

    // Query untuk mengambil semua kelas yang tersedia
    $sql_kelas = "SELECT id_kelas, matkul, kelas FROM kelas";
    $result_kelas = $conn->query($sql_kelas);

    // Query untuk mengambil data kelas yang diikuti oleh praktikan tertentu
    $sql = "SELECT kelas_praktikan.*, kelas.*
            FROM kelas_praktikan
            JOIN kelas ON kelas_praktikan.id_kelas = kelas.id_kelas
            WHERE kelas_praktikan.id_praktikan = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_praktikan);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close(); // Tutup statement
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Data Kelas Praktikan</title>
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
        }
        h1 {
            color: #fff;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .form-container {
            background-color: rgba(255, 255, 255, 0.15);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 15px;
        }
        .form-container label {
            font-weight: 500;
            margin-right: 10px;
        }
        .form-container select {
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            background-color: #fff;
            font-size: 15px;
            min-width: 250px;
        }
        .form-container button {
            padding: 10px 20px;
            background-color: #45a049;
            border: none;
            border-radius: 6px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-container button:hover {
            background-color: #2980b9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 15px;
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
        .action-link {
            display: inline-block;
            padding: 6px 12px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .action-link:hover {
            background-color: #c0392b;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 10px;
            background-color: rgba(255, 255, 255, 0.25);
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
        <h1>Data Kelas Praktikan: <?= htmlspecialchars($nama_praktikan) ?></h1>

        <div class="form-container">
            <form action="tambah_data_kelas_praktikan.php" method="post">
                <label for="id_kelas">Pilih Kelas:</label>
                <select name="id_kelas" required>
                    <option value="">Pilih Kelas</option>
                    <?php
                        while ($row_kelas = $result_kelas->fetch_assoc()) {
                            echo "<option value='".htmlspecialchars($row_kelas['id_kelas'])."'>"
                                .htmlspecialchars($row_kelas['matkul'])." ".htmlspecialchars($row_kelas['kelas']).
                                "</option>";
                        }
                    ?>
                </select>
                <input type="hidden" name="id_praktikan" value="<?= htmlspecialchars($id_praktikan) ?>">
                <button type="submit">Tambah Kelas</button>
            </form>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Mata Kuliah</th>
                <th>Kelas</th>
                <th>Lab</th>
                <th>Hari</th>
                <th>Waktu Masuk</th>
                <th>Waktu Keluar</th>
                <th>Aksi</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>".htmlspecialchars($row['id_kelas_praktikan'])."</td>
                            <td>".htmlspecialchars($row['matkul'])."</td>
                            <td>".htmlspecialchars($row['kelas'])."</td>
                            <td>".htmlspecialchars($row['lab'])."</td>
                            <td>".htmlspecialchars($row['hari'])."</td>
                            <td>".htmlspecialchars($row['waktu_mulai'])."</td>
                            <td>".htmlspecialchars($row['waktu_selesai'])."</td>
                            <td>
                                <a href='hapus_data_kelas_praktikan.php?id={$row['id_kelas_praktikan']}&id2={$row['id_praktikan']}' class='action-link'>Hapus</a>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='8' class='no-data'>Tidak ada data</td></tr>";
            }
            ?>
        </table>
    </div>
    
    <a href="lihat_data_praktikan.php" class="btn">Kembali ke Lihat Data Praktikan</a>

</body>
</html>

<?php
    $conn->close();
?>