<?php
include_once('./_common.php');
include_once('./_head.php');

$ch_array = array();
$character_result = sql_query("select * from {$g5['character_table']} where mb_id = '{$member['mb_id']}' and ch_state != '삭제'");
for($i=0; $row = sql_fetch_array($character_result); $i++) {
	$ch_array[$i] = $row;
}
?>

<section>
	<table class="theme-form">
		<colgroup>
			<col style="width: 110px;" />
		</colgroup>
		<tbody>
			<tr>
				<th>신청서</th>
				<td>
					<form name="frm_main_character" action="./maincharacter_update.php" method="post">
						<input type="hidden" name="mb_id" value="<?=$member['mb_id']?>" />
						<input type="hidden" name="return_url" value="character" />
						<select name="ch_id" id="ch_id">
							<option value="">신청서 선택</option>
					<?	for($i = 0; $i < count($ch_array); $i++) { $ch = $ch_array[$i]; ?>
							<option value="<?=$ch['ch_id']?>" <?=$member['ch_id'] == $ch['ch_id'] ? "selected" : ""?>>
								<?=$ch['ch_name']?>
							</option>
					<? } ?>
						<select>
						<input type="submit" value="변경" class="ui-btn"/>
					</form>
				</td>
			</tr>
		</tbody>
	</table>
</section>

<section>
	<table class="theme-list">
		<colgroup>
			<col style="width: 100px;" />
		</colgroup>
		<thead>
			<tr>
				<th>두상</th>
				<th>프로필정보</th>
			</tr>
		</thead>
		<tbody>
		<?	for($i = 0; $i < count($ch_array); $i++) { $ch = $ch_array[$i]; ?>
			<tr>
				<td class="txt-center">
					<a href="./viewer.php?ch_id=<?=$ch['ch_id']?>">
					<? if($ch['ch_thumb']) { ?>
						<img src="<?=$ch['ch_thumb']?>" alt="두상" style="max-width: 90px; max-height: 80px;"/>
					<? } else { ?>
						-
					<? } ?>
					<? if($ch['ch_state'] == '수정중') { ?>
						<p>수정중</p>
					<? } ?>
					</a>
				</td>
				<td style="vertical-align: top;">
					<? if($ad['ad_use_name']) { ?>
					<p>
						<strong class="txt-point"><?=$ad['ad_text_name']?></strong>
						<?php echo $ch['ch_name'] ?>
					</p>
					<? } ?>
					<? if($ad['ad_use_rank']) { ?>
					<p>
						<strong class="txt-point"><?=$config['cf_rank_name']?></strong>
						<?php echo get_rank_name($ch['ch_rank']) ?>
					</p>
					<? } ?>
					<? if($ad['ad_use_exp']) { ?>
					<p>
						<strong class="txt-point"><?=$config['cf_exp_name']?></strong>
						<?php echo $ch['ch_exp'].$config['cf_exp_pice'] ?>
					</p>
					<? } ?>
					<? if($config['cf_side_title']) { ?>
					<p>
						<strong class="txt-point"><?=$config['cf_side_title']?></strong>
						<?=get_side_name($ch['ch_side'])?>
					</p>
					<? } ?>
					<? if($config['cf_class_title']) { ?>
					<p>
						<strong class="txt-point"><?=$config['cf_class_title']?></strong>
						<?=get_class_name($ch['ch_class'])?>
					</p>
					<? } ?>
				</td>
			</tr>
		<? } if($i==0) { ?>
			<tr>
				<td colspan="2" class="no-data">
					등록된 캐릭터가 없습니다.
				</td>
			</tr>
		<? } ?>
		</tbody>
	</table>

	<br />
<? if($is_add_character && ($i == 0 || $i < $config['cf_character_count'])) { ?>
	<div class="txt-center">
		<a href="./character_form.php" class="ui-btn point">신규 캐릭터 등록</a>
	</div>
<? } ?>
</section>


<?php
include_once('./_tail.php');
?>
