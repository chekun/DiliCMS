<?php
    function setting($key)
    {
        return false;
    }
	
    class Initialize extends CI_Controller
    {
        
        function index()
        {
            $this->load->library('dili/platform');
			$this->load->helper('url');
            $this->load->database();
            $this->load->model('dili/cache_mdl');
            $this->cache_mdl->update_model_cache();
            $this->cache_mdl->update_category_cache();
            $this->cache_mdl->update_menu_cache();
            $this->cache_mdl->update_role_cache();
            $this->cache_mdl->update_site_cache();
            $this->cache_mdl->update_backend_cache();
            $this->cache_mdl->update_plugin_cache();
			$this->cache_mdl->update_fieldtypes_cache();
			$this->load->view('welcome_message');
        }   
        
        
    }
