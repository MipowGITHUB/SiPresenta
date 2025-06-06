<?php
include 'connection.php';

if (isset($_GET['nokartu'])) {
    $nokartu = $_GET['nokartu'];
    $sql = "SELECT * FROM asisten WHERE nokartu = '$nokartu'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $asisten = $result->fetch_assoc();
        echo json_encode([
            'nim' => $asisten['nim'],
            'nama' => $asisten['nama']
        ]);
    } else {
        echo json_encode(null);
    }
}
?>
