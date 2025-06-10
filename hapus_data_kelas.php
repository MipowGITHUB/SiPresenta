<?php
    include 'connection.php';

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Hapus data terkait di tabel kelas_asisten terlebih dahulu
        $stmt1 = $conn->prepare("DELETE FROM kelas_asisten WHERE id_kelas = ?");
        $stmt1->bind_param("i", $id);
        $stmt1->execute();
        $stmt1->close();
        
        // Hapus data terkait di tabel kelas_asisten terlebih dahulu
        $stmt2 = $conn->prepare("DELETE FROM kelas_praktikan WHERE id_kelas = ?");
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        $stmt2->close();

        // Hapus data asisten setelah data terkait dihapus
        $stmt3 = $conn->prepare("DELETE FROM kelas WHERE id_kelas = ?");
        $stmt3->bind_param("i", $id);

        if ($stmt3->execute()) {
            $stmt3->close();
            $conn->close();
            header("Location: lihat_data_kelas.php"); // Redirect setelah sukses
            exit();
        } else {
            echo "Error: " . $stmt3->error;
        }

        $stmt3->close();
    }

    $conn->close();
?>
