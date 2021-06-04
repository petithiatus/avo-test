<?php
include_once('./_common.php');

$in = sql_fetch("select * from {$g5['inventory_table']} inven, {$g5['item_table']} item where inven.in_id = '{$in_id}' and inven.it_id = item.it_id");
if(!$in['in_id']) { 
	echo "<p>아이템 보유 정보를 확인할 수 없습니다.</p>";
} else {
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
			<!--div>
				<p><?=$in['it_content']?></p>
			</div-->
		</div>
<form action="<?=G5_URL?>/mypage/inventory/inventory_update.php" method="post">
	<input type="hidden" name="in_id" value="<?=$in['in_id']?>" />
	<input type="hidden" name="ch_id" value="<?=$ch['ch_id']?>" />
	<input type="hidden" name="url" value="<?=$url?>" />
		<div class="send-item-form">
			<div class="item-input">
				<select name="re_ch_id" id="re_ch_id">
					<option value="" data-head = "">받는 캐릭터</option>
<?
			$character_result = sql_query("select ch_id, ch_name from {$g5['character_table']} where ch_state='승인' order by ch_name asc");
			for($i=0; $row = sql_fetch_array($character_result); $i++) { 
?>
					<option value="<?=$row['ch_id']?>"><?=$row['ch_name']?></option>
<?
			}
?>
				</select>
			</div>
			<div class="item-input">
				<input type="text" name="in_memo" placeholder="전달 메세지" />
			</div>

		</div>
		<div class="control-box">
			<button type="submit" class="ui-btn simple">보내기</button>
		</div>
</form>
<?
	
} ?>