<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$colspan = 5;
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);

if($is_member) { 
	set_session('ss_bo_table', $_REQUEST['bo_table']);
}

// 기본 설정 불러오기
$commu_conf = sql_fetch(" select * from {$g5['article_default_table']} ");

$write_action_url = G5_BBS_URL."/write_update.php";
$cate=explode("|",$board['bo_category_list']);
$category_option="";
for($i=0;$i<count($cate);$i++){
$bo=sql_fetch("select bo_subject from {$g5['board_table']} where bo_table='{$cate[$i]}'");
if($cate[$i]==$sca) $sel="selected";
else $sel="";
$category_option.="<option value=\"{$cate[$i]}\" {$sel}>{$bo['bo_subject']}</option>\n";
}
if($commu_conf['ad_use_title']){
$title_sql = "select * from {$g5['title_table']} where ti_use='Y' order by ti_title";
	$title_result = sql_query($title_sql);
	$ti = array();
	for($i = 0; $title_list = sql_fetch_array($title_result); $i++) {
		$ti[$i] = $title_list;
	}
}
if($commu_conf['ad_use_inven']){
$item_sql = "select * from {$g5['item_table']} where it_use='Y' and it_category!='개인' order by it_name ";
$item_result = sql_query($item_sql);
$it= array();
for($i = 0; $item_list = sql_fetch_array($item_result); $i++) {
	$it[$i] = $item_list;
}
}
?>

<p class="txt-right"><? if($admin_href){?><a href="<?=$admin_href?>" class="ui-btn admin" target="_blank">관리자</a><?}?></p>
<? if($board['bo_content_head']) { ?>
	<div class="board-notice theme-box txt-center">
		<?=stripslashes($board['bo_content_head']);?>
	</div><hr class="padding" />
<? } ?>

<? if ($write_href) { 
	$upload_action_url = G5_BBS_URL."/write_update.php"; 
?>
<div class="list-write-area">
	<a href="#" class="btn-write ui-btn point" onclick="$('#write_box').slideToggle(); return false;">정산글 등록하기</a>

	<div id="write_box" class="none-trans" style="display: none;">
		<form name="fwrite" id="fwrite" action="<?php echo $upload_action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
			<input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
			<input type="hidden" name="w" value="<?php echo $w ?>">
			<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
			<input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
			<input type="hidden" name="redirect" value="1">
			<input type="hidden" name="wr_subject" value="<?=$character['ch_name'] ? $character['ch_name'] : "GUEST"?>" />

			<div class="list-write-box">
				<?if($is_category){?><select name="ca_name" id="ca_name" required class="required" >
					<option value="">게시판 선택</option>
					<?=$category_option?>
				</select>
				<?}?>
				<fieldset>
					<textarea name="wr_content"></textarea>
				</fieldset>
				<button type="submit" accesskey="s" class="ui-btn">등록하기</button>
			</div>
		</form>
		<script>
			function fwrite_submit(f) {
				if (document.getElementById("char_count")) {
					if (char_min > 0 || char_max > 0) {
						var cnt = parseInt(check_byte("wr_content", "char_count"));
						if (char_min > 0 && char_min > cnt) {
							alert("내용은 "+char_min+"글자 이상 쓰셔야 합니다.");
							return false;
						}
						else if (char_max > 0 && char_max < cnt) {
							alert("내용은 "+char_max+"글자 이하로 쓰셔야 합니다.");
							return false;
						}
					}
				}
				document.getElementsByTagName("button['submit']").disabled = "disabled";
				return true;
			}
		</script>
			
	</div>
</div>
<? } ?>


<div class="board-skin-caculate">

	<!-- 게시판 카테고리 시작 { -->
	<nav  class="board-category">
	<? if ($is_category) { ?>
		<select name="sca" id="sca" onchange="location.href='?bo_table=<?=$bo_table?>&sca=' + this.value;">
			<option value="">전체</option>
			<? echo $category_option ?>
		</select>
		<?if($is_member){?><a href="./board.php?bo_table=<?=$bo_table?>&sfl=mb_id,1&stx=<?=$member['mb_id']?>" class="ui-btn point calc">내 정산글 보기</a><?} //@210404?>
	<? } ?>
	</nav>
	<!-- } 게시판 카테고리 끝 -->

	<div class="board-list">
