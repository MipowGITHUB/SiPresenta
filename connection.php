<?php
    $host = "localhost"; 
    $username = "root"; 
    $password = "admin"; 
    $dbname = "sipresentarfid"; 

    // Buat koneksi
    $conn = new mysqli($host, $username, $password, $dbname);

    // Periksa koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }
?>
