<?php  
$result = sql_query("select * from {$g5['exp_table']} where ex_content like '[정산]%' order by ex_id desc limit 0, {$limit}");
 
$colspan = 7;
?>
 

<section id="anc_003" class="calc_list">
	<h2 class="h2_frm"><?=$config['cf_exp_name']?> 지급 최근 <?=$limit?>건</h2>
	<?php echo $pg_anchor ?>
	<div class="tbl_head01 tbl_wrap">
	<p class="txt-right input"><a href="./exp_list.php" class="btn_submit">내역 관리</a></p>
		<table>
		<caption><?php echo $g5['title']; ?> 목록</caption>
		<colgroup>
			<col style="width: 150px;" />
			<col style="width: 150px;" />
			<col />
			<col style="width: 100px;" />
			<col style="width: 200px;" />
			<col />
			<col />
		</colgroup>
		<thead>
		<tr>
			<th>오너명</th>
			<th>캐릭터 이름</th>
			<th>지급 내용</th>
			<th><?=$config['cf_exp_name']?></th>
			<th>일시</th>
			<th><?=$config['cf_exp_name']?> 합계</th>
			<th>&nbsp;</th>
		</tr>
		</thead>
		<tbody>
		<?php
		for ($i=0; $row=sql_fetch_array($result); $i++) {
			$bg = 'bg'.($i%2);
			$ch = get_character($row['ch_id']);
		?>

		<tr class="<?php echo $bg; ?>">
			<td><?=get_member_name($ch['mb_id'])?></td>
			<td><a href="?sfl=ch_name&amp;stx=<?php echo $row['ch_name'] ?>"><?php echo $row['ch_name'] ?></a></td>
			<td class="txt-left"><?php echo $row['ex_content'] ?></td>
			<td><?php echo number_format($row['ex_point']) ?></td>
			<td><?php echo $row['ex_datetime'] ?></td>
			<td><?php echo number_format($row['ex_ch_exp']) ?></td>
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