<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/
/*
| -------------------------------------------------------------------------
| pre_controller 钩子
| -------------------------------------------------------------------------
| 此钩子使用于处理POST请求的函数只能通过POST请求访问
|
|	http://www.dilicms.com/
|
*/
$hook['pre_controller'] = array(
                                'class'    => 'MethodHook',
                                'function' => 'redirect',
                                'filename' => 'MethodHook.php',
                                'filepath' => 'hooks'
                                );


/* End of file hooks.php */
/* Location: ./application/config/hooks.php */