<?php

	class User extends Admin_Controller
	{
		function __construct()
		{
			parent::__construct();	
			$this->_check_permit();
			$this->load->model('dili/user_mdl');
		}
		
		function view($role = 0)
		{
			$data['list'] = $this->user_mdl->get_users($role);
			$data['role'] = $role;
			$data['roles'] = $this->user_mdl->get_roles();
			$this->_template('user_list',$data);
		}
		
		function add()
		{
			$this->_add_post();
		}
		
		function _add_post()
		{
			$data['roles'] = $this->user_mdl->get_roles();
			if(!$this->_validate_user_form())
			{
				$this->_template('user_add',$data);
			}
			else
			{
				$role_id = $this->user_mdl->add_user($this->_get_form_data());
				
				$this->_message('用户添加成功!','user/view',true);	
			}
		}
		
		function edit($id = 0)
		{
			$this->_edit_post($id);
		}
		
		function _edit_post( $id = 0)
		{
			$data['user'] = $this->user_mdl->get_user_by_uid($id);
			$data['roles'] = $this->user_mdl->get_roles();
			if(!$data['user']){$this->_message('不存在的用户','',false);}
			if(!$this->_validate_user_form($data['user']->username,true))
			{
				$this->_template('user_edit',$data);
			}
			else
			{
				$this->user_mdl->edit_user($id,$this->_get_form_data(true));
				
				$this->_message('用户修改成功!','user/edit/'.$id,true);
			}
		}
		
		function del( $id )
		{
			$user = $this->user_mdl->get_user_by_uid($id);
			if(!$user){$this->_message('不存在的用户!','',false);}
			$this->user_mdl->del_user($id);
			$this->_message('用户删除成功!','',false); 
		}
		
		function _check_user_name($name = '')
		{
			if($this->user_mdl->get_user_by_name($name))
			{
				$this->form_validation->set_message('_check_user_name', '已经存在的用户名称！');
				return false;		
			}
			return true;
		}	
		
		function _validate_user_form($name = '' , $edit = false)
		{
			$this->load->library('form_validation');
			$callback = '|callback__check_user_name';
			if( $name && $name == trim($this->input->post('username')) )
			{
				$callback = '';
			}
			$this->form_validation->set_rules('username', '用户名称' , 'trim|required|min_length[3]|max_length[16]'.$callback);
			if( !($edit && !$this->input->post('password') && !$this->input->post('confirm_password')) )
			{
				$this->form_validation->set_rules('password', '用户密码' , 'trim|required|min_length[6]|max_length[16]');
				$this->form_validation->set_rules('confirm_password', '重复用户密码' , 'trim|required|min_length[6]|max_length[16]|matches[password]');
			}
			$this->form_validation->set_rules('email', '用户EMAIL' , 'trim|required|valid_email');
			$this->form_validation->set_rules('role', '用户组' , 'trim|required');
  			if ($this->form_validation->run() == FALSE)
  			{
				$this->load->library('dili/form');
				return FALSE;
  			}
			else
			{
				return TRUE;
			}
		}
				
		function _get_form_data($edit = false)
		{
			$data['username'] = $this->input->post('username');
			if(!($edit && !$this->input->post('password') && !$this->input->post('confirm_password')))
			{
				$data['password'] = md5($this->input->post('password'));	
			}
			$data['email'] = $this->input->post('email');
			$data['role'] = $this->input->post('role');
			return $data;
		}


	}