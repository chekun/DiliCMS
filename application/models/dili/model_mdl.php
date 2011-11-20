<?php if ( ! defined('IN_DiliCMS')) exit('No direct script access allowed');
class Model_mdl extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	//获得所有内容模型
	function get_models()
	{
		return $this->db->get('dili_models')->result();	
	}
	//根据内容模型id获取内容模型
	function get_model_by_id($id)
	{
		return $this->db->where('id',$id)->get('dili_models')->row();	
	}
	//根据内容模型name获取内容模型
	function get_model_by_name($name)
	{
		return $this->db->where('name',$name)->get('dili_models')->row();
	}
	//新增内容模型
	function add_new_model($data)
	{
		$this->db->insert('dili_models',$data);
		$this->load->dbforge();
		$this->dbforge->drop_table('dili_u_m_'.$data['name']);
		$this->dbforge->add_field('id');
		$this->dbforge->add_field(array('create_time' => array('type' => 'INT','constraint' => 10, 'unsigned' => TRUE)));
		$this->dbforge->add_field(array('update_time' => array('type' => 'INT','constraint' => 10, 'unsigned' => TRUE)));
		$this->dbforge->create_table('dili_u_m_'.$data['name']);
		
		//$this->db->insert('dili_resources',array('name'=>'u_c_'.$data['name'],'description'=>$data['description']));	
	}
	
	//修改内容模型
	function edit_model($target_model , $data)
	{
		$this->db->where('id',$target_model->id)->update('dili_models',$data);
		$this->load->dbforge();
		$old_table_name = $target_model->name;
		if($old_table_name != $data['name'])
		{
			$this->dbforge->rename_table('dili_u_m_'.$old_table_name, 'dili_u_m_'.$data['name']);
			$this->platform->cache_delete(FCPATH.'settings/model/'.$old_table_name.EXT);
		}
	}
	
	//删除内容模型
	function del_model($model)
	{
		$this->load->dbforge();
		//删除表
		$this->dbforge->drop_table('dili_u_m_'.$model->name);
		//删除字段
		$this->db->where('model',$model->id)->delete('dili_model_fields');
		//删除附件
		$attachments = $this->db->select('name , folder , type')->where('model',$model->id)->where('from',0)->get('dili_attachments')->result();
		foreach($attachments as $attachment)
		{
			$this->platform->file_delete(FCPATH.setting('attachment_dir').'/'.$attachment->folder.'/'.$attachment->name.'.'.$attachment->type);		
		}
		$this->db->where('model',$model->id)->where('from',0)->delete('dili_attachments');
		//删除记录
		$this->db->where('id',$model->id)->delete('dili_models');
		//清除缓存文件
		$this->platform->cache_delete(FCPATH.'settings/model/'.$model->name.EXT);
	}
	
	//获取全部字段
	function get_model_fields( $id )
	{
		return $this->db->where('model',$id)->order_by('order','ASC')->get('dili_model_fields')->result();
	}
	
	//添加新内容模型字段
	function add_field($model , $data)
	{
		$this->load->dbforge();
		$this->load->library('dili/field_behavior');
		$data['model'] = $model->id;
		$this->db->insert('dili_model_fields',$data);
		$this->dbforge->add_column('dili_u_m_'.$model->name,$this->field_behavior->on_info($data));
	}
	//根据字段id获取字段信息
	function get_field_by_id($id)
	{
		return $this->db->where('id',$id)->get('dili_model_fields')->row();	
	}
	//根据字段name获取字段信息
	function get_field_by_name($name)
	{
		return $this->db->where('name',$name)->get('dili_model_fields')->row();	
	}
	//检查字段name唯一性
	function check_field_unique($model , $name)
	{
		return $this->db->where('model',$model)->where('name',$name)->get('dili_model_fields')->row();	
	}
	//修改内容模型字段信息
	function edit_field($model , $field , $data)
	{
		$this->load->dbforge();
		$this->load->library('dili/field_behavior');
		$old_name = $field->name;
		$this->db->where('id',$field->id)->update('dili_model_fields',$data);
		$this->dbforge->modify_column('dili_u_m_'.$model->name,$this->field_behavior->on_info($data,$old_name));
	}
	//删除内容模型字段
	function del_field($model , $field)
	{
		$this->load->dbforge();
		$this->dbforge->drop_column('dili_u_m_'.$model->name , $field->name);
		$this->db->where('id',$field->id)->delete('dili_model_fields');
	}

}