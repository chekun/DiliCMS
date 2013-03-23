DROP TABLE IF EXISTS `{DB_PREFIX}validations`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}validations` (
  `k` varchar(20) DEFAULT NULL,
  `v` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DELETE FROM `{DB_PREFIX}validations`;
INSERT INTO `{DB_PREFIX}validations` (`k`, `v`) VALUES ('required', '必填'), ('valid_email', 'E-mail格式');