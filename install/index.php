<?php
define('ROOT_PATH', dirname(__FILE__).'/../');
require_once(ROOT_PATH.'./install/common.php');

if(get_magic_quotes_gpc())
{
	$_POST    = stripSlash($_POST);
	$_GET     = stripSlash($_GET);
	$_COOKIE  = stripSlash($_COOKIE);
}

$act = url_get('act');
if($act != '')
{
	//载入函数库
	require(ROOT_PATH.'./install/include/function.php');

	if(function_exists($act))
	{
		return call_user_func($act);
	}
	else if(file_exists(ROOT_PATH.'./install/'.$act.'.php'))
	{
		require(ROOT_PATH.'./install/'.$act.'.php');
	}
}
else
{
	require(ROOT_PATH.'./install/install.php');
}
?>