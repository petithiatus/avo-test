<?php
$sub_menu = "600900";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = '통합정산';
include_once ('./admin.head.php');

if(!$limit) $limit=10;
$ad=sql_fetch(" select * from {$g5['article_default_table']} ");

$pg_anchor = '<ul class="anchor">
	<li><a href="#anc_001">정산지급</a></li>';
if($ad['ad_use_money']) 
	$pg_anchor .= '<li><a href="#anc_002">최근 '.$config['cf_money'].' 지급 내역</a></li>';
if($ad['ad_use_exp']) 
	$pg_anchor .= '<li><a href="#anc_003">최근 '.$config['cf_exp_name'].' 지급 내역</a></li>';
if($ad['ad_use_inven']) 
	$pg_anchor .= '<li><a href="#anc_004">최근 아이템 지급 내역</a></li>';
if($ad['ad_use_title']) 
	$pg_anchor .= '<li><a href="#anc_005">최근 타이틀 지급 내역</a></li>';
$pg_anchor.='</ul>';
?>
<section id="anc_001" style="padding-bottom:20px;">
	<h2 class="h2_frm">정산 지급</h2>
	<?php echo $pg_anchor ?>
	<form name="fpointlist2" method="post" id="fpointlist2" action="./calculate_update.php" autocomplete="off" onsubmit="return point_submit(this);">
	<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
	<input type="hidden" name="stx" value="<?php echo $stx ?>">
	<input type="hidden" name="sst" value="<?php echo $sst ?>">
	<input type="hidden" name="sod" value="<?php echo $sod ?>">
	<input type="hidden" name="page" value="<?php echo $page ?>">
	<input type="hidden" name="token" value="<?php echo $token ?>">

	<div class="tbl_frm01 tbl_wrap">
		<table class="form-table">
			<colgroup>
				<col style="width: 120px;">
				<col style="width: 120px;">
				<col style="width: 120px;">
				<col>
			</colgroup>
			<tbody>
				<tr>
					<th scope="row">지급유형</th>
					<td colspan="3">
						<input type="radio" id="take_type_01" name="take_type" value="P" checked onclick="if(document.getElementById('take_type_01').checked) $('#take_member_name').show();"/>
						<label for="take_type_01">개별지급</label>
						&nbsp;&nbsp;
						<input type="radio" id="take_type_02" name="take_type" value="A" onclick="if(document.getElementById('take_type_02').checked) $('#take_member_name').hide();"/>
						<label for="take_type_02">전체지급</label>
						<?if($config['cf_side_title']){
							$si_list=sql_query("select si_id,si_name from {$g5['side_table']} where si_auth<10");
							for($i=0;$si=sql_fetch_array($si_list);$i++){?> 
							&nbsp;&nbsp;
						<input type="radio" id="take_type_s_<?=$i?>" name="take_type" value="S<?=$si['si_id']?>" onclick="if(document.getElementById('take_type_s_<?=$i?>').checked) $('#take_member_name').hide();"/>	
						<label for="take_type_s_<?=$i?>"><?=$si['si_name']?> 전체지급</label>
						<?}?>
						<?}?>
							<?if($config['cf_class_title']){
							$cl_list=sql_query("select cl_id,cl_name from {$g5['class_table']} where cl_auth<10");
							for($i=0;$cl=sql_fetch_array($cl_list);$i++){?> 
							&nbsp;&nbsp;
						<input type="radio" id="take_type_c_<?=$i?>" name="take_type" value="C<?=$cl['cl_id']?>" onclick="if(document.getElementById('take_type_c_<?=$i?>').checked) $('#take_member_name').hide();"/>	
						<label for="take_type_c_<?=$i?>"><?=$cl['cl_name']?> 전체지급</label>
						<?}?>
						<?}?>
					</td>
				</tr>
				<tr id="take_member_name">
					<th scope="row">개별선택</th>
					<td colspan="3">
						<?php echo help('개별지급 시 입력') ?>
						<div class="input">
						<a href="#" class="add_item btn_submit" style="vertical-align:middle;">+</a>
						<select name="ch_id[]">
							<option value="">선택</option>
							<?$ch_list=sql_query("select ch_id,ch_name,mb_id from {$g5['character_table']} where ch_state='승인'");
							for($i=0;$ch=sql_fetch_array($ch_list);$i++){?>
								<option value="<?=$ch['ch_id']?>"><?=$ch['ch_name']."[".get_member_name($ch['mb_id'])."]"?></option>
							<?}?>
						</select></div>
					</td>
				</tr>
				<?if($ad['ad_use_money']){?>
				<tr>
					<th scope="row"><label for="po_point"><?=$config['cf_money']?></label></th>
					<td><input type="text" name="po_point" id="po_point" class="frm_input"></td>
					<th><label for="po_content"><?=$config['cf_money']?> 지급 내용</label></th>
					<td><input type="text" name="po_content" id="po_content" class="frm_input" size="80"></td>
				</tr> 
				<?}?>
				<?if($ad['ad_use_exp']){?>
				<tr>
					<th scope="row"><label for="ex_point"><?=$config['cf_exp_name']?></label></th>
					<td><input type="text" name="ex_point" id="ex_point" class="frm_input"></td>
					<th><label for="ex_content"><?=$config['cf_exp_name']?> 지급 내용</label></th>
					<td><input type="text" name="ex_content" id="ex_content" class="frm_input" size="80"></td>
				</tr> 
				<?}?>
				<?if($ad['ad_use_inven']){?>
				<tr>
					<th scope="row"><label for="it_name">아이템</label></th>
					<td colspan="3"><div class="input">
						<a href="#" class="add_item btn_submit" style="vertical-align:middle;">+</a>
						<select name="it_id[]">
							<option value="">선택</option>
							<?$it_list=sql_query("select it_id,it_name from {$g5['item_table']} where it_use='Y' order by it_name");
							for($i=0;$it=sql_fetch_array($it_list);$i++){?>
								<option value="<?=$it['it_id']?>"><?=$it['it_name']?></option>
							<?}?>
						</select></div></td>
				</tr> 
				<?}?>
				<?if($ad['ad_use_title']){?>
				<tr>
					<th scope="row"><label for="ti_name">타이틀</label></th>
					<td colspan="3"><div class="input">
						<a href="#" class="add_item btn_submit" style="vertical-align:middle;">+</a>
						<select name="ti_id[]">
							<option value="">선택</option>
							<?$ti_list=sql_query("select ti_id,ti_title from {$g5['title_table']} where ti_use='Y' order by ti_title");
							for($i=0;$ti=sql_fetch_array($ti_list);$i++){?>
								<option value="<?=$ti['ti_id']?>"><?=$ti['ti_title']?></option>
							<?}?>
						</select></div></td>
				</tr> 
				<?}?>
			</tbody>
		</table>
	</div>

	<div class="btn_confirm01 btn_confirm">
		<input type="submit" value="확인" class="btn_submit">
	</div>

	</form>

