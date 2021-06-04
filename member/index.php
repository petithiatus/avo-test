<?php
include_once('./_common.php');
include_once('./_head.php');

add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/style.member.css">', 0);

$category_title = '';
$sql_search = '';
if($side) { 
	$sql_search .= " and ch_side = '{$side}' ";
	$category_title = get_side_name($side);
}
if($class) { 
	$sql_search .= " and ch_class = '{$class}' ";
	$category_title = get_class_name($class);
}
$sql_common = "select *
			from	{$g5['character_table']}
			where	ch_state = '승인'
					{$sql_search}
			order by ch_id asc";

$result = sql_query($sql_common);
?>

<div id="member_page">

<? if($category_title) { ?>
	<h2><?=$category_title?></h2>
<? } ?>

	<ul class="member-list">
	<? for($i=0; $row=sql_fetch_array($result); $i++) { ?>
		<li>
			<div class="item">
				<div class="ui-thumb">
					<a href="./viewer.php?ch_id=<?=$row['ch_id']?>">
						<? if($row['ch_thumb']) { ?>
							<img src="<?=$row['ch_thumb']?>" />
						<? } ?>
					</a>
				</div>
				<div class="ui-profile">
					<a href="<?=G5_BBS_URL?>/memo_form.php?me_recv_mb_id=<?=$row['mb_id']?>" class='send_memo'>
						<strong><?=$row['ch_name']?></strong>
					</a>
				</div>
			</div>
		</li>
	<?
		}
		if($i == 0) { 
			echo "<li class='no-data'>등록된 멤버가 없습니다.</li>";
		}
		unset($row);
	?>
	</ul>
</div>


<script>
$('.send_memo').on('click', function() {
	var target = $(this).attr('href');
	window.open(target, 'memo', "width=500, height=300");
	return false;
});
</script>
<?php
include_once('./_tail.php');
?>
