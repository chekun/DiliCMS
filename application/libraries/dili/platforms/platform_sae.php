<?php if ( ! defined('IN_DiliCMS')) exit('No direct script access allowed');

    /**
     * SAE平台.
     * 使用SAE Storage 服务处理上传文件
     * 使用SAE Memcache 服务处理缓存配置文件
     */
    class platform_sae
    {
        
        private $_name = 'SAE平台';
        private $_config = array();
        
        private $_storage = NULL;
        private $_storage_domain = '';
                
        private $_memcache = NULL;
        
        public function __construct($config)
        {
            $this->_config = $config;
            if(!$this->_config['storage'])
            {
                show_error('请填写Storage服务的domain.');
            }
            $this->_storage = new SaeStorage();
            if(!$this->_storage)
            {
                show_error('Storage服务未初始化.');
            }
            $this->_memcache = memcache_init();
            if(!$this->_memcache)
            {
                show_error('Memcache服务未初始化.');
            }
        }
        
        
        //文件的写入，读取，删除以及判断是否存在
        public function file_exists($path =  '')
        {
            return $this->_storage->fileExists($this->_config['storage'],$this->_translate_path($path));
        }
        
        public function file_write($path = '',$content = '')
        {
            $url = $this->_storage->write($this->_config['storage'], $this->_translate_path($path),$content);
            if($url == FALSE)
            {
                show_error(SaeStorage::errmsg());
            }
            return $url;
        }
        
        public function file_read($path = '')
        {
            $content = $this->_storage->read($this->_config['storage'], $this->_translate_path($path));
            if($content == FALSE)
            {
                show_error(SaeStorage::errmsg());
            }
            return $content;
        }
        
        public function file_delete($path = '')
        {
            return $this->_storage->delete($this->_config['storage'], $this->_translate_path($path));
        }
        
        public function file_upload($from = '' , $to = '')
        {
            
			return $this->_storage->upload($this->_config['storage'],$this->_translate_path($to),$from);
        }
		
		public function file_url($path = '')
		{
			return $this->_storage->getUrl($this->_config['storage'],setting('attachment_dir').'/'.$path);
		}
        
        //缓存的写入，读取，删除以及判断是否存在
        public function cache_exists($path =  '')
        {
            if(memcache_get($this->_memcache,$this->_translate_path($path,TRUE)))
            {
                return TRUE;
            }
            return FALSE;
        }
        
        public function cache_write($path = '',$content = '')
        {
            return memcache_set($this->_memcache,$this->_translate_path($path,TRUE),$content);
        }
        
        public function cache_read($path = '')
        {
            return memcache_get($this->_memcache,$this->_translate_path($path,TRUE));
        }
        
        public function cache_delete($path = '')
        {
            return memcache_delete($this->_memcache,$this->_translate_path($path,TRUE));
        }
        /**
         * SAE平台.
         * 默认将attachments/改写成storage的attachments下面
         * 将settings/xxx改写成memcache的key,如有/换成_,.php替换为空.
         */
        private function _translate_path($path = '',$cache = FALSE)
        {
            if(!$cache)
            {
                $path = preg_replace("/.*?".setting('attachment_dir')."/", setting('attachment_dir'), $path);
            }
            else
            {
                $path = preg_replace("/.*?settings\//", "", $path);
                $path = str_replace('/','_',$path);
                $path = str_replace('.php','',$path);
            }
            return $path;
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
    
