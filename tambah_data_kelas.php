<?php
    include 'connection.php'; //coneksi database

    if ($_SERVER["REQUEST_METHOD"] == "POST") { //tangkap variabel
        $matkul = $_POST['matkul'];
        $kelas = $_POST['kelas'];
        $lab = $_POST['lab'];
        $hari = $_POST['hari'];
        $waktu_mulai = $_POST['waktu_mulai'];
        $waktu_selesai = $_POST['waktu_selesai'];

        // Query insert data
        $sql = "INSERT INTO kelas (matkul, kelas, lab, hari, waktu_mulai, waktu_selesai) VALUES ('$matkul', '$kelas', '$lab', '$hari', '$waktu_mulai', '$waktu_selesai')";
        /*
                nanti cron job nya ditambahin disini
        */

        if ($conn->query($sql) === TRUE) {
            header("Location: lihat_data_kelas.php"); // Redirect ke halaman utama
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
?>
