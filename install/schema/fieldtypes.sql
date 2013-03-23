DROP TABLE IF EXISTS `{DB_PREFIX}fieldtypes`{SEPERATOR}
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}fieldtypes` (
  `k` varchar(20) NOT NULL,
  `v` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8{SEPERATOR}
DELETE FROM `{DB_PREFIX}fieldtypes`{SEPERATOR}
INSERT INTO `{DB_PREFIX}fieldtypes` (`k`, `v`) VALUES ('int', '整形(INT)'){SEPERATOR}
INSERT INTO `{DB_PREFIX}fieldtypes` (`k`, `v`) VALUES ('float', '浮点型(FLOAT)'){SEPERATOR}
INSERT INTO `{DB_PREFIX}fieldtypes` (`k`, `v`) VALUES ('input', '单行文本框(VARCHAR)'){SEPERATOR}
INSERT INTO `{DB_PREFIX}fieldtypes` (`k`, `v`) VALUES ('textarea', '文本区域(VARCHAR)'){SEPERATOR}
INSERT INTO `{DB_PREFIX}fieldtypes` (`k`, `v`) VALUES ('select', '下拉菜单(VARCHAR)'){SEPERATOR}
INSERT INTO `{DB_PREFIX}fieldtypes` (`k`, `v`) VALUES ('select_from_model', '下拉菜单(模型数据)(INT)'){SEPERATOR}
INSERT INTO `{DB_PREFIX}fieldtypes` (`k`, `v`) VALUES ('linked_menu', '联动下拉菜单(VARCHAR)'){SEPERATOR}
INSERT INTO `{DB_PREFIX}fieldtypes` (`k`, `v`) VALUES ('radio', '单选按钮(VARCHAR)'){SEPERATOR}
INSERT INTO `{DB_PREFIX}fieldtypes` (`k`, `v`) VALUES ('radio_from_model', '单选按钮(模型数据)(INT)'){SEPERATOR}
INSERT INTO `{DB_PREFIX}fieldtypes` (`k`, `v`) VALUES ('checkbox', '复选框(VARCHAR)'){SEPERATOR}
INSERT INTO `{DB_PREFIX}fieldtypes` (`k`, `v`) VALUES ('checkbox_from_model', '复选框(模型数据)(VARCHAR)'){SEPERATOR}
INSERT INTO `{DB_PREFIX}fieldtypes` (`k`, `v`) VALUES ('wysiwyg', '编辑器(TEXT)'){SEPERATOR}
INSERT INTO `{DB_PREFIX}fieldtypes` (`k`, `v`) VALUES ('wysiwyg_basic', '编辑器(简)(TEXT)'){SEPERATOR}
INSERT INTO `{DB_PREFIX}fieldtypes` (`k`, `v`) VALUES ('datetime', '日期时间(VARCHAR)'){SEPERATOR}
INSERT INTO `{DB_PREFIX}fieldtypes` (`k`, `v`) VALUES ('colorpicker', '颜色选择器(VARCHAR)'{SEPERATOR}
INSERT INTO `{DB_PREFIX}fieldtypes` (`k`, `v`) VALUES ('content', '内容模型调用(INT)')