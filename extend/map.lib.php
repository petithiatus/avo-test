<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가


function get_map($ma_id) { 
	global $g5;
	$ma  = sql_fetch("select * from g5_mmb_map where ma_id = '{$ma_id}'");
	return $ma;
}

function get_map_name($ma_id) { 
	global $g5;
	$ma  = sql_fetch("select ma_name from g5_mmb_map where ma_id = '{$ma_id}'");
	$result = $ma['ma_name'] ? $ma['ma_name'] : "서울";
	return $result;
}


function get_map_parnet_name($ma_id) { 
	global $g5;
	$ma  = sql_fetch("select b.ma_name from (select ma_parent from g5_mmb_map where ma_id = '{$ma_id}') a, g5_mmb_map b where a.ma_parent = b.ma_id");

	return $ma['ma_name'];
}

?>