<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');

class Install extends CI_Controller 
{

	public function index()
	{
        date_default_timezone_set('PRC');
		$this->load->view('install');
	}

    public function platform()
    {
        $data['is_config_ok'] = FALSE;
        @include BASEPATH.'../shared/config/platform.php';
        if (isset($running_platform) AND 
            is_array($running_platform) AND 
            isset($running_platform['type']) AND
            isset($running_platform['storage']))
        {
            if (is_sae())
            {
                if ($running_platform['type'] == 'sae' AND $running_platform['storage'] == 'public')
                {
                    $data['is_config_ok'] = TRUE;
                }
                $data['is_memcache_ok'] = is_memcache_ok();
                $data['is_storage_ok'] = is_storage_ok();
                $data['is_mysql_ok'] = is_mysql_ok();
            }
            elseif ($running_platform['type'] == 'default')
            {
                $data['is_config_ok'] = TRUE;
            }
        }
        $this->load->view('platform', $data);
    }

    public function environment()
    {
        if (is_sae())
        {
            echo 'pass';
        }
        else
        {
            echo 'pass';
        }
    }

}
