<?php  if ( ! defined('IN_DiliCMS')) exit('No direct script access allowed');
	class User_mdl extends CI_Model{
		
		function __construct()
		{
			parent::__construct();	
		}
		
		function get_full_user_by_username( $username = '' )
		{
			return $this->db->select('dili_admins.uid , dili_admins.password , dili_admins.role , dili_roles.name')
								  ->from('dili_admins')
								  ->join('dili_roles','dili_roles.id = dili_admins.role')
								  ->where('dili_admins.username' , $username)
								  ->get()
								  ->row();
		}
		
		function get_user_by_uid($uid = 0)
		{
			return $this->db->where('uid',$uid)->get('dili_admins')->row();
		}
		
		function get_user_by_name($name)
		{
			return $this->db->where('username',$name)->get('dili_admins')->row();
		}
		
		function update_user_password()
		{
			$data['password'] = md5($this->input->post('new_pass'));
			$this->db->where('uid',$this->session->userdata('uid'))->update('dili_admins', $data);		
		}
		
		function get_roles()
		{
			$roles = array();
			foreach($this->db->select('id,name')->where('id <>',1)->get('dili_roles')->result_array() as $v)
			{
				$roles[$v['id']] = $v['name'];	
			}
			return $roles;
		}
		function get_users($role_id = 0)
		{
			$this->db->where('dili_admins.uid <>',1);
			if($role_id){$this->db->where('dili_admins.role',$role_id);}
			return $this->db->from('dili_admins')
							->join('dili_roles','dili_roles.id = dili_admins.role')
							->get()
							->result();
		}
		
		function add_user($data)
		{
			$this->db->insert('dili_admins',$data);
		}
		
		function edit_user($uid,$data)
		{
			$this->db->where('uid',$uid)->update('dili_admins',$data);	
		}
		
		function del_user($uid)
		{
			$this->db->where('uid',$uid)->delete('dili_admins');
		}
		
	}