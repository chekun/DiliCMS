DROP TABLE IF EXISTS `{DB_PREFIX}sessions`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DELETE FROM `{DB_PREFIX}sessions`;