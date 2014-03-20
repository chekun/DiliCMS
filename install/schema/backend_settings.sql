DROP TABLE IF EXISTS `{DB_PREFIX}backend_settings`{SEPERATOR}
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}backend_settings` (
  `backend_theme` varchar(15) DEFAULT NULL,
  `backend_lang` varchar(10) DEFAULT NULL,
  `backend_root_access` tinyint(1) unsigned DEFAULT '1',
  `backend_access_point` varchar(20) DEFAULT 'admin',
  `backend_title` varchar(100) DEFAULT 'DiliCMS后台管理',
  `backend_logo` varchar(100) DEFAULT 'images/logo.gif',
  `plugin_dev_mode` TINYINT(1) UNSIGNED NOT NULL DEFAULT  '0',
  `backend_http_auth_on`  TINYINT(1)  NULL  DEFAULT '0',
  `backend_http_auth_user` VARCHAR(40)  NULL  DEFAULT NULL,
  `backend_http_auth_password` VARCHAR(40)  NULL  DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8{SEPERATOR}
DELETE FROM `{DB_PREFIX}backend_settings`{SEPERATOR}
INSERT INTO `{DB_PREFIX}backend_settings` (`backend_theme`, `backend_lang`, `backend_root_access`, `backend_access_point`, `backend_title`, `backend_logo`) VALUES ('default', 'zh-cn', 1, '', 'DiliCMS', 'images/logo.gif')