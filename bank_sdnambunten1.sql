-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2024 at 05:52 AM
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
-- Database: `bank_sdnambunten1`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nama_admin` varchar(100) NOT NULL,
  `username_admin` char(50) NOT NULL,
  `password_admin` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama_admin`, `username_admin`, `password_admin`) VALUES
(1, 'admin', 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id_guru` int(11) NOT NULL,
  `nip_guru` char(20) NOT NULL,
  `nama_guru` varchar(100) NOT NULL,
  `alamat_guru` varchar(100) NOT NULL,
  `jk_guru` enum('Laki-laki','Perempuan') NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `username_guru` char(50) NOT NULL,
  `password_guru` char(20) NOT NULL,
  `id_kelas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`id_guru`, `nip_guru`, `nama_guru`, `alamat_guru`, `jk_guru`, `no_hp`, `username_guru`, `password_guru`, `id_kelas`) VALUES
(1, '1904111', 'Supratno', 'Aros Raje', 'Laki-laki', '081939301702', '1904111', '1904111', 5),
(3, '1312', 'Haryono', 'Ambunten', 'Laki-laki', '082128957770', '1312', '1312', 6),
(6, '123', 'Nurul Sofina', 'Ambunten', 'Perempuan', '085947656213', '123', 'sofi', 4);

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(11) NOT NULL,
  `nama_kelas` char(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`) VALUES
(4, 'Kelas IV'),
(5, 'Kelas V'),
(6, 'Kelas VI');

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `id_log` int(11) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `waktu` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log`
--

INSERT INTO `log` (`id_log`, `keterangan`, `waktu`) VALUES
(87, 'admin', '2024-06-02 09:53:06'),
(88, 'admin Login', '2024-06-02 09:53:17'),
(89, 'Menambahkan Siswa Riski ke Kelas IV', '2024-06-02 09:54:51'),
(90, 'Menghapus Testing dari Data Siswa', '2024-06-02 09:54:58');

-- --------------------------------------------------------

--
-- Table structure for table `mutasi`
--

CREATE TABLE `mutasi` (
  `id_mutasi` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `tgl_mutasi` datetime NOT NULL,
  `jenis_mutasi` enum('Penyimpanan','Penarikan') NOT NULL,
  `jumlah` int(20) NOT NULL,
  `total_saldo` int(20) NOT NULL,
  `catatan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mutasi`
--

INSERT INTO `mutasi` (`id_mutasi`, `id_siswa`, `tgl_mutasi`, `jenis_mutasi`, `jumlah`, `total_saldo`, `catatan`) VALUES
(18, 1, '2024-06-02 01:47:06', 'Penyimpanan', 10000, 10000, 'Menyimpan\r\n'),
(19, 1, '2024-06-02 01:47:20', 'Penarikan', 4000, 6000, 'Butuh Duit'),
(20, 1, '2024-06-02 01:47:37', 'Penyimpanan', 2000, 8000, 'Menabung'),
(21, 4, '2024-06-02 09:20:35', 'Penyimpanan', 10000, 10000, 'Menabung'),
(23, 4, '2024-06-02 09:26:07', 'Penarikan', 2000, 8000, 'Butuh Duit'),
(24, 4, '2024-06-02 09:26:26', 'Penyimpanan', 20000, 28000, 'Menabung');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `nisn_siswa` char(20) NOT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `alamat_siswa` varchar(100) NOT NULL,
  `jk_siswa` enum('Laki-laki','Perempuan') NOT NULL,
  `nama_wali` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `username_siswa` char(50) NOT NULL,
  `password_siswa` char(20) NOT NULL,
  `id_kelas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nisn_siswa`, `nama_siswa`, `alamat_siswa`, `jk_siswa`, `nama_wali`, `no_hp`, `username_siswa`, `password_siswa`, `id_kelas`) VALUES
(1, '123321', 'Restu', 'Jalan Siswa Ambunten', 'Laki-laki', 'Ali Mansyur', '081931291231', '123321', 'Galih123', 6),
(4, '1122', 'Galih', 'Kecamatan Kota', 'Laki-laki', 'Baihaqi', '082313612312', '1122', '1122', 4),
(5, '2222', 'Lisa', 'Ambunten', 'Perempuan', 'Muhammad', '082316317212', '2222', '2222', 4),
(6, '1941', 'Riski', 'Ambunten', 'Laki-laki', 'Suharto', '085123613123', '1941', '1941', 4);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_penarikan`
--

CREATE TABLE `transaksi_penarikan` (
  `id_penarikan` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `tgl_tarik` date NOT NULL,
  `jumlah` int(20) NOT NULL,
  `catatan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi_penarikan`
--

INSERT INTO `transaksi_penarikan` (`id_penarikan`, `id_guru`, `id_siswa`, `tgl_tarik`, `jumlah`, `catatan`) VALUES
(4, 1, 3, '2024-06-02', 2000, 'Butuh Duit'),
(5, 1, 3, '2024-06-02', 500, 'Penarikan'),
(7, 6, 4, '2024-06-02', 2000, 'Butuh Duit');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_penyimpanan`
--

CREATE TABLE `transaksi_penyimpanan` (
  `id_penyimpanan` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `tgl_simpan` date NOT NULL,
  `jumlah` int(20) NOT NULL,
  `catatan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi_penyimpanan`
--

INSERT INTO `transaksi_penyimpanan` (`id_penyimpanan`, `id_guru`, `id_siswa`, `tgl_simpan`, `jumlah`, `catatan`) VALUES
(12, 1, 3, '2024-06-02', 5000, 'Menabung'),
(13, 1, 3, '2024-06-02', 9000, 'Menabung'),
(14, 1, 3, '2024-06-02', 2000, 'Menabung'),
(17, 6, 4, '2024-06-02', 10000, 'Menabung'),
(19, 6, 4, '2024-06-02', 20000, 'Menabung');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id_guru`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id_log`);

--
-- Indexes for table `mutasi`
--
ALTER TABLE `mutasi`
  ADD PRIMARY KEY (`id_mutasi`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`);

--
-- Indexes for table `transaksi_penarikan`
--
ALTER TABLE `transaksi_penarikan`
  ADD PRIMARY KEY (`id_penarikan`);

--
-- Indexes for table `transaksi_penyimpanan`
--
ALTER TABLE `transaksi_penyimpanan`
  ADD PRIMARY KEY (`id_penyimpanan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `id_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `mutasi`
--
ALTER TABLE `mutasi`
  MODIFY `id_mutasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `transaksi_penarikan`
--
ALTER TABLE `transaksi_penarikan`
  MODIFY `id_penarikan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `transaksi_penyimpanan`
--
ALTER TABLE `transaksi_penyimpanan`
  MODIFY `id_penyimpanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
