<?php
    include 'connection.php';

    if (isset($_GET['id']) && isset($_GET['id2'])) { // Pastikan kedua parameter tersedia
        $id = $_GET['id'];  // ID kelas praktikan
        $id2 = $_GET['id2']; // ID praktikan

        // Hapus data kelas praktikan berdasarkan id_kelas_praktikan
        $stmt = $conn->prepare("DELETE FROM kelas_praktikan WHERE id_kelas_praktikan = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            // Redirect ke halaman dengan ID praktikan yang benar
            header("Location: lihat_data_kelas_praktikan.php?id={$id2}");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "ID tidak ditemukan!";
    }

    $conn->close();
?>
