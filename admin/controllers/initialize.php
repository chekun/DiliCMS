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
 * DiliCMS 系统初始化
 *
 * @package     DiliCMS
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */
class Initialize extends CI_Controller
{
    /**
     * 默认入口
     *
     * @access  public
     * @return  void
     */
    public function index()
    {
        $this->load->database();
        $this->load->model('cache_mdl');
        $this->cache_mdl->update_model_cache();
        $this->cache_mdl->update_category_cache();
        $this->cache_mdl->update_menu_cache();
        $this->cache_mdl->update_role_cache();
        $this->cache_mdl->update_site_cache();
        $this->cache_mdl->update_backend_cache();
        $this->cache_mdl->update_plugin_cache();
		$this->cache_mdl->update_fieldtypes_cache();
        $this->load->switch_theme();
        $this->load->view('system_initialize');
    }   
    
    // ------------------------------------------------------------------------

}

/* End of file initialize.php */
/* Location: ./admin/controllers/initialize.php */