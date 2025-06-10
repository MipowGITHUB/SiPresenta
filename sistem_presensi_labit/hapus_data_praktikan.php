<?php
    include 'connection.php';

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Gunakan prepared statement untuk keamanan
        $stmt = $conn->prepare("DELETE FROM praktikan WHERE id_praktikan = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: lihat_data_praktikan.php"); // Redirect setelah sukses
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
?>
