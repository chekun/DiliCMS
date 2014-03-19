<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');
/**
 * DiliCMS
 *
 * 一款基于并面向CodeIgniter开发者的开源轻型后端内容管理系统.
 *
 * @package     DiliCMS
 * @author      DiliCMS Team
 * @copyright   Copyright (c) 2011 - 2012, DiliCMS Team.
 * @license     http://www.dilicms.com/license
 * @link        http://www.dilicms.com
 * @since       Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * DiliCMS 附件上传控制器
 *
 * 本控制器为了避免FLASH上传带来的BUG，采取了变通的方式来处理
 *
 * @package     DiliCMS
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */
class Attachment extends CI_Controller
{
	/**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	// ------------------------------------------------------------------------

    /**
     * 处理上传的POST请求
     *
     * @access  public
     * @return  string
     */
	public function _upload_post()
	{
		
		//不能加载SESSION类库
		$hash = $this->input->post('hash', TRUE);
		list($session_id, $model_type, $model) = explode('^', $hash);
        $session = $this->db->where('session_id', $session_id)->get($this->db->dbprefix('sessions'))->row();
		$status = "ok";
		$response = "";
		if ($session)
		{
			$userdata = $session->user_data ? @unserialize($session->user_data) : array(); 
			$this->load->helper('date');
			$now = now();
			if (
				 ! $userdata 
				OR 
				$now - $session->last_activity > $this->config->item('sess_expiration') 
				OR 
				$userdata['uid'] != $this->input->post('uid', TRUE)
			)
			{
				$status = "fail";
			}
			else
			{
				if ( ! $_FILES['Filedata']['error'])
				{
					$data['folder'] = date('Y/m', $now);
					$target_path = DILICMS_SHARE_PATH . '../' . setting('attachment_dir') . '/' . $data['folder'];
					if ($status != 'fail')
					{
						$realname = explode(".", $_FILES['Filedata']['name']);
						$data['type'] = strtolower(array_pop($realname));
						$data['realname'] = implode('.', $realname); 
						$data['name'] = now() . substr(md5($data['realname'] . rand()), 0, 16); 
						$data['posttime'] = now();
						$data['uid'] = $userdata['uid'];
						$target_file = $target_path . '/' . $data['name'] . '.' . $data['type'];
						if ( ! $this->platform->file_upload($_FILES['Filedata']['tmp_name'], $target_file))
						{
							$status = "fail";
						}
						else
						{
							$data['image'] = (in_array($data['type'], array('jpg', 'gif', 'png', 'jpeg', 'bmp'))) ? 1 : 0;
                            //currently we only support Imagick to resize images
                            if ($data['image'] and extension_loaded('imagick')) {
                                $this->thumbnail($model_type, $model, $target_file, $data['type']);
                            }
							$this->db->insert($this->db->dbprefix('attachments'), $data);
							$response = $this->db->insert_id() . '|' . $data['realname'] . '|' . $data['name'] . '|' . $data['image'].'|'.$data['folder'].'|'.$data['type'];
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
		echo '<?xml version="1.0" encoding="UTF-8"?>
				<return>
					<status>' . $status.'</status>
					<proID>' . $this->input->post('proid') .'</proID>
					<data>' . $response . '</data>
				</return>';
	}

	// ------------------------------------------------------------------------

    /**
     * 缩略图片
     *
     * @access  private
     * @param $model_type
     * @param $model
     * @param $target
     * @param $ext
     * @return  void
     */
    private function thumbnail($model_type, $model, $target, $ext)
    {
        //do we have thumbnail settings for target model?
        if ($model_type and $model and in_array($model_type, array('model', 'category'))) {
            if ($model_type == 'model') {
                $this->settings->load('model/' . $model);
                $target_model = $this->settings->item('models');
                $target_model = $target_model[$model];
            } elseif ($model_type == 'category') {
                $this->settings->load('category/cate_' . $model);
                $target_model = $this->settings->item('cate_models');
                $target_model = $target_model[$model];
            }
            $thumb_preferences = json_decode($target_model['thumb_preferences']);
            if ($thumb_preferences and count($thumb_preferences->enabled) > 0) {
                $thumbs_preferences = json_decode(setting('thumbs_preferences'));
                if ($thumbs_preferences) {
                    $this->load->library('tailor');
                    foreach ($thumbs_preferences as $pref) {
                        if (in_array($pref->size, $thumb_preferences->enabled)) {
                            $this->tailor->initialize($target, $ext);
                            $this->tailor->measure($pref->size, $pref->rule);
                            $this->tailor->save($target.'.'.$pref->size);
                        }
                    }
                    return $thumb_preferences->default == 'original' ? '' : $thumb_preferences->default;
                }
            }
            return '';
        }
    }

    // ------------------------------------------------------------------------

    /**
     * 编辑器文件上传接口
     *
     * @access  public
     * @return  void
     * 
     * $_GET['field'], 上传域名称,不指定则使用默认值(xheditor),filedata
     * $_GET['tpl'],返回数据的模版,{{error}}代表错误信息,{{url}}代表附件的地址,{{aid}}代表上传返回的0，不传则使用默认值(xheditor),'{"err":"{{error}}","msg":"{{url}}","aid":{{aid}}}'
     * {{name}}代表名称,{{type}}代表扩展名
     * {{object}}代表文件的完整信息
     */
	public function _save_post()
	{
		if ( ! ($field = $this->input->post('field')))
		{
			$field = 'imgFile';
		}
		if ( ! ($tpl = $this->input->post('tpl')))
		{
			$tpl = '{"error":{{status}},"url":"{{url}}","message":"{{error}}","msg":"{{object}}"}';
		}
		$error = '啊哦，登陆超时了。';
		$url = '';
		$aid = 0;
		$name = '';
		$type = '';
		$object = '';
		$this->load->library('session');
		$is_valid = FALSE;
		if ($uid = $this->session->userdata('uid'))
		{
			if (isset($_FILES[$field]) AND ! $_FILES[$field]['error'])
			{
				//判断文件MIME是否合法,文件的格式将使用数据源的位置填写，不填写则允许一切格式上传
				//加载MIMES数据
				include APPPATH.'config/mimes.php';
				$error = '对不起，不支持上传此文件类型.';
				foreach (explode(';', str_replace("*.", "", setting('attachment_type'))) as $_mime)
				{
					if (isset($mimes[$_mime]))
					{
						if (! is_array($mimes[$_mime]))
						{
							$mimes[$_mime] = array($mimes[$_mime]);
						}
						if (in_array($_FILES[$field]['type'], $mimes[$_mime]))
						{
							$is_valid = TRUE;
							$error = '';
						}
					}
				}
				//判断文件大小
				if ($is_valid AND $_FILES[$field]['size'] > setting('attachment_maxupload'))
				{
					$is_valid = FALSE;
					$error = '文件过大.';
				}
				if ($is_valid)
				{
					$this->load->helper('date');
					$_timestamp = now();
					$upload['folder'] = date('Y/m', $_timestamp);
					$target_path = DILICMS_SHARE_PATH.'../'.setting('attachment_dir').'/'.$upload['folder'];
					$realname = explode('.', $_FILES[$field]['name']);
					$type = $upload['type'] = strtolower(array_pop($realname));
					$name = $upload['realname'] = implode('.', $realname);
					$upload['name'] = $_timestamp.substr(md5($upload['realname']. rand()), 0, 16);
					$upload['posttime'] = $_timestamp;
					$upload['uid'] = $uid;
					$target_file = $target_path.'/'.$upload['name'].'.'.$upload['type'];
					if ($this->platform->file_upload($_FILES[$field]['tmp_name'], $target_file))
					{
						$upload['image'] = (in_array($upload['type'], array('jpg', 'gif', 'png', 'jpeg', 'bmp')) ? 1 : 0);
						$this->db->insert($this->db->dbprefix('attachments'), $upload);
						if ($aid = $this->db->insert_id())
						{
                            $thumbnailDefaultSize = '';
                            //currently we only support Imagick to resize images
                            if ($upload['image'] and extension_loaded('imagick')) {
                                $thumbnailDefaultSize = $this->thumbnail($this->session->userdata('model_type'), $this->session->userdata('model'), $target_file, $upload['type']);
                            }

							//已上传成功并已插入数据库
							$url = setting('attachment_url').'/'.$upload['folder'].'/'.$upload['name'].'.'.$upload['type'];
                            if ($thumbnailDefaultSize) {
                                $url .= '.'.$thumbnailDefaultSize.'.'.($upload['type'] == 'gif' ? 'gif' : 'jpg');
                            }
							$error = '';
							$object = $aid . '|' . $upload['realname'] . '|' . $upload['name'] . '|' . $upload['image'].'|'.$upload['folder'].'|'.$upload['type'];
						}
					}
				}	
			}
			else
			{
				$error = '上传的文件不存在';
			}
		}
        $status = ($error === '' ?  0 : 1);
		echo str_replace(array('{{status}}', '{{error}}', '{{url}}', '{{aid}}', '{{name}}', '{{type}}', '{{object}}'), array($status, $error, $url, $aid, $name, $type, $object), $tpl);
	}
	
	// ------------------------------------------------------------------------

    /**
     * 返回上传控件所需配置信息的XML
     *
     * @access  public
     * @return  void
     */
	public function config()
	{
		$this->load->library('session');
        $model_type = $this->session->userdata('model_type') ?: '';
        $model = $this->session->userdata('model') ?: '';
		echo '<?xml version="1.0" encoding="UTF-8"?>
				<parameter>
					<allowsExtend>
						<extend depict="支持的上传文件类型">' . $this->settings->item('attachment_type') . '</extend>
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
						<hash>'.$this->session->userdata('session_id').'^'.$model_type.'^'.$model.'</hash>
						<maxupload>'.$this->settings->item('attachment_maxupload').'</maxupload>
					</config>
				</parameter>';
	}

	// ------------------------------------------------------------------------

}

/* End of file attachment.php */
/* Location: ./admin/controllers/attachment.php */