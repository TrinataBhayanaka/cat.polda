-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 19, 2016 at 09:33 AM
-- Server version: 5.5.43-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cat_polda`
--

-- --------------------------------------------------------

--
-- Table structure for table `cat_users`
--

CREATE TABLE IF NOT EXISTS `cat_users` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `projectList` text,
  `nik` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `register_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `jenis_kelamin` tinyint(1) DEFAULT NULL,
  `tempat_lahir` tinytext,
  `tanggal_lahir` date DEFAULT NULL,
  `pendidikan` varchar(255) DEFAULT NULL,
  `institusi` tinytext,
  `jenis_pekerjaan` varchar(255) DEFAULT NULL,
  `hp` tinytext,
  `alamat` text,
  `other_data` text,
  `type` int(11) DEFAULT NULL COMMENT '1:admin,2:user',
  `salt` varchar(200) DEFAULT NULL,
  `login_count` int(11) NOT NULL DEFAULT '0',
  `email_token` varchar(255) DEFAULT NULL,
  `is_online` int(11) NOT NULL DEFAULT '0',
  `n_status` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `cat_users`
--

INSERT INTO `cat_users` (`id`, `projectList`, `nik`, `name`, `username`, `email`, `password`, `register_date`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `pendidikan`, `institusi`, `jenis_pekerjaan`, `hp`, `alamat`, `other_data`, `type`, `salt`, `login_count`, `email_token`, `is_online`, `n_status`) VALUES
(1, NULL, NULL, 'admin', 'admin', 'admin@example.com', 'b2e982d12c95911b6abeacad24e256ff3fa47fdb', '2016-01-11 14:19:28', 0, 'Jakarta', '1989-09-08', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'codekir v3.0', 0, NULL, 0, 1),
(4, '', NULL, 'Andreass Bayu', 'andreassbayu', 'andreass.bayu@gmail.com', '8e7ade691e55def6d2984a2272a0fb9e75b8c7bc', '2016-01-15 00:53:26', NULL, NULL, NULL, 'S2', 'Gunadarma', 'Information Management', NULL, NULL, NULL, 3, '1234567890', 0, NULL, 0, 1),
(5, '1,2,3', NULL, 'Verra Theresia', 'veynicks', 'verra@gmail.com', 'fdb9f5f5d30065406c0635eba10fc07257c0bfdc', '2016-01-16 14:25:59', NULL, 'Cirebon', '1989-11-20', NULL, 'Fransiskus II', 'Perbankan', NULL, NULL, NULL, 2, '1234567890', 0, NULL, 0, 1),
(6, NULL, NULL, 'yuki', 'yuki', 'yuki@gmail.com', '4b738cccae22c51d2e1db5b98d8ebcef14d4c624', '2016-01-16 16:25:53', NULL, NULL, NULL, 'S1', 'Bina Nusantara', 'Manager', NULL, NULL, NULL, 2, '1234567890', 0, NULL, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ck_activity`
--

CREATE TABLE IF NOT EXISTS `ck_activity` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL COMMENT '1:content;2:norma;3:peraturan;4:produk;5:program;6:sig;7:user',
  `activity_value` varchar(50) NOT NULL,
  `n_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ck_activity_log`
--

CREATE TABLE IF NOT EXISTS `ck_activity_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `activity_desc` varchar(250) NOT NULL,
  `source` varchar(20) NOT NULL,
  `datetimes` datetime NOT NULL,
  `n_status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ck_admin_member`
--

CREATE TABLE IF NOT EXISTS `ck_admin_member` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `name` varchar(46) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `register_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `menu_akses` varchar(300) DEFAULT NULL,
  `username` varchar(46) DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '1:admin, 2:verifikator, 3:evaluator, 4: balai',
  `salt` varchar(200) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `n_status` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ck_admin_member`
--

INSERT INTO `ck_admin_member` (`id`, `name`, `nickname`, `email`, `register_date`, `menu_akses`, `username`, `type`, `salt`, `password`, `n_status`) VALUES
(1, 'admin', 'admin', 'admin@example.com', '2014-08-07 15:56:36', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24', 'admin', 1, 'codekir v3.0', 'b2e982d12c95911b6abeacad24e256ff3fa47fdb', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ck_menu`
--

CREATE TABLE IF NOT EXISTS `ck_menu` (
  `menuID` int(2) NOT NULL AUTO_INCREMENT,
  `menuDesc` varchar(50) DEFAULT NULL,
  `menuParent` int(2) DEFAULT NULL,
  `menuPath` varchar(100) DEFAULT NULL,
  `menuIcon` varchar(100) DEFAULT NULL,
  `menuStatus` int(11) NOT NULL,
  `menuAksesLogin` int(11) NOT NULL,
  PRIMARY KEY (`menuID`),
  KEY `menuID` (`menuID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ck_menu_parent`
--

CREATE TABLE IF NOT EXISTS `ck_menu_parent` (
  `menuParentID` int(2) NOT NULL AUTO_INCREMENT,
  `menuParentDesc` varchar(20) DEFAULT NULL,
  `menuOrder` int(11) NOT NULL,
  PRIMARY KEY (`menuParentID`),
  KEY `menuParentID` (`menuParentID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jawaban`
--

CREATE TABLE IF NOT EXISTS `jawaban` (
  `id_jawaban` int(11) NOT NULL AUTO_INCREMENT,
  `no_urut` int(11) NOT NULL,
  `id_soal` int(11) NOT NULL,
  `kategori` int(11) NOT NULL,
  `kunci` int(11) NOT NULL,
  `jawaban` int(11) NOT NULL,
  `nilai` int(11) NOT NULL,
  `id_peserta` int(11) NOT NULL,
  PRIMARY KEY (`id_jawaban`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lokasi`
--

CREATE TABLE IF NOT EXISTS `lokasi` (
  `id_lokasi` int(11) NOT NULL AUTO_INCREMENT,
  `nama_lokasi` varchar(255) NOT NULL,
  `status_pemanfaatan` tinyint(1) NOT NULL COMMENT '1-? aktif, 0--> non aktif',
  PRIMARY KEY (`id_lokasi`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `lokasi`
--

INSERT INTO `lokasi` (`id_lokasi`, `nama_lokasi`, `status_pemanfaatan`) VALUES
(1, 'Kampus Depok', 1),
(2, 'Kampus E(Kelapa Dua)', 1),
(3, 'Kampus J (Kalimalang)', 1),
(4, 'Kampus K (Karawaci)', 1),
(5, 'Kampus H (Ciliwung)', 1);

-- --------------------------------------------------------

--
-- Table structure for table `master_kategori`
--

CREATE TABLE IF NOT EXISTS `master_kategori` (
  `id_master` int(11) NOT NULL AUTO_INCREMENT,
  `nama_master` text NOT NULL,
  PRIMARY KEY (`id_master`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='daftar nama mata pelajaran/soal' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `master_kategori`
--

INSERT INTO `master_kategori` (`id_master`, `nama_master`) VALUES
(1, 'BAHASA INGGRIS'),
(2, 'BAHASA INDONESIA'),
(3, 'MATEMATIKA'),
(4, 'PENGETAHUAN UMUM');

-- --------------------------------------------------------

--
-- Table structure for table `master_peserta`
--

CREATE TABLE IF NOT EXISTS `master_peserta` (
  `id_peserta` int(11) NOT NULL AUTO_INCREMENT,
  `id_ujian` int(11) NOT NULL,
  `no_peserta` int(11) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `status_ujian` int(11) NOT NULL,
  `waktu_mulai` datetime NOT NULL,
  `durasi_pengerjaan` int(11) NOT NULL,
  `tambahan_waktu` int(11) NOT NULL,
  `waktu_selesai` datetime NOT NULL,
  `skor_total` int(11) NOT NULL,
  `keterangan` int(11) NOT NULL,
  PRIMARY KEY (`id_peserta`),
  KEY `id_ujian` (`id_ujian`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `paket_soal`
--

CREATE TABLE IF NOT EXISTS `paket_soal` (
  `id_soal` int(11) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `paket` int(11) NOT NULL,
  `waktu_acak` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE IF NOT EXISTS `pengguna` (
  `id_pengguna` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `level` int(11) NOT NULL COMMENT '1->super_admin,2->admin lokasi, 3->admin ruangan',
  `lokasi` int(11) NOT NULL,
  `ruangan` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '1-> aktif, 0-> non aktif',
  PRIMARY KEY (`id_pengguna`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `prosentase_soal`
--

CREATE TABLE IF NOT EXISTS `prosentase_soal` (
  `ID` int(11) NOT NULL,
  `Kategori` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `Persentase` double DEFAULT NULL,
  `Status` varchar(9) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `prosentase_soal`
--

INSERT INTO `prosentase_soal` (`ID`, `Kategori`, `Persentase`, `Status`) VALUES
(1, 'BAHASA INDONESIA', 30, 'AKTIF'),
(2, 'BAHASA INGGRIS', 30, 'AKTIF'),
(3, 'PENGETAHUAN UMUM', 40, 'AKTIF');

-- --------------------------------------------------------

--
-- Table structure for table `ruangan`
--

CREATE TABLE IF NOT EXISTS `ruangan` (
  `id_ruangan` int(11) NOT NULL AUTO_INCREMENT,
  `id_lokasi` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id_ruangan`),
  KEY `id_lokasi` (`id_lokasi`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

--
-- Dumping data for table `ruangan`
--

INSERT INTO `ruangan` (`id_ruangan`, `id_lokasi`, `nama`, `status`) VALUES
(1, 1, 'D436/LePKom', 0),
(2, 1, 'D437/LePKom', 0),
(3, 1, 'D440/LePMA', 0),
(4, 1, 'D450/LePMA', 0),
(5, 1, 'D441/LePMA', 0),
(6, 1, 'D448/LePTek', 0),
(7, 1, 'D456/LPUG', 0),
(8, 1, 'D457/LPUG', 0),
(9, 1, 'D522/UM', 0),
(10, 1, 'D521/UM', 0),
(11, 2, 'E428/T.Mesin', 0),
(12, 2, 'E516/SI', 0),
(13, 2, 'E342/Sastra', 0),
(14, 2, 'E521/SI', 0),
(15, 2, 'E522/Ak.Lanjut B', 0),
(16, 2, 'E523/Manaj.Lanjut', 0),
(17, 2, 'E532/Manaj Menengah', 0),
(18, 2, 'E533/Ak.Lanjut A', 0),
(19, 2, 'E534/TI', 0),
(20, 2, 'E542/TI', 0),
(21, 2, 'E543/TI', 0),
(22, 2, 'E545/Ak.Menengah', 0),
(23, 2, 'E546/SI', 0),
(24, 3, 'J1 Belakang/ Ujian Mandiri', 0),
(25, 3, 'J125 / TI', 0),
(26, 3, 'J1426 / Akuntasi', 0),
(27, 3, 'J1423 / Manajemen', 0),
(28, 3, 'J1525 / SI', 0),
(29, 3, 'J1522 / SI', 0),
(30, 4, 'K121', 0),
(31, 4, 'K122', 0),
(32, 4, 'K123', 0),
(33, 4, 'K124', 0),
(34, 5, 'LANTAI 2 MERAH (J)', 0),
(35, 5, 'LANTAI 2 TOSKA 1 (K)', 0),
(36, 5, 'LANTAI 2 TOSKA 2 (K)', 0),
(37, 5, 'LANTAI 2 BIRU (L)', 0),
(38, 5, 'LANTAI 2 HIJAU DAUN (M)', 0),
(39, 5, 'LANTAI 2 ORANGE (N)', 0),
(40, 5, 'LANTAI 2 PUTIH (O)', 0),
(41, 5, 'LANTAI 2 ABU-ABU (P)', 0),
(42, 5, 'LANTAI 2 KUNING (Q)', 0),
(43, 5, 'LANTAI 3 MERAH (A)', 0),
(44, 5, 'LANTAI 3 TOSKA (B)', 0),
(45, 5, 'LANTAI 3 BIRU (C)', 0),
(46, 5, 'LANTAI 3 HIJAU (D)', 0),
(47, 5, 'LANTAI 3 ORANGE (E)', 0),
(48, 5, 'LANTAI 3 PUTIH (F)', 0),
(49, 5, 'LANTAI 3 ABU-ABU (H)', 0),
(50, 5, 'LANTAI 3 KUNING (I)', 0),
(51, 1, 'D532/UM', 0);

-- --------------------------------------------------------

--
-- Table structure for table `template_soal`
--

CREATE TABLE IF NOT EXISTS `template_soal` (
  `id_soal` int(11) NOT NULL AUTO_INCREMENT,
  `tipe_soal` int(11) NOT NULL DEFAULT '0',
  `penulis` varchar(255) NOT NULL,
  `kisi` int(11) NOT NULL DEFAULT '0' COMMENT 'kategori_soal',
  `waktu` int(11) NOT NULL DEFAULT '0' COMMENT 'minutes',
  `tingkatan` int(11) NOT NULL DEFAULT '0',
  `prosedur_penilaian` int(11) NOT NULL DEFAULT '0',
  `kunci` text NOT NULL,
  `pilihan1` text NOT NULL,
  `pilihan2` text NOT NULL,
  `pilihan3` text NOT NULL,
  PRIMARY KEY (`id_soal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ujian`
--

CREATE TABLE IF NOT EXISTS `ujian` (
  `id_ujian` int(11) NOT NULL AUTO_INCREMENT,
  `id_kategori` int(11) NOT NULL,
  `lama_ujian` int(11) NOT NULL COMMENT 'minutes',
  `jumlah_soal` int(11) NOT NULL,
  `waktu_ujian` date NOT NULL,
  `mulai` int(11) NOT NULL,
  `selesai` int(11) NOT NULL,
  `jumlah_peserta` int(11) NOT NULL,
  `keterangan` int(11) NOT NULL,
  `pilihan_paket` varchar(5) NOT NULL,
  PRIMARY KEY (`id_ujian`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
