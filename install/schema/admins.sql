DROP TABLE IF EXISTS `{DB_PREFIX}admins`{SEPERATOR}
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}admins` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(16) NOT NULL,
  `password` varchar(40) NOT NULL,
  `salt` varchar(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` smallint(5) unsigned DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '1=正常，2=冻结',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  KEY `group` (`role`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8{SEPERATOR}
DELETE FROM `{DB_PREFIX}admins`
