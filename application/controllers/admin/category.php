<?php

	class Category extends Admin_Controller
	{
		var $model = 0;
		function __construct()
		{
			parent::__construct();
			$this->_check_permit();
			$this->load->model('dili/category_mdl');
		}
				
		function view()
		{
			$data['list'] = $this->category_mdl->get_category_models();
			$this->_template('category_list',$data);
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
				$data['level'] = $this->input->post('level');
				$data['hasattach'] = $this->input->post('hasattach');
				//新增分类模型
				$this->category_mdl->add_new_category($data);
				//更新缓存
				update_cache('category',$data['name']);
				
				update_cache('menu');
				
				$this->_message('分类模型添加成功!','category/view',true);
			}
			else
			{
				$this->_template('category_add');
			}
		}
		
		function edit( $id = 0 )
		{
			$this->_edit_post($id);
		}
		
		function del( $id = 0)
		{
			$model = $this->category_mdl->get_category_model_by_id($id);
			if($model)
			{
				$this->category_mdl->del_category_model($model);
				update_cache('menu');

				$this->_message('分类模型删除完成！','category/view',true);
			}
			else
			{
				$this->_message('不存在的分类模型!','',false);	
			}
			
		}
		
		
		function _edit_post( $id = 0)
		{
			$target_model = $this->category_mdl->get_category_model_by_id($id);
			!$target_model && $this->_message('不存在的分类模型!','',false);
			if($this->_validate_model_form($target_model->name) == TRUE)
			{
				$old_table_name = $target_model->name;
				$data['name'] = $this->input->post('name');
				$data['description'] = $this->input->post('description');
				$data['perpage'] = $this->input->post('perpage');
				$data['level'] = $this->input->post('level');
				$data['hasattach'] = $this->input->post('hasattach');
				$this->category_mdl->edit_category_model($target_model,$data);
				update_cache('category',$data['name']);
				update_cache('menu');

				$this->_message('分类模型修改成功!','category/edit/'.$target_model->id,true);
			}
			else
			{
				$this->_template('category_edit',array('model'=>$target_model));
			}
		}
		
		function _validate_model_form($name = '')
		{
			$this->load->library('form_validation');
			$callback = '|callback__check_model_name';
			if( $name && $name == trim($this->input->post('name')) )
			{
				$callback = '';
			}
			$this->form_validation->set_rules('name', '分类模型标识' , 'trim|required|alpha_dash|min_length[3]|max_length[20]'.$callback);
			$this->form_validation->set_rules('description', '分类模型名称' , 'trim|required|max_length[40]');
			$this->form_validation->set_rules('level', '分类模型层级' , 'trim|required|integer');
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
		
		function _check_model_name( $name)
		{
			if($this->category_mdl->get_category_model_by_name($name))
			{
				$this->form_validation->set_message('_check_model_name', '已经存在的分类模型标识！');	
				return FALSE;
			}
			else
			{
				return TRUE;	
			}
		}
		
		function fields( $id = 0 )
		{
			$data['model'] = $this->category_mdl->get_category_model_by_id($id);
			!$data['model'] && $this->_message('不存在的分类模型!','',false);
			$data['list']  = $this->category_mdl->get_model_fields($id);
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
			$data['model'] = $this->category_mdl->get_category_model_by_id($id);
			!$data['model'] && $this->_message('不存在的分类模型!','',false);
			$this->settings->load('fieldtypes');
			if(!$this->_validate_field_form($id))
			{
				$this->_template('fields_add',$data);
			}
			else
			{
				$this->category_mdl->add_category_field($data['model'] , $this->_get_post_data());
				
				update_cache('category',$data['model']->name);
				
				$this->_message('分类模型字段添加成功!','category/fields/'.$id,true);
			}
				
		}
		
		function edit_field( $id = 0 )
		{
			$this->_edit_field_post($id);
		}
		
		function _edit_field_post( $id = 0)
		{
			$data['field'] = $this->category_mdl->get_field_by_id( $id );
			!$data['field'] && $this->_message('不存在的分类字段!','',false);
			$data['model'] = $this->category_mdl->get_category_model_by_id($data['field']->model);
			!$data['model'] && $this->_message('不存在的分类模型!','',false);
			$this->settings->load('fieldtypes');
			if($this->_validate_field_form($data['field']->model , $data['field']->name))
			{
				$this->category_mdl->edit_category_field($data['model'],$data['field'],$this->_get_post_data());
				
				update_cache('category',$data['model']->name);
				
				$this->_message('分类模型字段修改成功!','category/edit_field/'.$id,true);		
			}
			else
			{	
				$this->_template('fields_edit',$data);	
			}
		}
		
		function del_field( $id = 0 )
		{
			$field = $this->category_mdl->get_field_by_id( $id );
			!$field && $this->_message('不存在的分类字段!','',false);
			$model = $this->category_mdl->get_category_model_by_id($field->model);
			!$model && $this->_message('不存在的分类模型!','',false);
			if($field && $model)
			{
				
				$this->category_mdl->del_category_field($model , $field);
				
				update_cache('category',$model->name);
			}
			$this->_message('字段删除成功!','category/fields/'.$model->id,true);	
		}
		
		function _check_field_name( $name )
		{
			if($this->category_mdl->check_field_unique($this->model , $name))
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
			if($name == 'classid' || $name == 'parentid' || $name == 'path' || $name == 'level')
			{
				$this->form_validation->set_message('_check_field_name_valid', '字段标识不能为classid或者parentid,或者path或者level！');	
				return FALSE;
			}
			else
			{
				return TRUE;	
			}
		}
		
		function _validate_field_form($name = '')
		{
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