<?php
include 'connection.php';

header('Content-Type: application/json');

$sql = "
    SELECT p.nim 
    FROM rekap_praktikan r
    JOIN praktikan p ON r.id_praktikan = p.id_praktikan
    ORDER BY r.waktu_checkin DESC 
    LIMIT 1
";

$result = $conn->query($sql);

if ($result && $row = $result->fetch_assoc()) {
    echo json_encode(['nim' => $row['nim']]);
} else {
    echo json_encode(['nim' => null]);
}

$conn->close();
?>
