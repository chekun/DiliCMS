<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');

/**
 * DiliCMS
 *
 * 一款基于并面向CodeIgniter开发者的开源轻型后端内容管理系统.
 *
 * @package     DiliCMS
 * @author      DiliCMS Team
 * @copyright   Copyright (c) 2011 - 2012, DiliCMS Team.
 * @license     http://www.dilicms.com/license
 * @link        http://www.dilicms.com
 * @since       Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * DiliCMS 辅助函数库
 *
 * @package     DiliCMS
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */

// ------------------------------------------------------------------------

/**
 * 读取配置数据
 *
 * @access	public
 * @param	string
 * @return	mixed
 */
if ( ! function_exists('setting'))
{
	function setting($key, $default = null)
	{
		$ci = &get_instance();

    	$sequences = explode('.', $key);

		$key = array_shift($sequences);

    	$tmp_result = $ci->settings->item($key);

	    for ($i = 0, $total = count($sequences); $i < $total; )
		{

	        if (isset($tmp_result[$sequences[$i]]))
			{
	            $tmp_result = $tmp_result[$sequences[$i]];
	            $i ++;
	        } else {
	            return $default;
	        }

	    }

    	return $tmp_result;
	}
}

// ------------------------------------------------------------------------

/**
 * 更新缓存
 *
 * @access	public
 * @param	array
 * @param	string
 * @return	void
 */
if ( ! function_exists('update_cache'))
{
	function update_cache($array, $fix = '')
	{
		$ci = &get_instance();
		$ci->load->model('cache_mdl');
		$array = is_array($array) ? $array : array($array);
		foreach ($array as $v)
		{
			$method = 'update_' . $v . '_cache';
			$ci->cache_mdl->$method($fix);
		}
	}
}

// ------------------------------------------------------------------------

/**
 * 将array转换成缓存字符
 *
 * @access	public
 * @param	string
 * @param	array
 * @return	void
 */
if ( ! function_exists('array_to_cache'))
{
	function array_to_cache($name, $array)
	{
		return '<?php if ( ! defined(\'IN_DILICMS\')) exit(\'No direct script access allowed\');' . PHP_EOL .
			   '$' . $name . '=' . var_export($array, TRUE) . ';';
	}
}

// ------------------------------------------------------------------------

/**
 * 后台URI生成函数
 *
 * @access	public
 * @param	string
 * @param	string
 * @return	string
 */
if ( ! function_exists('backend_url'))
{
	function backend_url($uri = '', $qs = '')
	{
		return site_url(setting('backend_access_point') . '/' . $uri) . ($qs == '' ? '' : '?' . $qs);
	}
}

/**
 * 插件URI生成函数
 *
 * @access	public
 * @param	string
 * @param	string
 * @return	string
 */
if ( ! function_exists('plugin_url'))
{
	function plugin_url($plugin, $controller, $method = 'index', $qs = array())
	{
	    $ci = &get_instance();
		if (false and $ci->config->item('index_page') === '')
	    {
	        return backend_url("plugin/$name/$controller/$method", http_build_query($qs));
	    }
	    $qs['plugin'] = $plugin;
	    $qs['c'] = $controller;
	    $qs['m'] = $method;
		return backend_url('module/run', http_build_query($qs));
	}
}

// ------------------------------------------------------------------------

/* End of file common_helper.php */
/* Location: ./shared/heleprs/common_helper.php */
