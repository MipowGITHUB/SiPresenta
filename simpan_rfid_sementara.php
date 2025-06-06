
<?php
include 'connection.php';

if (isset($_GET['nokartu'])) {
    $nokartu = $_GET['nokartu'];
    
    // Simpan UID ke tmprfid (overwrite jika ada)
    $sql = "INSERT INTO tmprfid (nokartu) VALUES ('$nokartu') ON DUPLICATE KEY UPDATE nokartu = '$nokartu'";
    
    if ($conn->query($sql) === TRUE) {
        echo "UID tersimpan: " . $nokartu;
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>