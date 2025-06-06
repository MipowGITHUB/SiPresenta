<?php
    // Import koneksi database
    include 'connection.php';

    // Query untuk mengambil data
    $sql = "SELECT * FROM asisten";
    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Asisten</title>
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

        .success-message {
            background-color: rgba(76, 175, 80, 0.2);
            border: 1px solid #4CAF50;
            color: #4CAF50;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        
        form {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }
        
        .form-group {
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
        
        .action-links a {
            color: #6DC8FF;
            text-decoration: none;
            margin: 0 5px;
            padding: 3px 8px;
            border-radius: 3px;
            transition: background-color 0.3s;
        }
        
        .action-links a:hover {
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
        <h1>Lihat Data Asisten</h1>
        
        <?php if (isset($_GET['success'])): ?>
        <div class="success-message">
            Data asisten berhasil ditambahkan!
        </div>
        <?php endif; ?>

        <!-- Tempat menyimpan data dari RFID -->
        <div id="norfid" style="display:none;"></div>

        <form action="tambah_data_asisten.php" method="post">
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" id="nama" name="nama" required>
            </div>
            <div class="form-group">
                <label for="nim">NIM:</label>
                <input type="text" id="nim" name="nim" required>
            </div>
            <div class="form-group">
                <label for="nokartu">No. Kartu:</label>
                <input type="text" id="nokartu" name="nokartu" readonly>
            </div>
            <div class="form-group">
                <button type="submit">Tambah</button>
            </div>
        </form>
        
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>No Kartu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id_asisten']}</td>
                                <td>{$row['nama']}</td>
                                <td>{$row['nim']}</td>
                                <td>{$row['nokartu']}</td>
                                <td class='action-links'>
                                    <a href='edit_data_asisten.php?id={$row['id_asisten']}'>Edit</a> | 
                                    <a href='lihat_data_kelas_asisten.php?id={$row['id_asisten']}'>Kelas</a> | 
                                    <a href='lihat_data_kehadiran_asisten.php?id={$row['id_asisten']}'>Kehadiran</a> | 
                                    <a href='hapus_data_asisten.php?id={$row['id_asisten']}' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
        <div class="btn-container">
            <a href="dashboard.php" class="btn">Kembali ke Dashboard</a>
        </div>
    </div>

    <!-- jQuery & Script Pembaca RFID -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function () {
        setInterval(function () {
            $("#norfid").load("nokartu.php", function () {
                const nokartu = $("#norfid").text().trim();

                if (nokartu !== "") {
                    $("#nokartu").val(nokartu);
                }
            });
        }, 1000);

        // Reset form setelah submit berhasil
        <?php if (isset($_GET['success'])): ?>
        // Kosongkan semua field setelah berhasil tambah data
        $("#nama").val('');
        $("#nim").val('');
        $("#nokartu").val('');
        
        // Hilangkan pesan sukses setelah 3 detik
        setTimeout(function() {
            $('.success-message').fadeOut();
        }, 3000);
        <?php endif; ?>
    });
    </script>
</body>
</html>

<?php
    // Tutup koneksi
    $conn->close();
?>