<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
$pin = array();
$p_count = 0;

// 개인 아이템 - 선물
$pe_inven_sql = "select * from {$g5['inventory_table']} inven, {$g5['item_table']} item where inven.ch_id = '$ch_id' and item.it_id = inven.it_id and inven.se_ch_id != '' order by inven.it_id asc";
$pe_inven_result = sql_query($pe_inven_sql);
for($i=0; $row=sql_fetch_array($pe_inven_result); $i++) {
	$pin[$p_count] = $row;
	$p_count++;
}

// 개인 아이템 - 비선물
$pe_inven_sql = "select *, count(*) as cnt from {$g5['inventory_table']} inven, {$g5['item_table']} item where inven.ch_id = '$ch_id' and item.it_id = inven.it_id and inven.se_ch_id = '' group by inven.it_id order by inven.it_id asc";
$pe_inven_result = sql_query($pe_inven_sql);
for($i; $row=sql_fetch_array($pe_inven_result); $i++) {
	$pin[$p_count] = $row;
	$p_count++;
}
$i = 0;
?>

<ul class="inventory-list">
<? 
for($i=0; $i < count($pin); $i++) { ?>
	<li class="box-line bak">
<? if($pin[$i]['in_id']){ ?>
		<a href="#<?=$pin[$i]['in_id']?>" class="inven-open-popup" data-idx="<?=$pin[$i]['in_id']?>" data-type="">
			<img src="<?=$pin[$i]['it_img']?>" />
		<? if($pin[$i]['cnt'] > 1) { ?>
			<i class="count"><?=$pin[$i]['cnt']?></i>
		<? } ?>
		<? if($pin[$i]['se_ch_id'] != '') { ?>
			<i class="present"></i>
		<? } ?>
		</a>
<? } ?>
	</li>
<? } 

if($i == 0) { 
?>
	<li class="no-data">
		보유중인 아이템이 없습니다.
	</li>
<? } ?>
</ul>