</section> 
 
<h2>최근 정산 내역</h2> 
<p style="font-size:13px;text-align:center;padding:15px;border:1px solid #efeff1;background:#f9f9f9;"> 정산으로 지급된 최근 <?=$limit?>건만 표기됩니다. 정산으로 지급된 모든 내역에는 [정산]이 붙습니다. 확인할 건수를 늘이고 싶을 경우 <?=G5_ADMIN_URL?>/calculate_list.php?limit=건수 (건수에 원하는 숫자)로 접속 해주세요</p>
<br><br>
	<?if($ad['ad_use_money']){ include_once("./calc_point.php"); }?>
	<?if($ad['ad_use_exp']){ include_once("./calc_exp.php"); }?>
	<?if($ad['ad_use_inven']){ include_once("./calc_inventory.php"); }?>
	<?if($ad['ad_use_title']){ include_once("./calc_title.php"); }?> 
</section>
<script>
	$('.add_item').click(function(){
	var item= $(this).next().clone().appendTo($(this).parent());
	return false;
	});
	function point_submit(f)
{
	if(f.po_point.value && !f.po_content.value){
		alert("화폐 지급내용을 입력해주세요.");
		return false;
	}
	if(f.ex_point.value && !f.ex_content.value){
		alert("경험치 지급내용을 입력해주세요.");
		return false;
	} 
	return true;
}

</script>

<?php
include_once ('./admin.tail.php');
?>
