<?php
include 'connection.php';

$nama = $_POST['nama'];
$nim = $_POST['nim'];
$nokartu = $_POST['nokartu'];

$sql = "INSERT INTO asisten (nama, nim, nokartu) VALUES ('$nama', '$nim', '$nokartu')";
if ($conn->query($sql) === TRUE) {
    $conn->query("DELETE FROM tmprfid WHERE nokartu = '$nokartu'");
    
    header("Location: lihat_data_asisten.php?success=1");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>