<?php
    include 'connection.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama = $_POST['nama'];
        $nim = $_POST['nim'];
        $uid = $_POST['uid'];

        $sql = "INSERT INTO praktikan (nama, nim, uid) VALUES ('$nama', '$nim', '$uid')";

        if ($conn->query($sql) === TRUE) {
            header("Location: lihat_data_praktikan.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
?>
