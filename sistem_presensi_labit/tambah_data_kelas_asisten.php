<?php
    include 'connection.php'; // Pastikan koneksi database

    // Pastikan request berasal dari form
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Tangkap data dari form
        $id_asisten = $_POST['id_asisten'];
        $id_kelas = $_POST['id_kelas'];

        // Periksa apakah data sudah ada di kelas_asisten (hindari duplikasi)
        $check_sql = "SELECT * FROM kelas_asisten WHERE id_asisten = ? AND id_kelas = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ii", $id_asisten, $id_kelas);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Data sudah ada!";
        } else {
            $sql = "INSERT INTO kelas_asisten (id_asisten, id_kelas) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $id_asisten, $id_kelas);

            if ($stmt->execute()) {
                header("Location: lihat_data_kelas_asisten.php?id=$id_asisten"); // Redirect kembali
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    } else {
        echo "Metode tidak valid!";
    }

    $conn->close();
?>