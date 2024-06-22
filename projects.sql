CREATE DATABASE IF NOT EXISTS `project`;
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
	(1, 'Admin', 'admin@admin.nl', '$2y$10$mDitVJH0pzYjSxUZ.4zeIebfUg8QNAS8ma91ZHpVJnH679mbAB3Mq', 'admin', 1, 1, 1);

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