<?
	for ($i=0; $i<count($list); $i++) {
		$data = $list[$i];
		$ch = get_character($data['ch_id']);
		$content = conv_content($data['wr_content'], 0, 'wr_content');
		// 해시태그 설정
		$hash_pattern = "/\\#([0-9a-zA-Z가-힣_])([0-9a-zA-Z가-힣_]*)/";
		$content = preg_replace($hash_pattern, '<a href="?bo_table='.$data['ca_name'].'&amp;hash=%23$1$2" class="link_hash_tag" target="_blank">&#35;$1$2</a>', $content);
		// 로그링크 설정
		$log_pattern = "/\\@([0-9])([0-9]*)/";
		$content = preg_replace($log_pattern, '<a href="?bo_table='.$data['ca_name'].'&amp;log=$1$2&amp;single=Y" target="_blank" class="log_link_tag">$1$2</a>', $content);
?>
		<div class="calc-item">
			<div class="thumb">
				<img src="<?=$ch['ch_thumb']?>" />
			</div>
			<div class="con-box theme-box">
				<div class="inner">
					<p class="name">
						<? if($data['wr_10']) { ?><i class="state" data-item = "<?=$data['wr_10']?>"><?=$data['wr_10']?></i><? } ?>
						<span>
							<?if($is_category){
							$ca=sql_fetch("select bo_subject from {$g5['board_table']} where bo_table='{$data['ca_name']}'");?>[<?=$ca['bo_subject']?>]<?}?>
							<a href="<?=G5_URL?>/member/viewer.php?ch_id=<?=$ch['ch_id']?>"><?=$ch['ch_name']?></a>
							<a href="<?=G5_BBS_URL?>/memo_form.php?me_recv_mb_id=<?$data['mb_id']?>" class='send_memo'>[<?=$data['wr_name']?>]</a>

							<?
								if(($data['mb_id'] == $member['mb_id'] && $data['wr_10'] == '') || $is_admin) { 
									$delete_href = G5_BBS_URL.'/delete.php?bo_table='.$bo_table.'&amp;wr_id='.$data['wr_id'];
							?>
						</span>
						<sup class="calc-btn-box">
							<? if(($data['mb_id'] == $member['mb_id'] && $data['wr_10'] == '') || $is_admin) { ?><a href="#" onclick="$(this).closest('.con-box').find('.modify-con').toggle(); return false;" class="btn-mod">수정</a><?}?>
							<a href="<?=$delete_href?>" onclick="return comment_delete();"  class="btn-del">삭제</a>
						</sup>
						<? } ?>
					</p>
					<div class="con">
						<?=$content?>
					</div>

					<? if(($data['mb_id'] == $member['mb_id'] && $data['wr_10'] == '') || $is_admin) { ?>
					<div class="modify-con">
						<form name="fwrite_<?=$data['wr_id']?>" action="<?php echo $write_action_url ?>" method="post" onsubmit="return fwrite_submit(this);" autocomplete="off">
							<input type="hidden" name="w" value="u">
							<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
							<input type="hidden" name="wr_10" value="<?php echo $data['wr_10'] ?>">
							<input type="hidden" name="wr_id" value="<?php echo $data['wr_id'] ?>">
							<input type="hidden" name="wr_subject" value="<?php echo $data['wr_subject'] ?>"> 
							<? if ($is_category) { ?>
							<select name="ca_name">
								<option value="">게시판 선택</option>
								<? $ca_list=explode("|",$board['bo_category_list']);
									for($j=0;$j<count($ca_list);$j++){
									$bo=sql_fetch("select bo_subject from {$g5['board_table']} where bo_table='{$cate[$j]}'"); ?>
									<option value="<?=$ca_list[$j]?>" <?if($ca_list[$j]==$data['ca_name']) echo "selected";?>><?=$bo['bo_subject']?></option>
									<?}?>
							</select>
							<?}?>
							<textarea name="wr_content"><?=$data['wr_content']?></textarea>
							<button type="submit" accesskey="s" class="btn_submit ui-btn point">내용수정완료</button>
						</form>
					</div>
					<? } ?>
					<? include($board_skin_path."/view_comment.php");?>
					<? if($is_admin) { ?>
					<div class="comment-form-box">
						<? include($board_skin_path."/write_comment.php");?>
					</div>
					<? } ?>
				</div>
			</div>
		</div>


<? } ?>
	</div>

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
			<option value="wr_subject"<? echo get_selected($sfl, 'wr_subject', true); ?>>캐릭터명</option>
			<option value="wr_content"<? echo get_selected($sfl, 'wr_content'); ?>>내용</option>
			<option value="mb_id,1"<? echo get_selected($sfl, 'mb_id,1'); ?>>회원아이디</option>
			<option value="mb_id,0"<? echo get_selected($sfl, 'mb_id,0'); ?>>회원아이디(코)</option>
			<option value="wr_name,1"<? echo get_selected($sfl, 'wr_name,1'); ?>>글쓴이</option>
			<option value="wr_name,0"<? echo get_selected($sfl, 'wr_name,0'); ?>>글쓴이(코)</option>
		</select>
		<input type="text" name="stx" value="<? echo stripslashes($stx) ?>" id="stx" class="frm_input" size="15" maxlength="20">
		<button type="submit" class="ui-btn point ico search default">검색</button>
		</form>
	</fieldset>
	<!-- } 게시판 검색 끝 -->
