<?php if ( ! defined('IN_DiliCMS')) exit('No direct script access allowed');

	class Role extends Admin_Controller
	{
		function __construct()
		{
			parent::__construct();	
			$this->_check_permit();
			$this->load->model('dili/role_mdl');
		}
		
		function view()
		{
			$data['list'] = $this->role_mdl->get_roles();
			$this->_template('role_list',$data);
		}
		
		function add()
		{
			$this->_add_post();
		}
		
		function _add_post()
		{
			$data = $this->role_mdl->get_form_data();
			if(!$this->_validate_role_form())
			{
				$this->_template('role_add',$data);
			}
			else
			{
				$role_id = $this->role_mdl->add_role($this->_get_form_data());
				
				update_cache('role',$role_id);
				
				$this->_message('用户组添加成功!','role/view',true);	
			}
		}
		
		function edit($id = 0)
		{
			$this->_edit_post($id);
		}
		
		function _edit_post( $id = 0)
		{
			$data = $this->role_mdl->get_form_data();
			$data['role'] = $this->role_mdl->get_role_by_id($id);
			if(!$data['role']){$this->_message('不存在的用户组','',false);}
			if(!$this->_validate_role_form($data['role']->name))
			{
				$this->_template('role_edit',$data);
			}
			else
			{
				$this->role_mdl->edit_role($id,$this->_get_form_data());
				update_cache('role',$id);
				$this->_message('用户组修改成功!','role/edit/'.$id,true);
			}
		}
		
		function del( $id )
		{
			$role = $this->role_mdl->get_role_by_id($id);
			if(!$role){$this->_message('不存在的用户组','',false);}
			if($this->role_mdl->get_role_user_num($id) > 0){$this->_message('该用户组下有用户不允许删除!','',false);}	
			$this->role_mdl->del_role($id);
			$this->_message('用户组删除成功!','',false); 
		}
		
		function _check_role_name($name = '')
		{
			if($this->role_mdl->get_role_by_name($name))
			{
				$this->form_validation->set_message('_check_role_name', '已经存在的用户组名称！');
				return false;		
			}
			return true;
		}	
		
		function _validate_role_form( $name = '')
		{
			$this->load->library('form_validation');
			$callback = '|callback__check_role_name';
			if( $name && $name == trim($this->input->post('name')) )
			{
				$callback = '';
			}
			$this->form_validation->set_rules('name', '用户组名称' , 'trim|required|min_length[3]|max_length[20]'.$callback);
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
		
		function _array_to_string($array)
		{
			if($array)
			{
				return implode(',',$array);	
			}
			else
			{
				return '0';	
			}
		}
		
		function _get_form_data()
		{
			$data['name'] = $this->input->post('name');
			$data['rights'] = $this->_array_to_string($this->input->post('right'));
			$data['models'] = $this->_array_to_string($this->input->post('model'));
			$data['category_models'] = $this->_array_to_string($this->input->post('category_model'));
			$data['plugins'] = $this->_array_to_string($this->input->post('plugin'));	
			return $data;
		}


	}