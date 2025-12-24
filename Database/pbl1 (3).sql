-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2025 at 06:53 AM
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
-- Database: `pbl1`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_user`
--

CREATE TABLE `admin_user` (
  `nama` varchar(50) NOT NULL DEFAULT 'admin',
  `id_admin` int(11) NOT NULL,
  `password_admin` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_user`
--

INSERT INTO `admin_user` (`nama`, `id_admin`, `password_admin`) VALUES
('admin', 1, 'admin'),
('shabir', 3, '');

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id_chat` int(11) NOT NULL,
  `id_pengirim` int(11) NOT NULL,
  `pesan` text NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id_chat`, `id_pengirim`, `pesan`, `waktu`) VALUES
(1, 0, 'halo', '2025-11-27 03:50:48');

-- --------------------------------------------------------

--
-- Table structure for table `dokumen`
--

CREATE TABLE `dokumen` (
  `id_dokumen` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `warga` varchar(16) NOT NULL,
  `status` enum('pending','setuju','tolak') DEFAULT 'pending',
  `jenis_dokumen` enum('domisili','pengantar rt','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dokumen`
--

INSERT INTO `dokumen` (`id_dokumen`, `tanggal`, `warga`, `status`, `jenis_dokumen`) VALUES
(20, '2025-12-17', '54321', 'tolak', 'domisili'),
(21, '2025-12-17', '54321', 'setuju', 'pengantar rt');

-- --------------------------------------------------------

--
-- Table structure for table `dokumen_warga`
--
-- Error reading structure for table pbl1.dokumen_warga: #1932 - Table &#039;pbl1.dokumen_warga&#039; doesn&#039;t exist in engine
-- Error reading data for table pbl1.dokumen_warga: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `pbl1`.`dokumen_warga`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `dokumen_wargart`
--

CREATE TABLE `dokumen_wargart` (
  `id_dokumen` int(11) NOT NULL,
  `id_warga` varchar(16) NOT NULL,
  `foto_kk` varchar(255) DEFAULT NULL,
  `foto_ktp` varchar(255) DEFAULT NULL,
  `status_verifikasi` enum('pending','diterima','ditolak') DEFAULT 'pending',
  `catatan_penolakan` text DEFAULT NULL,
  `tanggal_upload` date DEFAULT NULL,
  `jenis_dokumen` enum('domisili','pengantar_rt') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dokumen_wargart`
--

INSERT INTO `dokumen_wargart` (`id_dokumen`, `id_warga`, `foto_kk`, `foto_ktp`, `status_verifikasi`, `catatan_penolakan`, `tanggal_upload`, `jenis_dokumen`) VALUES
(5, '12341234', '6fa9ae086eb66fb80ee9484b12f4eb287927cbc7e4f95a799089e6379a8c4471.jpg', 'e916ef543e81da0c653370821b8e59a2e1e8adb2b24e8d34f9f269988ca9c25f.jpg', 'pending', NULL, '2025-12-15', 'pengantar_rt');

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id_laporan` int(11) NOT NULL,
  `nik_pelapor` varchar(20) NOT NULL,
  `nama_pelapor` varchar(100) NOT NULL,
  `nohp_pelapor` varchar(20) NOT NULL,
  `blok_pelapor` varchar(10) NOT NULL,
  `jenis_laporan` enum('ibu-hamil','warga-meninggal') NOT NULL,
  `nama_subjek` varchar(100) NOT NULL,
  `umur_subjek` int(11) NOT NULL,
  `tanggal_meninggal` date DEFAULT NULL,
  `blok_subjek` varchar(10) NOT NULL,
  `tanggal_laporan` timestamp NOT NULL DEFAULT current_timestamp(),
  `KK_pelapor` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_rt`
--

CREATE TABLE `user_rt` (
  `sk_rt` varchar(50) NOT NULL,
  `nik_rt` varchar(20) NOT NULL,
  `no_rt` char(10) NOT NULL,
  `no_rw` char(10) NOT NULL,
  `nama_rt` varchar(50) NOT NULL,
  `nohp_rt` varchar(13) NOT NULL,
  `wilayah_rt` varchar(100) NOT NULL,
  `alamat` varchar(200) NOT NULL,
  `admin` int(11) NOT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_rt`
--

INSERT INTO `user_rt` (`sk_rt`, `nik_rt`, `no_rt`, `no_rw`, `nama_rt`, `nohp_rt`, `wilayah_rt`, `alamat`, `admin`, `password`) VALUES
('15/12/2025', '2171434250102510', '001', '001', 'fadli', '081275796452', 'meditrania', '', 1, '$2y$10$5qpCkISB0.bQ2ouAZGU7dOC2a1JVt8W3Zu.En00GQoScmV2c3A2Jm');

-- --------------------------------------------------------

--
-- Table structure for table `user_warga`
--

CREATE TABLE `user_warga` (
  `nik_warga` varchar(16) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `dokumen` enum('domisili','pengantar rt','','') DEFAULT NULL,
  `laporan` int(11) DEFAULT NULL,
  `nama_warga` varchar(50) NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan','','') DEFAULT NULL,
  `agama` varchar(10) NOT NULL,
  `status_kawin` enum('kawin','belum-kawin','cerai-hidup','cerai-mati') DEFAULT NULL,
  `no_kk` varchar(16) NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pekerjaan` varchar(50) DEFAULT NULL,
  `pendidikan` enum('tidak bersekolah','sd','smp','sma','d3/d2','s1/d4','s2','s3') DEFAULT NULL,
  `rt` varchar(50) NOT NULL,
  `ibu_hamil` varchar(10) DEFAULT NULL,
  `keluarga` enum('kepala keluarga','anggota keluarga','wafat') DEFAULT NULL,
  `hp` int(15) NOT NULL,
  `no_rt` char(50) NOT NULL,
  `no_rw` char(50) NOT NULL,
  `kecamatan` varchar(50) NOT NULL,
  `kelurahan` varchar(50) NOT NULL,
  `sudah_lengkap` tinyint(1) DEFAULT 0,
  `warga_wafat` varchar(50) DEFAULT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_warga`
--

INSERT INTO `user_warga` (`nik_warga`, `password`, `dokumen`, `laporan`, `nama_warga`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `status_kawin`, `no_kk`, `tempat_lahir`, `alamat`, `email`, `pekerjaan`, `pendidikan`, `rt`, `ibu_hamil`, `keluarga`, `hp`, `no_rt`, `no_rw`, `kecamatan`, `kelurahan`, `sudah_lengkap`, `warga_wafat`, `tanggal_input`) VALUES
('', '$2y$10$RJmsWrzlF1ogi6O7AfZmb.0ikC2pphHrsEHpmXvlWy9c9H.lPJFyC', NULL, NULL, ' ', NULL, NULL, '', NULL, '', '', '', '', NULL, NULL, '15/12/2025', NULL, 'anggota keluarga', 0, '001', '001', '', '', 0, NULL, '2025-12-15 14:25:41'),
('123123123', '$2y$10$WIDkI.bBoUfQvCh9jsmF3ODt3oOF3JwoJ3foXLHgQEcA0HC5j44gy', NULL, NULL, 'rangga', NULL, NULL, '', NULL, '', '', '', '', NULL, NULL, '15/12/2025', NULL, 'anggota keluarga', 0, '001', '001', '', '', 0, NULL, '2025-12-15 15:25:07'),
('12341234', '$2y$10$nwaQGI7DHfRVN7WkV7n1HO6zp26NlnnksxL8lvZ4Eg/OUZNRTeEDC', NULL, NULL, 'femboys', '2007-01-03', 'Laki-Laki', 'Islam', 'belum-kawin', '373839303777', 'BATAM', 'Teluk mata ikan RT 03 RW 07 kelurahan Sambau kecamatan Nongsa', 'ranggakomik@gmail.com', 'swasta', 's1/d4', '15/12/2025', NULL, 'anggota keluarga', 2147483647, '001', '001', 'NONGSA', 'SAMBAU', 1, NULL, '2025-12-15 14:03:55'),
('192837', '$2y$10$D7kVMmGNrxD4uiKTQQEpHO1g1Y.ipQeA/g27VlT4609oqlrm4/hTq', NULL, NULL, 'femboys', '2007-01-09', 'Laki-Laki', 'Islam', 'kawin', '373839303777', 'BATAM', 'Teluk mata ikan RT 03 RW 07 kelurahan Sambau kecamatan Nongsa', 'ranggakomik@gmail.com', 'Pelajar/Mahasiswa', '', '15/12/2025', NULL, 'kepala keluarga', 2147483647, '001', '001', 'NONGSA', 'SAMBAU', 1, NULL, '2025-12-16 01:53:33'),
('54321', '$2y$10$FgOMfKFtDpV7mciPP131dudTW/4nyDlwE8xlIdMWY2R0YQE5zyG66', 'pengantar rt', NULL, 'femboys', '2000-02-01', 'Laki-Laki', 'Kristen', 'kawin', '373839303777', 'BATAM', 'Teluk mata ikan RT 03 RW 07 kelurahan Sambau kecamatan Nongsa', 'ranggakomik@gmail.com', 'pns', 'd3/d2', '15/12/2025', NULL, 'kepala keluarga', 2147483647, '001', '001', 'NONGSA', 'SAMBAU', 1, NULL, '2025-12-15 08:10:40'),
('987654321', '$2y$10$H6l84XW03UFweM1mS2budOa51BdXC7NLVXzj6KFkUg3yyzLEoRUo2', NULL, NULL, 'rangga', NULL, NULL, '', NULL, '', '', '', '', NULL, NULL, '15/12/2025', NULL, 'kepala keluarga', 0, '001', '001', '', '', 0, NULL, '2025-12-16 02:06:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_user`
--
ALTER TABLE `admin_user`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id_chat`),
  ADD KEY `id_pengirim` (`id_pengirim`);

--
-- Indexes for table `dokumen`
--
ALTER TABLE `dokumen`
  ADD PRIMARY KEY (`id_dokumen`),
  ADD KEY `fk_dokumen_warga` (`warga`);

--
-- Indexes for table `dokumen_wargart`
--
ALTER TABLE `dokumen_wargart`
  ADD PRIMARY KEY (`id_dokumen`),
  ADD KEY `dokumen_warga` (`id_warga`);

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id_laporan`),
  ADD KEY `laporaan` (`nik_pelapor`);

--
-- Indexes for table `user_rt`
--
ALTER TABLE `user_rt`
  ADD PRIMARY KEY (`sk_rt`),
  ADD UNIQUE KEY `nik_rt` (`nik_rt`),
  ADD KEY `admin` (`admin`);

--
-- Indexes for table `user_warga`
--
ALTER TABLE `user_warga`
  ADD PRIMARY KEY (`nik_warga`),
  ADD KEY `rt` (`rt`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_user`
--
ALTER TABLE `admin_user`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id_chat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dokumen`
--
ALTER TABLE `dokumen`
  MODIFY `id_dokumen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `dokumen_wargart`
--
ALTER TABLE `dokumen_wargart`
  MODIFY `id_dokumen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id_laporan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dokumen`
--
ALTER TABLE `dokumen`
  ADD CONSTRAINT `fk_dokumen_warga` FOREIGN KEY (`warga`) REFERENCES `user_warga` (`nik_warga`) ON DELETE CASCADE;

--
-- Constraints for table `dokumen_wargart`
--
ALTER TABLE `dokumen_wargart`
  ADD CONSTRAINT `dokumen_warga` FOREIGN KEY (`id_warga`) REFERENCES `user_warga` (`nik_warga`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `laporan`
--
ALTER TABLE `laporan`
  ADD CONSTRAINT `laporaan` FOREIGN KEY (`nik_pelapor`) REFERENCES `user_warga` (`nik_warga`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_rt`
--
ALTER TABLE `user_rt`
  ADD CONSTRAINT `admin` FOREIGN KEY (`admin`) REFERENCES `admin_user` (`id_admin`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_warga`
--
ALTER TABLE `user_warga`
  ADD CONSTRAINT `rt` FOREIGN KEY (`rt`) REFERENCES `user_rt` (`sk_rt`) ON DELETE CASCADE ON UPDATE CASCADE;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `hapus_laporan_9_bulan` ON SCHEDULE EVERY 1 DAY STARTS '2025-12-04 19:34:04' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM laporan
WHERE tanggal_laporan < NOW() - INTERVAL 9 MONTH$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