</div>

<script>
$('.send_memo').on('click', function() {
	var target = $(this).attr('href');
	window.open(target, 'memo', "width=500, height=300");
	return false;
});

var avo_mb_id = "<?=$member['mb_id']?>";
var avo_board_skin_path = "<?=$board_skin_path?>";
var avo_board_skin_url = "<?=$board_skin_url?>";

var save_before = '';
var save_html = '';

function fviewcomment_submit(f)
{
	set_comment_token(f);
	var pattern = /(^\s*)|(\s*$)/g; // \s 공백 문자
	var w="<?=$w?>";
	var content = "";
	$.ajax({
		url: g5_bbs_url+"/ajax.filter.php",
		type: "POST",
		data: {
			"content": f.wr_content.value
		},
		dataType: "json",
		async: false,
		cache: false,
		success: function(data, textStatus) {
			content = data.content;
		}
	});

	if (content) {
		alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
		f.wr_content.focus();
		return false;
	}
	
	if (!f.wr_content.value) {
		alert("댓글을 입력하여 주십시오.");
		return false;
	}

	if (typeof(f.wr_name) != 'undefined')
	{
		f.wr_name.value = f.wr_name.value.replace(pattern, "");
		if (f.wr_name.value == '')
		{
			alert('이름이 입력되지 않았습니다.');
			f.wr_name.focus();
			return false;
		}
	}

	if (typeof(f.wr_password) != 'undefined')
	{
		f.wr_password.value = f.wr_password.value.replace(pattern, "");
		if (f.wr_password.value == '')
		{
			alert('비밀번호가 입력되지 않았습니다.');
			f.wr_password.focus();
			return false;
		}
	}

	if(w=='c'){
	if(f.mo_value.value && !f.mo_content.value){
		alert("화폐 지급사유를 입력해주세요");
		f.mo_content.focus();
		return false;
	}
	if(f.ex_value.value && !f.ex_content.value){
		alert("경험치 지급사유를 입력해주세요");
		f.ex_content.focus();
		return false;
	}
	}

	return true;
}

function comment_delete()
{
	return confirm("삭제하시겠습니까?");
}

function comment_box(co_id, wr_id) { 
	$('.modify_area').hide();
	$('.original_comment_area').show();

	$('#c_'+co_id).find('.modify_area').show();
	$('#c_'+co_id).find('.original_comment_area').hide();

	$('#save_co_comment_'+co_id).focus();

	var modify_form = document.getElementById('frm_modify_comment');
	modify_form.wr_id.value = wr_id;
	modify_form.comment_id.value = co_id;
}

function modify_commnet(co_id) { 
	var modify_form = document.getElementById('frm_modify_comment');
	var wr_content = $('#save_co_comment_'+co_id).val();
	var wr_1 = $('#save_co_'+co_id).val();
	var state= $('#save_state_'+co_id).val();

	modify_form.wr_content.value = wr_content;
	modify_form.wr_1.value=wr_1;
	modify_form.state.value=state;
	$('#frm_modify_comment').submit();
}

$('.add_item').click(function(){
	var item= $(this).next().clone().appendTo($(this).parent());
	return false;
});

</script>

<form name="modify_comment" id="frm_modify_comment"  action="./write_comment_update.php" onsubmit="return fviewcomment_submit(this);" method="post" autocomplete="off">
	<input type="hidden" name="w" value="cu">
	<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
	<input type="hidden" name="sca" value="<?php echo $sca ?>">
	<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
	<input type="hidden" name="stx" value="<?php echo $stx ?>">
	<input type="hidden" name="spt" value="<?php echo $spt ?>">
	<input type="hidden" name="page" value="<?php echo $page ?>">
	<input type="hidden" name="wr_1" value="">
	<input type="hidden" name="comment_id" value="">
	<input type="hidden" name="state" value="">
	<input type="hidden" name="wr_id" value="">
	<textarea name="wr_content" style="display: none;"></textarea>
	<button type="submit" style="display: none;"></button>
</form>
