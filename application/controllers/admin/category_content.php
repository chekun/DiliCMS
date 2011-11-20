<?php if ( ! defined('IN_DiliCMS')) exit('No direct script access allowed');

	class Category_content extends Admin_Controller
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
			$this->_check_permit();
			$model = $this->input->get('model');
			if(!$this->platform->cache_exists(FCPATH.'settings/category/cate_'.$model.EXT))
			{
				$this->_message('不存在的分类模型！','',false);
			}
			$this->plugin_manager->trigger_model_action('register_on_reach_model_list');
			$this->settings->load('category/cate_'.$model);
			$data['model'] = $this->settings->item('cate_models');
			$data['model'] = $data['model'][$model];
			$this->load->library('dili/form');
			$this->load->library('dili/field_behavior');
			$data['provider'] = $this->_pagination($data['model']);
			$this->_template('category_content_list',$data);	
		}
		
		function _pagination( & $model){
			$this->load->library('pagination');
			$config['base_url'] = backend_url('category_content/view');
			$config['per_page'] = $model['perpage'];
			$config['uri_segment'] = 4;
			$config['suffix'] = '?model='.$model['name'];
				
			$condition = array('classid >' => '0');
			$data['where'] = array();
			
			//暂时无搜索
			
			$level = $this->input->get('u_c_level') ? $this->input->get('u_c_level') : 0;
			
			$config['suffix'] .= '&level='.$level;
			$data['where']['u_c_level'] = $level;
			$condition['parentid ='] = $level;
			
			$this->plugin_manager->trigger_model_action('register_before_query' , $condition);
			
			$config['total_rows'] = $this->db->where($condition)->count_all_results('dili_u_c_'.$model['name']);

			$this->db->from('dili_u_c_'.$model['name']);
			$this->db->select('classid,parentid');
			$this->db->where($condition);
			foreach($model['listable'] as $v)
			{
				$this->db->select($model['fields'][$v]['name']);	
			}
			
			$this->db->offset($this->uri->segment($config['uri_segment'],0));
			$this->db->limit($config['per_page']);
			
			$data['list'] = $this->db->get()->result();

			$this->plugin_manager->trigger_model_action('register_before_list', $data['list']);
			
			if($level != 0)
			{
				$data['parent'] = $this->db->where('classid',$level)->get('dili_u_c_'.$model['name'])->row();
				$data['next_level'] = $data['parent']->level + 1;
			}
			else
			{
				$data['parent'] = NULL ;
				$data['next_level'] = 1;
			}
				
			$data['path'] = $this->_find_path($data['next_level']);
			
			$config['first_url'] = $config['base_url'] . $config['suffix'];
			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();
			return $data;
		}
		
		//获取path，用于显示在导航栏
		function _find_path($parentid = 0)
		{
			$path = array();
			for($i = 1 ; $i <= $parentid  ; $i++)
			{
				array_push($path,$i."级分类");
			}
			return $path;
		}
		
		function form()
		{
			$this->_save_post();
		}
				
		function _save_post()
		{
			
			$model = $this->input->get('model');
			$this->settings->load('category/cate_'.$model);
			$data['model'] = $this->settings->item('cate_models');
			$data['model'] = $data['model'][$model];
			$id = $this->input->get('id');
			if( $id  )
			{
				$this->_check_permit('edit');
				$data['content'] = $this->db->where('classid',$id)->get('dili_u_c_'.$model)->row_array();
				$data['attachment'] = $this->db->where('model',$data['model']['id'])->where('content',$id)->where('from',1)->get('dili_attachments')->result_array();
				$data['parentid'] = $data['content']['parentid'];
			}
			else
			{
				$this->_check_permit('add');
				$data['parentid'] = $this->input->get('u_c_level') ? $this->input->get('u_c_level') : 0;
				$data['content'] = array();
			}
			
			if($data['parentid'] > 0)
			{
				$current_level = $this->db->where('classid',$data['parentid'])->get('dili_u_c_'.$model)->row()->level + 1;	
			}
			else
			{
				$current_level = 1;	
			}
			
			
			$data['path'] = $this->_find_path($current_level);
			
			$this->load->library('form_validation');
			
			foreach($data['model']['fields'] as $v)
			{
				if($v['rules'] != '')
				{
					$this->form_validation->set_rules($v['name'], $v['description'], str_replace(",","|",$v['rules']));	
				}
			}
			
  			if ($this->form_validation->run() == FALSE)
  			{
				$this->load->library('dili/form');
				$this->load->library('dili/field_behavior');
   				$this->_template('category_content_form',$data);
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
				$data['parentid'] = $this->input->post('parentid');
				//获取path
				if( $data['parentid'] > 0 )
				{
					//如果不是顶级分类，就读其path数据
					$data['path'] = '0';
					$data['level'] = 1;
					$parent_class = $this->db->where('classid',$data['parentid'])->get('dili_u_c_'.$model)->row();
					if($parent_class && !$parent_class->path)
					{
						$data['path'] .= ',' ;
						$data['level'] = $parent_class->level + 1; 
					}
					$data['path'] .= $data['parentid'].',0';
				}
				$attachment = $this->input->post('uploadedfile');
				
				if( $id )
				{
					$this->plugin_manager->trigger_model_action('register_before_update' , $data , $id);
					$this->db->where('classid',$id);
					$this->db->update('dili_u_c_'.$model,$data);
					$this->plugin_manager->trigger_model_action('register_after_update', $data , $id);
					if($attachment != '0')
					{
						$this->db->set('model',$modeldata['id'])->set('from',1)->set('content',$id)->where('aid in ('.$attachment.')')->update('dili_attachments');	
					}
					$this->_message('修改成功!','category_content/form',true,'?model='.$modeldata['name'].'&id='.$id);	
				}
				else
				{
					$this->plugin_manager->trigger_model_action('register_before_insert',$data);
					$this->db->insert('dili_u_c_'.$model,$data);
					$id = $this->db->insert_id();
					$this->plugin_manager->trigger_model_action('register_after_insert',$data,$id);
					if($attachment != '0')
					{
						$this->db->set('model',$modeldata['id'])->set('from',1)->set('content',$id)->where('aid in ('.$attachment.')')->update('dili_attachments');	
					}
					$this->_message('添加成功!','category_content/view',true,'?model='.$modeldata['name'].'&u_c_level='.$data['parentid']);	
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
			$ids = $this->input->get_post('classid');
			$model = $this->input->get('model');
			$model_id = $this->db->select('id')->where('name',$model)->get('dili_cate_models')->row()->id;
			if($ids)
			{
				
				if(!is_array($ids))
				{
					$ids = array($ids);
				}
				//搜索子分类
				$this->db->select('classid')->from('dili_u_c_'.$model);
				$where_string = 'classid < 0 ';
				foreach($ids as $v)
				{
					$where_string .= " OR path Like '%,".$v.",%'";
				}
				$this->db->where($where_string);
				$result = $this->db->get()->result();
				foreach($result as $v)
				{
					array_push($ids,$v->classid);	
				}
				$this->plugin_manager->trigger_model_action('register_before_delete',$ids);
				$attachments = $this->db->select('name , folder , type')->where('model',$model_id)->where_in('content',$ids)->where('from',1)->get('dili_attachments')->result();
				foreach($attachments as $attachment)
				{
					$this->platform->file_delete(FCPATH.$this->settings->item('attachment_dir').'/'.$attachment->folder.'/'.$attachment->name.'.'.$attachment->type);		
				}
				$this->db->where('model',$model_id)->where_in('content',$ids)->where('from',1)->delete('dili_attachments');
				$this->db->where_in('classid',$ids)->delete('dili_u_c_'.$model);
				$this->plugin_manager->trigger_model_action('register_after_delete', $ids);
			}
			$this->_message('删除操作成功完成!','',true);
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
					$this->platform->file_delete(FCPATH.$this->settings->item('attachment_dir').'/'.$attach->folder.'/'.$attach->name.'.'.$attach->type);		
					$this->db->where('aid',$attach->aid)->delete('dili_attachments');
					echo 'ok';
				}
			}
		}
	}
