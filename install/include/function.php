<?php
//测试链接数据库
function check_mysql()
{
	$is_connect = false;

	$db_host = url_get('db_address');
	$db_user = url_get('db_user');
	$db_pwd  = url_get('db_pwd');

	if($db_host != '' && function_exists('mysql_connect'))
	{
		$is_connect = mysql_connect($db_host,$db_user,$db_pwd);
	}

	if($is_connect)
	{
		echo 1;
	}
	else
	{
		echo -1;
	}
}

//解析备份文件中的SQL
function parseSQL($fileName)
{
	global $db_pre;

	//执行sql query次数的计数器 默认值
	$queryTimes = 0;

	//与前端交互的频率(数值与频率成反比,0表示关闭交互)
	$waitTimes  = 5;

	$is_test = false;
	if(stripos(basename($fileName),'_test') !== false)
	{
		$is_test = true;
	}

	$percent   = 0;
	$fhandle   = fopen($fileName,'r');
	$firstLine = fgets($fhandle);
	rewind($fhandle);

	//跨过BOM头信息
	$charset[1] = substr($firstLine,0,1);
	$charset[2] = substr($firstLine,1,1);
	$charset[3] = substr($firstLine,2,1);
	if(ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191)
	{
		fseek($fhandle,3);
	}

	//计算安装进度
	$totalSize  = filesize($fileName);

	while(!feof($fhandle))
	{
		$lstr = fgets($fhandle);     //获取指针所在的一行数据

		//判断当前行存在字符
		if(isset($lstr[0]) && $lstr[0]!='#')
		{
			$prefix = substr($lstr,0,2);  //截取前2字符判断SQL类型
			switch($prefix)
			{
				case '--' :
				case '//' :
				{
					continue;
				}

				case '/*':
				{
					if(substr($lstr,-5) == "*/;\r\n" || substr($lstr,-4) == "*/\r\n")
						continue;
					else
					{
						skipComment($fhandle);
						continue;
					}
				}

				default :
				{
					$sqlArray[] = trim($lstr);
					if(substr(trim($lstr),-1) == ";")
					{
						$rcount   = 1;
						$sqlStr   = str_ireplace('_no_need','',join($sqlArray),$rcount); //更换表前缀
						$sqlArray = array();
						$result   = mysql_query($sqlStr);

						$queryTimes++;
						if($waitTimes > 0 && ($queryTimes/$waitTimes == 1))
						{
							$queryTimes = 0;

							//计算安装进度百分比
							$percent    = ftell($fhandle)/($totalSize+1);
							sqlCallBack($sqlStr,$result,$percent,$is_test);
							set_time_limit(1000);
						}
					}
				}
			}
		}
	}
}

//略过注释
function skipComment($fhandle)
{
	$lstr = fgets($fhandle,4096);
	if(substr($lstr,-5) == "*/;\r\n" || substr($lstr,-4) == "*/\r\n")
		return true;
	else
		skipComment($fhandle);
}

//sql回调函数
function sqlCallBack($sql,$result,$percent,$is_test = false)
{
	//创建表
	if(preg_match('/create\s+table\s+(\S+)/i',$sql,$match))
	{
		$tableName = isset($match[1]) ? $match[1] : '';
		$message   = '创建表'.$tableName;
	}
	//插入数据
	else if(preg_match('/insert\s+into/i',$sql))
	{
		$message   = '插入数据';
	}
	//其余操作
	else
	{
		$message   = '执行SQL';
	}

	//判断sql执行结果
	if($result)
	{
		$isError  = false;
		$message .= '...';
	}
	else
	{
		$isError  = true;
		$message .= ' 失败! '.mysql_error();
	}

	//是否安装测试数据
	if($is_test == true)
	{
		$message = '安装体验数据 ,'.$message;
	}

	$return_info = array(
		'isError' => $isError,
		'message' => $message,
		'percent' => $percent
	);

	showProgress($return_info);
	usleep(5000);
}

