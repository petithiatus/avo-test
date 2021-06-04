<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

function get_item ($it_id) 
{
	global $g5;
	$result = sql_fetch("select * from {$g5['item_table']} where it_id = '{$it_id}'");
	return $result;
}

function get_item_img($it_id) { 
	global $g5;
	$result = sql_fetch("select it_img from {$g5['item_table']} where it_id = '{$it_id}'");
	return $result['it_img'];
}

function get_item_detail_img($it_id) { 
	global $g5;
	$result = sql_fetch("select it_1 from {$g5['item_table']} where it_id = '{$it_id}'");
	return $result['it_1'];
}

function get_item_name($it_id) { 
	global $g5;
	$result = sql_fetch("select it_name from {$g5['item_table']} where it_id = '{$it_id}'");
	return $result['it_name'];
}
function get_inventory_item($in_id) {
	global $g5;
	$result = sql_fetch("select inven.in_id, inven.ch_id, item.it_type, item.it_value, item.it_name from {$g5['inventory_table']} inven, {$g5['item_table']} item where inven.in_id = '{$in_id}' and inven.it_id = item.it_id");

	return $result;
}

function delete_inventory($in_id) {
	global $g5;

	sql_query("delete from {$g5['inventory_table']} where in_id = '{$in_id}'");
}


?>