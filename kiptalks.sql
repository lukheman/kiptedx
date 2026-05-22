/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.1.2-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: kiptedx
-- ------------------------------------------------------
-- Server version	12.1.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `backsounds`
--

DROP TABLE IF EXISTS `backsounds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `backsounds` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `file_audio` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backsounds`
--

LOCK TABLES `backsounds` WRITE;
/*!40000 ALTER TABLE `backsounds` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `backsounds` VALUES
(1,'FULL','backsounds/Xd1Lt7AO6L288sAOfddw7Pd4M3stNRuDVwAJSHaJ.wav','2026-05-20 05:30:23','2026-05-20 16:25:57'),
(2,'TRANSISI','backsounds/bEUJdLVyxESr5DsBPyfNTQmVxb7FxnWaxHcZuM1C.mp3','2026-05-20 16:26:27','2026-05-20 16:26:27');
/*!40000 ALTER TABLE `backsounds` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `cache` VALUES
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab','i:1;',1779323245),
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer','i:1779323245;',1779323245),
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba','i:2;',1779282450),
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba:timer','i:1779282450;',1779282450),
('laravel-cache-boost.roster.scan','a:2:{s:6:\"roster\";O:21:\"Laravel\\Roster\\Roster\":3:{s:13:\"\0*\0approaches\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:11:\"\0*\0packages\";O:32:\"Laravel\\Roster\\PackageCollection\":2:{s:8:\"\0*\0items\";a:10:{i:0;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:5:\"^12.0\";s:10:\"\0*\0package\";E:37:\"Laravel\\Roster\\Enums\\Packages:LARAVEL\";s:14:\"\0*\0packageName\";s:17:\"laravel/framework\";s:10:\"\0*\0version\";s:7:\"12.59.0\";s:6:\"\0*\0dev\";b:0;}i:1;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:7:\"v0.3.17\";s:10:\"\0*\0package\";E:37:\"Laravel\\Roster\\Enums\\Packages:PROMPTS\";s:14:\"\0*\0packageName\";s:15:\"laravel/prompts\";s:10:\"\0*\0version\";s:6:\"0.3.17\";s:6:\"\0*\0dev\";b:0;}i:2;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:6:\"v4.3.0\";s:10:\"\0*\0package\";E:38:\"Laravel\\Roster\\Enums\\Packages:LIVEWIRE\";s:14:\"\0*\0packageName\";s:17:\"livewire/livewire\";s:10:\"\0*\0version\";s:5:\"4.3.0\";s:6:\"\0*\0dev\";b:0;}i:3;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:6:\"^1.7.0\";s:10:\"\0*\0package\";E:34:\"Laravel\\Roster\\Enums\\Packages:VOLT\";s:14:\"\0*\0packageName\";s:13:\"livewire/volt\";s:10:\"\0*\0version\";s:6:\"1.10.5\";s:6:\"\0*\0dev\";b:0;}i:4;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:6:\"v0.5.9\";s:10:\"\0*\0package\";E:33:\"Laravel\\Roster\\Enums\\Packages:MCP\";s:14:\"\0*\0packageName\";s:11:\"laravel/mcp\";s:10:\"\0*\0version\";s:5:\"0.5.9\";s:6:\"\0*\0dev\";b:1;}i:5;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:5:\"^1.24\";s:10:\"\0*\0package\";E:34:\"Laravel\\Roster\\Enums\\Packages:PINT\";s:14:\"\0*\0packageName\";s:12:\"laravel/pint\";s:10:\"\0*\0version\";s:6:\"1.29.1\";s:6:\"\0*\0dev\";b:1;}i:6;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:5:\"^1.41\";s:10:\"\0*\0package\";E:34:\"Laravel\\Roster\\Enums\\Packages:SAIL\";s:14:\"\0*\0packageName\";s:12:\"laravel/sail\";s:10:\"\0*\0version\";s:6:\"1.59.0\";s:6:\"\0*\0dev\";b:1;}i:7;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:4:\"^3.0\";s:10:\"\0*\0package\";E:34:\"Laravel\\Roster\\Enums\\Packages:PEST\";s:14:\"\0*\0packageName\";s:12:\"pestphp/pest\";s:10:\"\0*\0version\";s:5:\"3.8.6\";s:6:\"\0*\0dev\";b:1;}i:8;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:7:\"11.5.50\";s:10:\"\0*\0package\";E:37:\"Laravel\\Roster\\Enums\\Packages:PHPUNIT\";s:14:\"\0*\0packageName\";s:15:\"phpunit/phpunit\";s:10:\"\0*\0version\";s:7:\"11.5.50\";s:6:\"\0*\0dev\";b:1;}i:9;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:0:\"\";s:10:\"\0*\0package\";E:41:\"Laravel\\Roster\\Enums\\Packages:TAILWINDCSS\";s:14:\"\0*\0packageName\";s:11:\"tailwindcss\";s:10:\"\0*\0version\";s:6:\"4.1.11\";s:6:\"\0*\0dev\";b:0;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:21:\"\0*\0nodePackageManager\";E:43:\"Laravel\\Roster\\Enums\\NodePackageManager:NPM\";}s:9:\"timestamp\";i:1779075468;}',1779161868);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `juris`
--

DROP TABLE IF EXISTS `juris`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `juris` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nim` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `juris_nim_unique` (`nim`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `juris`
--

LOCK TABLES `juris` WRITE;
/*!40000 ALTER TABLE `juris` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `juris` VALUES
(1,'242221120','Juri 1','$2y$12$4cExIql68UI968Cb8u9dTucAr9GVVV.FTruW7MTcSA.Q8W4i23KZ2',NULL,'2026-05-17 17:18:05','2026-05-17 17:18:05'),
(2,'242221121','Juri 2','$2y$12$3gGASsPXDeKREkjko1lLIekYyXUN9XimyoJ3YFAIYLOgm8SAsnvra',NULL,'2026-05-17 17:18:05','2026-05-17 17:18:05'),
(3,'242221122','Juri 3','$2y$12$2BzLsevvSumE3dS3WqZaDuSF6XLG//ufn6YgYKrm2PWOoJYcpCZaG',NULL,'2026-05-17 17:18:06','2026-05-17 17:18:06');
/*!40000 ALTER TABLE `juris` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `mahasiswas`
--

DROP TABLE IF EXISTS `mahasiswas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `mahasiswas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nim` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `urutan_tampil` int(10) unsigned DEFAULT NULL,
  `urutan_dikunci` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tema_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mahasiswas_nim_unique` (`nim`),
  KEY `mahasiswas_tema_id_foreign` (`tema_id`),
  CONSTRAINT `mahasiswas_tema_id_foreign` FOREIGN KEY (`tema_id`) REFERENCES `temas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mahasiswas`
--

LOCK TABLES `mahasiswas` WRITE;
/*!40000 ALTER TABLE `mahasiswas` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `mahasiswas` VALUES
(1,'242211075','DINA RAHMAWATI','$2y$12$x56cPHnf5HDxELmiV/2j7.vJi9zrfCzokML/dZmmU6l1uzDifTmSO',NULL,17,0,'2026-05-17 17:18:11','2026-05-20 06:17:19',14),
(2,'242211079','PUTU RESTI SETIAWATI','$2y$12$xE6L6dkbupipmDPVh2eRT.Wk7xEfFtxu0owZF12nXwG8oNjKyMG9u',NULL,12,0,'2026-05-17 17:18:11','2026-05-20 06:17:19',13),
(3,'242211083','ANISA AMELIA.R','$2y$12$Nh6MrHpGd8tm68maJxh.q.nsvS.dNsqG..kGINczI2XTm/Q0rR7om',NULL,20,0,'2026-05-17 17:18:12','2026-05-20 06:17:19',12),
(4,'242211084','SUBEDA RIANTI','$2y$12$9ew6zyoA0z79ABP2akyPUu.UfUGy4fvCL056F82RCj13Nca2S26eq',NULL,8,0,'2026-05-17 17:18:12','2026-05-20 06:17:19',5),
(5,'242211085','NURHIDAYAH','$2y$12$b4OW3Z35YaFhDsYaqbOZhOeio8BBSkzqbHmGUhb9wJQVJChB3ZrkK',NULL,6,0,'2026-05-17 17:18:12','2026-05-20 06:17:19',15),
(6,'242211086','FADILLAH AYMAN SEPTIANA','$2y$12$DJ7axJvzwewfScHV5ZuTg.9EKEWIcKeni4qiP3j/1RcVMRtFTPboW',NULL,13,0,'2026-05-17 17:18:13','2026-05-20 06:17:19',11),
(7,'242211088','BAKHRAENI','$2y$12$P.rG1BnInwEjqaF/eWaNSu8IvtzPyOajZ6PQ2BZiJEp.4RA2zIm/y',NULL,19,0,'2026-05-17 17:18:13','2026-05-20 06:17:19',7),
(8,'242211089','RIFANA AL MUNAWWARAH','$2y$12$N0JuBwmZRlKMCBxQ22ka5.1H/DwrrI86pIm3F8dPHR/MjnOURJJaa',NULL,16,0,'2026-05-17 17:18:13','2026-05-20 06:17:19',17),
(9,'242211090','LORITSZA SITI AULIA NOOR','$2y$12$QBjqq32h918ZX5xgv0vnf.nhzXbfFNYN66yXzyXQDmiSaU8US5jXS','foto-mahasiswa/ul5pFEqrVpWpmZ1BFXzhKEQb9ZqhAOoPrRX7PSU1.png',1,0,'2026-05-17 17:18:13','2026-05-20 06:17:19',18),
(10,'242211091','FINA FENIKA','$2y$12$2xGxxA8LFk8Wb7dbHUrjzOVw3eF9jgSHmA8qTY2tgf2G/hiEnYK8a',NULL,26,0,'2026-05-17 17:18:13','2026-05-20 06:17:19',11),
(11,'242211092','NURFADILLAH AMIRUDDIN','$2y$12$3iPALKRQXvabJQmfcpWtVOaa4tRuhF5dU8L4WW3rlgGIHxlZVl29i',NULL,24,0,'2026-05-17 17:18:14','2026-05-20 06:17:19',15),
(12,'242211097','CITRA WULANDARI','$2y$12$5XjxlPqdqS0F1WOAP9LSm.0FBGV/174prP6bl1n40xFBGBfqpNsFK',NULL,18,0,'2026-05-17 17:18:14','2026-05-20 06:17:19',15),
(13,'242221098','ANGGIKA JANUARKA','$2y$12$YdbpoI51Hfp0oCHVkvmf7ODR87wS2AuEJppf.hIypxwqdRZtFP08O',NULL,4,0,'2026-05-17 17:18:14','2026-05-20 06:17:19',1),
(14,'242221100','NURFADILA','$2y$12$sm8Y6nV6vZi7//W1mThTaO1//uMGcyGPPchu4q/XmmvHTu8lqlXne',NULL,5,0,'2026-05-17 17:18:14','2026-05-20 06:17:19',9),
(15,'242221106','NAHDAH MUHIBAH','$2y$12$3Xds1t6/sIzNcmIDrh8PbePozjvaLtJWIcOKHtFfM3VJ4Sse0nhdq',NULL,3,0,'2026-05-17 17:18:14','2026-05-20 06:17:19',20),
(16,'242221113','YUNI SARDILA','$2y$12$s8ROKJmi/GxJgX4LYrGB3OkhcyuX4oCKiMp5aXAS0F/N9HSDLfjt2',NULL,10,0,'2026-05-17 17:18:14','2026-05-20 06:17:19',19),
(17,'242221114','MELISA','$2y$12$S10kCgoAdyTwiARZsne3K.O6VTvhMH7C56g8.OjLtBiWF2kinZ14i',NULL,21,0,'2026-05-17 17:18:15','2026-05-20 06:17:19',12),
(18,'242221115','FATIMAH AZZAHARA ILHAM','$2y$12$JQ2uR.FFO24VnZXvU5nW0uvKF/oXclOLZ1ApaqnJqawYeC6YwPcGC',NULL,22,0,'2026-05-17 17:18:15','2026-05-20 06:17:19',20),
(19,'242221116','NURHIJRA','$2y$12$MsrbT8wqQ6w8YEB1cqaKoOos5Pp6js4IH1bd.gOX4/wm5kIRr65DC',NULL,14,0,'2026-05-17 17:18:15','2026-05-20 06:17:19',5),
(20,'242221117','ILBRIANA','$2y$12$2IltJ2SHfapVxdovEpN8UuEjDcyrAFm2MdAAUAMfZTPeiFhlwvFmu',NULL,23,0,'2026-05-17 17:18:15','2026-05-20 06:17:19',2),
(21,'242221118','DEA','$2y$12$uH0mzIJhlpDHJ.O3vbpIXOpV5UtpXe/9C3MzDq2QY2sznwneB3hXO',NULL,9,0,'2026-05-17 17:18:15','2026-05-20 06:17:19',11),
(22,'242221119','KADEK FERI PURNIAWAN','$2y$12$VP9ILeaod6NfLF98NqvliOTuWZT5xLakgR/Ruxa/keJZgppCR1a6q',NULL,25,0,'2026-05-17 17:18:15','2026-05-20 06:17:19',6),
(23,'242221120','LUKMANUL RAHMAN','$2y$12$camWFzbxmIU0Y5iVSY3zHe/1SPH.Ujc2E4gtM0LHX/E1RWM/OJnsi',NULL,11,0,'2026-05-17 17:18:16','2026-05-20 06:17:19',7),
(24,'242221121','NUR SALSABILA','$2y$12$cnMumUB9j4pdOcXcTQgnEed7Q.XmPz8byH8yqAKyu6r7unkE.OXCS',NULL,2,0,'2026-05-17 17:18:16','2026-05-20 06:17:19',4),
(25,'242221122','HILWA ZABILA','$2y$12$kR4IvRgMiJaoK3gvmo4fnOJLNMyVJNrDqZ1VBM/nBhO.DxmkQBlq2',NULL,15,0,'2026-05-17 17:18:16','2026-05-20 06:17:19',12),
(26,'242221125','NABILA ANGGUN AZZAHRA','$2y$12$8Xmnp25D.t1zGu235hJx0ucffwbV7kMey9PH8cuLz6xbspilaWZVm',NULL,7,0,'2026-05-17 17:18:16','2026-05-20 06:17:19',1);
/*!40000 ALTER TABLE `mahasiswas` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_01_29_055506_add_avatar_to_users_table',1),
(5,'2026_05_16_125424_create_mahasiswas_table',1),
(6,'2026_05_16_125424_create_slide_presentasis_table',1),
(7,'2026_05_16_130805_add_password_to_mahasiswas_table',1),
(8,'2026_05_16_134558_add_urutan_tampil_to_mahasiswas_table',1),
(9,'2026_05_17_034859_create_temas_table',1),
(10,'2026_05_17_035221_add_tema_id_to_mahasiswas_table',1),
(11,'2026_05_17_035613_create_juris_table',1),
(12,'2026_05_17_040955_create_nilais_table',1),
(13,'2026_05_17_042811_create_presentasi_settings_table',1),
(14,'2026_05_17_045345_add_pause_to_presentasi_settings',1),
(15,'2026_05_17_053659_add_current_slide_index_to_presentasi_settings',1),
(16,'2026_05_17_083335_add_all_scored_at_to_presentasi_settings',1),
(17,'2026_05_20_124500_add_phase_to_presentasi_settings',2),
(18,'2026_05_20_131000_create_backsounds_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `nilais`
--

DROP TABLE IF EXISTS `nilais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `nilais` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `juri_id` bigint(20) unsigned NOT NULL,
  `mahasiswa_id` bigint(20) unsigned NOT NULL,
  `nilai` tinyint(3) unsigned NOT NULL COMMENT 'Skor 1-100',
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nilais_juri_id_mahasiswa_id_unique` (`juri_id`,`mahasiswa_id`),
  KEY `nilais_mahasiswa_id_foreign` (`mahasiswa_id`),
  CONSTRAINT `nilais_juri_id_foreign` FOREIGN KEY (`juri_id`) REFERENCES `juris` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nilais_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nilais`
--

LOCK TABLES `nilais` WRITE;
/*!40000 ALTER TABLE `nilais` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `nilais` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `presentasi_settings`
--

DROP TABLE IF EXISTS `presentasi_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `presentasi_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `phase` varchar(255) NOT NULL DEFAULT 'idle',
  `is_paused` tinyint(1) NOT NULL DEFAULT 0,
  `current_mahasiswa_id` bigint(20) unsigned DEFAULT NULL,
  `timer_started_at` timestamp NULL DEFAULT NULL,
  `timer_remaining` int(10) unsigned DEFAULT NULL,
  `current_slide_index` int(11) NOT NULL DEFAULT 0,
  `all_scored_at` timestamp NULL DEFAULT NULL,
  `countdown_started_at` timestamp NULL DEFAULT NULL,
  `current_backsound_id` bigint(20) unsigned DEFAULT NULL,
  `music_playing` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `presentasi_settings_current_mahasiswa_id_foreign` (`current_mahasiswa_id`),
  KEY `presentasi_settings_current_backsound_id_foreign` (`current_backsound_id`),
  CONSTRAINT `presentasi_settings_current_backsound_id_foreign` FOREIGN KEY (`current_backsound_id`) REFERENCES `backsounds` (`id`) ON DELETE SET NULL,
  CONSTRAINT `presentasi_settings_current_mahasiswa_id_foreign` FOREIGN KEY (`current_mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presentasi_settings`
--

LOCK TABLES `presentasi_settings` WRITE;
/*!40000 ALTER TABLE `presentasi_settings` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `presentasi_settings` VALUES
(1,0,'idle',0,NULL,NULL,NULL,0,NULL,NULL,1,0,'2026-05-17 17:18:04','2026-05-20 16:26:46');
/*!40000 ALTER TABLE `presentasi_settings` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sessions` VALUES
('CcxDUTgOMt1svDxMIv9rmQONO86tPWKRVDIJX0Fb',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:146.0) Gecko/20100101 Firefox/146.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiM3QzZHRET01yRGtUTHVCdjNQSWFyN0M3QnV4UUdnTE1yRzUybEVRWSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozODoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL21haGFzaXN3YS9zbGlkZXMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozODoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL21haGFzaXN3YS9zbGlkZXMiO3M6NToicm91dGUiO3M6MTY6Im1haGFzaXN3YS5zbGlkZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1779322504),
('jsDS8aMw4TzbK7jj54hLy0MNRiiPfSgAcMXJ5SAi',1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:146.0) Gecko/20100101 Firefox/146.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiU0xsUmpUaDJTNWw3OUU3U2NaZ2p0cFp6TE02NFVrWDk3ZzZjMXoyUyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9wcmVzZW50YXNpIjtzOjU6InJvdXRlIjtzOjE2OiJhZG1pbi5wcmVzZW50YXNpIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',1779323214);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `slide_presentasis`
--

DROP TABLE IF EXISTS `slide_presentasis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `slide_presentasis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` bigint(20) unsigned NOT NULL,
  `urutan` int(11) NOT NULL,
  `file_gambar` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `slide_presentasis_mahasiswa_id_foreign` (`mahasiswa_id`),
  CONSTRAINT `slide_presentasis_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slide_presentasis`
--

LOCK TABLES `slide_presentasis` WRITE;
/*!40000 ALTER TABLE `slide_presentasis` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `slide_presentasis` VALUES
(1,9,1,'slides/ck9So6tBilAGWOiSnFqQbCIvgtKfR6ZuxcpG4ePE.png','2026-05-20 05:06:34','2026-05-20 05:06:34'),
(2,9,2,'slides/BGBqIypZGcEFqvVVI3adrzZJuHfRSMok3Mc8YAE1.png','2026-05-20 05:06:35','2026-05-20 05:06:35');
/*!40000 ALTER TABLE `slide_presentasis` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `temas`
--

DROP TABLE IF EXISTS `temas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `temas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temas`
--

LOCK TABLES `temas` WRITE;
/*!40000 ALTER TABLE `temas` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `temas` VALUES
(1,'Kuliah daring: Benarkah kita belajar?','2026-05-20 06:14:29','2026-05-20 06:14:29'),
(2,'IPK tinggi vs. Keterampilan tinggi','2026-05-20 06:14:29','2026-05-20 06:14:29'),
(3,'Mengapa banyak sarjana menganggur?','2026-05-20 06:14:29','2026-05-20 06:14:29'),
(4,'AI di kelas: Musuh atau teman?','2026-05-20 06:14:29','2026-05-20 06:14:29'),
(5,'Kesehatan mental bukan berarti cengeng','2026-05-20 06:14:29','2026-05-20 06:14:29'),
(6,'Budaya batal (cancel culture): Kritik atau perundungan massal?','2026-05-20 06:14:29','2026-05-20 06:14:29'),
(7,'FOMO vs. JOMO','2026-05-20 06:14:29','2026-05-20 06:14:29'),
(8,'Literasi keuangan anak kos','2026-05-20 06:14:29','2026-05-20 06:14:29'),
(9,'Hidup lambat (slow living) di era serba cepat','2026-05-20 06:14:29','2026-05-20 06:14:29'),
(10,'Menjadi aktivis dari kamar kos','2026-05-20 06:14:29','2026-05-20 06:14:29'),
(11,'Satu kebiasaan toksik','2026-05-20 06:14:29','2026-05-20 06:14:29'),
(12,'Chat ke diri sendiri','2026-05-20 06:14:29','2026-05-20 06:14:29'),
(13,'Perubahan kampus','2026-05-20 06:14:29','2026-05-20 06:14:29'),
(14,'Jujur saja','2026-05-20 06:14:29','2026-05-20 06:14:29'),
(15,'Aplikasi penyelamat','2026-05-20 06:14:29','2026-05-20 06:14:29'),
(16,'Sindrom penipu (impostor syndrome) di kampus','2026-05-20 06:14:29','2026-05-20 06:14:29'),
(17,'Kelelahan (burnout) vs. produktivitas toksik','2026-05-20 06:14:29','2026-05-20 06:14:29'),
(18,'Berani berkata tidak','2026-05-20 06:14:29','2026-05-20 06:14:29'),
(19,'Pencitraan diri (personal branding) sejak semester satu','2026-05-20 06:14:29','2026-05-20 06:14:29'),
(20,'Krisis seperempat abad (quarter-life crisis) versi mahasiswa','2026-05-20 06:14:29','2026-05-20 06:14:29');
/*!40000 ALTER TABLE `temas` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `users` VALUES
(1,'Admin','admin@gmail.com',NULL,'2026-05-17 17:18:04','$2y$12$22.lQkO7A2l4OVVlctKwAOLxyq9e14KoTg/AOVDRBDLGiuEdnxbtG','1XYMfCoD29','2026-05-17 17:18:05','2026-05-17 17:18:05');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
commit;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-05-21  9:24:21
