<?php
include 'connection.php';

$sql = "SELECT ka.*, a.nama, a.nim, a.nokartu, p.tanggal, k.matkul, k.kelas
        FROM kehadiran_asisten ka 
        LEFT JOIN asisten a ON ka.id_asisten = a.id_asisten 
        LEFT JOIN pertemuan p ON ka.id_pertemuan = p.id_pertemuan
        LEFT JOIN kelas k ON p.id_kelas = k.id_kelas
        ORDER BY ka.waktu_masuk DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Data Kehadiran Asisten</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1A3A63 0%, #0C233B 100%);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 50px 20px;
            min-height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            background-color: rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
            color: white;
            font-size: 28px;
        }

        .info-box {
            background-color: rgba(33, 150, 243, 0.2);
            border: 1px solid #2196F3;
            color: #2196F3;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-box {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #4CAF50;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9em;
            opacity: 0.8;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            padding: 15px;
            text-align: center;
        }

        th {
            background-color: rgba(0, 0, 0, 0.4);
            color: white;
            font-weight: bold;
        }

        td {
            background-color: rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .waktu-col {
            font-size: 0.9em;
        }

        .nama-col {
            font-weight: bold;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: rgba(255, 255, 255, 0.6);
            font-style: italic;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 25px;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn:hover {
            background-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }

        .btn-primary {
            background-color: #4CAF50;
        }

        .btn-primary:hover {
            background-color: #45a049;
        }

        @media screen and (max-width: 768px) {
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .btn-container {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Data Kehadiran Asisten</h1>

        <?php
        $total_kehadiran = $result->num_rows;

        $today = date('Y-m-d');
        $sql_today = "SELECT COUNT(*) as today_count 
                     FROM kehadiran_asisten ka
                     LEFT JOIN pertemuan p ON ka.id_pertemuan = p.id_pertemuan  
                     WHERE p.tanggal = '$today'";
        $result_today = $conn->query($sql_today);
        $today_count = $result_today->fetch_assoc()['today_count'];

        $sql_week = "SELECT COUNT(*) as week_count 
                    FROM kehadiran_asisten ka
                    LEFT JOIN pertemuan p ON ka.id_pertemuan = p.id_pertemuan
                    WHERE YEARWEEK(p.tanggal) = YEARWEEK(NOW())";
        $result_week = $conn->query($sql_week);
        $week_count = $result_week->fetch_assoc()['week_count'];
        ?>

        <div class="info-box">
            Sistem pencatatan kehadiran asisten berdasarkan scan kartu RFID
        </div>

        <div class="stats-container">
            <div class="stat-box">
                <div class="stat-number"><?php echo $total_kehadiran; ?></div>
                <div class="stat-label">Total Kehadiran</div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><?php echo $today_count; ?></div>
                <div class="stat-label">Hari Ini</div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><?php echo $week_count; ?></div>
                <div class="stat-label">Minggu Ini</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>No Kartu</th>
                    <th>Kelas</th>
                    <th>Tanggal</th>
                    <th>Waktu Masuk</th>
                    <th>Waktu Keluar</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($total_kehadiran > 0) {
                    $result = $conn->query("SELECT ka.*, a.nama, a.nim, a.nokartu, p.tanggal, k.matkul, k.kelas
                                          FROM kehadiran_asisten ka 
                                          LEFT JOIN asisten a ON ka.id_asisten = a.id_asisten 
                                          LEFT JOIN pertemuan p ON ka.id_pertemuan = p.id_pertemuan
                                          LEFT JOIN kelas k ON p.id_kelas = k.id_kelas
                                          ORDER BY ka.waktu_masuk DESC");

                    while ($row = $result->fetch_assoc()) {
                        $tanggal = $row['tanggal'] ? date('d/m/Y', strtotime($row['tanggal'])) : '-';
                        $waktu_masuk = $row['waktu_masuk'] ? date('H:i:s', strtotime($row['waktu_masuk'])) : '-';
                        $waktu_keluar = $row['waktu_keluar'] ?
                            date('H:i:s', strtotime($row['waktu_keluar'])) :
                            '<span style="color: #FFC107;">Belum Keluar</span>';

                        $nama = $row['nama'] ?? 'Unknown';
                        $nim = $row['nim'] ?? '-';
                        $nokartu = $row['nokartu'] ?? '-';
                        $kelas_info = $row['matkul'] && $row['kelas'] ? $row['matkul'] . ' ' . $row['kelas'] : '-';
                        $keterangan = $row['keterangan'] ?? '-';

                        echo "<tr>
                                <td>{$row['id_kehadiran_asisten']}</td>
                                <td class='nama-col'>{$nama}</td>
                                <td>{$nim}</td>
                                <td>{$nokartu}</td>
                                <td>{$kelas_info}</td>
                                <td>{$tanggal}</td>
                                <td class='waktu-col'>{$waktu_masuk}</td>
                                <td class='waktu-col'>{$waktu_keluar}</td>
                                <td>{$keterangan}</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9' class='no-data'>
                            Belum ada data kehadiran asisten<br>
                            <small>Data akan muncul setelah asisten melakukan scan kartu RFID</small>
                          </td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="btn-container">
            <a href="lihat_data_asisten.php" class="btn btn-primary">Data Asisten</a>
            <a href="dashboard.php" class="btn">Dashboard</a>
        </div>
    </div>
</body>

</html>

<?php
$conn->close();
?>