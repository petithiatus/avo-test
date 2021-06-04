<?php
include_once('./_common.php');
include_once('./_head.php');

if(!$ch_id) $ch_id = $character['ch_id'];
$ch = get_character($ch_id);
if(!$ch['ch_id']) {
	alert("캐릭터 정보가 존재하지 않습니다.");
}
if($ch['mb_id'] != $member['mb_id'] && !$is_admin) { 
	alert("본인 소유의 캐릭터가 아닙니다.");
}
/* 오너 정보 */
$mb = $member;
?>

<div id="character_control" class="txt-right">
	<? if($ch['ch_state'] != '승인' || $is_admin || $is_mod_character) { ?>
	<a href="./character_form.php?w=u&amp;ch_id=<?=$ch['ch_id']?>" class="ui-btn point">
		수정
	</a>
	<? if($ch['ch_state'] != '승인') { ?>
	<a href="./character_delete.php?ch_id=<?=$ch['ch_id']?>" onclick="return confirm('정말 삭제 하시겠습니까?');" class="ui-btn">
		삭제
	</a>
	<? } } ?>
</div>

<?php
include_once(G5_PATH.'/member/viewer.inc.php');
include_once('./_tail.php');
?>
