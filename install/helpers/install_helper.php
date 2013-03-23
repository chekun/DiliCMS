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
 * DiliCMS 安装辅助函数库
 *
 * @package     DiliCMS
 * @subpackage  Helpers
 * @category    Helpers
 * @author      chekun
 * @link        http://www.dilicms.com
 */

// ------------------------------------------------------------------------

/**
 * 检测当前安装环境是否为SAE
 *
 * @access  public
 * @param   string  
 * @return  mixed
 */
if ( ! function_exists('is_sae'))
{
    function is_sae()
    {
        return defined('SAE_ACCESSKEY') && (substr(SAE_ACCESSKEY, 0, 4 ) != 'kapp');
    }
}