//安装mysql数据库
function install_sql()
{
	global $db_pre;

	//安装配置信息
	$db_address   = url_get('db_address');
	$db_user      = url_get('db_user');
	$db_pwd       = url_get('db_pwd');
	$db_name      = url_get('db_name');
	$db_pre       = url_get('db_pre');
	$admin_user   = url_get('admin_user');
	$admin_pwd    = url_get('admin_pwd');
	$install_type = url_get('install_type');

	//链接mysql数据库
	$mysql_link = @mysql_connect($db_address,$db_user,$db_pwd);
	if(!$mysql_link)
	{
		showProgress(array('isError' => true,'message' => 'mysql链接失败'.mysql_error()));
	}

	//检测SQL安装文件
	$sql_file = ROOT_PATH.'./install/dilicms.sql';
	if(!file_exists($sql_file))
	{
		showProgress(array('isError' => true,'message' => '安装的SQL文件'.basename($sql_file).'不存在'));
	}

	//检测测试数据SQL文件
	$sql_test_file = ROOT_PATH.'./install/dilicms_test.sql';
	if($install_type == 'all' && !file_exists($sql_test_file))
	{
		showProgress(array('isError' => true,'message' => '测试数据SQL文件'.basename($sql_test_file).'不存在'));
	}

	//执行SQL,创建数据库操作
	mysql_query("set names 'UTF8'");

	if(!@mysql_select_db($db_name))
	{
		$DATABASESQL = '';
		if(version_compare(mysql_get_server_info(), '4.1.0', '>='))
		{
	    	$DATABASESQL = "DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
		}
		if(!mysql_query('CREATE DATABASE `'.$db_name.'` '.$DATABASESQL))
		{
			showProgress(array('isError' => true,'message' => '用户权限受限，创建'.$db_name.'数据库失败，请手动创建数据表'));
		}
	}

	if(!@mysql_select_db($db_name))
	{
		showProgress(array('isError' => true,'message' => $db_name.'数据库不存在'.mysql_error()));
	}

	//安装SQL
	parseSQL($sql_file);

	//安装测试数据
	if($install_type == 'all')
	{
		parseSQL($sql_test_file);
	}

	//插入管理员数据
	$adminSql = "INSERT INTO `dili_admins` (`uid`, `username`, `password`, `email`, `role`) VALUES (1, '".$admin_user."', '".md5($admin_pwd)."', 'dili@cms.com', 1);";
	if(!mysql_query($adminSql))
	{
		showProgress(array('isError' => true,'message' => '创建管理员失败'.mysql_error(),'percent' => 0.9));
	}

	//写入配置文件
	$configDefFile = ROOT_PATH.'./install/template/database.php';
	$configFile    = ROOT_PATH.'./application/config/database.php';
	$updateData    = array(
		'{HOSTNAME}' => $db_address,
		'{USERNAME}'    => $db_user,
		'{PASSWORD}'     => $db_pwd,
		'{DATABASE}'    => $db_name
	);

	$is_success = create_config($configFile,$configDefFile,$updateData);
	if(!$is_success)
	{
		showProgress(array('isError' => true,'message' => '更新数据库配置文件失败','percent' => 0.9));
	}

	//执行完毕
	showProgress(array('isError' => false,'message' => '安装完成','percent' => 1));
}

//输出json数据
function showProgress($return_info)
{
	echo '<script type="text/javascript">parent.update_progress('.JSON::encode($return_info).');</script>';
	flush();
	if($return_info['isError'] == true)
	{
		exit;
	}
}

//根据默认模板生成config文件
function create_config($config_file,$config_def_file,$updateData)
{
	$defaultData = file_get_contents($config_def_file);
	$configData  = str_replace(array_keys($updateData),array_values($updateData),$defaultData);
	return file_put_contents($config_file,$configData);
}