DROP TABLE IF EXISTS `{DB_PREFIX}menus`{SEPERATOR}
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}menus` (
  `menu_id` tinyint(10) unsigned NOT NULL AUTO_INCREMENT,
  `class_name` varchar(20) NOT NULL,
  `method_name` varchar(30) NOT NULL,
  `menu_name` varchar(20) NOT NULL,
  `menu_level` tinyint(2) unsigned DEFAULT '0',
  `menu_parent` tinyint(10) unsigned DEFAULT '0',
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8{SEPERATOR}
DELETE FROM `{DB_PREFIX}menus`{SEPERATOR}
INSERT INTO `{DB_PREFIX}menus` (`menu_id`, `class_name`, `method_name`, `menu_name`, `menu_level`, `menu_parent`) VALUES (1, 'system', 'home', '系统', 0, 0), (2, 'system', 'home', '后台首页', 1, 1), (3, 'system', 'home', '后台首页', 2, 2), (4, 'setting', 'site', '系统设置', 1, 1), (5, 'setting', 'site', '站点设置', 2, 4), (6, 'setting', 'backend', '后台设置', 2, 4), (7, 'system', 'password', '修改密码', 2, 4), (8, 'system', 'cache', '更新缓存', 2, 4), (9, 'model', 'view', '模型管理', 1, 1), (10, 'model', 'view', '内容模型管理', 2, 9), (11, 'category', 'view', '分类模型管理', 2, 9), (12, 'plugin', 'view', '扩展管理', 1, 1), (13, 'plugin', 'view', '插件管理', 2, 12), (14, 'role', 'view', '权限管理', 1, 1), (15, 'role', 'view', '用户组管理', 2, 14), (16, 'user', 'view', '用户管理', 2, 14), (17, 'content', 'view', '内容管理', 0, 0), (18, 'content', 'view', '内容管理', 1, 17), (19, 'category_content', 'view', '分类管理', 1, 17), (20, 'module', 'run', '插件', 0, 0), (21, 'database', 'index', '数据库管理', 1, 1), (22, 'database', 'index', '数据库备份', 2, 21), (23, 'database', 'recover', '数据库还原', 2, 21), (24, 'database', 'optimize', '数据库优化', 2, 21)