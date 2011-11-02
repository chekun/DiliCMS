<?php

	class Model extends Admin_Controller
	{
		var $model = 0; 
		
		function __construct()
		{
			parent::__construct();
			$this->_check_permit();
			$this->load->model('dili/model_mdl');
		}
						
		function view()
		{
			$data['list'] = $this->model_mdl->get_models();
			$this->_template('model_list',$data);
		}
		
		function add()
		{
			$this->_add_post();
		}
		
		function _add_post()
		{
			if($this->_validate_model_form() == TRUE)
			{
				//获取表单数据
				$data['name'] = $this->input->post('name');
				$data['description'] = $this->input->post('description');
				$data['perpage'] = $this->input->post('perpage');
				$data['hasattach'] = $this->input->post('hasattach');
				//新增内容模型
				$this->model_mdl->add_new_model($data);
				//更新缓存
				update_cache('model',$data['name']);
				update_cache('menu');
				
				$this->_message('内容模型添加成功!','model/view',true);
			}
			else
			{
				$this->_template('model_add');
			}
		}
		
		function edit( $id = 0 )
		{
			$this->_edit_post($id);
		}
		
		function del( $id = 0)
		{
			$model = $this->model_mdl->get_model_by_id($id);
			if($model)
			{
				$this->model_mdl->del_model($model);
				update_cache('menu');
				$this->_message('内容模型删除完成！','model/view',true);
			}
			else
			{
				$this->_message('不存在的内容模型!','',false);	
			}
			
		}
		
		
		function _edit_post( $id = 0)
		{
			$target_model = $this->model_mdl->get_model_by_id($id);
			!$target_model && $this->_message('不存在的内容模型!','',false);
			if($this->_validate_model_form($target_model->name) == TRUE)
			{
				$old_table_name = $target_model->name;
				$data['name'] = $this->input->post('name');
				$data['description'] = $this->input->post('description');
				$data['perpage'] = $this->input->post('perpage');
				$data['hasattach'] = $this->input->post('hasattach');
				$this->model_mdl->edit_model($target_model,$data);
				update_cache('model',$data['name']);
				update_cache('menu');
				$this->_message('内容模型修改成功!','model/edit/'.$target_model->id,true);
			}
			else
			{
				$this->_template('model_edit',array('model'=>$target_model));
			}
		}
		
		function _validate_model_form( $name = '')
		{
			
			$this->load->library('form_validation');
			$callback = '|callback__check_model_name';
			if( $name && $name == trim($this->input->post('name')) )
			{
				$callback = '';
			}
			$this->form_validation->set_rules('name', '内容模型标识' , 'trim|required|alpha_dash|min_length[3]|max_length[20]'.$callback);
			$this->form_validation->set_rules('description', '内容模型名称' , 'trim|required|max_length[40]');
			$this->form_validation->set_rules('perpage', '每页显示条数' , 'trim|required|integer');
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
		
		function _check_model_name($name = '')
		{
			if($this->model_mdl->get_model_by_name($name))
			{
				$this->form_validation->set_message('_check_model_name', '已经存在的内容模型标识！');	
				return FALSE;
			}
			else
			{
				return TRUE;	
			}
		}
		
		function fields( $id = 0 )
		{
			$data['model'] = $this->model_mdl->get_model_by_id($id);
			!$data['model'] && $this->_message('不存在的内容模型!','',false);
			$data['list']  = $this->model_mdl->get_model_fields($id);
			$this->settings->load('fieldtypes');
			$this->load->library('dili/form');
			$this->_template('fields_list',$data);
		}
		
		function add_field( $id = 0 )
		{
			$this->_add_field_post($id);
		}
		
		function _add_field_post($id = 0)
		{
			$data['model'] = $this->model_mdl->get_model_by_id($id);
			!$data['model'] && $this->_message('不存在的内容模型!','',false);
			$this->settings->load('fieldtypes');
			if(!$this->_validate_field_form($id))
			{
				$this->_template('fields_add',$data);
			}
			else
			{
				$this->model_mdl->add_field($data['model'] , $this->_get_post_data());
				
				update_cache('model',$data['model']->name);
				
				$this->_message('内容模型字段添加成功!','model/fields/'.$id,true);
			}
				
		}
		
		function edit_field( $id = 0 )
		{
			$this->_edit_field_post($id);
		}
		
		function _edit_field_post( $id = 0)
		{
			$data['field'] = $this->model_mdl->get_field_by_id( $id );
			!$data['field'] && $this->_message('不存在的内容字段!','',false);
			$data['model'] = $this->model_mdl->get_model_by_id($data['field']->model);
			!$data['model'] && $this->_message('不存在的内容模型!','',false);
			$this->settings->load('fieldtypes');
			if($this->_validate_field_form($data['field']->model,$data['field']->name))
			{
				$this->model_mdl->edit_field($data['model'],$data['field'],$this->_get_post_data());
				
				update_cache('model',$data['model']->name);
				
				$this->_message('内容模型字段修改成功!','model/edit_field/'.$id,true);		
			}
			else
			{	
				$this->_template('fields_edit',$data);	
			}
		}
		
		function del_field( $id = 0 )
		{
			$field = $this->model_mdl->get_field_by_id( $id );
			!$field && $this->_message('不存在的内容字段!','',false);
			$model = $this->model_mdl->get_model_by_id($field->model);
			!$model && $this->_message('不存在的内容模型!','',false);
			if($field && $model)
			{
				
				$this->model_mdl->del_field($model , $field);
				
				update_cache('model',$model->name);
			}
			$this->_message('字段删除成功!','model/fields/'.$model->id,true);	
		}
		
		function _check_field_name( $name )
		{
			if($this->model_mdl->check_field_unique($this->model , $name))
			{
				$this->form_validation->set_message('_check_field_name', '已经存在的字段标识！');	
				return FALSE;
			}
			else
			{
				return TRUE;	
			}
		}
		
		function _check_field_name_valid( $name )
		{
			if($name == 'id' || $name == 'create_time' || $name == 'update_time')
			{
				$this->form_validation->set_message('_check_field_name_valid', '字段标识不能为id或者create_time或者update_time！');	
				return FALSE;
			}
			else
			{
				return TRUE;	
			}
		}
		
		function _validate_field_form($model = 0 ,$name = '')
		{
			$this->model = $model;
			$this->load->library('form_validation');
			$callback = '|callback__check_field_name';
			if( $name && $name == trim($this->input->post('name')) )
			{
				$callback = '';
			}
			$this->form_validation->set_rules('name', '字段标识' , 'trim|required|alpha_dash|min_length[3]|max_length[20]|callback__check_field_name_valid'.$callback);
			$this->form_validation->set_rules('description', '字段名称' , 'trim|required|max_length[40]');
			$this->form_validation->set_rules('type', '字段类型' , 'trim|required');
			$this->form_validation->set_rules('length', '字段长度' , 'trim');
			$this->form_validation->set_rules('values', '数据源' , 'trim');
			$this->form_validation->set_rules('width', '宽度' , 'trim|integer');
			$this->form_validation->set_rules('height', '高度' , 'trim|integer');
			$this->form_validation->set_rules('width', '宽度' , 'trim|integer');
			$this->form_validation->set_rules('order', '显示顺序' , 'trim|integer');
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
		
		function _get_post_data()
		{
			$data['name'] = $this->input->post('name');
			$data['description'] = $this->input->post('description');
			$data['type'] = $this->input->post('type');
			$data['length'] = $this->input->post('length');
			$data['values'] = $this->input->post('values');
			$data['width'] = $this->input->post('width');
			$data['height'] = $this->input->post('height');
			$data['rules'] = $this->input->post('rules');
			$data['ruledescription'] = $this->input->post('ruledescription');
			$data['searchable'] = $this->input->post('searchable');
			$data['listable'] = $this->input->post('listable');
			$data['editable'] = $this->input->post('editable');
			$data['order'] = $this->input->post('order');
			if( $data['rules'] &&  is_array($data['rules']) )
			{
				$data['rules'] = implode('|',$data['rules']);	
			}
			else
			{
				$data['rules'] = '';
			}
			return $data;	
		}

	}