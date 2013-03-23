DROP TABLE IF EXISTS `{DB_PREFIX}fieldtypes`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}fieldtypes` (
  `k` varchar(20) NOT NULL,
  `v` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DELETE FROM `{DB_PREFIX}fieldtypes`;
INSERT INTO `{DB_PREFIX}fieldtypes` (`k`, `v`) VALUES ('int', '整形(INT)'), ('float', '浮点型(FLOAT)'), ('input', '单行文本框(VARCHAR)'), ('textarea', '文本区域(VARCHAR)'), ('select', '下拉菜单(VARCHAR)'), ('select_from_model', '下拉菜单(模型数据)(INT)'), ('linked_menu', '联动下拉菜单(VARCHAR)'), ('radio', '单选按钮(VARCHAR)'), ('radio_from_model', '单选按钮(模型数据)(INT)'), ('checkbox', '复选框(VARCHAR)'), ('checkbox_from_model', '复选框(模型数据)(VARCHAR)'), ('wysiwyg', '编辑器(TEXT)'), ('wysiwyg_basic', '编辑器(简)(TEXT)'), ('datetime', '日期时间(VARCHAR)'), ('colorpicker', '颜色选择器(VARCHAR)', ('content', '内容模型调用(INT)'));