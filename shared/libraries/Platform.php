<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
 * DiliCMS 平台适配类
 *
 * 本类用于适配多平台环境时提供统一的操作接口。
 *
 * @package     DiliCMS
 * @subpackage  Libraries
 * @category    Libraries
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */
class Platform
{
    /**
     * _supported_platforms
     * 支持的平台集合
     *
     * @var array
     * @access  private
     **/
    private $_supported_platforms  = array('default', 'sae');
    
    /**
     * _platform
     * 具体平台驱动的句柄
     *
     * @var object
     * @access  private
     **/
    private $_platform = NULL;
    
    /**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
    public function __construct($config = array())
    {
        date_default_timezone_set('PRC');//强制时区为PRC,以后可增加配置变量
        $this->_init($config);
    }
    
    // ------------------------------------------------------------------------

    /**
     * 初始化
     *
     * @access  private
     * @return  void
     */
    private function _init($running_platform)
    {
        $platform = '';
        if ( ! is_array($running_platform) || ! $running_platform)
        {
            if (file_exists(DILICMS_SHARE_PATH . 'config/platform.php'))
            {
               include DILICMS_SHARE_PATH . 'config/platform.php';
            }
        }
        if (isset($running_platform['type']) AND $running_platform['type'])
        {
            $platform = $running_platform['type'];
        }
        
        if ( ! in_array($platform, $this->_supported_platforms))
        {
            $platform = $this->_supported_platforms[0];
        }
        $platform_class = 'platform_' . $platform;
        if (file_exists($platform_class_path = DILICMS_SHARE_PATH . 'libraries/platforms/platform_' . $platform . '.php'))
        {
            include_once $platform_class_path;
            if (class_exists($platform_class))
            {
                $this->_platform = new $platform_class($running_platform);
            }
            else
            {
                show_error('没有找到平台驱动类:' . $platform_class);
            }
        }
        else
        {
            show_error('没有找到文件:' . $platform_class);
        }
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
        return $this->_platform->file_exists($path);
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
        return $this->_platform->file_write($path, $content);
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
        return $this->_platform->file_read($path);
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
        return $this->_platform->file_delete($path);
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
        return $this->_platform->file_upload($from , $to);   
    }

    // ------------------------------------------------------------------------

    /**
     * 获取上传文件的URL
     *
     * @access  public
     * @param   string
     * @return  string
     */
    public function file_url($path = '')
    {
        return $this->_platform->file_url($path);   
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
        return $this->_platform->cache_exists($path);
    }
    
    // ------------------------------------------------------------------------

    /**
     * 写缓存
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function cache_write($path = '', $content = '')
    {
        return $this->_platform->cache_write($path, $content);
    }

    // ------------------------------------------------------------------------

    /**
     * 读文件
     *
     * @access  public
     * @param   string
     * @return  string/bytes
     */
    public function cache_read($path = '')
    {
        return $this->_platform->cache_read($path);
    }
    
    // ------------------------------------------------------------------------

    /**
     * 删除缓存
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function cache_delete($path = '')
    {
        return $this->_platform->cache_delete($path);
    }

    // ------------------------------------------------------------------------

    /**
     * 获取当前平台名称
     *
     * @access  public
     * @return  string
     */
    public function get_name()
    {
        return $this->_platform->get_name();
    }
    
    // ------------------------------------------------------------------------

    /**
     * 获取当前平台标识
     *
     * @access  public
     * @return  string
     */
    public function get_type()
    {
        return $this->_platform->get_type();
    }

    // ------------------------------------------------------------------------
}
    
/* End of file Platform.php */
/* Location: ./shared/libraries/Platform.php */