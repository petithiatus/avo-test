<?php
$sub_menu = "600900";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

check_admin_token();
 
$ch=array();
$idx=0;

if($take_type=='P'&&count($ch_id)>0){
	for($i=0;$i<count($ch_id);$i++){
		$ch_list=sql_fetch("select ch_id from {$g5['character_table']} where ch_state='승인' and ch_type!='npc' and ch_id='{$ch_id[$i]}'");
		if(!$ch_list['ch_id']) continue;
		$ch[$idx]=$ch_list['ch_id'];
		$idx++;
	}
	$ch=array_unique($ch);
}else{

if($take_type == 'A') { 
	$ch_list=sql_query("select ch_id from {$g5['character_table']} where ch_state='승인' and ch_type!='npc'");
} else if (strstr($take_type,'S')){
	$side=str_replace('S','',$take_type);
	$ch_list=sql_query("select ch_id from {$g5['character_table']} where ch_state='승인' and ch_type!='npc' and ch_side='{$side}'");
} else if (strstr($take_type,'C')){
	$class=str_replace('C','',$take_type);
	$ch_list=sql_query("select ch_id from {$g5['character_table']} where ch_state='승인' and ch_type!='npc' and ch_class='{$class}'");
} 
	for($i=0;$row=sql_fetch_array($ch_list);$i++){
		if(!$row['ch_id']) continue;
		$ch[$idx]=$row['ch_id'];
		$idx++;
	}
}

	$fail_p=array();
	$fail_e=array();
	$j=0;
	$k=0;
if(count($ch)>0){
	for($i=0;$i<count($ch);$i++){
		$char=get_character($ch[$i]);
		$mb=get_member($char['mb_id']);
		if(!$char['ch_id'])continue;
		if($ex_point!=0 && is_numeric($ex_point)){
			if($ex_point<0) $action="차감";
			else $action="획득";
			$exp=$char['ch_exp']+$ex_point;
			if($exp<0){
				$fail_e[$k]=$char['ch_name']."[".$mb['mb_name']."]";
				$k++;
			}else{
				insert_exp($char['ch_id'],$ex_point,"[정산]".$ex_content,$action);
			}
		}
		if(count($it_id)>0){
			for($h=0;$h<count($it_id);$h++){
				$it=get_item($it_id[$h]);
				if(!$it['it_id']) continue;
				sql_query("insert into {$g5['inventory_table']}
							set ch_id='{$char['ch_id']}',
								it_id='{$it['it_id']}',
								it_name='{$it['it_name']}',
								in_memo='[정산]',
								ch_name = '{$char['ch_name']}'");
			}
		}
		if(count($ti_id)>0){
			for($h=0;$h<count($ti_id);$h++){
				$ti=get_title($ti_id[$h]);
				$m_ti=sql_fetch("select ti_id from {$g5['title_has_table']} where ti_id='{$ti['ti_id']}' and ch_id='{$char['ch_id']}'");
				if(!$m_ti['ti_id']){
					sql_query("insert into {$g5['title_has_table']}
								set ch_id='{$char['ch_id']}',
									ch_name='{$char['ch_name']}',
									ti_id='{$ti['ti_id']}',
									hi_use='2'");
				}
			}
		}		
		if($po_point!=0 && is_numeric($po_point)){ 
			if($char['ch_id']!=$mb['ch_id']) continue;
			$point=$mb['mb_point']+$po_point;
			if($point<0){
				$fail_p[$j]=$char['ch_name']."[".$mb['mb_name']."]";
				$j++;
			}else{
			insert_point($char['mb_id'], $po_point, "[정산]".$po_content, '@passive', $char['mb_id'], $member['mb_id'].'-'.uniqid(''), 0);
			}
		}
	}
		
}
if(count($fail_p)>0 || count($fail_e)>0){
	if(count($fail_p)>0){
	$f_p_name=implode(",",$fail_p);
	$msg.=$f_p_name." 멤버의 소지금이 부족해 금액을 회수하지 못했습니다.<br>";
	}
	if(count($fail_e)>0){
	$f_e_name=implode(",",$fail_e);
	$msg.=$f_e_name." 캐릭터의 경험치가 부족해 경험치를 회수하지 못했습니다.<br>";
	}
	alert($msg);
}

goto_url('./calculate_list.php?'.$qstr);
?>
