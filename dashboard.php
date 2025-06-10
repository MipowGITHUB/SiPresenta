<?php
if (isset($_GET['cek_kelas'])) {
    include 'connection.php';

    $response = ['presensi' => [], 'checkout' => []];

    $sqlPresensi = "SELECT matkul, kelas FROM kelas WHERE status_presensi = 1";
    $resultPresensi = $conn->query($sqlPresensi);

    if ($resultPresensi && $resultPresensi->num_rows > 0) {
        while ($row = $resultPresensi->fetch_assoc()) {
            $response['presensi'][] = $row['matkul'] . ' ' . $row['kelas'];
        }
    }

    $sqlCheckout = "SELECT matkul, kelas FROM kelas WHERE status_checkout = 1";
    $resultCheckout = $conn->query($sqlCheckout);

    if ($resultCheckout && $resultCheckout->num_rows > 0) {
        while ($row = $resultCheckout->fetch_assoc()) {
            $response['checkout'][] = $row['matkul'] . ' ' . $row['kelas'];
        }
    }

    $conn->close();
    echo json_encode($response);
    exit;
}

// Handle RFID Auto Attendance - Redirect ke existing flow
if (isset($_GET['auto_attendance'])) {
    include 'connection.php';

    $sql = "SELECT nokartu FROM tmprfid ORDER BY nokartu DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $uid = $row['nokartu'];

        $sql = "SELECT id_asisten, nama FROM asisten WHERE nokartu = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $uid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $asisten = $result->fetch_assoc();
            $conn->query("DELETE FROM tmprfid WHERE nokartu = '$uid'");

            echo json_encode([
                'status' => 'redirect',
                'id_asisten' => $asisten['id_asisten'],
                'nama' => $asisten['nama'],
                'uid' => $uid
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Kartu belum terdaftar: ' . $uid
            ]);
        }
    } else {
        echo json_encode(['status' => 'no_card']);
    }

    $conn->close();
    exit;
}

