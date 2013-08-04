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
 * DiliCMS 普通平台适配实现类
 *
 * @package     DiliCMS
 * @subpackage  Libraries
 * @category    Libraries
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */
class platform_default
{
    /**
     * _name
     * 适配平台的名称
     *
     * @var string
     * @access  private
     **/
    private $_name = '普通平台';
    
    /**
     * _config
     * 配置数组
     *
     * @var array
     * @access  private
     **/
    private $_config = array();
    

    /**
     * 构造函数
     *
     * @access  public
     * @param   array
     * @return  void
     */
    public function __construct($config = array())
    {
        $this->_config = $config;
    }
    
    // ------------------------------------------------------------------------

    /**
     * 判断文件是否存在
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function file_exists($path =  '')
    {
        return file_exists($path);
    }

    // ------------------------------------------------------------------------

    /**
     * 写文件
     *
     * @access  public
     * @param   string
     * @param   string/bytes
     * @return  bool
     */
    public function file_write($path = '', $content = '')
    {
        return file_put_contents($path, $content);
    }

    // ------------------------------------------------------------------------

    /**
     * 读文件
     *
     * @access  public
     * @param   string
     * @return  string/bytes
     */
    public function file_read($path = '')
    {
        return file_get_contents($path);
    }
    
    // ------------------------------------------------------------------------

    /**
     * 删除文件
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function file_delete($path = '')
    {
        return unlink($path);
    }
    
    // ------------------------------------------------------------------------

    /**
     * 上传文件
     *
     * @access  public
     * @param   string
     * @param   string
     * @return  bool
     */
    public function file_upload($from = '', $to = '')
    {
		$target_path = dirname($to);
        if ( ! is_dir($target_path) AND  ! mkdir($target_path, 0755, TRUE))
		{
			return FALSE;
		}
		else
		{
		    return move_uploaded_file($from, $to);
		}
    }
	
    // ------------------------------------------------------------------------

    /**
     * 返回上传文件所在路径
     *
     * @access  public
     * @param   string
     * @return  string
     */
	public function file_url($path = '')
	{
        $CI = &get_instance();
		return  $CI->settings->item('attachment_url'). '/' . $path;
	}
    
    // ------------------------------------------------------------------------

    /**
     * 判断缓存文件是否存在
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function cache_exists($path =  '')
    {
        return $this->file_exists($path);
    }
    
    // ------------------------------------------------------------------------

    /**
     * 写缓存文件
     *
     * @access  public
     * @param   string
     * @param   string/bytes
     * @return  bool
     */
    public function cache_write($path = '', $content = '')
    {
        return $this->file_write($path, $content);
    }
    
    // ------------------------------------------------------------------------

    /**
     * 读缓存文件
     *
     * @access  public
     * @param   string
     * @return  string/bytes
     */
    public function cache_read($path = '')
    {
        return $this->file_read($path);
    }

    // ------------------------------------------------------------------------

    /**
     * 删除缓存文件
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function cache_delete($path = '')
    {
        return $this->file_delete($path);
    }
    
    // ------------------------------------------------------------------------

    /**
     * 转换路径
     *
     * @access  private
     * @param   string
     * @return  bool
     */
    private function _translate_path($path = '')
    {
        //普通环境下传入的路径即是正确的路径
        //无需转换
    }
    
    // ------------------------------------------------------------------------

    /**
     * 返回此平台名称
     *
     * @access  public
     * @return  string
     */
    public function get_name()
    {
        return $this->_name;
    }
    
    // ------------------------------------------------------------------------

    /**
     * 返回此平台标识
     *
     * @access  public
     * @return  string
     */
    public function get_type()
    {
        return $this->_config['type'];
    }
    
    // ------------------------------------------------------------------------
    
}

/* End of file platform_default.php */
/* Location: ./shared/libraries/plateforms/platform_default.php */