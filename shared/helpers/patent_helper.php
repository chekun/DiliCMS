<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');

if ( ! function_exists('create_pages'))
{
	function create_pages($per_page , $total , $base_url , $where = array())
	{
		$CI = & get_instance();
		$CI->load->library('pagination');
		$config['page_query_string'] = TRUE;
		$config['use_page_numbers'] = TRUE;
		$config['per_page'] = $per_page;
		$config['query_string_segment'] = 'page'; 
		$config['base_url'] = $base_url.'?{fix}';
		foreach($where as $key => $v)
		{
			$config['base_url'] .= '&' . $key . '=' . $v;	
		}
		$config['total_rows'] = $total;
		$CI->pagination->initialize($config); 
		$CI->pagination->base_url = str_replace('{fix}&','',$CI->pagination->base_url);
		return '<div class="pagination">'.str_replace('{fix}&','',$CI->pagination->create_links()).'</div>';	
	}
}

if ( ! function_exists('get_page_offset'))
{
	function get_page_offset($limit = 10, $segment = 'page')
	{
		$CI = & get_instance();
		$page = $CI->input->get($segment, TRUE) ? $CI->input->get($segment, TRUE) : 1;
		$offset = $limit * ($page - 1);	
		return $offset;	
	}
}

if ( ! function_exists('get_cate_cache'))
{
	function get_cate_cache($from = array(), $index = '', $label = '')
	{
		if (isset($from[$index][$label]))
		{
			return $from[$index][$label];	
		}
		else
		{
			return '未知';	
		}
	}
}


/* End of file study_helper.php */
/* Location: ./shared/heleprs/study_helper.php */