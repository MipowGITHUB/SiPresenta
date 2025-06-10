-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 21, 2025 at 09:46 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wiraydmy_praktikumdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `asisten`
--

CREATE TABLE `asisten` (
  `id_asisten` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `nim` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `asisten`
--

INSERT INTO `asisten` (`id_asisten`, `nama`, `nim`) VALUES
(18, 'Wira Yudha Aji Pratama', '2023103703110110'),
(19, 'Moh. Khairul Umam', '202310370311448'),
(20, 'Ken Aryo Bimantoro', '202310370311006'),
(21, 'Muhammad Zam Zam Baihaqi', '202310370311011009'),
(22, 'Wahyu Andhika', '202310370311087'),
(23, 'M. Ramadhan Titan D. C.', '202310370311086'),
(24, 'M. Ega Faiz F.', '202310370311042'),
(25, 'Nisrina Nurhafidhah', '202310370311321');

-- --------------------------------------------------------

--
-- Table structure for table `kehadiran_asisten`
--

CREATE TABLE `kehadiran_asisten` (
  `id_kehadiran_asisten` int(11) NOT NULL,
  `id_asisten` int(11) NOT NULL,
  `id_pertemuan` int(11) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `waktu_masuk` time DEFAULT NULL,
  `waktu_keluar` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kehadiran_praktikan`
--

CREATE TABLE `kehadiran_praktikan` (
  `id_kehadiran_praktikan` int(11) NOT NULL,
  `id_praktikan` int(11) NOT NULL,
  `id_pertemuan` int(11) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `waktu_masuk` time DEFAULT NULL,
  `waktu_keluar` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kehadiran_praktikum`
--

CREATE TABLE `kehadiran_praktikum` (
  `id_kehadiran_praktikum` int(11) NOT NULL,
  `id_kehadiran_asisten` int(11) NOT NULL,
  `id_kehadiran_praktikan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(11) NOT NULL,
  `matkul` varchar(50) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `lab` varchar(10) NOT NULL,
  `hari` varchar(10) NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `matkul`, `kelas`, `lab`, `hari`, `waktu_mulai`, `waktu_selesai`) VALUES
(10, 'Basis Data', 'B', 'A/B', 'Selasa', '13:30:00', '14:30:00'),
(11, 'Pemrograman Berorientasikan Obyek', 'K', 'A/B', 'Selasa', '14:14:00', '10:14:00'),
(12, 'Pemrograman Berorientasikan Obyek', 'J', 'C/D', 'Rabu', '10:14:00', '13:17:00'),
(13, 'Pemrograman Berorientasikan Obyek', 'E', 'A/B', 'Kamis', '08:30:00', '09:30:00'),
(14, 'Basis Data', 'C', 'C/D', 'Jum\'at', '14:48:00', '14:48:00'),
(15, 'Sistem Operasi', 'A', 'C/D', '', '15:18:00', '15:19:00'),
(16, 'Sistem Operasi', 'B', 'A/B', '', '16:00:00', '17:00:00'),
(17, 'Sistem Operasi', 'C', 'A/B', 'Rabu', '15:20:00', '16:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `kelas_asisten`
--

CREATE TABLE `kelas_asisten` (
  `id_kelas_asisten` int(11) NOT NULL,
  `id_asisten` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas_asisten`
--

INSERT INTO `kelas_asisten` (`id_kelas_asisten`, `id_asisten`, `id_kelas`) VALUES
(14, 22, 10),
(17, 19, 11),
(18, 24, 12),
(20, 24, 13),
(22, 19, 10),
(23, 20, 10),
(24, 21, 10),
(25, 18, 11);

-- --------------------------------------------------------

--
-- Table structure for table `kelas_praktikan`
--

CREATE TABLE `kelas_praktikan` (
  `id_kelas_praktikan` int(11) NOT NULL,
  `id_praktikan` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas_praktikan`
--

INSERT INTO `kelas_praktikan` (`id_kelas_praktikan`, `id_praktikan`, `id_kelas`) VALUES
(8, 10, 13),
(9, 7, 10),
(10, 8, 10),
(11, 9, 10),
(12, 10, 10);

-- --------------------------------------------------------

--
-- Table structure for table `pertemuan`
--

CREATE TABLE `pertemuan` (
  `id_pertemuan` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `pertemuan_ke` int(11) NOT NULL,
  `modul` int(11) NOT NULL,
  `kegiatan` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `praktikan`
--

CREATE TABLE `praktikan` (
  `id_praktikan` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `nim` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `praktikan`
--

INSERT INTO `praktikan` (`id_praktikan`, `nama`, `nim`) VALUES
(7, 'Nayla', '045'),
(8, 'Fandi', '047'),
(9, 'Ahmad', '076'),
(10, 'Althof', '023');

-- --------------------------------------------------------

--
-- Table structure for table `praktikum`
--

CREATE TABLE `praktikum` (
  `id_praktikum` int(11) NOT NULL,
  `id_kelas_asisten` int(11) NOT NULL,
  `id_kelas_praktikan` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `asisten`
--
ALTER TABLE `asisten`
  ADD PRIMARY KEY (`id_asisten`);

--
-- Indexes for table `kehadiran_asisten`
--
ALTER TABLE `kehadiran_asisten`
  ADD PRIMARY KEY (`id_kehadiran_asisten`),
  ADD KEY `id_asisten` (`id_asisten`),
  ADD KEY `id_pertemuan` (`id_pertemuan`);

--
-- Indexes for table `kehadiran_praktikan`
--
ALTER TABLE `kehadiran_praktikan`
  ADD PRIMARY KEY (`id_kehadiran_praktikan`),
  ADD KEY `id_praktikan` (`id_praktikan`),
  ADD KEY `id_pertemuan` (`id_pertemuan`);

--
-- Indexes for table `kehadiran_praktikum`
--
ALTER TABLE `kehadiran_praktikum`
  ADD PRIMARY KEY (`id_kehadiran_praktikum`),
  ADD KEY `id_kehadiran_asisten` (`id_kehadiran_asisten`),
  ADD KEY `id_kehadiran_praktikan` (`id_kehadiran_praktikan`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indexes for table `kelas_asisten`
--
ALTER TABLE `kelas_asisten`
  ADD PRIMARY KEY (`id_kelas_asisten`),
  ADD KEY `id_asisten` (`id_asisten`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- Indexes for table `kelas_praktikan`
--
ALTER TABLE `kelas_praktikan`
  ADD PRIMARY KEY (`id_kelas_praktikan`),
  ADD KEY `id_praktikan` (`id_praktikan`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- Indexes for table `pertemuan`
--
ALTER TABLE `pertemuan`
  ADD PRIMARY KEY (`id_pertemuan`),
  ADD KEY `id_praktikum` (`id_kelas`);

--
-- Indexes for table `praktikan`
--
ALTER TABLE `praktikan`
  ADD PRIMARY KEY (`id_praktikan`);

--
-- Indexes for table `praktikum`
--
ALTER TABLE `praktikum`
  ADD PRIMARY KEY (`id_praktikum`),
  ADD KEY `id_kelas_asisten` (`id_kelas_asisten`),
  ADD KEY `id_kelas_praktikan` (`id_kelas_praktikan`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `asisten`
--
ALTER TABLE `asisten`
  MODIFY `id_asisten` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `kehadiran_asisten`
--
ALTER TABLE `kehadiran_asisten`
  MODIFY `id_kehadiran_asisten` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kehadiran_praktikan`
--
ALTER TABLE `kehadiran_praktikan`
  MODIFY `id_kehadiran_praktikan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kehadiran_praktikum`
--
ALTER TABLE `kehadiran_praktikum`
  MODIFY `id_kehadiran_praktikum` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `kelas_asisten`
--
ALTER TABLE `kelas_asisten`
  MODIFY `id_kelas_asisten` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `kelas_praktikan`
--
ALTER TABLE `kelas_praktikan`
  MODIFY `id_kelas_praktikan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pertemuan`
--
ALTER TABLE `pertemuan`
  MODIFY `id_pertemuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `praktikan`
--
ALTER TABLE `praktikan`
  MODIFY `id_praktikan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `praktikum`
--
ALTER TABLE `praktikum`
  MODIFY `id_praktikum` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kehadiran_asisten`
--
ALTER TABLE `kehadiran_asisten`
  ADD CONSTRAINT `kehadiran_asisten_ibfk_1` FOREIGN KEY (`id_asisten`) REFERENCES `asisten` (`id_asisten`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kehadiran_asisten_ibfk_2` FOREIGN KEY (`id_pertemuan`) REFERENCES `pertemuan` (`id_pertemuan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kehadiran_praktikan`
--
ALTER TABLE `kehadiran_praktikan`
  ADD CONSTRAINT `kehadiran_praktikan_ibfk_1` FOREIGN KEY (`id_praktikan`) REFERENCES `praktikan` (`id_praktikan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kehadiran_praktikan_ibfk_2` FOREIGN KEY (`id_pertemuan`) REFERENCES `pertemuan` (`id_pertemuan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kehadiran_praktikum`
--
ALTER TABLE `kehadiran_praktikum`
  ADD CONSTRAINT `kehadiran_praktikum_ibfk_1` FOREIGN KEY (`id_kehadiran_asisten`) REFERENCES `kehadiran_asisten` (`id_kehadiran_asisten`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kehadiran_praktikum_ibfk_2` FOREIGN KEY (`id_kehadiran_praktikan`) REFERENCES `kehadiran_praktikan` (`id_kehadiran_praktikan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kelas_asisten`
--
ALTER TABLE `kelas_asisten`
  ADD CONSTRAINT `kelas_asisten_ibfk_1` FOREIGN KEY (`id_asisten`) REFERENCES `asisten` (`id_asisten`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kelas_asisten_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kelas_praktikan`
--
ALTER TABLE `kelas_praktikan`
  ADD CONSTRAINT `kelas_praktikan_ibfk_1` FOREIGN KEY (`id_praktikan`) REFERENCES `praktikan` (`id_praktikan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kelas_praktikan_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pertemuan`
--
ALTER TABLE `pertemuan`
  ADD CONSTRAINT `pertemuan_kelas_fk` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `praktikum`
--
ALTER TABLE `praktikum`
  ADD CONSTRAINT `praktikum_ibfk_1` FOREIGN KEY (`id_kelas_asisten`) REFERENCES `kelas_asisten` (`id_kelas_asisten`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `praktikum_ibfk_2` FOREIGN KEY (`id_kelas_praktikan`) REFERENCES `kelas_praktikan` (`id_kelas_praktikan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `praktikum_ibfk_3` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
