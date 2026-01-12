-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2026 at 10:22 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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

-- ========================================
-- OTOMATIS MEMBUAT DATABASE
-- ========================================

-- Hapus database jika sudah ada (opsional, hapus baris ini jika tidak ingin menghapus database lama)
DROP DATABASE IF EXISTS `pbl1`;

-- Buat database baru
CREATE DATABASE IF NOT EXISTS `pbl1` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Gunakan database yang baru dibuat
USE `pbl1`;

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
('mangga', 4, '$2y$10$5k3by7PNwGsYJohPaWRfUubSPhrxZIHLxovSztSScJoaBSJXQzm/C'),
('admin', 5, '$2y$10$NfOGQkZ8ysIqWeXfWQar.uZDdyamkUB/MMGDfdDW1jZvSPLPpV9m2'),
('nadya', 7, '$2y$10$oUtAnS.fC1xkVHo8k0PlSuMmWNTOjGZURojWPcrQZWOEg3otgbzCe'),
('adminbaru', 8, '$2y$10$NCauQiLxHfwoIlHiibN.4e6UJp9k78EWjvBNbsvMM9k.ftMkdoEYe');

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
(56, '2026-01-08', '0987654321123451', 'tolak', 'domisili'),
(57, '2026-01-08', '0987654321123451', 'setuju', 'pengantar rt'),
(58, '2026-01-08', '0987654321123451', 'setuju', 'domisili');

-- --------------------------------------------------------

--
-- Table structure for table `dokumen_warga`
--
-- Error reading structure for table pbl1.dokumen_warga: #1932 - Table 'pbl1.dokumen_warga' doesn't exist in engine
-- Error reading data for table pbl1.dokumen_warga: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM `pbl1`.`dokumen_warga`' at line 1

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
(14, '0987654321123453', '141b778a5ac4662619d3268ec7cf307f2c8fd49100c47230f5b0adc911b7b06a.jpg', 'e15a620433b229a5493774941151436e0b64faa937b196f8d76dca16d5a88852.jpg', 'pending', NULL, '2026-01-08', 'pengantar_rt');

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
  `KK_pelapor` varchar(100) NOT NULL,
  `status_laporan` enum('aktif','selesai') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporan`
--

INSERT INTO `laporan` (`id_laporan`, `nik_pelapor`, `nama_pelapor`, `nohp_pelapor`, `blok_pelapor`, `jenis_laporan`, `nama_subjek`, `umur_subjek`, `tanggal_meninggal`, `blok_subjek`, `tanggal_laporan`, `KK_pelapor`, `status_laporan`) VALUES
(30, '0987654321123451', 'mangga', '081275796451', 'taman raya', 'ibu-hamil', 'Ara', 35, NULL, 'taman raya', '2026-01-08 04:16:29', '373839303777', 'aktif'),
(31, '0987654321123451', 'mangga', '081275796451', 'taman raya', 'warga-meninggal', 'rangga', 3, '2026-01-12', 'blok m', '2026-01-08 04:16:53', '373839303777', 'aktif');

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
  `admin` int(11) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `foto_profile` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_rt`
--

