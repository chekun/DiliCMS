<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');

class Install extends CI_Controller 
{

    public function __construct()
    {
        parent::__construct();
        if (is_installed())
        {
            header("Content-type:text/html;charset=utf-8");
            echo 'DiliCMS已经安装过,要重新安装请先删除install.lock';
            exit($this->output->get_output());
        }
    }

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
            $data['environments'] = check_environments();
            $this->load->view('environment', $data);
        }
    }

    public function database()
    {
        $step = $this->input->post('step', TRUE);   
        $platform = '普通平台';
        if (is_sae())
        {
            $config['hostname'] = SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT;
            $config['username'] = SAE_MYSQL_USER;
            $config['password'] = SAE_MYSQL_PASS;
            $config['database'] = SAE_MYSQL_DB;
            //SAE暂不支持自动设置前缀
            $config['dbprefix'] = "dili_";
            $platform = 'SAE';
        }
        else
        {
            $config['hostname'] = $this->input->post('server', TRUE);
            $config['username'] = $this->input->post('user', TRUE);
            $config['password'] = $this->input->post('password', TRUE);
            $config['database'] = $this->input->post('db', TRUE);
            $config['dbprefix'] = $this->input->post('prefix', TRUE);
        }
        $config['dbdriver'] = "mysql";
        $config['pconnect'] = FALSE;
        $config['db_debug'] = FALSE;
        $config['cache_on'] = FALSE;
        $config['cachedir'] = "";
        $config['char_set'] = "utf8";
        $config['dbcollat'] = "utf8_general_ci";
        $db = $this->load->database($config, TRUE);
        if ($step == 'check')
        {
            if ( ! mysql_errno())
            {
                //进行替换数据库配置操作
                if ( ! is_sae())
                {
                    $search_array = array('{HOSTNAME}', '{USERNAME}', '{PASSWORD}', '{DATABASE}', '{PREFIX}');
                    $replace_array = array($config['hostname'], $config['username'], $config['password'], $config['database'], $config['dbprefix']);
                    $database_config = str_replace($search_array, $replace_array, @file_get_contents(BASEPATH.'../admin/config/database.php'));
                    @file_put_contents(BASEPATH.'../admin/config/database.php', $database_config);
                    @file_put_contents(BASEPATH.'../application/config/database.php', $database_config);
                    $database_config = str_replace("get_instance()->platform->get_type()", "PLATFORM", $database_config);
                    @file_put_contents(BASEPATH.'../install/config/database.php', $database_config);
                }
                
                echo json_encode(array(
                    'status' => 1,
                    'messages' => array('所在平台: '.$platform, '数据库连接: Success')
                ));
            }
            else
            {
                echo json_encode(array(
                    'status' => 0,
                    'messages' => array('所在平台: '.$platform, '数据库连接: Fail')
                ));
            }
        }
        else
        {
            $sql = str_replace("{DB_PREFIX}", $config['dbprefix'], file_get_contents('../schema/'.$step.'.sql'));
            foreach (explode('{SEPERATOR}', $sql) as $query)
            {
                if ($query)
                {
                    $db->query($query);
                }
            }
            echo json_encode(array(
                    'status' => 1,
                    'messages' => array('表'.$config['dbprefix'].$step.'安装成功, 插入行数: '.$db->affected_rows())
            ));
        }
    }

    public function account()
    {
        define('PLATFORM', is_sae() ? 'sae' : 'default');
        $this->load->database();
        $data['username'] = $this->input->post('username', TRUE);
        $data['salt'] = substr(sha1(time()), -10);
        $data['password'] = sha1($this->input->post('userpass', TRUE).$data['salt']);
        $data['email'] = 'hello@dilicms.com';
        $data['role'] = 1;
        $data['status'] = 1;
        if ($this->db->insert('admins', $data) AND $this->db->affected_rows() == 1)
        {
            echo json_encode(array('status' => 1));
        }
        else
        {
            echo json_encode(array('status' => 0));
        }
    }

    public function complete()
    {
        //更新缓存
        define('PLATFORM', is_sae() ? 'sae' : 'default');
        define('DILICMS_SHARED_PATH', BASEPATH.'../shared/');
        $this->load->add_package_path(DILICMS_SHARED_PATH);
        $this->load->library('platform', array('type' => PLATFORM, 'storage' => 'public'));
        $this->load->helper('common');
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
        //创建安装锁定文件
        if (is_sae())
        {
            $this->platform->file_write('install.lock', 'Welcome to DiliCMS!');
        }
        else
        {
            $this->platform->file_write(DILICMS_SHARED_PATH.'settings/install.lock', 'Welcome to DiliCMS!');
        }
    }

}
