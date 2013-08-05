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
 * DiliCMS 数据库备份还原优化控制器
 *
 * @package     DiliCMS
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */
class Database extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->platform->get_type() !== 'default') {
            $this->_message('对不起，数据库管理功能无法在该环境下运行.', '', FALSE);
        }
        $this->load->model('db_mdl');
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * 数据库备份表单
     *
     */
    public function index()
    {
        $this->_check_permit();
        $status = $this->db_mdl->get_folder_status();
        if ($status !== TRUE)
        {
            $this->_message($status, '', FALSE);
            return;
        }
        $data['tables'] = $this->db_mdl->get_all_tables();
        $this->_template('database_index', $data);
        return;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * 数据库备份
     *
     */
    public function export()
    {
        $this->_check_permit();
        foreach (array(
                     'export_type',
                     'tables',
                     'table_id',
                     'filename',
                     'is_compress',
                     'is_extend_insert',
                     'volume_size',
                     'start',
                     'volume') as $arg)
        {
            $$arg = $this->input->get($arg, TRUE);
        }

        if ( ! in_array($export_type, array('all', 'custom')))
        {
            $this->_message('参数错误', '', true);
            return;
        }
        $valid_tables = $this->db_mdl->get_all_tables();
        if ($export_type == 'custom')
        {
            ! is_array($tables) AND $tables = array($tables);
            foreach ($tables as $key => $table)
            {
                if ( ! in_array($table, $valid_tables))
                {
                    unset($tables[$key]);
                }
            }
            if ( ! $tables)
            {
                $this->_message('请选择要备份的数据库表', '', true);
                return;
            }
        }
        else
        {
            $tables = $valid_tables;
        }
        //设置默认值
        $volume_size = intval($volume_size);
        ! $volume AND $volume = 0;
        ! $start AND $start = 0;
        ! $table_id AND $table_id = 0;
        // 开始备份
        $status = $this->db_mdl->export($filename, $tables, $table_id, $is_compress, $volume_size, $start, $is_extend_insert, $volume);
        if ($status !== TRUE)
        {
            $status['export_type'] = $export_type;
            $status['tables'] = $this->input->get('tables');
            $message = '数据库第 '.++$volume.' 卷备份成功，页面自动跳转！';
            $url = site_url("database/export").'?'.http_build_query($status);
            $this->_message($message, $url, TRUE, '', 1000);
            return;
        }

        //备份成功完成，转到还原页面
        $this->_message('数据表备份成功', 'database/recover');
        return;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * 数据库还原
     *
     */
    public function recover()
    {
        $this->_check_permit();
        $data['files'] = $this->db_mdl->get_backup_files();
        $this->_template('database_recover', $data);
        return;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * 数据库优化
     *
     */
    public function optimize()
    {
        $this->_check_permit();
        $optimize_data = $this->db_mdl->get_unoptimized_tables();
        $this->_template('database_optimize', $optimize_data);
        return;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * 执行数据库优化
     *
     */
    public function _optimize_post()
    {
        $this->_check_permit();
        if($this->db_mdl->optimize() !== FALSE)
        {
            $this->_message('数据库优化成功', "database/optimize");
            return;
        }
        $this->_message('数据库优化失败', "database/optimize");
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * 删除数据库备份文件操作
     *
     * @return void
     */
    public function _files_post()
    {
        $files = $this->input->post('file', TRUE);
        $this->db_mdl->delete_backup_files($files);
        $this->_message('备份删除成功', "database/recover");
        return;
    }


    // ---------------------------------------------------------------------------------------------------

    /**
     * 数据库 .sql 操作
     *
     * @param  string   $operation
     * @param  string   $file .sql
     * @return void
     */
    public function files($operation, $file)
    {

        if( ! in_array($operation, array('delete', 'download', 'import')))
        {
            redirect('database');
        }

        // 删除指定 sql 文件
        if($operation == 'delete')
        {
            // do somthing here
            $this->db_mdl->delete_backup_files($file);
            $this->_message('备份删除成功', "database/recover");
            return;
        }

        // 下载指定 sql 文件
        if($operation == 'download')
        {
            $file_data = $this->db_mdl->get_backup_file_content($file);
            if ($file_data === FALSE)
            {
                $this->_message('要下载的文件不存在', "database/recover");
                return;
            }
            $this->load->helper('download');
            force_download($file, $file_data);
            return;
        }

        // 导入指定 sql 文件
        if($operation == 'import')
        {
            // do somthing here
            $this->db_mdl->import($file);
            $this->_message('备份还原成功', "database/recover");
            return;
        }
    }
}