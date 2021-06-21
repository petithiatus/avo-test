<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$it = array();
$customer_sql = "";
$temp_wr_id = $comment_id;
$wr_num = $wr['wr_num'];
if(!$wr_num) $wr_num = $comment['wr_num'];

$sql = " update {$write_table}
			set wr_10 = '{$state}'
		  where wr_id = '{$wr_id}' ";
sql_query($sql);

if($w != 'cu') { 
	if($state=="정산완료"){ 
	$contents="";
	$cname=get_character_name($comment_ch_id);
	$msg="";
	// ----- 금액 변동
	if( $mo_value != 0 && is_numeric($mo_value)) {
		$po=sql_fetch("select mb_point from {$g5['member_table']} where mb_id='{$comment_mb_id}'");
		$point=$po['mb_point']+$mo_value;
		if($point<0) {
			$msg.="* 멤버의 소지금이 부족하여 금액을 회수하지 못했습니다.<br>";
		} else {
		insert_point($comment_mb_id, $mo_value, "[정산]".$mo_content, '@passive', $comment_mb_id, 'admin-'.uniqid(''), 0);
		$contents.=$config['cf_money'].": ".$mo_value." ".$config['cf_money_pice']."\n";
		}
	}
	// ----- 경험치 변동
	if(is_numeric($ex_value) && $ex_value != 0 ) {
		if($ex_value<0)$action="차감";
		else $action="획득";
		$ex=sql_fetch("select ch_exp from {$g5['character_table']} where ch_id='{$comment_ch_id}'");
		$exp=$ex['ch_exp']+$ex_value;
		if($exp<0){
			$msg.="* 캐릭터의 경험치가 부족하여 경험치를 회수하지 못했습니다.<br>";
		}else{
		insert_exp($comment_ch_id, $ex_value, "[정산]".$ex_content, $action);
		$contents.=$config['cf_exp_name'].": ".$ex_value." ".$config['cf_exp_pice']."\n";
		}
	}
	if(count($items)>0){
		$it_list=array();
		$idx=0;
		for($i=0;$i<count($items);$i++){
			$it=get_item($items[$i]);
			if(!$it['it_id']) continue;
			$it_list[$idx]=$it['it_name'];
			sql_query("insert into {$g5['inventory_table']}
						set ch_id = '{$comment_ch_id}',
							it_id = '{$it['it_id']}',
							in_memo = '[정산]',
							it_name = '{$it['it_name']}',
							ch_name = '{$cname}'");
			$idx++;
		}
		$item=implode(",",$it_list);
		if($item) $contents.="아이템: ".$item."\n";
	}
	if(count($titles)>0){
		$ti_list=array();
		$idx=0;
		for($i=0;$i<count($titles);$i++){
		$ti=get_title($titles[$i]);
		if(!$ti['ti_id']) continue;
		$m_ti=sql_fetch("select ti_id from {$g5['title_has_table']} where ti_id='{$ti['ti_id']}' and ch_id='{$comment_ch_id}'");
		if(!$m_ti['ti_id']){
		$ti_list[$idx]=$ti['ti_title'];
		sql_query("insert into {$g5['title_has_table']} 
					set ch_id = '{$comment_ch_id}',
						ch_name = '{$cname}',
						ti_id = '{$ti['ti_id']}',
						hi_use='2'");
		$idx++;
		}
		}
		$title=implode(",",$ti_list);
		if($title)$contents.="타이틀: ".$title."\n";
	}
	sql_query("update {$write_table} set wr_1='{$contents}' where wr_id='{$comment_id}'");
	if($msg){
		sql_query("update {$write_table} set wr_2='{$msg}' where wr_id='{$comment_id}'");
		alert($msg);
	}
	}
}

goto_url('./board.php?bo_table='.$bo_table.'&amp;'.$qstr.'&amp;#c_'.$comment_id);
?>
