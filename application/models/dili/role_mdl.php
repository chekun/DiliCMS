<?php  if ( ! defined('IN_DiliCMS')) exit('No direct script access allowed');
	class Role_mdl extends CI_Model{
		
		function __construct()
		{
			parent::__construct();	
		}
		
		function get_roles()
		{
			return $this->db->where('id <>','1')->get('dili_roles')->result();	
		}
		
		function get_role_by_id($id)
		{
			return $this->db->where('id',$id)->get('dili_roles')->row();	
		}
		
		function get_role_by_name($name)
		{
			return $this->db->where('name',$name)->get('dili_roles')->row();	
		}
		
		function _re_parse_array($array , $key , $value)
		{
			$data = array();
			foreach($array as $v)
			{
				$data[$v->$key] = $v->$value;	
			}
			return $data;
		}
		
		function get_form_data()
		{
			$data['rights'] = $this->_re_parse_array($this->db->select('right_id,right_name')->get('dili_rights')->result(),'right_id','right_name');	
			$data['models'] = $this->_re_parse_array($this->db->select('name,description')->get('dili_models')->result(),'name','description');
			$data['category_models'] = $this->_re_parse_array($this->db->select('name,description')->get('dili_cate_models')->result(),'name','description');
			$data['plugins'] = $this->_re_parse_array($this->db->select('name,title')->where('active',1)->get('dili_plugins')->result(),'name','title');
			return $data;
		}
		
		function add_role($data)
		{
			$this->db->insert('dili_roles',$data);
			return $this->db->insert_id();
		}
		
		function edit_role($id,$data)
		{
			$this->db->where('id',$id)->update('dili_roles',$data);
		}
		
		function get_role_user_num($id)
		{
			return $this->db->where('role',$id)->count_all_results('dili_admins');
		}
		
		function del_role($id)
		{
			$this->db->where('id',$id)->delete('dili_roles');	
			$this->platform->cache_delete(FCPATH.'settings/acl/role_'.$id.EXT);	
		}
	}