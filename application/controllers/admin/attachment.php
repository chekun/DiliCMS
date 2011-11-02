<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Attachment extends Dili_Controller {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function _upload_post()
	{
		$this->load->database();
		$session_id = $this->input->post('hash');
		$session = $this->db->where('session_id',$session_id)->get('dili_sessions')->row();
		$status = "ok";
		$response = "";
		if($session)
		{
			$userdata = $session->user_data ? unserialize($session->user_data) : array(); 
			$this->load->helper('date');
			$now = now();
			if($session->last_activity + $this->config->item('sess_expiration') < $now || !$userdata || $userdata['uid'] != $this->input->post('uid'))
			{
				$status = "fail";
			}
			else
			{
				if(!$_FILES['Filedata']['error'])
				{
					$data['folder'] = date('Y/m',$now);
					$target_path = FCPATH.setting('attachment_dir').'/'.$data['folder'];
					if(!is_dir($target_path))
					{
						if(!mkdir($target_path, 0755, true))
						{
							$status = "fail";
						}
					}
					if($status != 'fail')
					{
						$realname = explode(".",$_FILES['Filedata']['name']);
						$data['type'] = strtolower(array_pop($realname));
						$data['realname'] = implode('.',$realname); 
						$data['name'] = now().substr(md5($data['realname'].rand()),0,16); 
						$data['posttime'] = now();
						$data['uid'] = $userdata['uid'];
						if(! move_uploaded_file($_FILES['Filedata']['tmp_name'],$target_path.'/'.$data['name'].'.'.$data['type']))
						{
							$status = "fail";	
						}
						else
						{
							$data['image'] = (in_array($data['type'],array('jpg','gif','png','jpeg','bmp'))) ? 1 : 0;
							$this->db->insert('dili_attachments',$data);
							$response = $this->db->insert_id().'|'.$data['realname'].'|'.$data['name'].'|'.$data['image'].'|'.$data['folder'].'|'.$data['type'];
						}
					}
				}
				else
				{
					$status = "fail";	
				}
			}
		}
		else
		{
			$status = "fail";	
		}
		echo '<return><status>'.$status.'</status><proID>'. $this->input->post('proid') .'</proID><data>' . $response . '</data></return>';
		
	}
	
	function config()
	{
		$this->load->library('session');
		echo '<?xml version="1.0" encoding="UTF-8"?>
				<parameter>
					<allowsExtend>
						<extend depict="支持的上传文件类型">'.$this->settings->item('attachment_type').'</extend>
					</allowsExtend>
					<language>
						<okbtn>确定</okbtn>
						<ctnbtn>继续</ctnbtn>
						<fileName>文件名</fileName>
						<size>文件大小</size>
						<stat>上传进度</stat>
						<browser>浏览</browser>
						<delete>删除</delete>
						<return>返回</return>
						<upload>上传</upload>
						<okTitle>上传完成</okTitle>
						<okMsg>文件上传完成</okMsg>
						<uploadTitle>正在上传</uploadTitle>
						<uploadMsg1>总共有</uploadMsg1>
						<uploadMsg2>个文件等待上传,正在上传第</uploadMsg2>
						<uploadMsg3>个文件</uploadMsg3>
						<bigFile>文件过大</bigFile>
						<uploaderror>上传失败</uploaderror>
					</language>
					<config>
						<userid>'.$this->session->userdata('uid').'</userid>
						<hash>'.$this->session->userdata('session_id').'</hash>
						<maxupload>'.$this->settings->item('attachment_maxupload').'</maxupload>
					</config>
				</parameter>';
	}
	 	
	
}