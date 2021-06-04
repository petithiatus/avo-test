<?php 

$colspan = 6; 
$result=sql_query("select * from {$g5['inventory_table']} where in_memo='[정산]' order by in_id desc limit 0, {$limit}");
?>
 
<section id="anc_004" class="calc_list">
	<h2 class="h2_frm">아이템 지급 최근 <?=$limit?>건</h2>
	<?php echo $pg_anchor ?>
		<div class="tbl_head01 tbl_wrap">
	<p class="txt-right input"><a href="./inventory_list.php" class="btn_submit">내역 관리</a></p>
			<table>
				<caption><?php echo $g5['title']; ?> 목록</caption>
				<colgroup> 
					<col style="width: 120px;" />
					<col style="width: 80px;" />
					<col style="width: 180px;" />
					<col style="width: 120px;" />
					<col />
					<col />
				</colgroup>
				<thead>
					<tr> 
						<th scope="col">소유자</a></th>
						<th scope="col" colspan="2">아이템 이름</a></th>
						<th scope="col">보낸사람</th>
						<th scope="col">메모</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php
					for ($i=0; $row=sql_fetch_array($result); $i++) {
						$bg = 'bg'.($i%2);
						$it = get_item($row['it_id']);
					?>

					<tr class="<?php echo $bg; ?>"> 
						<td class="txt-left"><?php echo get_text($row['ch_name']); ?></td>
						<td>
						<? if($it['it_img']) { ?>
							<img src="<?=$it['it_img']?>" style="max-width: 30px;"/>
						<? } else { ?>
							이미지 없음
						<? } ?>
						</td>
						<td class="txt-left">
							<?php echo get_text($row['it_name']); ?>
						</td>
						<td class="txt-left"><?=$row['se_ch_name']?></td>
						<td class="txt-left"><?php echo $row['in_memo'] ?></td>
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
 