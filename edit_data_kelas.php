<?php
    include 'connection.php';

    // Cek apakah ada ID yang dikirim
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Ambil data asisten berdasarkan ID
        $stmt = $conn->prepare("SELECT * FROM kelas WHERE id_kelas = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Cek apakah data ditemukan
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            echo "Data tidak ditemukan!";
            exit();
        }
    } else {
        echo "ID tidak ditemukan!";
        exit();
    }

    // Jika tombol Update ditekan
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $matkul = $_POST['matkul'];
        $kelas = $_POST['kelas'];
        $lab = $_POST['lab'];
        $hari = $_POST['hari'];
        $waktu_mulai = $_POST['waktu_mulai'];
        $waktu_selesai = $_POST['waktu_selesai'];

        // Update data di database
        $stmt = $conn->prepare("UPDATE kelas SET matkul = ?, kelas = ?, lab = ?, hari = ?, waktu_mulai = ?, waktu_selesai = ? WHERE id_kelas = ?");
        $stmt->bind_param("ssssssi", $matkul, $kelas, $lab, $hari, $waktu_mulai, $waktu_selesai, $id);

        if ($stmt->execute()) {
            header("Location: lihat_data_kelas.php"); // Redirect ke halaman utama
            exit();
        } else {
            echo "Gagal memperbarui data!";
        }
    }

    $conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Kelas</title>
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
            margin: 0;
            min-height: 100vh;
        }
        h2 {
            color: white;
            margin-bottom: 30px;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
        }
        form {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 8px;
            backdrop-filter: blur(5px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 500px;
            display: flex;
            flex-direction: column;
        }
        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"], 
        input[type="time"], 
        select {
            padding: 10px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            background-color: white;
        }
        select {
            cursor: pointer;
        }
        button[type="submit"] {
            padding: 12px;
            margin-top: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
            font-weight: bold;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
        a {
            margin-top: 20px;
            color: #4fc3f7;
            text-decoration: none;
            font-size: 16px;
            background-color: rgba(255, 255, 255, 0.2);
            padding: 10px 20px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: rgba(255, 255, 255, 0.3);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h2>Edit Data Kelas</h2>
    <form method="post">
        <div class="form-group">
            <label for="matkul">Mata Kuliah:</label>
            <input type="text" id="matkul" name="matkul" value="<?php echo $row['matkul']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="kelas">Kelas:</label>
            <input type="text" id="kelas" name="kelas" value="<?php echo $row['kelas']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="lab">Laboratorium:</label>
            <input type="text" id="lab" name="lab" value="<?php echo $row['lab']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="hari">Hari:</label>
            <select name="hari" id="hari" required>
                <option value="Senin" <?php if($row['hari'] == 'Senin') echo 'selected'; ?>>Senin</option>
                <option value="Selasa" <?php if($row['hari'] == 'Selasa') echo 'selected'; ?>>Selasa</option>
                <option value="Rabu" <?php if($row['hari'] == 'Rabu') echo 'selected'; ?>>Rabu</option>
                <option value="Kamis" <?php if($row['hari'] == 'Kamis') echo 'selected'; ?>>Kamis</option>
                <option value="Jumat" <?php if($row['hari'] == 'Jumat') echo 'selected'; ?>>Jumat</option>
                <option value="Sabtu" <?php if($row['hari'] == 'Sabtu') echo 'selected'; ?>>Sabtu</option>
                <option value="Minggu" <?php if($row['hari'] == 'Minggu') echo 'selected'; ?>>Minggu</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="waktu_mulai">Waktu Mulai:</label>
            <input type="time" id="waktu_mulai" name="waktu_mulai" value="<?php echo $row['waktu_mulai']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="waktu_selesai">Waktu Selesai:</label>
            <input type="time" id="waktu_selesai" name="waktu_selesai" value="<?php echo $row['waktu_selesai']; ?>" required>
        </div>
        
        <button type="submit">Update</button>
    </form>
    <a href="lihat_data_kelas.php">Kembali</a>
</body>
</html>