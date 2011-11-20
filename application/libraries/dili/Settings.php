<?php  if ( ! defined('IN_DiliCMS')) exit('No direct script access allowed');

class Settings {
	
	var $_ci = NULL;
	var $setting = array();
	var $is_loaded = array();
	var $_setting_paths = array(BASEPATH);
	
	function __construct()
	{
		$this->_ci = &get_instance();
		$this->load('site');
	}
	
	function load($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
	{
		$file = ($file == '') ? 'site' : str_replace(EXT, '', $file);
		$loaded = FALSE;

		foreach($this->_setting_paths as $path)
		{
			$file_path = $path.'../settings/'.$file.EXT;

			if (in_array($file_path, $this->is_loaded, TRUE))
			{
				$loaded = TRUE;
				continue;
			}

			if ( ! $this->_ci->platform->cache_exists($path.'../settings/'.$file.EXT))
			{
				continue;
			}
			@eval('?>'.$this->_ci->platform->cache_read($file_path));
			if ( ! isset($setting) OR ! is_array($setting))
			{
				if ($fail_gracefully === TRUE)
				{
					return FALSE;
				}
				show_error('Your '.$file_path.' file does not appear to contain a valid configuration array.');
			}

			if ($use_sections === TRUE)
			{
				if (isset($this->setting[$file]))
				{
					$this->setting[$file] = array_merge($this->setting[$file], $setting);
				}
				else
				{
					$this->setting[$file] = $setting;
				}
			}
			else
			{
				$this->setting = array_merge_recursive($this->setting, $setting);
			}

			$this->is_loaded[] = $file_path;
			unset($setting);

			$loaded = TRUE;
		}

		if ($loaded === FALSE)
		{
			if ($fail_gracefully === TRUE)
			{
				return FALSE;
			}
			show_error('The configuration file '.$file.EXT.' does not exist.');
		}

		return TRUE;
	}


	function item($item, $index = '')
	{
		if ($index == '')
		{
			if ( ! isset($this->setting[$item]))
			{
				return FALSE;
			}

			$pref = $this->setting[$item];
		}
		else
		{
			if ( ! isset($this->setting[$index]))
			{
				return FALSE;
			}

			if ( ! isset($this->setting[$index][$item]))
			{
				return FALSE;
			}

			$pref = $this->setting[$index][$item];
		}

		return $pref;
	}

	function set_item($item, $value)
	{
		$this->setting[$item] = $value;
	}
	
}
//setting helper
function setting($key)
{
	$ci = &get_instance();
	return 	$ci->settings->item($key);
}

function update_cache($array , $fix = '')
{
	$ci = &get_instance();
	$ci->load->model('dili/cache_mdl');
	$array = is_array($array) ? $array : array($array);
	foreach($array as $v)
	{
		$method = 'update_'.$v.'_cache';
		$ci->cache_mdl->$method($fix);
	}
}
