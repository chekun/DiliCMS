<?php

	class Content extends Admin_Controller
	{
		function __construct()
		{
			parent::__construct();	
		}
						
		function view()
		{
			$this->_view_post();
		}
		
		function _view_post()
		{
			$model = $this->input->get('model');
			if(!$model && $this->acl->_default_link){redirect($this->acl->_default_link);}
			$this->_check_permit();
			if(!file_exists(FCPATH.'settings/model/'.$model.EXT))
			{
				$this->_message('不存在的模型！','',false);
			}
			$this->settings->load('model/'.$model);
			$data['model'] = $this->settings->item('models');
			$data['model'] = $data['model'][$model];
			$data['provider'] = $this->_pagination($data['model']);
			$this->load->library('dili/form');
			$this->_template('content_list',$data);	
		}
		
		function _pagination( & $model){
			$this->load->library('pagination');
			$config['base_url'] = backend_url('content/view');
			$config['per_page'] = $model['perpage'];
			$config['uri_segment'] = 4;
			$config['suffix'] = '?model='.$model['name'];
				
			$condition = array('id >'=> '0');
			$data['where'] = array();
			
			foreach($model['searchable'] as $v)
			{
				if($search = $this->input->get_post($model['fields'][$v]['name']))
				{
					if(in_array($model['fields'][$v]['type'],array('input','textarea','wysiwyg','wysiwyg_basic')))
					{
						$condition[$model['fields'][$v]['name'].' LIKE'] = '%'.$search.'%';
						$data['where'][$model['fields'][$v]['name']] = $search;
						$config['suffix'] .= '&'.$model['fields'][$v]['name'].'='.$search;
					}
					else if(in_array($model['fields'][$v]['type'],array('select','radio','datetime','colorpicker','int','float','select_from_model','radio_checkbox')))
					{
						$condition[$model['fields'][$v]['name'].' ='] = $search;
						$data['where'][$model['fields'][$v]['name']] = $search;
						$config['suffix'] .= '&'.$model['fields'][$v]['name'].'='.$search;
					}
					else if($model['fields'][$v]['type'] == 'checkbox' || $model['fields'][$v]['type'] == 'checkbox_from_model')
					{
						$data['where'][$model['fields'][$v]['name']] = $search;
						$config['suffix'] .= '&'.$model['fields'][$v]['name'].'='.$search;
						$search = is_array($search) ? $search : explode(',',$search);
						foreach($search as $k)
						{
							$condition[$model['fields'][$v]['name'].' LIKE'] = '%'.$k.'%';
						}
						
					}else if($model['fields'][$v]['type'] == 'linked_menu')
					{
						$data['where'][$model['fields'][$v]['name']] = $search;
						$config['suffix'] .= '&'.$model['fields'][$v]['name'].'='.$search;
						$search = is_array($search) ? $search : explode('|',$search);
						foreach($search as $k)
						{
							$condition[$model['fields'][$v]['name'].' LIKE'] = '%'.$k.'%';
						}
					}
				}
			}
			
			$this->plugin_manager->trigger_model_action('register_before_query' , $condition);
			
			$config['total_rows'] = $this->db->where($condition)->count_all_results('dili_u_m_'.$model['name']);
			$this->db->from('dili_u_m_'.$model['name']);
			$this->db->select('id');
			$this->db->where($condition);
			foreach($model['listable'] as $v)
			{
				$this->db->select($model['fields'][$v]['name']);	
			}
			
			$this->db->order_by('create_time','DESC');
			$this->db->offset($this->uri->segment($config['uri_segment'],0));
			$this->db->limit($config['per_page']);
			
			$data['list'] = $this->db->get()->result();
			
			$this->plugin_manager->trigger_model_action('register_before_list', $data['list']);
			
			$config['first_url'] = $config['base_url'] . $config['suffix'];
			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();
			return $data;
		}
		
		function form()
		{
			$this->_save_post();
		}
				
		function _save_post()
		{
			
			$model = $this->input->get('model');
			$this->settings->load('model/'.$model);
			$data['model'] = $this->settings->item('models');
			$data['model'] = $data['model'][$model];
			$id = $this->input->get('id');
			if( $id  )
			{
				$this->_check_permit('edit');
				$data['content'] = $this->db->where('id',$id)->get('dili_u_m_'.$model)->row_array();
				$data['attachment'] = $this->db->where('model',$data['model']['id'])->where('content',$id)->where('from',0)->get('dili_attachments')->result_array();
			}
			else
			{
				$this->_check_permit('add');
				$data['content'] = array();
			}
			
			$this->load->library('form_validation');
			
			foreach($data['model']['fields'] as $v)
			{
				if($v['rules'] != '')
				{
					$this->form_validation->set_rules($v['name'], $v['description'], $v['rules']);	
				}
			}
			
  			if ($this->form_validation->run() == FALSE)
  			{
				$this->load->library('dili/form');
   				$this->_template('content_form',$data);
  			}
			else
			{
				$modeldata = $data['model'];
				$data = array();
				foreach($modeldata['fields'] as $v)
				{
					if($v['editable'])
					{
						$data[$v['name']] = $this->input->post($v['name']);
						if(($v['type'] == 'checkbox' || $v['type'] == 'checkbox_from_model') && is_array($data[$v['name']]))
						{
							$data[$v['name']] = $data[$v['name']] ? implode(',',$data[$v['name']]) : '';     
						}
					}
					
				}
				$attachment = $this->input->post('uploadedfile');
				if( $id )
				{
					$this->db->where('id',$id);
					$data['update_time'] = $this->session->_get_time();
					$this->plugin_manager->trigger_model_action('register_before_update' , $data , $id);
					$this->db->update('dili_u_m_'.$model,$data);
					$this->plugin_manager->trigger_model_action('register_after_update', $data , $id);
					if($attachment != '0')
					{
						$this->db->set('model',$modeldata['id'])->set('from',0)->set('content',$id)->where('aid in ('.$attachment.')')->update('dili_attachments');	
					}
					$this->_message('修改成功！','content/form',true,'?model='.$modeldata['name'].'&id='.$id);
				}
				else
				{
					$data['create_time'] = $data['update_time'] = $this->session->_get_time();
					$this->plugin_manager->trigger_model_action('register_before_insert',$data);
					$this->db->insert('dili_u_m_'.$model,$data);
					$id = $this->db->insert_id();
					$this->plugin_manager->trigger_model_action('register_after_insert',$data,$id);
					if($attachment != '0')
					{
						$this->db->set('model',$modeldata['id'])->set('from',0)->set('content',$id)->where('aid in ('.$attachment.')')->update('dili_attachments');	
					}
					$this->_message('添加成功！','content/view',true,'?model='.$modeldata['name']);	
				}
			}
			
		}
		
		function del()
		{
			$this->_check_permit();
			$this->_del_post();	
		}
		
		function _del_post()
		{
			$this->_check_permit();
			$ids = $this->input->get_post('id');
			$model = $this->input->get('model');
			$model_id = $this->db->select('id ')->where('name',$model)->get('dili_models')->row()->id;
			if($ids)
			{
				
				if(!is_array($ids))
				{
					$ids = array($ids);
				}
				$this->plugin_manager->trigger_model_action('register_before_delete',$ids);
				$attachments = $this->db->select('name , folder , type')->where('model',$model_id)->where('from',0)->where_in('content',$ids)->get('dili_attachments')->result();
				foreach($attachments as $attachment)
				{
					@unlink(FCPATH.$this->settings->item('attachment_dir').'/'.$attachment->folder.'/'.$attachment->name.'.'.$attachment->type);		
				}
				$this->db->where('model',$model_id)->where_in('content',$ids)->where('from',0)->delete('dili_attachments');
				$this->db->where_in('id',$ids)->delete('dili_u_m_'.$model);
				$this->plugin_manager->trigger_model_action('register_after_delete', $ids);
			}
			$this->_message('删除成功！','',true);	
		}
		
		function attachment($action = 'list')
		{
			if($action == 'list')
			{
				$response = array();
				$ids = $this->input->get('ids');
				$attachments = 	$this->db->select('aid,realname,name,image,folder,type')
										 ->where("aid in ($ids)")
										 ->get('dili_attachments')
										 ->result_array();
				foreach($attachments as $v)
				{
					array_push($response,implode('|',$v));	
				}
				echo implode(',',$response);
			}
			else if($action == 'del')
			{
				$attach = $this->db->select('aid,name,folder,type')
								   ->where('aid',$this->input->get('id'))
								   ->get('dili_attachments')->row(); 
				if($attach)
				{
					@unlink(FCPATH.$this->settings->item('attachment_dir').'/'.$attach->folder.'/'.$attach->name.'.'.$attach->type);		
					$this->db->where('aid',$attach->aid)->delete('dili_attachments');
					echo 'ok';
				}
			}
		}
		
		
		
	}