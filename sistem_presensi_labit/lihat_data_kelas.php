<?php
    // Import koneksi database
    include 'connection.php';

    // Query untuk mengambil semua data dari tabel kelas
    $sql = "SELECT * FROM kelas";
    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Data Kelas</title>
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
        
        input[type="text"], input[type="time"], select {
            padding: 12px;
            border: none;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.9);
        }
        
        select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
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
        <h1>Lihat Data Kelas</h1>
        
        <div class="form-container">
            <form action="tambah_data_kelas.php" method="post">
                <div class="input-group">
                    <label for="matkul">Matkul:</label>
                    <input type="text" id="matkul" name="matkul" required>
                </div>
                <div class="input-group">
                    <label for="kelas">Kelas:</label>
                    <input type="text" id="kelas" name="kelas" required>
                </div>
                <div class="input-group">
                    <label for="lab">Lab:</label>
                    <input type="text" id="lab" name="lab" required>
                </div>
                <div class="input-group">
                    <label for="hari">Hari:</label>
                    <select name="hari" id="hari" required>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="waktu_mulai">Waktu Mulai:</label>
                    <input type="time" id="waktu_mulai" name="waktu_mulai" required>
                </div>
                <div class="input-group">
                    <label for="waktu_selesai">Waktu Selesai:</label>
                    <input type="time" id="waktu_selesai" name="waktu_selesai" required>
                </div>
                <div class="input-group">
                    <button type="submit">Tambah</button>
                </div>
            </form>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Matkul</th>
                    <th>Kelas</th>
                    <th>Lab</th>
                    <th>Hari</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['matkul']}</td>
                                <td>{$row['kelas']}</td>
                                <td>{$row['lab']}</td>
                                <td>{$row['hari']}</td>
                                <td>{$row['waktu_mulai']}</td>
                                <td>{$row['waktu_selesai']}</td>
                                <td>
                                    <a href='edit_data_kelas.php?id={$row['id_kelas']}'>Edit</a> | 
                                    <a href='lihat_lebih_detail.php?id={$row['id_kelas']}'>Keterangan</a> |
                                    <a href='hapus_data_kelas.php?id={$row['id_kelas']}' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Tidak ada data</td></tr>";
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