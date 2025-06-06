<?php
    // Import koneksi database
    include 'connection.php';

    // Query untuk mengambil data
    $sql = "SELECT * FROM praktikan";
    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Praktikan</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        html, body {
            height: 100%;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1A3A63 0%, #0C233B 100%);
            background-color: #f5f5f5;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 50px 20px;
            min-height: 100vh;
        }
        
        .container {
            width: 90%;
            max-width: 1000px;
            background-color: rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        
        h1 {
            text-align: center;
            margin-bottom: 25px;
            color: white;
            font-size: 28px;
        }
        
        .form-container {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        
        form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }
        
        .input-group {
            display: flex;
            flex-direction: column;
        }
        
        label {
            margin-bottom: 8px;
            font-weight: bold;
        }
        
        input[type="text"] {
            padding: 12px;
            border: none;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.9);
        }
        
        button {
            padding: 12px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: bold;
        }
        
        button:hover {
            background-color: #45a049;
        }
        
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
        }
        
        th, td {
            padding: 15px;
            text-align: center;
        }
        
        th {
            background-color: rgba(0, 0, 0, 0.4);
            color: white;
            font-weight: bold;
        }
        
        td {
            background-color: rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        tr:hover td {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .btn-container {
            display: flex;
            justify-content: center;
            margin-top: 25px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }
        
        a {
            color: #6DC8FF;
            text-decoration: none;
            margin: 0 5px;
            padding: 3px 8px;
            border-radius: 3px;
            transition: background-color 0.3s;
        }
        
        a:hover {
            background-color: rgba(109, 200, 255, 0.2);
        }
        
        /* Fix untuk tabel responsif */
        @media screen and (max-width: 768px) {
            table {
                display: block;
                overflow-x: auto;
            }
            
            form {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lihat Data Praktikan</h1>
        
        <div class="form-container">
            <form action="tambah_data_praktikan.php" method="post">
                <div class="input-group">
                    <label for="nama">Nama:</label>
                    <input type="text" id="nama" name="nama" required>
                </div>
                <div class="input-group">
                    <label for="nim">NIM:</label>
                    <input type="text" id="nim" name="nim" required>
                </div>
                <div class="input-group">
                    <button type="submit">Tambah</button>
                </div>
            </form>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id_praktikan']}</td>
                                <td>{$row['nama']}</td>
                                <td>{$row['nim']}</td>
                                <td>
                                    <a href='edit_data_praktikan.php?id={$row['id_praktikan']}'>Edit</a> | 
                                    <a href='lihat_data_kelas_praktikan.php?id={$row['id_praktikan']}'>Kelas</a> | 
                                    <a href='hapus_data_praktikan.php?id={$row['id_praktikan']}' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Tidak ada data</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
        <div class="btn-container">
            <a href="dashboard.php" class="btn">Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>

<?php
    // Tutup koneksi
    $conn->close();
?>