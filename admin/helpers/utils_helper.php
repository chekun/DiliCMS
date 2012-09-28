<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');

if (!function_exists('make_bread'))
{

/**
 * 创建面包屑导航
 */
function make_bread($flour)
{
	$bread = array();
	foreach($flour as $name => $link)
	{
		if(empty($link))
		{
			// $bread[] = "<span class=\"bread_name\"><a href=\"javascript:void(0);\">$name</a></span>";
			$bread[] = "<span class=\"bread_name\">$name</span>";
		}
		else
		{
			$bread[] = "<span class=\"bread_name\"><a href=\"$link\" target=\"_self\">$name</a></span>";
		}
	}
	return implode('<span class="bread_gt">&gt;</span>', $bread);
}

/**
 * 转换数字为中文大写
 * TODO::目前只支持数字直接转换
 */
function translate_number_to_tradition($string)
{
	return preg_replace_callback('/(\d+)/', create_function(
		'$matches',
		'return number_to_tradition($matches[0]);'
	), $string);
}

function number_to_tradition($num){
	$unit=array('','十','百','千');
	$units=array('','万','亿','兆');
	$n2s=array('零','一','二','三','四','五','六','七','八','九');
	$s2=strrev($num);//倒转字符串。
	$r="";
	$i4=-1;
	$zero="";
	for($i=0,$len=strlen($s2);$i<$len;$i++){
		if($i%4==0){
			$i4++;
			$r=$units[$i4].$r;
			$zero="";
		}
		//处理0
		if($s2{$i}=='0'){
			switch($i%4){
				case 0:
					break;
				case 1:
				case 2:        
				case 3:        
					if($s2{$i-1}!='0')$zero="零";
					break;
			}
			$r=$zero.$r;
			$zero='';
		}
		else
		{
			$r= $n2s[intval($s2{$i})].$unit[$i%4] .$r;
		}
	}
	//处理前面的0
	$zPos=strpos($r,"零");
	if($zPos==0 && $zPos!==false)$r=substr($r,2,strlen($r)-2);
	return $r;
}

}