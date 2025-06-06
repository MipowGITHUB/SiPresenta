<?php
    include 'connection.php';

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Hapus data terkait di tabel kelas_asisten terlebih dahulu
        $stmt1 = $conn->prepare("DELETE FROM kelas_asisten WHERE id_asisten = ?");
        $stmt1->bind_param("i", $id);
        $stmt1->execute();
        $stmt1->close();

        // Hapus data asisten setelah data terkait dihapus
        $stmt2 = $conn->prepare("DELETE FROM asisten WHERE id_asisten = ?");
        $stmt2->bind_param("i", $id);

        if ($stmt2->execute()) {
            $stmt2->close();
            $conn->close();
            header("Location: lihat_data_asisten.php"); // Redirect setelah sukses
            exit();
        } else {
            echo "Error: " . $stmt2->error;
        }

        $stmt2->close();
    }

    $conn->close();
?>
