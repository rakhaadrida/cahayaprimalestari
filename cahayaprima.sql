# Host: localhost  (Version 5.7.24)
# Date: 2020-08-29 15:12:51
# Generator: MySQL-Front 6.0  (Build 2.20)


#
# Structure for table "akun"
#

DROP TABLE IF EXISTS `akun`;
CREATE TABLE `akun` (
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`username`,`password`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "akun"
#


#
# Structure for table "barang"
#

DROP TABLE IF EXISTS `barang`;
CREATE TABLE `barang` (
  `id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ukuran` int(11) NOT NULL,
  `isi` int(11) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "barang"
#

INSERT INTO `barang` VALUES ('1','Lampu Phillips 30W',9,87,NULL,NULL,'2020-08-11 09:51:33'),('2','Extrana NYY 4X10 @300M',5,24,NULL,NULL,'2020-08-14 11:22:07'),('3','Eridani 080 3 Watt',2,51,NULL,NULL,'2020-08-14 11:22:44'),('4','Eridani 125 7.5 Watt',7,59,NULL,NULL,'2020-08-14 11:23:11'),('5','Meson 090 5 Watt',7,72,NULL,NULL,'2020-08-14 11:23:29'),('6','Kualitas Cuy',10,10,NULL,'2020-08-24 12:47:29','2020-08-26 08:15:51'),('BRG07','Tes Barang',10,10,NULL,'2020-08-26 14:13:16','2020-08-26 14:13:16');

#
# Structure for table "customer"
#

DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
  `id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `limit` int(11) NOT NULL,
  `sales_cover` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "customer"
#

INSERT INTO `customer` VALUES ('1','PD Zulaika Pudjiastuti','Gg. Sudiarto No. 925, Medan 63969, MalUt','0587 7387 5287','Bahuwarna','reprehenderit',2,'quaerat',NULL,NULL,NULL),('2','UD Hartati Hasanah','Jr. Jakarta No. 483, Bandung 68340, Gorontalo','029 9733 388','Kariman','modi',1,'sint',NULL,NULL,NULL),('3','PD Widiastuti','Kpg. Rajawali Barat No. 298, Sukabumi 63110, KalSel','(+62) 756 8472 1436','Rina','quam',8,'soluta',NULL,NULL,NULL),('4','UD Kuswandari','Gg. Badak No. 599, Bitung 83777, DKI','0293 5652 440','Wage','id',6,'aut',NULL,NULL,NULL),('5','CV Maryadi Yulianti','Ds. Banceng Pondok No. 967, Solok 45946, KepR','0820 708 534','Anita','rem',5,'quasi',NULL,NULL,NULL),('6','rakha','wee','345','532','dshs',24,'gdshs','2020-08-06 09:29:37','2020-08-06 09:22:57','2020-08-06 09:29:37'),('7','PT ABC','DEF','021-02929','Juned','3',3,'3','2020-08-19 07:36:55','2020-08-19 07:36:52','2020-08-19 07:36:55'),('CUS06','PT ABC','ANCOL','02193931','YYYYY','2',3,'2',NULL,'2020-08-26 08:02:30','2020-08-26 08:02:30');

#
# Structure for table "detilpo"
#

DROP TABLE IF EXISTS `detilpo`;
CREATE TABLE `detilpo` (
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

#
# Data for table "detilpo"
#

INSERT INTO `detilpo` VALUES ('PO03','5',8000,10,NULL,NULL,NULL,'2020-08-26 08:27:05','2020-08-26 08:27:05');

#
# Structure for table "detilso"
#

DROP TABLE IF EXISTS `detilso`;
CREATE TABLE `detilso` (
  `id_so` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int(11) NOT NULL DEFAULT '0',
  `qty` int(11) NOT NULL,
  `diskon` int(11) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_so`,`id_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

#
# Data for table "detilso"
#

INSERT INTO `detilso` VALUES ('SO01','1',3000,100,15,NULL,'2020-08-19 20:25:59','2020-08-19 20:25:59'),('SO01','2',4000,10,20,NULL,'2020-08-19 20:25:59','2020-08-19 20:25:59'),('SO01','5',7000,500,30,NULL,'2020-08-19 20:25:59','2020-08-19 20:25:59'),('SO02','2',4000,10,10,NULL,'2020-08-26 08:33:16','2020-08-26 08:33:16');

#
# Structure for table "failed_jobs"
#

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "failed_jobs"
#


#
# Structure for table "gudang"
#

DROP TABLE IF EXISTS `gudang`;
CREATE TABLE `gudang` (
  `id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "gudang"
#

INSERT INTO `gudang` VALUES ('1','Johar Baru','Jr. Warga No. 199, Padangpanjang 21974, KalUt',NULL,NULL,'2020-08-25 18:10:13'),('2','Kemayoran','Jln. Adisucipto No. 250, Kendari 91316, SumSel',NULL,NULL,'2020-08-25 18:10:18'),('3','Cempaka Putih','Dk. Basudewo No. 257, Banda Aceh 18509, Bengkulu',NULL,NULL,'2020-08-25 18:10:29'),('4','Johar Lama','JL JBL','2020-08-05 14:16:11','2020-08-05 14:12:11','2020-08-05 14:16:11'),('GDG04','Ancol','Komplek Ancol Raya','2020-08-26 08:01:40','2020-08-26 08:01:02','2020-08-26 08:01:40');

#
# Structure for table "harga"
#

DROP TABLE IF EXISTS `harga`;
CREATE TABLE `harga` (
  `id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "harga"
#

INSERT INTO `harga` VALUES ('1','Harga Beli (Pcs)',NULL,NULL,'2020-08-14 11:24:19'),('2','Harga Beli (Pack)',NULL,NULL,'2020-08-14 11:24:30'),('3','Harga Jual (Pcs)',NULL,NULL,'2020-08-14 11:24:49'),('4','Harga Jual (Pack)',NULL,NULL,'2020-08-14 11:24:58'),('HRG05','Kualitaszzzz',NULL,'2020-08-26 07:52:44','2020-08-26 08:16:01');

#
# Structure for table "hargabarang"
#

DROP TABLE IF EXISTS `hargabarang`;
CREATE TABLE `hargabarang` (
  `id_barang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_harga` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int(11) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_barang`,`id_harga`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "hargabarang"
#

INSERT INTO `hargabarang` VALUES ('1','1',3000,NULL,'2020-08-11 17:24:56','2020-08-11 17:24:56'),('1','2',2000,NULL,'2020-08-11 17:24:56','2020-08-25 17:14:15'),('1','3',2000,NULL,'2020-08-11 17:24:56','2020-08-25 17:14:15'),('1','4',1000,NULL,'2020-08-11 17:24:56','2020-08-25 17:14:15'),('1','8',5000,NULL,'2020-08-25 17:14:15','2020-08-25 17:14:15'),('2','1',3000,NULL,'2020-08-11 08:44:50','2020-08-25 17:14:00'),('2','2',2000,NULL,'2020-08-25 17:14:00','2020-08-25 17:14:00'),('2','3',4000,NULL,'2020-08-11 08:44:50','2020-08-11 08:44:50'),('2','4',1000,NULL,'2020-08-11 08:44:51','2020-08-25 17:14:00'),('2','8',5000,NULL,'2020-08-25 17:14:00','2020-08-25 17:14:00'),('3','1',1000,NULL,'2020-08-25 17:13:44','2020-08-25 17:13:44'),('3','2',5000,NULL,'2020-08-14 11:37:42','2020-08-14 11:37:42'),('3','3',1000,NULL,'2020-08-25 17:13:44','2020-08-25 17:13:44'),('3','4',2000,NULL,'2020-08-14 11:37:42','2020-08-25 17:13:44'),('3','8',1000,NULL,'2020-08-25 17:13:44','2020-08-25 17:13:44'),('4','1',6000,NULL,'2020-08-14 11:37:50','2020-08-14 11:37:50'),('4','2',6000,NULL,'2020-08-14 11:37:50','2020-08-14 11:37:50'),('4','3',6000,NULL,'2020-08-14 11:37:50','2020-08-14 11:37:50'),('4','4',3000,NULL,'2020-08-25 17:11:14','2020-08-25 17:11:14'),('4','8',4000,NULL,'2020-08-25 17:11:14','2020-08-25 17:11:14'),('5','1',8000,NULL,'2020-08-14 11:38:08','2020-08-25 17:15:01'),('5','2',5000,NULL,'2020-08-14 11:38:08','2020-08-25 17:15:01'),('5','3',6000,NULL,'2020-08-14 11:38:08','2020-08-25 17:15:01'),('5','4',8000,NULL,'2020-08-14 11:38:08','2020-08-25 17:15:01'),('5','8',6000,NULL,'2020-08-25 17:11:02','2020-08-25 17:11:02'),('6','1',1005,NULL,'2020-08-25 17:27:26','2020-08-25 17:27:26'),('6','2',1006,NULL,'2020-08-25 17:25:18','2020-08-25 17:27:26'),('6','3',1003,NULL,'2020-08-25 17:25:18','2020-08-25 17:25:18'),('6','4',1007,NULL,'2020-08-25 17:27:26','2020-08-25 17:27:26'),('6','8',1008,NULL,'2020-08-25 17:27:26','2020-08-25 17:27:26');

#
# Structure for table "migrations"
#

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "migrations"
#

INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2020_08_04_135131_create_supplier_table',1),(5,'2020_08_05_122012_create_barang_table',2),(6,'2020_08_05_133520_create_gudang_table',3),(7,'2020_08_05_141659_create_harga_table',4),(8,'2020_08_06_085059_create_customer_table',5),(9,'2020_08_10_113713_create_po_table',6),(10,'2020_08_11_080254_create_hargabarang_table',7),(11,'2020_08_12_065508_update_po_table',8),(12,'2020_08_13_174149_create_tempdetil_table',9),(13,'2020_08_19_071049_create_so_table',10),(14,'2020_08_25_173535_create_stok_table',11);

#
# Structure for table "password_resets"
#

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "password_resets"
#


#
# Structure for table "po"
#

DROP TABLE IF EXISTS `po`;
CREATE TABLE `po` (
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

#
# Data for table "po"
#

INSERT INTO `po` VALUES ('PO01','2020-08-15','1',275000,'LENGKAP',NULL,'2020-08-15 13:44:01','2020-08-18 13:40:05'),('PO02','2020-08-18','3',82500,'PENDING',NULL,'2020-08-18 14:13:00','2020-08-18 14:13:00'),('PO03','2020-08-26','4',88000,'PENDING',NULL,'2020-08-26 08:27:05','2020-08-26 08:27:05');

#
# Structure for table "so"
#

DROP TABLE IF EXISTS `so`;
CREATE TABLE `so` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_so` date NOT NULL,
  `tgl_kirim` date DEFAULT NULL,
  `total` int(11) NOT NULL,
  `diskon` int(11) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_customer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "so"
#

INSERT INTO `so` VALUES ('SO01','2020-08-19',NULL,2860165,5,'PENDING','2',NULL,'2020-08-19 20:25:59','2020-08-19 20:25:59'),('SO02','2020-08-26',NULL,35640,10,'PENDING','4',NULL,'2020-08-26 08:33:16','2020-08-26 08:33:16');

#
# Structure for table "stok"
#

DROP TABLE IF EXISTS `stok`;
CREATE TABLE `stok` (
  `id_barang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_gudang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stok` int(11) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_barang`,`id_gudang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "stok"
#

INSERT INTO `stok` VALUES ('1','1',20,NULL,'2020-08-26 14:30:17','2020-08-26 14:30:17'),('1','2',40,NULL,'2020-08-26 14:30:17','2020-08-26 14:30:17'),('1','3',50,NULL,'2020-08-26 14:30:17','2020-08-26 14:30:17'),('2','1',30,NULL,'2020-08-26 14:30:23','2020-08-26 14:30:23'),('2','2',60,NULL,'2020-08-26 14:30:23','2020-08-26 14:30:23'),('2','3',70,NULL,'2020-08-26 14:30:23','2020-08-26 14:30:23'),('5','1',10,NULL,'2020-08-26 14:30:35','2020-08-26 14:30:35'),('5','2',60,NULL,'2020-08-26 14:30:35','2020-08-26 14:30:35'),('5','3',90,NULL,'2020-08-26 14:30:35','2020-08-26 14:30:35'),('BRG07','1',30,NULL,'2020-08-26 14:31:25','2020-08-26 14:31:25'),('BRG07','2',50,NULL,'2020-08-26 14:31:25','2020-08-26 14:31:25'),('BRG07','3',20,NULL,'2020-08-26 14:31:25','2020-08-26 14:31:25');

#
# Structure for table "supplier"
#

DROP TABLE IF EXISTS `supplier`;
CREATE TABLE `supplier` (
  `id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "supplier"
#

INSERT INTO `supplier` VALUES ('1','PD Pudjiastuti Tbk','Jr. Padma No. 573, Administrasi Jakarta Timur 45079, KalTeng','0680 5363 964',NULL,NULL,NULL),('2','Perum Ardianto','Gg. M.T. Haryono No. 294, Mataram 20010, SulTra','(+62) 315 4261 110',NULL,NULL,NULL),('3','UD Anggriawan Laksmiwati (Persero) Tbk','Jln. Gading No. 258, Dumai 73354, KalTim','(+62) 453 8452 0947',NULL,NULL,NULL),('4','CV Suryatmi (Persero) Tbk','Jr. Raya Ujungberung No. 864, Banjarmasin 11698, KalBar','0682 8524 6932',NULL,NULL,NULL),('5','PT Santoso','Psr. Bhayangkara No. 409, Bau-Bau 52921, KalTim','0682 5487 957',NULL,NULL,NULL),('SUP06','ABCDEFG','Ancol Raya','7475757',NULL,'2020-08-26 08:14:41','2020-08-26 08:15:30');

#
# Structure for table "temp_detil"
#

DROP TABLE IF EXISTS `temp_detil`;
CREATE TABLE `temp_detil` (
  `id_po` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `harga` int(11) NOT NULL DEFAULT '0',
  `qty` int(11) NOT NULL,
  `id_supplier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_po`,`id_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "temp_detil"
#


#
# Structure for table "temp_detilso"
#

DROP TABLE IF EXISTS `temp_detilso`;
CREATE TABLE `temp_detilso` (
  `id_so` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `harga` int(11) NOT NULL DEFAULT '0',
  `qty` int(11) NOT NULL,
  `diskon` int(11) NOT NULL DEFAULT '0',
  `diskon_faktur` int(11) NOT NULL DEFAULT '0',
  `id_customer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_so`,`id_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

#
# Data for table "temp_detilso"
#


#
# Structure for table "users"
#

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "users"
#

