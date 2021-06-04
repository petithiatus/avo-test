<?
include_once("./_common.php");

$item = sql_fetch("select * from {$g5['shop_table']} shop, {$g5['item_table']} item where shop.it_id = item.it_id and shop.sh_id = '{$sh_id}'");

if($item['sh_id']) {

	$money = "";
	$add_str = "";

	if($item['sh_money']) { 
		$money .= $add_str.$item['sh_money'].' '.$config['cf_money_pice'];
		$add_str = ", ";
	}

	if($item['sh_exp']) { 
		$money .= $add_str.$item['sh_exp'].' '.$config['cf_exp_pice'];
	}

	if($item['it_content2']) { 
		$item['it_content'] .= "<br />(".$item['it_content2'].")";
	}

?>

<div class="type-item theme-box">

<div id="item_talk">
	<div id="item_simple_viewer">
		<div id="buy_item_data">
			<div class="item-thumb">
				<img src="<?=$item['it_img']?>" />
			</div>
			<div class="item-name"><?=$item['it_name']?> <sup><?=$money?></sup></div>
			<div class="item-content"><?=$item['it_content']?></div>
		</div>
	</div>
	<div class="item_talk"><?=$item['sh_content']?></div>
	<br />
</div>
<? if($character['ch_id'] && $character['ch_state'] == '승인') { ?>
<a href="javascript:fn_buy_item('<?=$item['sh_id']?>');" id="btn_buy" class="ui-btn full point">
	구매하기
</a>
<? } ?>

</div>

<? } else { ?>
<div id="default_talk">
	<p>
		오류가 발생했습니다. 다시 한번 선택해 주시길 바랍니다.
	</p>
</div>
<? } ?>