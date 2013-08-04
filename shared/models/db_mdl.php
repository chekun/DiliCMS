<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

/**
 * 数据库备份，还原及优化
 * 修改自EasyTalk, 并使用CI类库实现
 *
 * @package      DiliCMS
 * @subpackage  Models
 * @category    Models
 * @author      CheKun <kun.che@yoozi.cn>
 * @link        http://www.dilicms.com
 */
class Db_mdl extends CI_Model
{

    private $_start_row = 0;
    private $_complete_status = 0;
    private $_backup_path = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->dbutil();
        $this->_backup_path = APPPATH . 'backup/';
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * 获取备份文件夹状态
     *
     */
    public function get_folder_status()
    {
        if ( ! file_exists($this->_backup_path))
        {
            return '备份文件夹不存在!请先在admin目录下建立backup文件夹';
        }
        else if ( ! is_really_writable($this->_backup_path))
        {
            return '备份文件夹不可写!';
        }
        return TRUE;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * 获取数据库中所有表
     *
     */
    public function get_all_tables()
    {
        return $this->db->list_tables();
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * 获取数据库需要优化的表
     *
     */
    public function get_unoptimized_tables()
    {
        $return = array(
            'tables' => array(),
            'total_size' => 0
        );
        $table_type = intval($this->db->version()) > 4.1 ? 'Engine' : 'Type';
        $tables = $this->db->query('SHOW TABLE STATUS')->result_array();
        foreach($tables as $table)
        {
            if($table['Data_free'] AND $table[$table_type] == 'MyISAM')
            {
                $checked = $table[$table_type] == 'MyISAM' ? 'checked' : 'disabled';
                $return['tables'][] = array(
                    $table['Name'],
                    $table[$table_type],
                    $table['Rows'],
                    $table['Data_length'],
                    $table['Index_length'],
                    $table['Data_free']
                );
                $return['total_size'] += ($table['Data_length'] + $table['Index_length']);
            }
        }
        return $return;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * 执行优化数据库
     *
     */
    public function optimize()
    {
        return $this->dbutil->optimize_database();
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * 获取备份文件
     *
     */
    public function get_backup_files()
    {
        $this->load->helper('file');
        $files = get_dir_file_info($this->_backup_path, TRUE);
        foreach ($files as $k => &$file)
        {
            if ($file['name'] === 'index.html') {
                unset($files[$k]);
                continue;
            }
            $file['volume'] = preg_replace("/(.*)\-(\d{1})(.*)/", "$2", $file['name']);
            if ($file['volume'] == $file['name'])
            {
                $file['volume'] = '未知';
            }
            $file['size'] = round($file['size'] / 1024, 2);
            $file['date'] = date('Y-m-d H:i', $file['date']);
            $file['extension'] = pathinfo($file["server_path"], PATHINFO_EXTENSION);

        }
        return $files;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * 删除备份文件
     *
     */
    public function delete_backup_files($files = array())
    {
        if ( ! is_array($files))
        {
            $files = array($files);
        }
        foreach ($files as $file)
        {
            $path = $this->_backup_path.$file;
            if (file_exists($path))
            {
                unlink($path);
            }
        }
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * 获取文件内容，用于下载
     *
     */
    public function get_backup_file_content($file)
    {
        if ( ! $file OR ! file_exists($this->_backup_path.$file))
        {
            return FALSE;
        }
        else
        {
            return file_get_contents($this->_backup_path.$file);
        }
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * export database as requested
     *
     */
    public function export($filename = 'db', $tables = array(), $table_id = 0, $is_compress = FALSE, $volume_size = 2048, $start = 0, $is_extend_insert = FALSE, $volume)
    {
        $volume_size = ($volume_size <= 0 ? 2048 : $volume_size);
        $volume = intval($volume) + 1;
        $sql = '';

        $this->_complete_status = TRUE;
        while ($this->_complete_status AND $table_id < count($tables) AND strlen($sql) + 500 < $volume_size * 1000)
        {
            $sql .= $this->_dump_table($tables[$table_id], $volume_size, $is_extend_insert, $start, strlen($sql));
            if ($this->_complete_status)
            {
                $start = 0;
            }
            $table_id ++;
        }
        $filename = str_replace(array('/', '\\', '.'), '', $filename);
        $backupfilename = $this->_backup_path.$filename;
        $dumpfile = $backupfilename."-%s".'.sql';

        ! $this->_complete_status AND $table_id--;

        if (trim($sql))
        {
            $sql = '# 备份标识: '.base64_encode(date('Y-m-d H:i:s').", $volume")."\n".
                "# 分卷:".$volume."\n".
                "# 时间: ".date('Y-m-d H:i:s')."\n".
                $sql;
            $dumped_filename = sprintf($dumpfile, $volume);
            if ($is_compress)
            {
                $this->load->library('zip');
                $zip_filename = str_replace('.sql', '.zip', $dumped_filename);
                if (file_exists($zip_filename))
                {
                    unlink($zip_filename);
                }
                $this->zip->add_data(str_replace($this->_backup_path, '', $dumped_filename), $sql);
                $this->zip->archive($zip_filename);
            }
            else
            {
                $this->load->helper('file');
                write_file($dumped_filename, $sql);
            }
            //not complete, return next request data
            return array(
                'volume' => $volume,
                'filename' => rawurlencode($filename),
                'is_compress' => $is_compress,
                'volume_size' => $volume_size,
                'start' => $this->_start_row,
                'table_id' => rawurlencode($table_id),
                'is_extend_insert' => $is_extend_insert
            );
        }
        //all done!
        return TRUE;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * dump table to sql
     *
     */
    private function _dump_table($table, $volume_size, $is_extend_insert, $start_from = 0, $current_size = 0)
    {
        $offset = 300;
        $sql = '';
        $table_fields = $this->db->field_data($table);
        if ( ! $start_from)
        {
            $sql = "DROP TABLE IF EXISTS `$table`;\n";
            $query = $this->db->query("SHOW CREATE TABLE ".$table);
            if ($query === FALSE)
            {
                //can't continue with this table
                return '';
            }
            $sql .= (str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $query->row()->{'Create Table'}) .";\n\n");
        }

        $num_rows = $offset;
        while ($current_size + strlen($sql) + 500 < $volume_size * 1000 AND $num_rows == $offset)
        {
            $select_sql = "SELECT * FROM $table LIMIT $start_from, $offset";
            $query = $this->db->query($select_sql);
            $fields = $query->list_fields();
            $num_rows = $query->num_rows();

            if ($num_rows > 0)
            {
                $is_extend_insert AND $sql .= "INSERT INTO `$table` VALUES ";
                foreach ($query->result_array() as $row)
                {
                    $_data = array();
                    foreach ($fields as $field)
                    {
                        $_data[] = $this->db->escape($row[$field]);
                    }
                    $_data_sql = '('. implode(',', $_data) .')';
                    if (strlen($_data_sql) + $current_size + strlen($sql) + 500 < $volume_size * 1000)
                    {
                        $start_from ++;
                        // Extended Insert ?
                        if ( ! $is_extend_insert)
                        {
                            $sql .= "INSERT INTO `$table` VALUES $_data_sql;\n";
                        }
                        else
                        {
                            $sql .= "$_data_sql, ";
                        }
                    }
                    else
                    {
                        $this->_complete_status = FALSE;
                        break 2;
                    }
                }
            }
            if ($is_extend_insert AND substr($sql, -2) == ', ')
            {
                $sql = substr_replace($sql, ';', -2);
            }
            $sql .= "\n";
        }
        $this->_start_row = $start_from;
        return $sql;
    }

    public function import($files = array())
    {
        $status = array();
        if ( ! is_array($files))
        {
            $files = array($files);
        }
        foreach ($files as $file)
        {
            $status[$file] = $this->_do_import($file);
        }
    }

    private function _do_import($file)
    {
        $path = $this->_backup_path.$file;
        if ( ! file_exists($path))
        {
            //文件不存在
            return FALSE;
        }
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        if ( ! in_array($extension, array('sql', 'zip')))
        {
            //文件格式不正确
            return FALSE;
        }
        if ($extension === 'zip')
        {
            $this->load->library('unzip');
            $status = $this->unzip->extract($path, $this->_backup_path);
            if ($status === FALSE)
            {
                //解压失败
                return FALSE;
            }
            //将path指向真正的sql文件
            $path = $this->_backup_path.str_replace(".zip", ".sql", $file);
        }

        $content = str_replace("\r", "\n", file_get_contents($path));
        $queryies = array();
        $sql_array = explode(";\n", trim($content));
        //删除临时解压的文件
        if ($extension === 'zip')
        {
            unlink($path);
        }
        foreach ($sql_array as $sql)
        {
            $_sqls = explode("\n", trim($sql));
            foreach ($_sqls as $_k => $_sql)
            {
                if ($_sql[0] == "#")
                {
                    unset($_sqls[$_k]);
                }
            }
            $queryies[] = implode("\n", $_sqls);
        }
        foreach ($queryies as $query)
        {
            $this->db->query($query);
        }
        return TRUE;
    }

}