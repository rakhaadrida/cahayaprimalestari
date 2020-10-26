-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.7.24 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for cahayaprima
CREATE DATABASE IF NOT EXISTS `cahayaprima` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `cahayaprima`;

-- Dumping structure for table cahayaprima.akun
CREATE TABLE IF NOT EXISTS `akun` (
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`username`,`password`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table cahayaprima.akun: ~0 rows (approximately)
/*!40000 ALTER TABLE `akun` DISABLE KEYS */;
/*!40000 ALTER TABLE `akun` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.ap
CREATE TABLE IF NOT EXISTS `ap` (
  `id_bm` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_bayar` date DEFAULT NULL,
  `transfer` int(11) DEFAULT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_bm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.ap: ~11 rows (approximately)
/*!40000 ALTER TABLE `ap` DISABLE KEYS */;
INSERT INTO `ap` (`id_bm`, `tgl_bayar`, `transfer`, `keterangan`, `created_at`, `updated_at`) VALUES
	('BM0001', NULL, NULL, 'BELUM LUNAS', '2020-10-22 07:29:47', '2020-10-22 07:29:47'),
	('BM0002', '2020-10-22', 9600000, 'LUNAS', '2020-10-22 07:30:26', '2020-10-22 14:00:23'),
	('BM0003', '2020-10-22', 2097000, 'BELUM LUNAS', '2020-10-22 07:30:47', '2020-10-22 14:00:23'),
	('BM0004', NULL, NULL, 'BELUM LUNAS', '2020-10-22 07:31:16', '2020-10-22 07:31:16'),
	('BM0005', '2020-10-22', 3506000, 'BELUM LUNAS', '2020-10-22 07:31:44', '2020-10-22 14:00:23'),
	('BM0006', '2020-10-22', 7000000, 'BELUM LUNAS', '2020-10-22 12:10:32', '2020-10-22 14:00:23'),
	('BM0007', NULL, NULL, 'BELUM LUNAS', '2020-10-22 12:11:03', '2020-10-22 12:11:03'),
	('BM0008', '2020-10-22', 12555000, 'BELUM LUNAS', '2020-10-22 12:11:24', '2020-10-22 14:00:23'),
	('BM0009', NULL, NULL, 'BELUM LUNAS', '2020-10-22 14:02:09', '2020-10-22 14:02:09'),
	('BM0010', NULL, NULL, 'BELUM LUNAS', '2020-10-22 14:02:34', '2020-10-22 14:02:34'),
	('BM0011', NULL, NULL, 'BELUM LUNAS', '2020-10-22 14:03:22', '2020-10-22 14:03:22');
/*!40000 ALTER TABLE `ap` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.approval
CREATE TABLE IF NOT EXISTS `approval` (
  `id_so` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_so`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.approval: ~2 rows (approximately)
/*!40000 ALTER TABLE `approval` DISABLE KEYS */;
INSERT INTO `approval` (`id_so`, `tanggal`, `status`, `keterangan`, `created_at`, `updated_at`) VALUES
	('INV0001', '2020-10-08', 'UPDATE', 'Permintaan Customer', '2020-10-08 18:59:13', '2020-10-08 18:59:13'),
	('INV0018', '2020-10-09', 'UPDATE', 'Ubah Qty Order', '2020-10-09 08:55:39', '2020-10-09 08:55:39'),
	('INV0021', '2020-10-08', 'UPDATE', 'Permintaan Customer', '2020-10-08 18:26:17', '2020-10-08 18:26:17');
/*!40000 ALTER TABLE `approval` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.ar
CREATE TABLE IF NOT EXISTS `ar` (
  `id_so` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_bayar` date DEFAULT NULL,
  `cicil` int(11) DEFAULT NULL,
  `retur` int(11) DEFAULT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_so`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.ar: ~8 rows (approximately)
/*!40000 ALTER TABLE `ar` DISABLE KEYS */;
INSERT INTO `ar` (`id_so`, `tgl_bayar`, `cicil`, `retur`, `keterangan`, `created_at`, `updated_at`) VALUES
	('INV0001', '2020-10-22', 1000000, 0, 'BELUM LUNAS', '2020-10-22 12:58:01', '2020-10-22 13:13:24'),
	('INV0002', '2020-10-22', 5000000, 0, 'BELUM LUNAS', '2020-10-22 12:59:09', '2020-10-22 13:13:24'),
	('INV0003', NULL, NULL, NULL, 'BELUM LUNAS', '2020-10-22 12:59:59', '2020-10-22 12:59:59'),
	('INV0004', '2020-10-22', 7500000, 0, 'BELUM LUNAS', '2020-10-22 13:01:23', '2020-10-22 13:13:24'),
	('INV0005', '2020-10-22', 10200000, 0, 'BELUM LUNAS', '2020-10-22 13:02:45', '2020-10-22 13:13:24'),
	('INV0006', NULL, NULL, NULL, 'BELUM LUNAS', '2020-10-22 13:03:57', '2020-10-22 13:03:57'),
	('INV0007', '2020-10-22', 1397300, 0, 'BELUM LUNAS', '2020-10-22 13:04:38', '2020-10-22 13:13:24'),
	('INV0008', NULL, NULL, NULL, 'BELUM LUNAS', '2020-10-22 14:05:26', '2020-10-22 14:05:26');
/*!40000 ALTER TABLE `ar` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.barang
CREATE TABLE IF NOT EXISTS `barang` (
  `id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `satuan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ukuran` int(11) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.barang: ~5 rows (approximately)
/*!40000 ALTER TABLE `barang` DISABLE KEYS */;
INSERT INTO `barang` (`id`, `nama`, `id_kategori`, `satuan`, `ukuran`, `deleted_at`, `created_at`, `updated_at`) VALUES
	('BRG001', 'Lampu Phillips 30W', 'KAT01', 'Pcs / Pack', 10, NULL, NULL, '2020-10-23 09:24:33'),
	('BRG002', 'Extrana NYY 4X10 @300M', 'KAT03', 'Meter / Rol', 50, NULL, NULL, '2020-10-23 09:24:54'),
	('BRG003', 'Eridani 080 3 Watt', 'KAT02', 'Pcs / Pack', 10, NULL, NULL, '2020-10-23 09:25:04'),
	('BRG004', 'Eridani 125 7.5 Watt', 'KAT02', 'Pcs / Pack', 10, NULL, NULL, '2020-10-23 09:25:09'),
	('BRG005', 'Meson 090 5 Watt', 'KAT02', 'Pcs / Pack', 10, NULL, NULL, '2020-10-23 09:25:17');
/*!40000 ALTER TABLE `barang` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.barangmasuk
CREATE TABLE IF NOT EXISTS `barangmasuk` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `total` int(20) DEFAULT NULL,
  `id_gudang` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_supplier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.barangmasuk: ~11 rows (approximately)
/*!40000 ALTER TABLE `barangmasuk` DISABLE KEYS */;
INSERT INTO `barangmasuk` (`id`, `tanggal`, `total`, `id_gudang`, `id_supplier`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
	('BM0001', '2020-10-22', 12780000, 'GDG01', 'SUP001', 'LENGKAP', NULL, '2020-10-22 07:29:46', '2020-10-22 12:09:02'),
	('BM0002', '2020-10-22', 9600000, 'GDG02', 'SUP004', 'LENGKAP', NULL, '2020-10-22 07:30:26', '2020-10-22 12:08:38'),
	('BM0003', '2020-10-22', 5697000, 'GDG03', 'SUP003', 'LENGKAP', NULL, '2020-10-22 07:30:47', '2020-10-22 12:08:22'),
	('BM0004', '2020-10-22', 5256000, 'GDG02', 'SUP001', 'LENGKAP', NULL, '2020-10-22 07:31:16', '2020-10-22 12:08:08'),
	('BM0005', '2020-10-22', 8506000, 'GDG01', 'SUP005', 'LENGKAP', NULL, '2020-10-22 07:31:44', '2020-10-22 12:05:18'),
	('BM0006', '2020-10-22', 9000000, 'GDG03', 'SUP004', 'LENGKAP', NULL, '2020-10-22 12:10:32', '2020-10-22 12:13:01'),
	('BM0007', '2020-10-22', 12400000, 'GDG03', 'SUP003', 'LENGKAP', NULL, '2020-10-22 12:11:03', '2020-10-22 12:12:49'),
	('BM0008', '2020-10-22', 18795000, 'GDG03', 'SUP005', 'LENGKAP', NULL, '2020-10-22 12:11:24', '2020-10-22 12:12:37'),
	('BM0009', '2020-10-22', 51150000, 'GDG01', 'SUP002', 'NO_DISC', NULL, '2020-10-22 14:02:09', '2020-10-22 14:02:09'),
	('BM0010', '2020-10-22', 7350000, 'GDG03', 'SUP005', 'LENGKAP', NULL, '2020-10-22 14:02:34', '2020-10-22 18:57:41'),
	('BM0011', '2020-10-22', 16000000, 'GDG02', 'SUP001', 'NO_DISC', NULL, '2020-10-22 14:03:22', '2020-10-22 14:03:22');
/*!40000 ALTER TABLE `barangmasuk` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.customer
CREATE TABLE IF NOT EXISTS `customer` (
  `id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `npwp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '-',
  `limit` int(11) NOT NULL,
  `id_sales` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.customer: ~5 rows (approximately)
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` (`id`, `nama`, `alamat`, `telepon`, `contact_person`, `npwp`, `limit`, `id_sales`, `deleted_at`, `created_at`, `updated_at`) VALUES
	('CUS001', 'PD Zulaika Pudjiastuti', 'Gg. Sudiarto No. 925, Medan 63969, MalUt', '0587 7387 5287', 'Oke', '36993193019301', 200, 'SLS03', NULL, NULL, '2020-09-10 13:00:25'),
	('CUS002', 'UD Hartati Hasanah', 'Jl Siliwangi No 34, Pamulang, Tangsel, 15417', '305I25302', 'Kariman', '-', 32501, 'SLS02', NULL, NULL, '2020-10-21 18:54:15'),
	('CUS003', 'PD Widiastuti', 'Kpg. Rajawali Barat No. 298, Sukabumi 63110, KalSel', '(+62) 756 8472 1436', 'Rina', '483919319391', 8, 'SLS03', NULL, NULL, '2020-09-10 13:02:20'),
	('CUS004', 'UD Kuswandari', 'Gg. Badak No. 599, Bitung 83777, DKI', '0293 5652 440', 'Wage', '-', 3000000, 'SLS03', NULL, NULL, '2020-10-13 11:47:52'),
	('CUS005', 'CV Maryadi Yulianti', 'Ds. Banceng Pondok No. 967, Solok 45946, KepR', '0820 708 534', 'Anita', '-', 5, 'SLS01', NULL, NULL, '2020-09-14 06:15:03');
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.detilbm
CREATE TABLE IF NOT EXISTS `detilbm` (
  `id_bm` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `diskon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hpp` int(20) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_bm`,`id_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.detilbm: ~17 rows (approximately)
/*!40000 ALTER TABLE `detilbm` DISABLE KEYS */;
INSERT INTO `detilbm` (`id_bm`, `id_barang`, `harga`, `qty`, `diskon`, `hpp`, `deleted_at`, `created_at`, `updated_at`) VALUES
	('BM0001', 'BRG001', 150000, 100, '42+4', 84000, NULL, '2020-10-22 07:29:47', '2020-10-22 12:09:02'),
	('BM0001', 'BRG004', 120000, 50, '26+2', 87600, NULL, '2020-10-22 07:29:47', '2020-10-22 12:09:02'),
	('BM0002', 'BRG002', 200000, 80, '37+3+2', 120000, NULL, '2020-10-22 07:30:26', '2020-10-22 12:08:38'),
	('BM0003', 'BRG003', 180000, 20, '12+2', 156600, NULL, '2020-10-22 07:30:47', '2020-10-22 12:08:22'),
	('BM0003', 'BRG005', 95000, 30, '10', 85500, NULL, '2020-10-22 07:30:47', '2020-10-22 12:08:22'),
	('BM0004', 'BRG003', 180000, 40, '25+3', 131400, NULL, '2020-10-22 07:31:16', '2020-10-22 12:08:08'),
	('BM0005', 'BRG003', 180000, 40, '30+3', 122400, NULL, '2020-10-22 07:31:44', '2020-10-22 12:05:18'),
	('BM0005', 'BRG005', 95000, 50, '23+2', 72200, NULL, '2020-10-22 07:31:44', '2020-10-22 12:05:18'),
	('BM0006', 'BRG001', 150000, 30, '12', 132000, NULL, '2020-10-22 12:10:32', '2020-10-22 12:13:01'),
	('BM0006', 'BRG004', 120000, 50, '14+3', 100800, NULL, '2020-10-22 12:10:32', '2020-10-22 12:13:01'),
	('BM0007', 'BRG002', 200000, 100, '35+3+2', 124000, NULL, '2020-10-22 12:11:03', '2020-10-22 12:12:49'),
	('BM0008', 'BRG003', 180000, 120, '36+3', 113400, NULL, '2020-10-22 12:11:25', '2020-10-22 12:12:37'),
	('BM0008', 'BRG005', 95000, 70, '22+1', 74100, NULL, '2020-10-22 12:11:25', '2020-10-22 12:12:37'),
	('BM0009', 'BRG001', 150000, 120, NULL, NULL, NULL, '2020-10-22 14:02:09', '2020-10-22 14:02:09'),
	('BM0009', 'BRG002', 200000, 70, NULL, NULL, NULL, '2020-10-22 14:02:09', '2020-10-22 14:02:09'),
	('BM0009', 'BRG003', 180000, 80, NULL, NULL, NULL, '2020-10-22 14:02:09', '2020-10-22 14:02:09'),
	('BM0009', 'BRG005', 95000, 50, NULL, NULL, NULL, '2020-10-22 14:02:09', '2020-10-22 14:02:09'),
	('BM0010', 'BRG001', 150000, 70, '26+4+2', 105000, NULL, '2020-10-22 14:02:34', '2020-10-22 18:57:41'),
	('BM0011', 'BRG002', 200000, 80, NULL, NULL, NULL, '2020-10-22 14:03:22', '2020-10-22 14:03:22');
/*!40000 ALTER TABLE `detilbm` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.detilpo
CREATE TABLE IF NOT EXISTS `detilpo` (
  `id_po` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int(11) NOT NULL DEFAULT '0',
  `qty` int(11) NOT NULL,
  `qty_terima` int(11) DEFAULT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_po`,`id_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.detilpo: ~12 rows (approximately)
/*!40000 ALTER TABLE `detilpo` DISABLE KEYS */;
INSERT INTO `detilpo` (`id_po`, `id_barang`, `harga`, `qty`, `qty_terima`, `keterangan`, `deleted_at`, `created_at`, `updated_at`) VALUES
	('PO0003', 'BRG002', 4000, 20, NULL, NULL, NULL, '2020-09-21 11:55:39', '2020-09-21 11:55:39'),
	('PO0003', 'BRG005', 10000, 10, NULL, NULL, NULL, '2020-09-21 11:55:39', '2020-09-21 11:55:39'),
	('PO0004', 'BRG003', 5000, 20, NULL, NULL, NULL, '2020-09-21 11:56:10', '2020-09-21 11:56:10'),
	('PO0004', 'BRG005', 10000, 10, NULL, NULL, NULL, '2020-09-21 11:56:10', '2020-09-21 11:56:10'),
	('PO0005', 'BRG003', 5000, 20, NULL, NULL, NULL, '2020-09-21 12:54:26', '2020-09-21 12:54:26'),
	('PO0005', 'BRG004', 7000, 10, NULL, NULL, NULL, '2020-09-21 12:54:26', '2020-09-21 12:54:26'),
	('PO0006', 'BRG001', 3000, 15, NULL, NULL, NULL, '2020-09-21 12:56:35', '2020-09-21 12:56:35'),
	('PO0006', 'BRG003', 5000, 10, NULL, NULL, NULL, '2020-09-21 12:56:35', '2020-09-21 12:56:35'),
	('PO0006', 'BRG005', 10000, 10, NULL, NULL, NULL, '2020-09-21 12:56:35', '2020-09-21 12:56:35'),
	('PO0007', 'BRG004', 7, 20, NULL, NULL, NULL, '2020-09-25 08:06:57', '2020-09-25 08:06:57'),
	('PO0009', 'BRG001', 3, 1000, NULL, NULL, NULL, '2020-09-25 12:21:15', '2020-09-25 12:21:15'),
	('PO0010', 'BRG001', 3000, 20000, NULL, NULL, NULL, '2020-09-25 12:22:58', '2020-09-25 12:22:58');
/*!40000 ALTER TABLE `detilpo` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.detilsj
CREATE TABLE IF NOT EXISTS `detilsj` (
  `id_so` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qtyRevisi` int(11) NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_so`,`id_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.detilsj: ~6 rows (approximately)
/*!40000 ALTER TABLE `detilsj` DISABLE KEYS */;
INSERT INTO `detilsj` (`id_so`, `id_barang`, `qtyRevisi`, `keterangan`, `created_at`, `updated_at`, `deleted_at`) VALUES
	('INV0001', 'BRG005', 8, 'Permintaan Customer', '2020-09-15 08:26:53', '2020-09-15 08:26:53', NULL),
	('INV0002', 'BRG003', 15, 'Permintaan Customer', '2020-09-15 08:33:51', '2020-09-15 08:33:51', NULL),
	('INV0002', 'BRG005', 28, 'Permintaan Customer', '2020-09-15 08:33:51', '2020-09-15 08:33:51', NULL),
	('INV0003', 'BRG002', 15, 'Permintaan Customer', '2020-09-15 08:38:37', '2020-09-15 08:38:37', NULL),
	('INV0004', 'BRG003', 15, 'Permintaan Customer', '2020-09-15 08:40:27', '2020-09-15 08:40:27', NULL),
	('INV0004', 'BRG004', 5, 'Permintaan Customer', '2020-09-15 08:40:27', '2020-09-15 08:40:27', NULL);
/*!40000 ALTER TABLE `detilsj` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.detilso
CREATE TABLE IF NOT EXISTS `detilso` (
  `id_so` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_gudang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int(11) NOT NULL DEFAULT '0',
  `qty` int(11) NOT NULL,
  `diskon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `diskonRp` int(20) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_so`,`id_barang`,`id_gudang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table cahayaprima.detilso: ~21 rows (approximately)
/*!40000 ALTER TABLE `detilso` DISABLE KEYS */;
INSERT INTO `detilso` (`id_so`, `id_barang`, `id_gudang`, `harga`, `qty`, `diskon`, `diskonRp`, `deleted_at`, `created_at`, `updated_at`) VALUES
	('INV0001', 'BRG001', 'GDG01', 150000, 30, '14', 630000, NULL, '2020-10-22 12:58:01', '2020-10-22 12:58:01'),
	('INV0001', 'BRG002', 'GDG01', 200000, 50, '20+3', 2240000, NULL, '2020-10-22 12:58:01', '2020-10-22 12:58:01'),
	('INV0002', 'BRG003', 'GDG01', 180000, 30, '24', 1296000, NULL, '2020-10-22 12:59:09', '2020-10-22 12:59:09'),
	('INV0002', 'BRG004', 'GDG01', 120000, 80, '16+4', 1858560, NULL, '2020-10-22 12:59:09', '2020-10-22 12:59:09'),
	('INV0003', 'BRG001', 'GDG01', 150000, 50, '24', 1800000, NULL, '2020-10-22 12:59:59', '2020-10-22 12:59:59'),
	('INV0003', 'BRG005', 'GDG01', 95000, 50, '20+3+3', 1174200, NULL, '2020-10-22 12:59:59', '2020-10-22 12:59:59'),
	('INV0004', 'BRG003', 'GDG01', 180000, 70, '15+3+2', 2419200, NULL, '2020-10-22 13:01:23', '2020-10-22 13:01:23'),
	('INV0004', 'BRG004', 'GDG01', 120000, 60, '18', 1296000, NULL, '2020-10-22 13:01:24', '2020-10-22 13:01:24'),
	('INV0004', 'BRG005', 'GDG01', 95000, 40, '8', 304000, NULL, '2020-10-22 13:01:23', '2020-10-22 13:01:23'),
	('INV0005', 'BRG003', 'GDG01', 180000, 30, '20+4+2', 1780560, NULL, '2020-10-22 13:02:45', '2020-10-22 13:02:45'),
	('INV0005', 'BRG003', 'GDG03', 180000, 50, '20+4+2', 1780560, NULL, '2020-10-22 13:02:45', '2020-10-22 13:02:45'),
	('INV0005', 'BRG005', 'GDG01', 95000, 30, '15+2', 793250, NULL, '2020-10-22 13:02:45', '2020-10-22 13:02:45'),
	('INV0005', 'BRG005', 'GDG02', 95000, 70, '15+2', 793250, NULL, '2020-10-22 13:02:45', '2020-10-22 13:02:45'),
	('INV0006', 'BRG002', 'GDG01', 200000, 70, '27+3+2', 3978000, NULL, '2020-10-22 13:03:57', '2020-10-22 13:03:57'),
	('INV0006', 'BRG002', 'GDG02', 200000, 90, '27+3+2', 3978000, NULL, '2020-10-22 13:03:57', '2020-10-22 13:03:57'),
	('INV0006', 'BRG002', 'GDG03', 200000, 40, '27+3+2', 3978000, NULL, '2020-10-22 13:03:57', '2020-10-22 13:03:57'),
	('INV0007', 'BRG001', 'GDG01', 150000, 30, '23+4+3', 1698000, NULL, '2020-10-22 13:04:38', '2020-10-22 13:04:38'),
	('INV0007', 'BRG001', 'GDG02', 150000, 10, '23+4+3', 1698000, NULL, '2020-10-22 13:04:38', '2020-10-22 13:04:38'),
	('INV0007', 'BRG001', 'GDG03', 150000, 80, '23+4+3', 1698000, NULL, '2020-10-22 13:04:38', '2020-10-22 13:04:38'),
	('INV0008', 'BRG001', 'GDG01', 150000, 70, '20+3', 2352000, NULL, '2020-10-22 14:05:26', '2020-10-22 14:05:26'),
	('INV0008', 'BRG002', 'GDG01', 200000, 50, '24', 2400000, NULL, '2020-10-22 14:05:26', '2020-10-22 14:05:26');
/*!40000 ALTER TABLE `detilso` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.detiltb
CREATE TABLE IF NOT EXISTS `detiltb` (
  `id_tb` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_asal` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_tujuan` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_tb`,`id_barang`,`id_asal`,`id_tujuan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.detiltb: ~4 rows (approximately)
/*!40000 ALTER TABLE `detiltb` DISABLE KEYS */;
INSERT INTO `detiltb` (`id_tb`, `id_barang`, `id_asal`, `id_tujuan`, `qty`, `created_at`, `updated_at`) VALUES
	('TB0001', 'BRG001', 'GDG01', 'GDG02', 20, '2020-10-22 07:52:37', '2020-10-22 07:52:37'),
	('TB0001', 'BRG003', 'GDG02', 'GDG03', 20, '2020-10-22 07:52:37', '2020-10-22 07:52:37'),
	('TB0002', 'BRG002', 'GDG02', 'GDG03', 30, '2020-10-22 07:53:18', '2020-10-22 07:53:18'),
	('TB0002', 'BRG004', 'GDG01', 'GDG02', 20, '2020-10-22 07:53:18', '2020-10-22 07:53:18');
/*!40000 ALTER TABLE `detiltb` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.detil_approval
CREATE TABLE IF NOT EXISTS `detil_approval` (
  `id_so` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `diskon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_so`,`id_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.detil_approval: ~2 rows (approximately)
/*!40000 ALTER TABLE `detil_approval` DISABLE KEYS */;
INSERT INTO `detil_approval` (`id_so`, `id_barang`, `harga`, `qty`, `diskon`, `created_at`, `updated_at`) VALUES
	('INV0001', 'BRG005', 16000, 10, '8', '2020-10-08 18:59:14', '2020-10-08 18:59:14'),
	('INV0018', 'BRG004', 11000, 50, '10', '2020-10-09 08:55:39', '2020-10-09 08:55:39'),
	('INV0021', 'BRG002', 7000, 50, '10', '2020-10-08 18:26:17', '2020-10-08 18:26:17');
/*!40000 ALTER TABLE `detil_approval` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.failed_jobs: ~0 rows (approximately)
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.gudang
CREATE TABLE IF NOT EXISTS `gudang` (
  `id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.gudang: ~4 rows (approximately)
/*!40000 ALTER TABLE `gudang` DISABLE KEYS */;
INSERT INTO `gudang` (`id`, `nama`, `alamat`, `deleted_at`, `created_at`, `updated_at`) VALUES
	('GDG01', 'Johar Baru', 'Jr. Warga No. 199, Padangpanjang 21974, KalUt', NULL, NULL, '2020-08-25 18:10:13'),
	('GDG02', 'Kemayoran', 'Jln. Adisucipto No. 250, Kendari 91316, SumSel', NULL, NULL, '2020-08-25 18:10:18'),
	('GDG03', 'Cempaka Putih', 'Dk. Basudewo No. 257, Banda Aceh 18509, Bengkulu', NULL, NULL, '2020-08-25 18:10:29');
/*!40000 ALTER TABLE `gudang` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.harga
CREATE TABLE IF NOT EXISTS `harga` (
  `id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.harga: ~1 rows (approximately)
/*!40000 ALTER TABLE `harga` DISABLE KEYS */;
INSERT INTO `harga` (`id`, `nama`, `deleted_at`, `created_at`, `updated_at`) VALUES
	('HRG01', 'Harga Beli (Pcs)', NULL, NULL, '2020-08-14 11:24:19');
/*!40000 ALTER TABLE `harga` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.hargabarang
CREATE TABLE IF NOT EXISTS `hargabarang` (
  `id_barang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_harga` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int(11) NOT NULL,
  `ppn` int(11) DEFAULT NULL,
  `harga_ppn` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_barang`,`id_harga`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.hargabarang: ~5 rows (approximately)
/*!40000 ALTER TABLE `hargabarang` DISABLE KEYS */;
INSERT INTO `hargabarang` (`id_barang`, `id_harga`, `harga`, `ppn`, `harga_ppn`, `deleted_at`, `created_at`, `updated_at`) VALUES
	('BRG001', 'HRG01', 148350, 1650, 150000, NULL, '2020-08-11 17:24:56', '2020-10-13 12:37:06'),
	('BRG002', 'HRG01', 197800, 2200, 200000, NULL, '2020-09-14 07:40:10', '2020-10-21 18:56:37'),
	('BRG003', 'HRG01', 178020, 1980, 180000, NULL, '2020-09-14 07:40:27', '2020-10-21 18:56:51'),
	('BRG004', 'HRG01', 118680, 1320, 120000, NULL, '2020-09-14 07:40:41', '2020-10-21 18:56:59'),
	('BRG005', 'HRG01', 93955, 1045, 95000, NULL, '2020-09-14 07:40:58', '2020-10-21 18:57:06');
/*!40000 ALTER TABLE `hargabarang` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.jenisbarang
CREATE TABLE IF NOT EXISTS `jenisbarang` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.jenisbarang: ~6 rows (approximately)
/*!40000 ALTER TABLE `jenisbarang` DISABLE KEYS */;
INSERT INTO `jenisbarang` (`id`, `nama`, `deleted_at`, `created_at`, `updated_at`) VALUES
	('KAT01', 'PHILIPS', NULL, '2020-10-20 16:01:59', '2020-10-20 16:05:48'),
	('KAT02', 'SUPREME', NULL, '2020-10-20 16:02:07', '2020-10-20 16:02:07'),
	('KAT03', 'EXTRANA', NULL, '2020-10-20 16:02:14', '2020-10-20 16:02:14'),
	('KAT04', 'MCB', NULL, '2020-10-20 16:02:25', '2020-10-20 16:02:25'),
	('KAT05', 'NITTO', NULL, '2020-10-20 16:02:35', '2020-10-20 16:02:35'),
	('KAT06', 'SAKLAR', NULL, '2020-10-20 16:02:41', '2020-10-20 16:02:41'),
	('KAT07', 'Oke', '2020-10-20 16:06:16', '2020-10-20 16:06:13', '2020-10-20 16:06:16');
/*!40000 ALTER TABLE `jenisbarang` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.migrations: ~18 rows (approximately)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_resets_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2020_08_04_135131_create_supplier_table', 1),
	(5, '2020_08_05_122012_create_barang_table', 2),
	(6, '2020_08_05_133520_create_gudang_table', 3),
	(7, '2020_08_05_141659_create_harga_table', 4),
	(8, '2020_08_06_085059_create_customer_table', 5),
	(9, '2020_08_10_113713_create_po_table', 6),
	(10, '2020_08_11_080254_create_hargabarang_table', 7),
	(11, '2020_08_12_065508_update_po_table', 8),
	(12, '2020_08_13_174149_create_tempdetil_table', 9),
	(13, '2020_08_19_071049_create_so_table', 10),
	(14, '2020_08_25_173535_create_stok_table', 11),
	(15, '2020_09_07_121300_create_sales_table', 12),
	(16, '2020_09_09_143724_create_barangmasuk_table', 13),
	(17, '2020_09_14_085927_create_transferbarang_table', 14),
	(18, '2020_09_14_090231_create_detiltb_table', 15),
	(19, '2020_09_14_090724_create_temp_detiltb_table', 16),
	(20, '2020_09_15_074535_create_sj_table', 17),
	(21, '2020_09_15_075020_create_detilsj_table', 18),
	(22, '2020_09_15_104640_create_tempdetilpo_table', 19),
	(23, '2020_10_19_083837_create_ar_table', 20),
	(24, '2020_10_19_102445_change_ar_field', 21),
	(25, '2020_10_20_053349_create_ap_table', 22),
	(26, '2020_10_20_154821_create_jenisbarang_table', 23);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.need_appdetil
CREATE TABLE IF NOT EXISTS `need_appdetil` (
  `id_app` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `diskon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_app`,`id_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.need_appdetil: ~0 rows (approximately)
/*!40000 ALTER TABLE `need_appdetil` DISABLE KEYS */;
INSERT INTO `need_appdetil` (`id_app`, `id_barang`, `qty`, `harga`, `diskon`, `created_at`, `updated_at`) VALUES
	('APP0001', 'BRG005', 190, 16000, '10', '2020-10-21 14:22:17', '2020-10-21 14:22:17');
/*!40000 ALTER TABLE `need_appdetil` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.need_approval
CREATE TABLE IF NOT EXISTS `need_approval` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` timestamp NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(225) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_so` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table cahayaprima.need_approval: ~0 rows (approximately)
/*!40000 ALTER TABLE `need_approval` DISABLE KEYS */;
INSERT INTO `need_approval` (`id`, `tanggal`, `status`, `keterangan`, `id_so`, `created_at`, `updated_at`) VALUES
	('APP0001', '2020-10-21 14:22:17', 'PENDING_UPDATE', 'Permintaan Customer', 'INV0001', '2020-10-21 14:22:17', '2020-10-21 14:22:17');
/*!40000 ALTER TABLE `need_approval` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.password_resets: ~0 rows (approximately)
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.po
CREATE TABLE IF NOT EXISTS `po` (
  `id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tgl_po` date NOT NULL,
  `id_supplier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` int(11) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.po: ~8 rows (approximately)
/*!40000 ALTER TABLE `po` DISABLE KEYS */;
INSERT INTO `po` (`id`, `tgl_po`, `id_supplier`, `total`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
	('PO0001', '2020-09-21', 'SUP001', 1518000, 'PENDING', NULL, '2020-09-21 11:54:44', '2020-09-21 11:54:44'),
	('PO0002', '2020-09-21', 'SUP002', 264000, 'PENDING', NULL, '2020-09-21 11:55:16', '2020-09-21 11:55:16'),
	('PO0003', '2020-09-21', 'SUP002', 198000, 'PENDING', NULL, '2020-09-21 11:55:39', '2020-09-21 11:55:39'),
	('PO0004', '2020-09-21', 'SUP004', 220000, 'PENDING', NULL, '2020-09-21 11:56:10', '2020-09-21 11:56:10'),
	('PO0005', '2020-09-21', 'SUP004', 187000, 'PENDING', NULL, '2020-09-21 12:54:26', '2020-09-21 12:54:26'),
	('PO0006', '2020-09-21', 'SUP002', 214500, 'PENDING', NULL, '2020-09-21 12:56:35', '2020-09-21 12:56:35'),
	('PO0007', '2020-09-25', 'SUP003', 154, 'PENDING', NULL, '2020-09-25 08:06:57', '2020-09-25 08:06:57'),
	('PO0008', '2020-09-25', 'SUP002', 253, 'PENDING', NULL, '2020-09-25 08:11:37', '2020-09-25 08:11:37'),
	('PO0009', '2020-09-25', 'SUP002', 3300000, 'PENDING', NULL, '2020-09-25 12:21:15', '2020-09-25 12:21:15'),
	('PO0010', '2020-09-25', 'SUP003', 66000000, 'PENDING', NULL, '2020-09-25 12:22:58', '2020-09-25 12:22:58');
/*!40000 ALTER TABLE `po` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.sales
CREATE TABLE IF NOT EXISTS `sales` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.sales: ~2 rows (approximately)
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` (`id`, `nama`, `created_at`, `updated_at`, `deleted_at`) VALUES
	('SLS01', 'Mas Agung', '2020-09-07 14:02:48', '2020-09-07 14:06:41', NULL),
	('SLS02', 'Ibu', '2020-09-07 14:16:31', '2020-09-07 14:16:31', NULL),
	('SLS03', 'Indah', '2020-09-07 14:16:37', '2020-09-07 14:16:37', NULL);
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.sj
CREATE TABLE IF NOT EXISTS `sj` (
  `id_so` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_sj` date NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_so`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.sj: ~4 rows (approximately)
/*!40000 ALTER TABLE `sj` DISABLE KEYS */;
INSERT INTO `sj` (`id_so`, `tgl_sj`, `keterangan`, `created_at`, `updated_at`, `deleted_at`) VALUES
	('INV0001', '2020-09-15', 'Perubahan Jumlah Barang yg Dipesan', '2020-09-15 08:26:53', '2020-09-15 08:26:53', NULL),
	('INV0002', '2020-09-15', 'Perubahan Jumlah Barang yg Dipesan', '2020-09-15 08:33:51', '2020-09-15 08:33:51', NULL),
	('INV0003', '2020-09-15', 'Perubahan Jumlah Barang yg Dipesan', '2020-09-15 08:38:36', '2020-09-15 08:38:36', NULL),
	('INV0004', '2020-09-15', 'Perubahan Jumlah Barang yg Dipesan', '2020-09-15 08:40:27', '2020-09-15 08:40:27', NULL);
/*!40000 ALTER TABLE `sj` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.so
CREATE TABLE IF NOT EXISTS `so` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_so` date NOT NULL,
  `tgl_kirim` date NOT NULL,
  `total` int(11) NOT NULL,
  `kategori` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tempo` int(11) NOT NULL DEFAULT '0',
  `pkp` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_customer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.so: ~8 rows (approximately)
/*!40000 ALTER TABLE `so` DISABLE KEYS */;
INSERT INTO `so` (`id`, `tgl_so`, `tgl_kirim`, `total`, `kategori`, `tempo`, `pkp`, `status`, `id_customer`, `deleted_at`, `created_at`, `updated_at`) VALUES
	('INV0001', '2020-10-22', '2020-10-22', 11630000, 'Cash', 0, 0, 'INPUT', 'CUS002', NULL, '2020-10-22 12:58:01', '2020-10-22 12:58:01'),
	('INV0002', '2020-10-22', '2020-10-22', 11845440, 'Prime', 30, 0, 'INPUT', 'CUS005', NULL, '2020-10-22 12:59:09', '2020-10-22 12:59:09'),
	('INV0003', '2020-10-22', '2020-10-22', 9275800, 'Extrana', 45, 0, 'INPUT', 'CUS003', NULL, '2020-10-22 12:59:59', '2020-10-22 12:59:59'),
	('INV0004', '2020-10-22', '2020-10-22', 19580800, 'Prime', 40, 0, 'INPUT', 'CUS004', NULL, '2020-10-22 13:01:23', '2020-10-22 13:01:23'),
	('INV0005', '2020-10-22', '2020-10-22', 18752380, 'Cash', 0, 0, 'INPUT', 'CUS002', NULL, '2020-10-22 13:02:45', '2020-10-22 13:02:45'),
	('INV0006', '2020-10-22', '2020-10-22', 28066000, 'Prime', 60, 0, 'INPUT', 'CUS003', NULL, '2020-10-22 13:03:57', '2020-10-22 13:03:57'),
	('INV0007', '2020-10-22', '2020-10-22', 12906000, 'Cash', 0, 0, 'INPUT', 'CUS001', NULL, '2020-10-22 13:04:38', '2020-10-22 13:04:38'),
	('INV0008', '2020-10-22', '2020-10-22', 15748000, 'Prime', 35, 0, 'INPUT', 'CUS005', NULL, '2020-10-22 14:05:26', '2020-10-22 14:05:26');
/*!40000 ALTER TABLE `so` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.stok
CREATE TABLE IF NOT EXISTS `stok` (
  `id_barang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_gudang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stok` int(11) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_barang`,`id_gudang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.stok: ~15 rows (approximately)
/*!40000 ALTER TABLE `stok` DISABLE KEYS */;
INSERT INTO `stok` (`id_barang`, `id_gudang`, `stok`, `deleted_at`, `created_at`, `updated_at`) VALUES
	('BRG001', 'GDG01', 40, NULL, '2020-08-26 14:30:17', '2020-10-23 11:53:46'),
	('BRG001', 'GDG02', 60, NULL, '2020-08-26 14:30:17', '2020-10-22 13:04:38'),
	('BRG001', 'GDG03', 70, NULL, '2020-08-26 14:30:17', '2020-10-22 14:02:34'),
	('BRG002', 'GDG01', 20, NULL, '2020-08-26 14:30:23', '2020-10-22 14:05:26'),
	('BRG002', 'GDG02', 80, NULL, '2020-08-26 14:30:23', '2020-10-22 14:03:22'),
	('BRG002', 'GDG03', 140, NULL, '2020-08-26 14:30:23', '2020-10-22 13:03:57'),
	('BRG003', 'GDG01', 80, NULL, '2020-09-14 07:29:20', '2020-10-22 14:02:09'),
	('BRG003', 'GDG02', 80, NULL, '2020-09-14 07:29:20', '2020-10-22 11:43:28'),
	('BRG003', 'GDG03', 110, NULL, '2020-09-14 07:29:20', '2020-10-22 13:02:45'),
	('BRG004', 'GDG01', 40, NULL, '2020-09-14 07:29:28', '2020-10-22 13:01:24'),
	('BRG004', 'GDG02', 50, NULL, '2020-09-14 07:29:28', '2020-10-22 10:59:18'),
	('BRG004', 'GDG03', 140, NULL, '2020-09-14 07:29:28', '2020-10-22 12:10:32'),
	('BRG005', 'GDG01', 50, NULL, '2020-08-26 14:30:35', '2020-10-22 14:02:09'),
	('BRG005', 'GDG02', 30, NULL, '2020-08-26 14:30:35', '2020-10-22 13:02:45'),
	('BRG005', 'GDG03', 100, NULL, '2020-08-26 14:30:35', '2020-10-22 12:11:25');
/*!40000 ALTER TABLE `stok` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.supplier
CREATE TABLE IF NOT EXISTS `supplier` (
  `id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `npwp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '-',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.supplier: ~5 rows (approximately)
/*!40000 ALTER TABLE `supplier` DISABLE KEYS */;
INSERT INTO `supplier` (`id`, `nama`, `alamat`, `telepon`, `npwp`, `deleted_at`, `created_at`, `updated_at`) VALUES
	('SUP001', 'PD Pudjiastuti Tbk', 'Jl. Pemuda No 256, Jakarta Selatan, DKI Jakarta 15429', '0856-7038-4913', '-', NULL, NULL, '2020-10-21 18:53:40'),
	('SUP002', 'Perum Ardianto', 'Gg. M.T. Haryono No. 294, Mataram 20010, SulTra', '(+62) 315 4261 110', NULL, NULL, NULL, NULL),
	('SUP003', 'UD Anggriawan Laksmiwati (Persero) Tbk', 'Jln. Gading No. 258, Dumai 73354, KalTim', '(+62) 453 8452 0947', NULL, NULL, NULL, NULL),
	('SUP004', 'CV Suryatmi (Persero) Tbk', 'Jr. Raya Ujungberung No. 864, Banjarmasin 11698, KalBar', '0682 8524 6932', NULL, NULL, NULL, NULL),
	('SUP005', 'PT Santoso', 'Psr. Bhayangkara No. 409, Bau-Bau 52921, KalTim', '0682 5487 957', NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `supplier` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.transferbarang
CREATE TABLE IF NOT EXISTS `transferbarang` (
  `id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_tb` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.transferbarang: ~2 rows (approximately)
/*!40000 ALTER TABLE `transferbarang` DISABLE KEYS */;
INSERT INTO `transferbarang` (`id`, `tgl_tb`, `created_at`, `updated_at`) VALUES
	('TB0001', '2020-10-22', '2020-10-22 07:52:37', '2020-10-22 07:52:37'),
	('TB0002', '2020-10-22', '2020-10-22 07:53:18', '2020-10-22 07:53:18');
/*!40000 ALTER TABLE `transferbarang` ENABLE KEYS */;

-- Dumping structure for table cahayaprima.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `roles` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ADMIN',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cahayaprima.users: ~2 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `roles`) VALUES
	('01', 'Indah', '$2y$10$iXh.AIfTOElyciNpCtIKbOn7/KhJ90jRo2A8ZaaSbWgUkNaZNIcdW', NULL, '2020-10-02 08:44:12', '2020-10-02 08:44:12', NULL, 'SUPER'),
	('02', 'Rakha', '$2y$10$Z70aKZXBiW7OTOstJmOIdeSdz8.wlIzXzmFADcwPWg7n9hFaagFw.', NULL, '2020-10-02 08:55:26', '2020-10-02 08:55:26', NULL, 'ADMIN'),
	('03', 'Adrida', '$2y$10$FcA2NV.SH0f52z5nQAi52uSwe2Q0YN4vs4Nh/2tvut1B/FcFwjCUO', NULL, '2020-10-19 14:49:45', '2020-10-19 14:49:45', NULL, 'FINANCE');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
