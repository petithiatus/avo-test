<?php
include_once('./_common.php');

$ch = sql_fetch("select * from {$g5['character_table']} where ch_id=".$ch_id);
if(!$ch['ch_id']) {
	alert("캐릭터 정보가 존재하지 않습니다.");
}

$g5['title'] = $ch['ch_name']." 프로필";
include_once('./_head.php');


$mb = get_member($ch['mb_id']);

include_once(G5_PATH.'/member/viewer.inc.php');
include_once('./_tail.php');
?>
