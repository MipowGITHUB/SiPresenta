<?php
    include 'connection.php';

    if (isset($_GET['id']) && isset($_GET['id2'])) { // Pastikan kedua parameter tersedia
        $id = $_GET['id'];  // ID kelas asisten
        $id2 = $_GET['id2']; // ID asisten

        // Hapus data kelas asisten berdasarkan id_kelas_asisten
        $stmt = $conn->prepare("DELETE FROM kelas_asisten WHERE id_kelas_asisten = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            // Redirect ke halaman dengan ID asisten yang benar
            header("Location: lihat_data_kelas_asisten.php?id={$id2}");
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
