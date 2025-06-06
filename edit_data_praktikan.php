<?php
    include 'connection.php';

    // Cek apakah ada ID yang dikirim
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Ambil data praktikan berdasarkan ID
        $stmt = $conn->prepare("SELECT * FROM praktikan WHERE id_praktikan = ?");
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
        $nama = $_POST['nama'];
        $nim = $_POST['nim'];

        // Update data di database
        $stmt = $conn->prepare("UPDATE praktikan SET nama = ?, nim = ? WHERE id_praktikan = ?");
        $stmt->bind_param("ssi", $nama, $nim, $id);

        if ($stmt->execute()) {
            header("Location: lihat_data_praktikan.php"); // Redirect ke halaman utama
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
    <title>Edit Data Praktikan</title>
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
            max-width: 400px;
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
        input[type="text"] {
            padding: 10px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
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
    <h2>Edit Data Praktikan</h2>
    <form method="post">
        <div class="form-group">
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" value="<?php echo $row['nama']; ?>" required>
        </div>
        <div class="form-group">
            <label for="nim">NIM:</label>
            <input type="text" id="nim" name="nim" value="<?php echo $row['nim']; ?>" required>
        </div>
        <button type="submit">Update</button>
    </form>
    <a href="/project_presensi_lab/lihat_data_praktikan.php">Kembali</a>
</body>
</html>