// Handle Fingerprint Auto Attendance - Direct from rekap_praktikan
if (isset($_GET['auto_fingerprint'])) {
    include 'connection.php';

    // 1. Ambil data fingerprint terakhir (ambil id_praktikan terakhir)
    $sqlGetLastFingerprint = "SELECT id_praktikan, GREATEST(COALESCE(waktu_checkin, '1970-01-01'), COALESCE(waktu_checkout, '1970-01-01')) AS waktu_terakhir
FROM rekap_praktikan
ORDER BY waktu_terakhir DESC
LIMIT 1
";
    $result = $conn->query($sqlGetLastFingerprint);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_praktikan = $row['id_praktikan'];

        // 2. Query data praktikan dan kelas berdasarkan id_praktikan
        $sql = "SELECT p.nama, p.nim, k.kelas, k.matkul
                FROM praktikan p
                JOIN kelas_praktikan kp ON p.id_praktikan = kp.id_praktikan
                JOIN kelas k ON kp.id_kelas = k.id_kelas
                WHERE p.id_praktikan = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id_praktikan);
        $stmt->execute();
        $result2 = $stmt->get_result();

        if ($result2 && $result2->num_rows > 0) {
            $data = $result2->fetch_assoc();
            echo json_encode([
                'status' => 'success',
                'nama' => $data['nama'],
                'nim' => $data['nim'],
                'matkul' => $data['matkul'],
                'kelas' => $data['kelas']
            ]);
        } else {
            echo json_encode(['status' => 'no_data']);
        }

    } else {
        echo json_encode(['status' => 'no_fingerprint']);
    }

    $conn->close();
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="60">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 50px;
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #1A3A63 0%, #0C233B 100%);
            color: white;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 800px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            background-color: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            text-align: center;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .btn-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        #clock {
            font-size: 24px;
            font-weight: bold;
            margin: 25px 0;
            color: #f5f5f5;
            background-color: rgba(0, 0, 0, 0.2);
            padding: 10px 20px;
            border-radius: 8px;
        }

        .info-box {
            margin-top: 20px;
            width: 100%;
            padding: 15px;
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        .info-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
        }

        form {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
            width: 100%;
        }

        input {
            padding: 10px;
            border-radius: 4px;
            border: none;
            background-color: rgba(255, 255, 255, 0.8);
            margin: 5px 0;
            width: 200px;
        }

        button {
            padding: 10px 15px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

        h1 {
            color: #f5f5f5;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
            width: 100%;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #f5f5f5;
        }

        /* RFID Section Styling */
        .rfid-section {
            background-color: rgba(76, 175, 80, 0.1);
            border: 2px solid #4CAF50;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }

        /* Fingerprint Section Styling - Konsisten dengan RFID */
        .fingerprint-section {
            background-color: rgba(33, 150, 243, 0.1);
            border: 2px solid #2196F3;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }

        .rfid-status,
        .fingerprint-status {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .rfid-result,
        .fingerprint-result {
            background-color: rgba(0, 0, 0, 0.2);
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            min-height: 60px;
            text-align: left;
        }

        .scanning {
            color: #FFC107;
        }

        .success {
            color: #4CAF50;
        }

        .error {
            color: #F44336;
        }

        /* Section Headers */
        .rfid-section h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: #4CAF50;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .fingerprint-section h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: #2196F3;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        @media (min-width: 768px) {
            .info-container {
                flex-direction: row;
            }

            .form-container form {
                flex: 1;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Dashboard</h1>

        <div id="clock"></div>

        <div class="btn-container">
            <a href="lihat_data_asisten.php" class="btn">Data Asisten</a>
            <a href="lihat_data_kelas.php" class="btn">Data Kelas</a>
            <a href="lihat_data_praktikan.php" class="btn">Data Praktikan</a>
        </div>

        <!-- RFID Auto Attendance Section -->
        <div class="rfid-section">
            <h3>RFID Auto Check-in/Check-out</h3>
            <div class="rfid-status scanning" id="rfid-status">
                Menunggu scan kartu RFID...
            </div>
            <div class="rfid-result" id="rfid-result">
                Scan kartu RFID untuk otomatis check-in/check-out.
            </div>
        </div>

        <!-- Fingerprint Auto Attendance Section -->
        <div class="fingerprint-section">
            <h3>Fingerprint Auto Check-in/Check-out</h3>
            <div class="fingerprint-status scanning" id="fingerprint-status">
                Menunggu scan jari anda...
            </div>
            <div class="fingerprint-result" id="fingerprint-result">
                Scan jari anda untuk otomatis check-in/check-out.
            </div>
        </div>

        <div class="info-container">
            <div class="info-box" id="info-box-presensi">Tidak ada kelas yang aktif saat ini.</div>
            <div class="info-box" id="info-box-checkout">Tidak ada kelas yang membuka checkout saat ini.</div>
        </div>

        <div class="btn-container">
            <a href="index.html" class="btn">Kembali ke Login</a>
        </div>
    </div>

    <script>
        function updateClock() {
            let now = new Date();
            let year = now.getFullYear();
            let month = String(now.getMonth() + 1).padStart(2, '0');
            let day = String(now.getDate()).padStart(2, '0');
            let hours = String(now.getHours()).padStart(2, '0');
            let minutes = String(now.getMinutes()).padStart(2, '0');
            let seconds = String(now.getSeconds()).padStart(2, '0');

            let currentTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
            document.getElementById("clock").innerText = currentTime;
        }

        setInterval(updateClock, 1000);
        updateClock();

        function checkClasses() {
            fetch(window.location.href + '?cek_kelas=true')
                .then(response => response.json())
                .then(data => {
                    const presensiBox = document.getElementById('info-box-presensi');
                    const checkoutBox = document.getElementById('info-box-checkout');

                    if (presensiBox) {
                        if (data.presensi.length > 0) {
                            presensiBox.innerHTML = `Checkin telah dibuka untuk kelas:<ul>${data.presensi.map(k => `<li>${k}</li>`).join('')}</ul>`;
                        } else {
                            presensiBox.innerHTML = "Tidak ada kelas yang membuka checkin saat ini.";
                        }
                    }

                    if (checkoutBox) {
                        if (data.checkout.length > 0) {
                            checkoutBox.innerHTML = `Checkout telah dibuka untuk kelas:<ul>${data.checkout.map(k => `<li>${k}</li>`).join('')}</ul>`;
                        } else {
                            checkoutBox.innerHTML = "Tidak ada kelas yang membuka checkout saat ini.";
                        }
                    }
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        function checkAutoAttendance() {
            fetch(window.location.href + '?auto_attendance=true')
                .then(response => response.json())
                .then(data => {
                    const statusElement = document.getElementById('rfid-status');
                    const resultElement = document.getElementById('rfid-result');

                    if (data.status === 'redirect') {
                        statusElement.className = 'rfid-status success';
                        statusElement.innerHTML = `Kartu terdeteksi: ${data.uid}`;

                        resultElement.innerHTML = `
                            <strong>AUTO SUBMIT</strong><br>
                            Asisten: ${data.nama}<br>
                            ID: ${data.id_asisten}<br>
                            <small>Submitting to existing system...</small>
                        `;

                        setTimeout(() => {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = 'tambah_data_pertemuan_asisten.php';
                            form.style.display = 'none';

                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'rfid';
                            input.value = data.id_asisten;

                            form.appendChild(input);
                            document.body.appendChild(form);
                            form.submit();
                        }, 1500);

                    } else if (data.status === 'error') {
                        statusElement.className = 'rfid-status error';
                        statusElement.innerHTML = `Error: ${data.message}`;

                        resultElement.innerHTML = `
                            <strong>Error:</strong> ${data.message}<br>
                            <small>Periksa registrasi kartu di menu "Lihat Data Asisten"</small>
                        `;

                        setTimeout(() => {
                            statusElement.className = 'rfid-status scanning';
                            statusElement.innerHTML = 'Menunggu scan kartu RFID...';
                            resultElement.innerHTML = 'Scan kartu RFID untuk otomatis check-in/check-out.';
                        }, 5000);

                    } else {
                        statusElement.className = 'rfid-status scanning';
                        statusElement.innerHTML = 'Menunggu scan kartu RFID...';
                    }
                })
                .catch(error => {
                    console.error('Error in auto attendance:', error);
                    const statusElement = document.getElementById('rfid-status');
                    statusElement.className = 'rfid-status scanning';
                    statusElement.innerHTML = 'Menunggu scan kartu RFID...';
                });
        }

        // Fingerprint Auto Check function (placeholder for future implementation)

        function checkFingerprint() {
            fetch(window.location.href + '?auto_fingerprint=true')
                .then(res => res.json())
                .then(data => {
                    const statusElem = document.getElementById('fingerprint-status');
                    const resultElem = document.getElementById('fingerprint-result');
                    if (!statusElem || !resultElem) return;

                    if (data.status === 'success') {
                        statusElem.className = 'fingerprint-status success';
                        statusElem.textContent = 'Fingerprint terdeteksi.';
                        resultElem.innerHTML = `Nama: ${data.nama}<br>NIM: ${data.nim}<br>Matkul: ${data.matkul}<br>Kelas: ${data.kelas}`;
                    } else {
                        statusElem.className = 'fingerprint-status error';
                        statusElem.textContent = 'Terjadi kesalahan: ' + (data.message || 'Data tidak ditemukan');
                        resultElem.textContent = '';
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    const statusElem = document.getElementById('fingerprint-status');
                    if (statusElem) {
                        statusElem.className = 'fingerprint-status error';
                        statusElem.textContent = 'Gagal mengambil data fingerprint.';
                    }
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            checkFingerprint(); // Panggil setelah halaman siap
        });


        setInterval(checkAutoAttendance, 2000);
        setInterval(checkFingerprint, 2000);
        setInterval(checkClasses, 5000);
        checkClasses();
    </script>
</body>

</html>