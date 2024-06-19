/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE DATABASE IF NOT EXISTS `project` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `project`;

CREATE TABLE IF NOT EXISTS `bestellingen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `bestellingen_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `gebruikers` (`id`),
  CONSTRAINT `bestellingen_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `producten` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `gebruikers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL DEFAULT '0',
  `password` varchar(255) NOT NULL DEFAULT '0',
  `type` varchar(50) NOT NULL DEFAULT 'user',
  `deleteUserPerms` int(11) NOT NULL DEFAULT 0,
  `ChangeUserType` int(11) NOT NULL DEFAULT 0,
  `code` mediumint(50) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4;

INSERT INTO `gebruikers` (`id`, `full_name`, `email`, `password`, `type`, `deleteUserPerms`, `ChangeUserType`, `code`) VALUES
	(1, 'Anas', 'scipto1990@gmail.com', '$2y$10$45SqKZ6MJueKmtjE3KYgAOQp9Ot35sOliZAoHlf5YPCPKleglSvZC', 'admin', 0, 0, 0),
	(46, 'Gebruiker', 'gebruiker@gebruiker.nl', '$2y$10$E8T.BI7HshtDeeSdmKekb.7Zyvxrjw/nPbH0hag5j9aRl0w2ws.46', 'admin', 1, 0, 0),
	(49, 'DEMO', 'scipto19990@gmail.com', '$2y$10$XRyHXNFNnI69fq/v4tpOceuPN22dsvVBroLxkyku9JuN9U16X1V42', 'user', 1, 0, 0),
	(52, 'test@test.nl', 'test@test.nl', '$2y$10$pIzyr7Ccs4mvbTsSi0cl/eFKuZJvnXZGBwbX6ZB9SXZ6AzfdTRtx.', 'admin', 1, 0, 0),
	(53, 'chai', 'chichi9@hotmail.nl', '$2y$10$sz.eiJhdpAdyyxq59TjUn.qnN57QbN7i/0OSlovelYrbF6ZIwcVdG', 'admin', 0, 0, 0);

CREATE TABLE IF NOT EXISTS `producten` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `naam` varchar(50) NOT NULL DEFAULT 'test',
  `prijs` varchar(50) NOT NULL DEFAULT '1',
  `img` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

INSERT INTO `producten` (`id`, `naam`, `prijs`, `img`) VALUES
	(1, 'Iphone 15', '1', 'https://i.imgur.com/jOzORSm.png'),
	(2, 'Iphone 15', '999', 'https://i.imgur.com/B2FxR3f.png'),
	(3, 'Iphone 11', '1234', 'https://i.imgur.com/jOzORSm.png'),
	(4, 'Iphone 15', '1999', 'https://i.imgur.com/jOzORSm.png'),
	(5, 'Iphone 12', '1', 'https://i.imgur.com/B2FxR3f.png'),
	(6, 'Iphone 15', '1234', 'https://i.imgur.com/B2FxR3f.png'),
	(7, 'Iphone 15', '1', 'https://i.imgur.com/jOzORSm.png'),
	(8, 'Iphone 13', '1234', 'https://i.imgur.com/B2FxR3f.png');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
