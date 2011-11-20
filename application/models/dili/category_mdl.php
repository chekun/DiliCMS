<?php if ( ! defined('IN_DiliCMS')) exit('No direct script access allowed');
class Category_mdl extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function get_cate_by_id($id)
	{
		return $this->db->where('id',$id)->get('dili_cate_models')->row()->name;
	}

	function get_all($model , $level = -1)
	{
		$records = array();
		if( $level > 0 )
		{
			$this->db->where('parentid',$level);	
		}
		$query = $this->db->get('dili_u_c_'.$model);
		foreach ($query->result_array() as $row)
		{
			$row['children'] = $this->get_child_num($row['classid'],$model);
			$records[] = $row ;
		}
		return $records ;
	}
	
	function get_child_num($classid , $model)
	{
		$data = $this->db->select("COUNT(*) as children")->where("parentid",$classid)->get('dili_u_c_'.$model)->row_array();
		return $data['children'];
	}

	function  get_in_tree($array,$pid=0,$y,&$tdata=array())
	{
		foreach ($array as $row){
			if($row['parentid'] == $pid){
				$n = $y + 1;
				$row['deep'] = $y;
				$tdata[]=$row;
				$this->get_in_tree($array,$row['classid'],$n,$tdata);
			}
		}
		
		return $tdata;
	}
	
	function get_category($model)
	{
		return $this->get_in_tree($this->get_all($model),0,0);
	}
	
	/*留着待用
	function move_category($from,$to)
	{
		$data = array(
               'parent_id' => $to
        );
		$this->db->where('class_id', $from);
		$this->db->update('dili_categories', $data);
		return true;
	}
	*/
	
	//获得所有分类模型
	function get_category_models()
	{
		return $this->db->get('dili_cate_models')->result();	
	}
	//根据分类模型id获取分类模型
	function get_category_model_by_id($id)
	{
		return $this->db->where('id',$id)->get('dili_cate_models')->row();	
	}
	//根据分类模型name获取分类模型
	function get_category_model_by_name($name)
	{
		return $this->db->where('name',$name)->get('dili_cate_models')->row();	
	}
	//新增分类模型
	function add_new_category($data)
	{
		$this->db->insert('dili_cate_models',$data);
		$this->load->dbforge();
		$this->dbforge->drop_table('dili_u_c_'.$data['name']);
		$this->dbforge->add_field(array('classid' => array('type' => 'INT','constraint' => 5, 'unsigned' => TRUE , 'auto_increment' => TRUE)));
		$this->dbforge->add_key('classid',TRUE);
		$this->dbforge->add_field(array('parentid' => array('type' => 'INT','constraint' => 5, 'unsigned' => TRUE,'default'=>0)));
		$this->dbforge->add_field(array('level' => array('type' => 'INT','constraint' => 2, 'unsigned' => TRUE,'default'=>1)));
		$this->dbforge->add_field(array('path' => array('type' => 'VARCHAR','constraint' => 50)));
		
		$this->dbforge->create_table('dili_u_c_'.$data['name']);
		
		//$this->db->insert('dili_resources',array('name'=>'u_c_'.$data['name'],'description'=>$data['description']));	
	}
	
	//修改分类模型
	function edit_category_model($target_model , $data)
	{
		$this->db->where('id',$target_model->id)->update('dili_cate_models',$data);
		$this->load->dbforge();
		$old_table_name = $target_model->name;
		if($old_table_name != $data['name'])
		{
			$this->dbforge->rename_table('dili_u_c_'.$old_table_name, 'dili_u_c_'.$data['name']);
			$this->platform->cache_delete(FCPATH.'settings/category/cate_'.$old_table_name.EXT);
			$this->platform->cache_delete(FCPATH.'settings/category/data_'.$old_table_name.EXT);
		}
	}
	
	//删除分类模型
	function del_category_model($model)
	{
		$this->load->dbforge();
		//删除表
		$this->dbforge->drop_table('dili_u_c_'.$model->name);
		//删除字段
		$this->db->where('model',$model->id)->delete('dili_cate_fields');
		//删除附件
		$attachments = $this->db->select('name , folder , type')->where('model',$model->id)->where('from',1)->get('dili_attachments')->result();
		foreach($attachments as $attachment)
		{
			$this->platform->file_delete(FCPATH.setting('attachment_dir').'/'.$attachment->folder.'/'.$attachment->name.'.'.$attachment->type);		
		}
		$this->db->where('model',$model->id)->where('from',1)->delete('dili_attachments');
		//删除记录
		$this->db->where('id',$model->id)->delete('dili_cate_models');
		//清除缓存文件
		$this->platform->cache_delete(FCPATH.'settings/category/cate_'.$model->name.EXT);
		$this->platform->cache_delete(FCPATH.'settings/category/data_'.$model->name.EXT);
	}
	
	//获取全部字段
	function get_model_fields( $id )
	{
		return $this->db->where('model',$id)->order_by('order','ASC')->get('dili_cate_fields')->result();
	}
	
	//添加新分类模型字段
	function add_category_field($model , $data)
	{
		$this->load->dbforge();
		$this->load->library('dili/field_behavior');
		$data['model'] = $model->id;
		$this->db->insert('dili_cate_fields',$data);
		$this->dbforge->add_column('dili_u_c_'.$model->name,$this->field_behavior->on_info($data));
	}
	//根据字段id获取字段信息
	function get_field_by_id($id)
	{
		return $this->db->where('id',$id)->get('dili_cate_fields')->row();	
	}
	//根据字段name获取字段信息
	function get_field_by_name($name)
	{
		return $this->db->where('name',$name)->get('dili_cate_fields')->row();	
	}
	//检查字段name唯一性
	function check_field_unique($model , $name)
	{
		return $this->db->where('model',$model)->where('name',$name)->get('dili_cate_fields')->row();
	}
	//修改分类模型字段信息
	function edit_category_field($model , $field , $data)
	{
		$this->load->dbforge();
		$this->load->library('dili/field_behavior');
		$old_name = $field->name;
		$this->db->where('id',$field->id)->update('dili_cate_fields',$data);
		$this->dbforge->modify_column('dili_u_c_'.$model->name,$this->field_behavior->on_info($data,$old_name));
	}
	//删除分类模型字段
	function del_category_field($model , $field)
	{
		$this->load->dbforge();
		$this->dbforge->drop_column('dili_u_c_'.$model->name , $field->name);
		$this->db->where('id',$field->id)->delete('dili_cate_fields');	
	}

}