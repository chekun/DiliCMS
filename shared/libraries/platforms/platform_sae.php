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
 * DiliCMS SAE平台(新浪云平台)适配实现类
 * 
 * 使用SAE Storage 服务处理上传文件
 * 使用SAE Memcache 服务处理缓存配置文件
 * 如何使用此服务请参见新浪云平台开发手册
 *
 * @package     DiliCMS
 * @subpackage  Libraries
 * @category    Libraries
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */
class platform_sae
{
    /**
     * _name
     * 适配平台的名称
     *
     * @var string
     * @access  private
     **/
    private $_name = 'SAE平台';

    /**
     * _config
     * 配置数组
     *
     * @var array
     * @access  private
     **/
    private $_config = array();
    
    /**
     * _storage
     * SAE storage类句柄
     *
     * @var object
     * @access  private
     **/
    private $_storage = NULL;

    /**
     * _storage_domain
     * SAE storage domain
     *
     * @var string
     * @access  private
     **/
    private $_storage_domain = '';
    
    /**
     * _memcache
     * SAE memcache类句柄
     *
     * @var object
     * @access  private
     **/        
    private $_memcache = NULL;
    
    /**
     * 构造函数
     *
     * @access  public
     * @param   array
     * @return  void
     */
    public function __construct($config)
    {
        $this->_config = $config;
        if ( ! $this->_config['storage'])
        {
            show_error('请填写Storage服务的domain.');
        }
        $this->_storage = new SaeStorage();
        if ( ! $this->_storage)
        {
            show_error('Storage服务未初始化.');
        }
        $this->_memcache = memcache_init();
        if ( ! $this->_memcache)
        {
            show_error('Memcache服务未初始化.');
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
        return $this->_storage->fileExists($this->_config['storage'], $this->_translate_path($path));
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
        $url = $this->_storage->write($this->_config['storage'], $this->_translate_path($path), $content);
        if ($url == FALSE)
        {
            show_error(SaeStorage::errmsg());
        }
        return TRUE;
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
        $content = $this->_storage->read($this->_config['storage'], $this->_translate_path($path));
        if ($content == FALSE)
        {
            show_error(SaeStorage::errmsg());
        }
        return $content;
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
        return $this->_storage->delete($this->_config['storage'], $this->_translate_path($path));
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
        
		return $this->_storage->upload($this->_config['storage'], $this->_translate_path($to), $from);
    }
	
    // ------------------------------------------------------------------------

    /**
     * 返回上传文件的路径
     *
     * @access  public
     * @param   string
     * @return  string
     */
	public function file_url($path = '')
	{
		return $this->_storage->getUrl($this->_config['storage'], setting('attachment_dir') . '/' . $path);
	}
    
    // ------------------------------------------------------------------------

    /**
     * 判断缓存是否存在
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function cache_exists($path =  '')
    {
        if (memcache_get($this->_memcache, $this->_translate_path($path, TRUE)))
        {
            return TRUE;
        }
        return FALSE;
    }
    
    // ------------------------------------------------------------------------

    /**
     * 写缓存
     *
     * @access  public
     * @param   string
     * @param   string/bytes
     * @return  bool
     */
    public function cache_write($path = '', $content = '')
    {
        return memcache_set($this->_memcache, $this->_translate_path($path, TRUE), $content);
    }
    
    // ------------------------------------------------------------------------

    /**
     * 读缓存
     *
     * @access  public
     * @param   string
     * @return  string/bytes
     */
    public function cache_read($path = '')
    {
        return memcache_get($this->_memcache, $this->_translate_path($path, TRUE));
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
        return memcache_delete($this->_memcache, $this->_translate_path($path, TRUE));
    }
    
    // ------------------------------------------------------------------------

    /**
     * 转换路径
     * 参数2为FALSE,则将attachments/改写成storage的attachments下面
     * 参数2为TRUE，将settings/xxx改写成memcache的key,如有/换成_,.php替换为空.
     * @access  private
     * @param   string
     * @param   bool
     * @return  string
     */
    private function _translate_path($path = '', $cache = FALSE)
    {
        if ( ! $cache)
        {
            $path = preg_replace("/.*?" . setting('attachment_dir') . "/", setting('attachment_dir'), $path);
        }
        else
        {
            $path = preg_replace("/.*?settings\//", "", $path);
            $path = str_replace('/', '_', $path);
            $path = str_replace('.php', '', $path);
        }
        return $path;
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

/* End of file platform_sae.php */
/* Location: ./shared/libraries/plateforms/platform_sae.php */