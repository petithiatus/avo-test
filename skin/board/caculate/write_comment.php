<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if ($is_comment_write) {
	if($w == '') $w = 'c';
?>
<!-- 댓글 쓰기 시작 { -->
<hr class="line">
<aside class="bo_vc_w" id="bo_vc_w_<?=$data['wr_id']?>">
	<form name="fviewcomment" action="./write_comment_update.php" onsubmit="return fviewcomment_submit(this);" method="post" autocomplete="off">
	<input type="hidden" name="w" value="<?php echo $w ?>" id="w">
	<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
	<input type="hidden" name="wr_id" value="<?php echo $data['wr_id'] ?>">
	<input type="hidden" name="sca" value="<?php echo $sca ?>">
	<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
	<input type="hidden" name="stx" value="<?php echo $stx ?>">
	<input type="hidden" name="spt" value="<?php echo $spt ?>">
	<input type="hidden" name="page" value="<?php echo $page ?>">

	<input type="hidden" name="ch_id" value="<?=$character['ch_id']?>" />
	<input type="hidden" name="wr_subject" value="<?=$character['ch_name']?>" />

	<input type="hidden" name="comment_mb_id" value="<?=$data['mb_id']?>" />
	<input type="hidden" name="comment_ch_id" value="<?=$data['ch_id']?>" />

<? if($is_admin) { ?>
	<div class="admin-comment">
		<p>
			<input type="radio" name="state" id="state_<?=$data['wr_id']?>_1" value="정산완료" checked/>
			<label for="state_<?=$data['wr_id']?>_1">정산완료</label>

			<input type="radio" name="state" id="state_<?=$data['wr_id']?>_2" value="반려" />
			<label for="state_<?=$data['wr_id']?>_2">반려</label>
		</p>
	</div>

	<? if($commu_conf['ad_use_money']) { ?>
	<div class="admin-comment">
		<dl class="input money">
			<dt>화폐지급</dt>
			<dd>
			<input type="text" name="mo_value" class="point" placeholder="지급금액">
			<input type="text" name="mo_content" placeholder="지급내용">
			</dd>
		</dl>
	</div>
	<? } ?>
	<? if($commu_conf['ad_use_exp']) { ?>
	<div class="admin-comment">
		<dl class="input exp">
			<dt>경험치지급</dt>
			<dd>
			<input type="text" name="ex_value" class="point" placeholder="지급경험치">
			<input type="text" name="ex_content" placeholder="지급내용">
			</dd>
		</dl>
	</div>
	<? } ?>
	<? if($commu_conf['ad_use_inven']) { ?>
	<div class="admin-comment">
		<dl class="input items">
			<dt>아이템지급</dt>
			<dd>
			<a href="#" class="add_item ui-btn">+</a>
			<select name="items[]" class="select-item">
			<option value="">아이템 선택</option>
			<?for($k=0;$k<count($it);$k++){?>
			<option value="<?=$it[$k]['it_id']?>"><?=$it[$k]['it_name']?></option>
			<?}?>
			</select></dd>
	</div> 
	<? } ?>
	<?if($commu_conf['ad_use_title']){?>
	<div class="admin-comment">
		<dl class="input titles">
			<dt>타이틀지급</dt>
			<dd>
			<a href="#" class="add_item ui-btn">+</a>
			<select name="titles[]" class="select-title" >
			<option value="">타이틀 선택</option>
			<?for($k=0;$k<count($ti);$k++){?>
			<option value="<?=$ti[$k]['ti_id']?>"><?=$ti[$k]['ti_title']?></option>
			<?}?>
			</select></dd>
		</dl>
	</div>
	<?}?>
	<div class="input-comment">
		<textarea name="wr_content" required class="required" placeholder="상세내역(상기 지급한 내역은 자동으로 출력됩니다)"></textarea>
		<div class="btn_confirm">
			<button type="submit" class="ui-comment-submit ui-btn">입력</button>
		</div>
	</div> 
<? } ?> 
	</form>
</aside>
<?
}
?>