INSERT INTO `user_rt` (`sk_rt`, `nik_rt`, `no_rt`, `no_rw`, `nama_rt`, `nohp_rt`, `admin`, `password`, `foto_profile`) VALUES
('12341234', '0987654321123457', '001', '007', 'mangga', '081275796452', 5, '$2y$10$CvxD95GBdjROCfHSN1rZqOWuf4IZ5G.e7YPPUYx760Xo/KXSLhw.y', NULL),
('15/12/2025', '0987654321123456', '002', '001', 'femboysku', '081275796452', 5, '$2y$10$GeA7IChM8AvO76x.eeNEMezvpX8Hi0R6ekyi7kDWBD0Dj.VmSV6v2', '1767319837_6957291d367ae.gif'),
('30/11/2025 sk tahun pertama', '0987654321123451', '01', '001', '0987654321123456', '081275796452', 5, '$2y$10$O38I.gKKot0pRidbPPOsNuFjlZIsgokqKUV02/BQWsCY3QMqSUYUK', NULL),
('54321', '0987654321123454', '01', '01', 'mangga', '081275796452', 5, '$2y$10$HWuyGhSgjG.BgWYonpkVfOR36gebBJY19uCIOXLP4JaKAcLglN/vu', '1767849480_695f3e08339a3.jpg');

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
  `hp` varchar(15) NOT NULL,
  `no_rt` char(50) NOT NULL,
  `no_rw` char(50) NOT NULL,
  `kecamatan` varchar(50) NOT NULL,
  `kelurahan` varchar(50) NOT NULL,
  `sudah_lengkap` tinyint(1) DEFAULT 0,
  `warga_wafat` varchar(50) DEFAULT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp(),
  `foto_profile` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_warga`
--

INSERT INTO `user_warga` (`nik_warga`, `password`, `dokumen`, `laporan`, `nama_warga`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `status_kawin`, `no_kk`, `tempat_lahir`, `alamat`, `email`, `pekerjaan`, `pendidikan`, `rt`, `ibu_hamil`, `keluarga`, `hp`, `no_rt`, `no_rw`, `kecamatan`, `kelurahan`, `sudah_lengkap`, `warga_wafat`, `tanggal_input`, `foto_profile`) VALUES
('0987654321123433', '$2y$10$rfqarlRdKn0TiPswkDx81eXRVXEIJ/RErOJMe4/FK37k7RnDGPDmO', NULL, NULL, 'rangga', NULL, NULL, '', NULL, '', '', '', '', NULL, NULL, '54321', NULL, 'anggota keluarga', '', '01', '01', '', '', 0, NULL, '2026-01-08 05:18:14', NULL),
('0987654321123451', '$2y$10$Ktgrkt6URjLlyivODCJALeOcNKdcDJhEyVmOo5sJ0bZthwRgiJ4Xa', 'domisili', NULL, 'mangga', '2009-01-03', 'Laki-Laki', 'Kristen', 'kawin', '373839303777', 'belakang padang', 'Teluk mata ikan RT 03 RW 07 kelurahan Sambau kecamatan Nongsa', 'ranggakomika@gmail.com', 'BUMN', 's2', '54321', NULL, 'kepala keluarga', '081275796451', '01', '01', 'NONGSA', 'SAMBAU', 1, NULL, '2026-01-02 08:26:08', '1767858112_695f5fc08f697.png'),
('0987654321123452', '$2y$10$DaPA/35pF6inXmGoIOi.suSSmbotJ7j9Yca4XQhrFHGxJjLIl.CRe', NULL, NULL, 'rangga', '2023-01-03', 'Laki-Laki', 'Kristen', 'belum-kawin', '373839303777', 'BATAM', 'Teluk mata ikan RT 03 RW 07 kelurahan Sambau kecamatan Nongsa', 'ranggakomik@gmail.com', 'TNI', 'tidak bersekolah', '54321', NULL, 'anggota keluarga', '081275796452', '01', '01', 'NONGSA', 'SAMBAU', 1, NULL, '2026-01-03 17:14:54', NULL),
('0987654321123453', '$2y$10$S8oyvJsJaw.Wq5e7VRE5aOaP84/Q6qwh577Ot/tat4vrWpj2xXzM6', NULL, NULL, 'Ara', '1990-01-09', 'Perempuan', 'Islam', 'kawin', '373839303777', 'belakang padang', 'Teluk mata ikan RT 03 RW 07 kelurahan Sambau kecamatan Nongsa', 'ranggakomik@gmail.com', 'Wirausaha', 's2', '54321', NULL, 'wafat', '081275796452', '01', '01', 'NONGSA', 'SAMBAU', 1, NULL, '2026-01-03 17:18:20', NULL),
('0987654321123457', '$2y$10$sZUf4lZF1HwGNLuERql8wODlxEx3.hfuW4UXuErzRRTBMdf0nkEiS', NULL, NULL, 'mangga', '2020-02-03', 'Laki-Laki', 'Islam', 'belum-kawin', '373839303777', 'belakang padang', 'Teluk mata ikan RT 03 RW 07 kelurahan Sambau kecamatan Nongsa', 'ranggakomik@gmail.com', 'BUMN', 'tidak bersekolah', '54321', NULL, 'wafat', '081275796452', '01', '01', 'NONGSA', 'SAMBAU', 1, NULL, '2026-01-08 03:59:58', NULL),
('0987654321123458', '$2y$10$sKdUKuAUaP67/.07EYG6huNn8cVaxEUMKued/O76r8ScqU6CF7E/W', NULL, NULL, 'zaki', '2020-02-01', 'Laki-Laki', 'Kristen', 'kawin', '373839303777', 'belakang padang', 'Teluk mata ikan RT 03 RW 07 kelurahan Sambau kecamatan Nongsa', 'ranggakomik@gmail.com', 'Swasta', 'tidak bersekolah', '54321', NULL, 'wafat', '081275796452', '01', '01', 'NONGSA', 'SAMBAU', 1, NULL, '2026-01-08 04:40:07', NULL),
('54321', '$2y$10$QZSykIowSmGBZCd3TxYSXe2R/sKL.hVgU72V0uo/42sGog0wEWkES', NULL, NULL, 'mangga', NULL, NULL, '', NULL, '', '', '', '', NULL, NULL, '54321', NULL, 'kepala keluarga', '', '01', '01', '', '', 0, NULL, '2026-01-08 09:03:10', NULL);

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
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id_chat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dokumen`
--
ALTER TABLE `dokumen`
  MODIFY `id_dokumen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `dokumen_wargart`
--
ALTER TABLE `dokumen_wargart`
  MODIFY `id_dokumen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id_laporan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
CREATE DEFINER=`root`@`localhost` EVENT `update_laporan_ibu_hamil` ON SCHEDULE EVERY 1 DAY STARTS '2026-01-04 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE laporan
SET status_laporan = 'selesai'
WHERE jenis_laporan = 'ibu-hamil'
AND status_laporan = 'aktif'
AND tanggal_laporan <= DATE_SUB(CURDATE(), INTERVAL 9 MONTH)$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
