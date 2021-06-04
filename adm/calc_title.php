<?php
$result = sql_query("select * from {$g5['title_has_table']} where hi_use='2' order by hi_id desc limit 0,{$limit}");
$colspan = 6;
?> 
<section id="anc_005" class="calc_list">
	<h2 class="h2_frm">타이틀 지급 최근 <?=$limit?>건</h2> 
	<?php echo $pg_anchor ?>
	<div class="tbl_head01 tbl_wrap">
	<p class="txt-right input"><a href="./title_has_list.php" class="btn_submit">내역 관리</a></p>
		<table>
		<caption>타이틀 목록</caption>
		<colgroup>
			<col style="width: 80px;" />
			<col style="width: 150px;" />
			<col style="width: 150px;" />
			<col style="width: 80px;" />
			<col style="width: 80px;" />
			<col />
		</colgroup>
		<thead>
		<tr>
			<th>&nbsp;</th>
			<th>소유자</th>
			<th>타이틀 이름</th>
			<th>상태</th>
			<th>사용여부</th>
			<th>&nbsp;</th>
		</tr>
		</thead>
		<tbody>
		<?php
		for ($i=0; $row=sql_fetch_array($result); $i++) {
			$bg = 'bg'.($i%2);
			$ti = sql_fetch("select * from {$g5['title_table']} where ti_id = '{$row['ti_id']}'");
		?>

		<tr class="<?php echo $bg; ?>">
			<td>
				<img src="<?=$ti['ti_img']?>"/>
			</td>
			<td class="txt-left">
				<?=get_character_name($row['ch_id']);?>
			</td>
			<td class="txt-left">
				<?php echo get_text($ti['ti_title']); ?>
			</td>
			<td>
				<?=$ch['ch_title'] == $row['ti_id'] ? "착용" : "<span style='color: #cacaca;'>미착용</span>"?>
			</td>
			<td>
				<?php echo $row['hi_use'] ? 'Y' : 'N'?>
			</td>
			<td></td>
		</tr>

		<?php
		}

		if ($i == 0)
			echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
		?>
		</tbody>
		</table>
	</div>

</section>