<?php
include_once('./_common.php');

if($url) { 
	$return_url = urldecode($url);
} else {
	$return_url = "./viewer.php?ch_id=".$ch_id;
}

$in = sql_fetch("select * from {$g5['inventory_table']} inven, {$g5['item_table']} item where inven.in_id = '{$in_id}' and inven.it_id = item.it_id");
if(!$in['in_id']) { 
	echo "<p>아이템 보유 정보를 확인할 수 없습니다.</p>";
} else {
	if($in['it_type'] == '프로필수정') { 

		echo "LOCATIONURL||||".G5_URL."/mypage/character/character_form.php?w=u&ch_id=".$in['ch_id']."&in_id=".$in['in_id']."&url=".$url;

	} else if($in['it_type'] == '아이템추가') {
		
		// 개인 아이템 추가
		$ch = get_character($in['ch_id']);
?>
				<div class="info">
					<div class="ui-thumb">
						<img src="<?=$in['it_img']?>" />
					</div>
				</div>
				<div class="text">
					<p class="title">
						<?=$in['it_name']?>
						<span><?=number_format($in['it_sell'])?><?=$config['cf_money_pice']?></span>
					</p>
					<div>
						<p><?=$in['it_content']?></p>
					</div>
				</div>
<form action="<?=G5_URL?>/mypage/inventory/item_form_update.php" method="post" name="frmItemAdd" enctype="multipart/form-data">
	<input type="hidden" name="type" value="add" />
	<input type="hidden" name="in_id" id="a_item_add_in_id" value="<?=$in['in_id']?>" />
	<input type="hidden" name="ch_id" id="a_item_add_ch_id" value="<?=$ch['ch_id']?>" />
	<input type="hidden" name="url" value="<?=$url?>" />
				<div class="add-item-form">
					<div class="item-info">
						<input type="file" name="it_img" id="inven_it_img" class="required" required/>
					</div>
					<div class="item-input">
						<input type="text" name="it_name" class="frm_input required" placeholder="아이템 이름" required/>
					</div>
					<div class="item-input">
						<input type="text" name="it_content" class="frm_input required" placeholder="아이템 설명" required/>
					</div>
				</div>
				<div class="control-box">
					<button type="submit" class="ui-btn simple">등록하기</button>
				</div>
</form>
<?
	} else {
		if(!$it['it_use_ever']) { 
			delete_inventory($in['in_id']);
		}
		echo "LOCATIONURL||||".$return_url;
	}
} ?>