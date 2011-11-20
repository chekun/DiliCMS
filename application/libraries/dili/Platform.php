<?php if ( ! defined('IN_DiliCMS')) exit('No direct script access allowed');

    /**
     * 需要在application/config/下建立platform.php配置
     * $running_platform['type'] = 'default';//当前支持传统(default)和新浪云平台(sae).
     * $running_platform['storage'] = '';//用于设置文件存储服务的domain
     */
    class Platform
    {
    
        private $_supported_platforms  = array('default','sae');
        
        private $_platform = NULL;
        
        public function __construct()
        {
            $this->_init();
        }
        
        private function _init()
        {
            $platform = '';
            if(file_exists(APPPATH.'config/platform'.EXT))
            {
               include APPPATH.'config/platform'.EXT;
               if(isset($running_platform['type']) && $running_platform['type'])
               {
                   $platform = $running_platform['type'];
               }
            }
            if(!in_array($platform,$this->_supported_platforms))
            {
                $platform = $this->_supported_platforms[0];
            }
            $platform_class = 'platform_'.$platform;
            if(file_exists($platform_class_path = APPPATH.'libraries/dili/platforms/platform_'.$platform.EXT))
            {
                include $platform_class_path;
                if(class_exists($platform_class))
                {
                    $this->_platform = new $platform_class($running_platform);
                }
                else
                {
                    show_error('Platform Class not Found:'.$platform_class);
                }
            }
            else
            {
                show_error('File Not Found:'.$platform_class);
            }
        }
        //文件的写入，读取，删除以及判断是否存在
        public function file_exists($path =  '')
        {
            return $this->_platform->file_exists($path);
        }
        
        public function file_write($path = '',$content = '')
        {
            return $this->_platform->file_write($path,$content);
        }
        
        public function file_read($path = '')
        {
            return $this->_platform->file_read($path);
        }
        
        public function file_delete($path = '')
        {
            return $this->_platform->file_delete($path);
        }
        
        public function file_upload($from = '' , $to = '')
        {
            return $this->_platform->file_upload($from , $to );   
        }
		
		public function file_url($path = '')
        {
            return $this->_platform->file_url($path);   
        }
        
        //缓存的写入，读取，删除以及判断是否存在
        public function cache_exists($path =  '')
        {
            return $this->_platform->cache_exists($path);
        }
        
        public function cache_write($path = '',$content = '')
        {
            return $this->_platform->cache_write($path,$content);
        }
        
        public function cache_read($path = '')
        {
            return $this->_platform->cache_read($path);
        }
        
        public function cache_delete($path = '')
        {
            return $this->_platform->cache_delete($path);
        }
        
        public function get_name()
        {
            return $this->_platform->get_name();
        }
        
        public function get_type()
        {
            return $this->_platform->get_type();
        }
        
    }
    
