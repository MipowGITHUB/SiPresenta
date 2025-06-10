<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kehadiran Praktikan</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #1A3A63 0%, #0C233B 100%);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow-y: auto;
        }

        .container {
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 500px;
            max-width: 90%;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .container:hover {
            transform: scale(1.02);
        }

        h2 {
            margin-bottom: 25px;
            font-size: 1.8em;
            color: #e0e0e0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .praktikan-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .praktikan-card p {
            margin: 10px 0;
            font-size: 1.1em;
            color: #d1d1d1;
        }

        .praktikan-card span {
            font-weight: bold;
            color: #6DC8FF;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 10px;
            color: #b0c4de;
        }

        select {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.9);
            color: #1A3A63;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        select:hover {
            background: rgba(255, 255, 255, 1);
        }

        .button-group {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        button {
            flex: 1;
            padding: 12px;
            background: linear-gradient(90deg, #4CAF50, #45a049);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 600;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        button:hover {
            background: linear-gradient(90deg, #45a049, #3d8b40);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
        }

        .back-btn {
            flex: 1;
            padding: 12px;
            background: transparent;
            border: 2px solid #4CAF50;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            font-size: 1.1em;
            font-weight: 600;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: rgba(76, 175, 80, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
        }

        .error {
            color: #F44336;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="praktikan-card">
            <p><span>Nama:</span> <?php echo htmlspecialchars($_GET['nama'] ?? 'Praktikan'); ?></p>
            <p><span>NIM:</span> <?php echo htmlspecialchars($_GET['nim'] ?? 'N/A'); ?></p>
        </div>
        <?php
        include 'connection.php';
        $id_praktikan = $_GET['id_praktikan'] ?? '';
        if (!$id_praktikan) {
            echo "<p class='error'>Error: ID Praktikan tidak ditemukan.</p>";
        } else {
            $sql = "SELECT DISTINCT k.id_kelas, k.matkul, k.kelas FROM kelas k JOIN kelas_praktikan kp ON k.id_kelas = kp.id_kelas WHERE kp.id_praktikan = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                echo "<p class='error'>Error: " . $conn->error . "</p>";
            } else {
                $stmt->bind_param("i", $id_praktikan);
                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
        ?>
                        <form method="GET" action="data_detail_kehadiran_praktikan.php">
                            <div class="form-group">
                                <label for="kelas">Pilih Kelas:</label>
                                <select name="kelas" id="kelas" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='{$row['id_kelas']}'>{$row['matkul']} - {$row['kelas']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <input type="hidden" name="id_praktikan" value="<?php echo htmlspecialchars($id_praktikan); ?>">
                            <input type="hidden" name="nama" value="<?php echo htmlspecialchars($_GET['nama'] ?? ''); ?>">
                            <input type="hidden" name="nim" value="<?php echo htmlspecialchars($_GET['nim'] ?? ''); ?>">
                            <div class="button-group">
                                <button type="submit">Lihat Kehadiran</button>
                                <a href="dashboard.php" class="back-btn">Kembali ke Dashboard</a>
                            </div>
                        </form>
        <?php
                    } else {
                        echo "<p class='error'>Tidak ada kelas yang tersedia untuk praktikan ini.</p>";
                    }
                } else {
                    echo "<p class='error'>Error executing query: " . $stmt->error . "</p>";
                }
                $stmt->close();
            }
            $conn->close();
        }
        ?>
    </div>
</body>

</html>