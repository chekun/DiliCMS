<?php

	class Column_mdl extends CI_Model
	{
		function __construct()
		{
			parent::__construct();	
		}
		
		function info($data,$oldname = '')
		{
			switch($data['type'])
			{
				case 'select_from_model' :
				case 'radio_from_model':
				case 'int'   	: $field = array(
												'type' => 'INT',
                                                 'constraint' => $data['length'] ? $data['length'] : 10 ,
												 'default' => 0
												) ;
								break;
				case 'float' : $field = array(
												'type' => 'FLOAT',
                                                 'constraint' => $data['length'] ? $data['length'] : 10,
												 'default' => 0
												) ;
								break;
				case 'input' : 
				case 'select':
				case 'radio' :
				case 'checkbox':
				case 'checkbox_from_model':
				case 'datetime':
				case 'colorpicker':
				case 'linked_menu':
				case 'textarea' : 
								$field = array(
												'type' => 'VARCHAR',
                                                 'constraint' => $data['length'] ? $data['length'] : 100 ,
												 'default' => ''
												) ;
								break;
				case 'wysiwyg' :
				case 'wysiwyg_basic':
								$field = array(
												'type' => 'TEXT',
												'default' => ''
												) ;
								break;
			}
			if($oldname != '')
			{
				$field['name'] = $data['name'];
				return array($oldname => $field);
			}
			else
			{
				return array($data['name'] => $field);
			}
		}
		
	}