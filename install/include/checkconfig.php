<?php
//检查环境配置
class checkConfig
{
	//安装需求
	private $php_version     = '5.1.6';
	private $must_extension  = array('mysql','xml','iconv');
	private $recom_extension = array('zip','gd','session');
	private $writeable_dir   = array('.','application/config','attachments','plugins','settings','install','index.php');
	private $readable_dir    = array('admincp','templates','system');
	private $php_ini         = array('safe_mode' => 'off','allow_url_fopen' => 'on');

	//记录检查结果
	private static $npass_must_num  = 0;
	private static $npass_recom_num = 0;

	//检查php版本
	public function c_phpVersion()
	{
		$is_pass = version_compare(phpversion(),$this->php_version);
		if(!$is_pass)
		{
			self::$npass_must_num++;
		}
		return $is_pass;
	}

	//获取程序所需的php版本号
	public function getPHPVersion()
	{
		return $this->php_version;
	}

	//检查目录权限
	public function c_writeableDir()
	{
		if(defined('ROOT_PATH') == false)
		{
			die('缺少ROOT_PATH常量,无法找到程序路径');
		}
		$return = array();
		foreach($this->writeable_dir as $key => $val)
		{
			$is_pass = is_writable(ROOT_PATH.'./'.$val);
			if(!$is_pass)
			{
				self::$npass_must_num++;
			}

			//根目录
			if($val == '.')
			{
				$val = '根目录';
			}
			$return[$val] = $is_pass;
		}
		return $return;
	}

	//检查目录可读性
	public function c_readableDir()
	{
		if(defined('ROOT_PATH') == false)
		{
			die('缺少ROOT_PATH常量,无法找到程序路径');
		}
		$return = array();
		foreach($this->readable_dir as $key => $val)
		{
			$is_pass = is_readable(ROOT_PATH.'./'.$val);
			if(!$is_pass)
			{
				self::$npass_must_num++;
			}
			$return[$val] = $is_pass;
		}
		return $return;

	}

	//检查php_ini配置
	public function c_phpIni()
	{
		$return = array();
		foreach($this->php_ini as $key => $val)
		{
			$localIni = @ini_get($key);
			if($localIni == $val)
			{
				$return[$key] = true;
				self::$npass_must_num++;
			}
			else
			{
				$return[$key] = true;
			}
		}
		return $return;
	}

	//检查必备php扩展
	public function c_must_extension()
	{
		$return = array();
		foreach($this->must_extension as $key => $val)
		{
			$is_pass = extension_loaded($val);
			$return[$val] = $is_pass;

			if($is_pass == false)
			{
				self::$npass_must_num++;
			}
		}
		return $return;
	}

	//检查建议php扩展
	public function c_recom_extension()
	{
		$return = array();
		foreach($this->recom_extension as $key => $val)
		{
			$is_pass = extension_loaded($val);
			$return[$val] = $is_pass;

			if($is_pass == false)
			{
				self::$npass_recom_num++;
			}
		}
		return $return;
	}

	//获取检测数据
	public function getNpassMustNum()
	{
		return self::$npass_must_num;
	}
}