<?php if ( ! defined('IN_DiliCMS')) exit('No direct script access allowed');


class Ld extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('dili/platform');
	}
	
	function json($model = '' , $name = '')
	{
		if(!$model || !$name){return;}
		$parentid = $this->input->get('parentid');
		if($parentid == ''){echo  '[]';return;}
		@eval('?>'.$this->platform->cache_read(FCPATH.'settings/category/data_'.$model.EXT));
		$json_str = "[";
		$json = array();
		foreach($setting['category'][$model] as $v) {
			if($v['parentid'] == $parentid)
			{
				$json[] = json_encode(array('classid' => $v['classid'],$name => $v[$name]));
			}
		}
		$json_str .= implode(',',$json);
		$json_str .= "]";
		echo $json_str;	
	}
	
}