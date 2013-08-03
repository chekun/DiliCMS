<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| running_platform
|--------------------------------------------------------------------------
|
| DiliCMS运行的平台参数
| 非云环境(默认参数)如下：
|
|	$running_platform = array(
|						'type'		=> 'default',
|						'storage'   => ''
|					);
| 云环境,除配置所在平台标识外，还需要填写存储服务的域名称，如SAE配置如下：
|
|	$running_platform = array(
|						'type'		=> 'sae',
|						'storage'   => 'your-storage-domain'
|					);
|
| DiliCMS默认支持本地和新浪云，其他云服务需要按照对应的驱动程序说明来填写。
|
*/

$running_platform = array(
						'type'		=> 'default',
						'storage'   => ''
					);

/* End of file platform.php */
/* Location: ./shared/config/platform.php */