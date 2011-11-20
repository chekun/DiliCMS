<?php 

	class MethodHook {
		
		//var $ci ; //maybe useful later
		
		function __construct()
		{
			//$this->ci = &get_instance();		
		}
		
		function redirect()
		{
			global $method;
			if( $_SERVER['REQUEST_METHOD'] == 'POST' )
			{
				$method = '_'.$method.'_post';
			}
		}
			
	}
