DROP TABLE IF EXISTS `{DB_PREFIX}roles`{SEPERATOR}
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `rights` varchar(255) NOT NULL,
  `models` varchar(255) NOT NULL,
  `category_models` varchar(255) NOT NULL,
  `plugins` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8{SEPERATOR}
DELETE FROM `{DB_PREFIX}roles`{SEPERATOR}
INSERT INTO `{DB_PREFIX}roles` (`id`, `name`, `rights`, `models`, `category_models`, `plugins`) VALUES (1, 'root', '', '', '', '')