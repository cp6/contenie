-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.11-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             10.1.0.5464
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for contenie
CREATE DATABASE IF NOT EXISTS `contenie` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `contenie`;

-- Dumping structure for table contenie.accounts
CREATE TABLE IF NOT EXISTS `accounts` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(24) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(84) DEFAULT NULL,
  `login_count` int(11) DEFAULT 0,
  `login_fails` int(11) DEFAULT 0,
  `logged_out` datetime DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `last_login_ip` varchar(50) DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `activated` tinyint(1) DEFAULT 0,
  `account_type` tinyint(1) DEFAULT 1,
  `last_fail` datetime DEFAULT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `user_key` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table contenie.login_attempts
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(24) NOT NULL,
  `ip` varchar(55) DEFAULT NULL,
  `datetime` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
