<?php if ( ! defined('IN_DiliCMS')) exit('No direct script access allowed');

    /**
     * 默认平台.
     */
    class platform_default
    {
        
        private $_name = '普通平台';
        private $_config = array();
        
        public function __construct($config)
        {
            $this->_config = $config;
        }
        
        //文件的写入，读取，删除以及判断是否存在
        public function file_exists($path =  '')
        {
            return @file_exists($path);
        }
        
        public function file_write($path = '',$content = '')
        {
            return @file_put_contents($path,$content);
        }
        
        public function file_read($path = '')
        {
            return @file_get_contents($path);
        }
        
        public function file_delete($path = '')
        {
            return @unlink($path);
        }
        
        public function file_upload($from = '' , $to = '')
        {
			$target_path = dirname($to);
            if(!is_dir($target_path) && !mkdir($target_path, 0755, true))
			{
				return false;
			}
			else
			{
			    return @move_uploaded_file($from , $to);
			}
        }
		
		public function file_url($path = '')
		{
			return '/'.setting('attachment_dir').'/'.$path;
		}
        
        //缓存的写入，读取，删除以及判断是否存在
        public function cache_exists($path =  '')
        {
            return $this->file_exists($path);
        }
        
        public function cache_write($path = '',$content = '')
        {
            return $this->file_write($path,$content);
        }
        
        public function cache_read($path = '')
        {
            return $this->file_read($path);
        }
        
        public function cache_delete($path = '')
        {
            return $this->file_delete($path);
        }
        
        private function _translate_path($path = '')
        {
            //无需转换
        }
        
        public function get_name()
        {
            return $this->_name;
        }
        
        public function get_type()
        {
            return $this->_config['type'];
        }
        
    }
    
