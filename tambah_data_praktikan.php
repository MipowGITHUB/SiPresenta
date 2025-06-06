<?php
    include 'connection.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama = $_POST['nama'];
        $nim = $_POST['nim'];

        // Query insert data
        $sql = "INSERT INTO praktikan (nama, nim) VALUES ('$nama', '$nim')";

        if ($conn->query($sql) === TRUE) {
            header("Location: lihat_data_praktikan.php"); // Redirect ke halaman utama
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
?>
