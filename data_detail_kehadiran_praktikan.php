<?php
include 'connection.php';

$id_praktikan = $_GET['id_praktikan'] ?? '';
$id_kelas = $_GET['kelas'] ?? '';
$nama = $_GET['nama'] ?? 'Praktikan';
$nim = $_GET['nim'] ?? 'N/A';

if (!$id_praktikan || !$id_kelas) {
    die("Parameter tidak lengkap.");
}


function getPertemuanType($minggu)
{
    if ($minggu % 2 == 1 && $minggu < 13) {
        return "Teori";
    } elseif ($minggu % 2 == 0 && $minggu < 13) {
        return "Demo";
    } else {
        return "UAP";
    }
}

$sql_pertemuan = "SELECT id_pertemuan, pertemuan_ke, modul, kegiatan FROM pertemuan WHERE id_kelas = ?";
$stmt_pertemuan = $conn->prepare($sql_pertemuan);
$stmt_pertemuan->bind_param("i", $id_kelas);
$stmt_pertemuan->execute();
$result_pertemuan = $stmt_pertemuan->get_result();
$pertemuan_data = $result_pertemuan->fetch_all(MYSQLI_ASSOC) ?? [];

$kehadiran = [];
if (!empty($pertemuan_data)) {
    foreach ($pertemuan_data as $pertemuan) {
        $id_pertemuan = $pertemuan['id_pertemuan'];
        $sql_kehadiran = "SELECT waktu_checkin, waktu_checkout, keterangan FROM rekap_praktikan WHERE id_praktikan = ? AND id_pertemuan = ?";
        $stmt_kehadiran = $conn->prepare($sql_kehadiran);
        $stmt_kehadiran->bind_param("ii", $id_praktikan, $id_pertemuan);
        $stmt_kehadiran->execute();
        $result_kehadiran = $stmt_kehadiran->get_result();
        while ($row = $result_kehadiran->fetch_assoc()) {
            $row['pertemuan_ke'] = $pertemuan['pertemuan_ke'];
            $row['modul'] = $pertemuan['modul'];
            $row['kegiatan'] = $pertemuan['kegiatan'];
            $row['jenis'] = getPertemuanType($pertemuan['pertemuan_ke']); // Tambahkan jenis pertemuan
            $kehadiran[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Kehadiran - <?php echo htmlspecialchars($nama); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1A3A63 0%, #0C233B 100%);
            color: white;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .class-info {
            background: linear-gradient(45deg, #3498db, #2ecc71);
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
        }

        .pertemuan-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .pertemuan-table th,
        .pertemuan-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .pertemuan-table th {
            background-color: rgba(0, 0, 0, 0.3);
            font-weight: bold;
            text-transform: uppercase;
        }

        .pertemuan-table tr {
            background-color: rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .pertemuan-table tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .pertemuan-table tr:hover {
            background-color: rgba(255, 255, 255, 0.15);
            transform: scale(1.01);
        }

        /* Color coding untuk jenis pertemuan */
        .type-teori {
            background-color: rgba(76, 175, 80, 0.6) !important;
            border-left: 4px solid #4CAF50;
        }

        .type-demo {
            background-color: rgba(255, 152, 0, 0.6) !important;
            border-left: 4px solid #FF9800;
        }

        .type-uap {
            background-color: rgba(244, 67, 54, 0.6) !important;
            border-left: 4px solid #f44336;
        }

        .jenis-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-teori {
            background-color: #4CAF50;
            color: white;
        }

        .badge-demo {
            background-color: #FF9800;
            color: white;
        }

        .badge-uap {
            background-color: #f44336;
            color: white;
        }

        .back-section {
            text-align: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            /* Green background for button */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn:hover {
            background-color: #45a049;
            /* Slightly darker green on hover */
            transform: scale(1.05);
            /* Slight zoom effect on hover */
        }

        @media (max-width: 768px) {
            .pertemuan-table {
                font-size: 14px;
            }

            .pertemuan-table th,
            .pertemuan-table td {
                padding: 8px 4px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Riwayat Kehadiran - <?php echo htmlspecialchars($nama); ?></h1>

        <?php
        // Ambil data kelas untuk ditampilkan di class-info
        $sql_kelas = "SELECT matkul, kelas, hari FROM kelas WHERE id_kelas = ?";
        $stmt_kelas = $conn->prepare($sql_kelas);
        $stmt_kelas->bind_param("i", $id_kelas);
        $stmt_kelas->execute();
        $result_kelas = $stmt_kelas->get_result();
        $row_kelas = $result_kelas->fetch_assoc();
        ?>

        <?php if ($row_kelas): ?>
            <div class="class-info">
                <strong><?php echo htmlspecialchars($row_kelas['matkul'] . ' ' . $row_kelas['kelas']); ?></strong> -
                <?php echo htmlspecialchars($row_kelas['hari']); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($kehadiran)): ?>
            <div class="empty-state">
                <h3>Belum Ada Riwayat Kehadiran</h3>
                <p>Praktikan ini belum memiliki riwayat kehadiran untuk kelas ini.</p>
            </div>
        <?php else: ?>
            <table class="pertemuan-table">
                <thead>
                    <tr>
                        <th>Pertemuan Ke</th>
                        <th>Jenis</th>
                        <th>Modul</th>
                        <th>Kegiatan</th>
                        <th>Tanggal Check-in</th>
                        <th>Tanggal Check-out</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kehadiran as $record): ?>
                        <?php
                        $jenis = $record['jenis'];
                        $css_class = '';
                        $badge_class = '';

                        if ($jenis == 'Teori') {
                            $css_class = 'type-teori';
                            $badge_class = 'badge-teori';
                        } elseif ($jenis == 'Demo') {
                            $css_class = 'type-demo';
                            $badge_class = 'badge-demo';
                        } elseif ($jenis == 'UAP') {
                            $css_class = 'type-uap';
                            $badge_class = 'badge-uap';
                        }
                        ?>
                        <tr class="<?php echo $css_class; ?>">
                            <td><?php echo htmlspecialchars($record['pertemuan_ke'] ?? 'N/A'); ?></td>
                            <td><span class="jenis-badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($jenis); ?></span></td>
                            <td><?php echo htmlspecialchars($record['modul'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($record['kegiatan'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($record['waktu_checkin'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($record['waktu_checkout'] ?? 'Belum Checkout'); ?></td>
                            <td><?php echo htmlspecialchars($record['keterangan'] ?? 'N/A'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="back-section">
                <a href="javascript:history.back()" class="btn">Kembali ke Data Praktikan</a>
        </div>
    </div>
</body>

</html>

<?php
$stmt_pertemuan->close();
$stmt_kelas->close();
$conn->close();
?>