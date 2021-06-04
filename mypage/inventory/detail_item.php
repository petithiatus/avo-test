<?php
include_once('./_common.php');

$in = sql_fetch("select * from {$g5['inventory_table']} inven, {$g5['item_table']} item where inven.in_id = '{$in_id}' and inven.it_id = item.it_id");
$ch = get_character($in['ch_id']);

if(!$in['in_id']) { 
	echo "<p>아이템 보유 정보를 확인할 수 없습니다.</p>";
} else {
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
	<div class="item-content-box">
		<div class="default">
			<?=$in['it_content']?>
		</div>
	<? if($in['it_content2']) { ?>
		<div class="effect">
			<?=$in['it_content2']?>
		</div>
	<? }?>
	<? if($in['se_ch_name']) { ?>
		<div class="memo">
			<? if($in['in_memo']) { ?><p><?=$in['in_memo']?></p><? } ?>
			<p style="text-align: right;">By. <?=$in['se_ch_name']?></p>
		</div>
	<? }?>
	</div>
</div>
<div class="control-box">
<? if($ch['mb_id'] == $character['mb_id']) { ?>
	<ul>
<? if($in['it_use_sell']==1) { ?>
		<li><a href="javascript:fn_inven_link_event('<?=$in['in_id']?>', 'sell');" data-idx="<?=$in['in_id']?>" data-type="sell" class="ui-style-btn">판매하기</a></li>
<? }?>
<? if($in['it_use_able']==1) { ?>
		<li><a href="javascript:fn_inven_link_event('<?=$in['in_id']?>', 'use');" data-idx="<?=$in['in_id']?>" data-type="use" class="ui-style-btn">사용하기</a></li>
<? } ?>
<? if(!$in['it_has']) { 
?>
		<li><a href="javascript:fn_inven_link_event('<?=$in['in_id']?>', 'take');" data-idx="<?=$in['in_id']?>" data-type="take" class="ui-style-btn">선물하기</a></li>
<?
	} ?>
	</ul>
<? } ?>
</div>
<? } ?>