<?php

$result = sql_query("select * from {$g5['point_table']} where po_content like '[정산]%' order by po_id desc limit 0,{$limit}");
$colspan = 6;
?>

<section id="anc_002" class="calc_list">
	<h2 class="h2_frm"><?=$config['cf_money']?> 지급 최근 <?=$limit?>건</h2> 
	<?php echo $pg_anchor ?>
<div class="tbl_head01 tbl_wrap">
	<p class="txt-right input"><a href="./point_list.php" class="btn_submit">내역 관리</a></p>
	<table>
	<caption><?=$config['cf_money']?> 목록</caption><caption>
		<colgroup>
			<col style="width: 100px;" />  
			<col />
			<col style="width: 140px;"  />
			<col style="width: 90px;" />
			<col />
			<col />
		</colgroup>
	<thead>
	<tr>
		<th scope="col">멤버명</th>
		<th scope="col"><?php echo subject_sort_link('po_content') ?>내용</a></th>
		<th scope="col"><?php echo subject_sort_link('po_datetime') ?>일시</a></th>
		<th scope="col"><?php echo subject_sort_link('po_point') ?><?=$config['cf_money']?></a></th>
		<th scope="col"><?=$config['cf_money']?>합</th>
		<th>&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php
	for ($i=0; $row=sql_fetch_array($result); $i++) {
		if ($i==0 || ($row2['mb_id'] != $row['mb_id'])) {
			$sql2 = " select mb_id, mb_name, mb_nick, mb_email, mb_homepage, mb_point from {$g5['member_table']} where mb_id = '{$row['mb_id']}' ";
			$row2 = sql_fetch($sql2);
		}

		$mb_nick = get_sideview($row['mb_id'], $row2['mb_nick'], $row2['mb_email'], $row2['mb_homepage']);

		$link1 = $link2 = '';
		if (!preg_match("/^\@/", $row['po_rel_table']) && $row['po_rel_table']) {
			$link1 = '<a href="'.G5_BBS_URL.'/board.php?bo_table='.$row['po_rel_table'].'&amp;wr_id='.$row['po_rel_id'].'" target="_blank">';
			$link2 = '</a>';
		}

		$expr = '';
		if($row['po_expired'] == 1)
			$expr = ' txt_expired';

		$bg = 'bg'.($i%2);
	?>

	<tr class="<?php echo $bg; ?>">
		<td><div><?php echo $mb_nick ?></div></td>
		<td class="txt-left"><?php echo $link1 ?><?php echo $row['po_content'] ?><?php echo $link2 ?></td>
		<td><?php echo $row['po_datetime'] ?></td>
		<td><?php echo number_format($row['po_point']) ?></td>
		<td><?php echo number_format($row['po_mb_point']) ?></td>
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