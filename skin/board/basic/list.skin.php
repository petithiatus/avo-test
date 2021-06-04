<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$colspan = 5;
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);

$category_option = get_category_option($bo_table, $sca);
?>
<hr class="padding">
<hr class="padding">
<? if($board['bo_content_head']) { ?>
	<div class="board-notice">
		<?=stripslashes($board['bo_content_head']);?>
	</div><hr class="padding" />
<? } ?>

<div class="board-skin-basic">
	<!-- 게시판 카테고리 시작 { -->
	<? if ($is_category) { ?>
	<nav  class="board-category">
		<select name="sca" id="sca" onchange="location.href='?bo_table=<?=$bo_table?>&sca=' + this.value;">
			<option value="">전체</option>
			<? echo $category_option ?>
		</select>
	</nav>
	<? } ?>
	<!-- } 게시판 카테고리 끝 -->

	<ul class="avocado-list">
		<?
		for ($i=0; $i<count($list); $i++) {
		?>
		<li class="theme-box <? if ($list[$i]['is_notice']) echo "bo_notice"; ?>">
			<a href="<? echo $list[$i]['href'] ?>">
				<? if ($is_category && $list[$i]['ca_name']) { ?>
				<i class="ico-cate"><? echo $list[$i]['ca_name'] ?></i>
				<? } ?>
			
				<strong <? if (!$list[$i]['is_notice']) echo "class='txt-default'";?>>
					<? echo $list[$i]['subject'] ?>
					<? if ($list[$i]['comment_cnt']) { ?>
						<?=$list[$i]['comment_cnt']?>
					<? } ?>
				</strong>

				<div class="info">
					<span class="name">
						<? echo $list[$i]['name'] ?>
					</span>
					<span class="date">
						<? echo date('y/m/d', strtotime($list[$i]['wr_datetime'])) ?>
					</span>
				</div>
			</a>
		</li>
		
		<? } ?>
		<? if (count($list) == 0) { echo '<li class="no-data">게시물이 없습니다.</li>'; } ?>
	</ul>
	
	<? if ($list_href || $is_checkbox || $write_href) { ?>
	<div class="bo_fx txt-right" style="padding: 20px 0;">
		<? if ($list_href || $write_href) { ?>
		<? if ($list_href) { ?><a href="<? echo $list_href ?>" class="ui-btn">목록</a><? } ?>
		<? if ($write_href) { ?><a href="<? echo $write_href ?>" class="ui-btn point">글쓰기</a><? } ?>
		<? } ?>
		<? if($admin_href){?><a href="<?=$admin_href?>" class="ui-btn admin">관리자</a><?}?>
	</div>
	<? } ?>

	<!-- 페이지 -->
	<? echo $write_pages;  ?>

	<!-- 게시판 검색 시작 { -->
	<fieldset id="bo_sch" class="txt-center">
		<legend>게시물 검색</legend>

		<form name="fsearch" method="get">
		<input type="hidden" name="bo_table" value="<? echo $bo_table ?>">
		<input type="hidden" name="sca" value="<? echo $sca ?>">
		<input type="hidden" name="sop" value="and">
		<select name="sfl" id="sfl">
			<option value="wr_subject"<? echo get_selected($sfl, 'wr_subject', true); ?>>제목</option>
			<option value="wr_content"<? echo get_selected($sfl, 'wr_content'); ?>>내용</option>
			<option value="wr_subject||wr_content"<? echo get_selected($sfl, 'wr_subject||wr_content'); ?>>제목+내용</option>
			<option value="mb_id,1"<? echo get_selected($sfl, 'mb_id,1'); ?>>회원아이디</option>
			<option value="mb_id,0"<? echo get_selected($sfl, 'mb_id,0'); ?>>회원아이디(코)</option>
			<option value="wr_name,1"<? echo get_selected($sfl, 'wr_name,1'); ?>>글쓴이</option>
			<option value="wr_name,0"<? echo get_selected($sfl, 'wr_name,0'); ?>>글쓴이(코)</option>
		</select>
		<input type="text" name="stx" value="<? echo stripslashes($stx) ?>" required id="stx" class="frm_input required" size="15" maxlength="20">
		<button type="submit" class="ui-btn point ico search default">검색</button>
		</form>
	</fieldset>
	<!-- } 게시판 검색 끝 -->
</div>


<? if ($is_checkbox) { ?>
<script>
function all_checked(sw) {
	var f = document.fboardlist;

	for (var i=0; i<f.length; i++) {
		if (f.elements[i].name == "chk_wr_id[]")
			f.elements[i].checked = sw;
	}
}

function fboardlist_submit(f) {
	var chk_count = 0;

	for (var i=0; i<f.length; i++) {
		if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
			chk_count++;
	}

	if (!chk_count) {
		alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
		return false;
	}

	if(document.pressed == "선택복사") {
		select_copy("copy");
		return;
	}

	if(document.pressed == "선택이동") {
		select_copy("move");
		return;
	}

	if(document.pressed == "선택삭제") {
		if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
			return false;

		f.removeAttribute("target");
		f.action = "./board_list_update.php";
	}

	return true;
}

// 선택한 게시물 복사 및 이동
function select_copy(sw) {
	var f = document.fboardlist;

	if (sw == "copy")
		str = "복사";
	else
		str = "이동";

	var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

	f.sw.value = sw;
	f.target = "move";
	f.action = "./move.php";
	f.submit();
}
</script>
<? } ?>
<!-- } 게시판 목록 끝 -->
