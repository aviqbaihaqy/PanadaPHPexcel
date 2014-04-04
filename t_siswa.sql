/*
Navicat MySQL Data Transfer

Source Server         : lokalcoys
Source Server Version : 50524
Source Host           : localhost:3306
Source Database       : fastikom_formulir

Target Server Type    : MYSQL
Target Server Version : 50524
File Encoding         : 65001

Date: 2014-04-04 10:01:58
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for t_siswa
-- ----------------------------
DROP TABLE IF EXISTS `t_siswa`;
CREATE TABLE `t_siswa` (
  `NIS` varchar(12) NOT NULL,
  `NO_CALSIS` varchar(9) DEFAULT NULL,
  `NM_SISWA` varchar(50) NOT NULL,
  `NM_PANGGILAN` varchar(20) NOT NULL,
  `KD_JENIS_KELAMIN` char(1) NOT NULL,
  `KOTA_LAHIR` varchar(50) NOT NULL,
  `TANGGAL_LAHIR` date NOT NULL,
  `ALAMAT` varchar(150) NOT NULL,
  `RT` varchar(3) DEFAULT NULL,
  `RW` varchar(2) DEFAULT NULL,
  `KD_POS` char(5) DEFAULT NULL,
  `KD_GOL_DARAH` char(1) NOT NULL DEFAULT '4',
  `KD_AGAMA` char(1) NOT NULL,
  `NO_TELP` varchar(30) DEFAULT NULL,
  `NO_HP` varchar(30) DEFAULT NULL,
  `STATUS_SISWA` char(1) NOT NULL DEFAULT '0',
  `KEWARGANEGARAAN` varchar(50) NOT NULL,
  `ANAK_KE` tinyint(3) unsigned DEFAULT '1',
  `JUMLAH_KANDUNG` tinyint(3) unsigned DEFAULT '0',
  `JUMLAH_TIRI` tinyint(3) unsigned DEFAULT '0',
  `JUMLAH_ANGKAT` tinyint(3) unsigned DEFAULT '0',
  `STATUS_YATIM_PIATU` char(1) DEFAULT NULL,
  `BAHASA` varchar(50) DEFAULT NULL,
  `TINGGAL_DI` varchar(50) DEFAULT NULL,
  `JARAK_SEK` decimal(5,2) DEFAULT NULL,
  `KELAINAN_JASMANI` varchar(50) DEFAULT NULL,
  `BERAT_BADAN` tinyint(3) unsigned DEFAULT NULL,
  `TINGGI_BADAN` tinyint(3) unsigned DEFAULT NULL,
  `ASAL_SMP` smallint(5) unsigned DEFAULT NULL,
  `NO_STL_SMP` varchar(30) DEFAULT NULL,
  `TANGGAL_STL_SMP` date DEFAULT NULL,
  `LAMA_BELAJAR_SMP` tinyint(3) unsigned DEFAULT NULL,
  `ASAL_SMA` smallint(5) unsigned DEFAULT NULL,
  `KD_TINGKAT_KELAS` char(2) NOT NULL DEFAULT '01',
  `KD_PROGRAM_PENGAJARAN` char(1) NOT NULL DEFAULT '1',
  `DITERIMA_TANGGAL` date NOT NULL DEFAULT '1818-09-09',
  `PINDAH_ALASAN` varchar(255) DEFAULT NULL,
  `HUBUNGI` char(1) NOT NULL,
  `TANGGUNG_BIAYA` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `TEMP_AKSES_NET` char(1) DEFAULT NULL,
  `FREK_AKSES_NET` char(1) DEFAULT NULL,
  `FREK_REKRE_KEL` char(1) DEFAULT NULL,
  `NILAI` decimal(5,3) DEFAULT NULL,
  `NO_INDUK` char(12) NOT NULL DEFAULT '',
  `DIR_FOTO` varchar(255) DEFAULT NULL,
  `STATUS_ENTRI` char(1) NOT NULL DEFAULT '1',
  `KD_JENIS_KETUNAAN` char(1) DEFAULT NULL,
  `KD_STATUS_DALAM_KELUARGA` char(1) DEFAULT NULL,
  `TANGGAL_SKHUN_SMP` date DEFAULT NULL,
  `NO_SKHUN_SMP` varchar(30) DEFAULT NULL,
  `NISN` char(18) DEFAULT NULL,
  `NIK` varchar(20) DEFAULT NULL,
  `JENIS_TINGGAL` char(1) NOT NULL DEFAULT '1' COMMENT '1=Bersama Orangtua; 2=Wali; 3=Kost; 4=Asrama; 5=Panti Asuhan; 9=Lainnya',
  `KELURAHAN_DESA` varchar(100) DEFAULT NULL,
  `KECAMATAN` varchar(100) DEFAULT NULL,
  `KABUPATEN_KOTA` varchar(100) DEFAULT NULL,
  `PROPINSI` varchar(100) DEFAULT NULL,
  `KD_AREA` varchar(5) DEFAULT NULL,
  `KD_JARAK_SEK` char(1) DEFAULT NULL COMMENT '1=Kurang dari 1 Km; 2=Lebih dari 1 Km',
  `ALAT_TRANSPORTASI` char(2) DEFAULT NULL COMMENT '01=Jalan Kaki; 02=Kendaraan Pribadi; 03=Kendaraan Umum; 04=Jemputan Sekolah; 05=Kereta Api; 06=Ojek',
  `EMAIL_PRIBADI` varchar(255) DEFAULT NULL,
  `USERNAME` varchar(20) NOT NULL,
  `TANGGAL_AKSES` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`NIS`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
