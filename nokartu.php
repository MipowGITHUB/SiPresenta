<?php
include 'connection.php';

// Ambil data terakhir dari tmprfid
$sql = "SELECT * FROM tmprfid ORDER BY nokartu DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo $row['nokartu'];
} else {
    echo "";
}

$conn->close();
?>
