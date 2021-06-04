<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>

<script language="JavaScript">
// 글자수 제한
var char_min = parseInt(<?=$comment_min?>); // 최소
var char_max = parseInt(<?=$comment_max?>); // 최대
</script>

<!-- 코멘트 리스트 -->
 
<ul>
<!-- 코멘트 리스트 -->
<?
for ($i=0; $i<count($list); $i++) {
	$comment_id = $list[$i]['wr_id'];
?>
			
	<li class="theme-box">
		<a name="c_<?=$comment_id?>"></a>
		<div class="qna-comment-content">
			<!-- 코멘트 출력 -->
			<?
			if (strstr($list[$i]['wr_option'], "secret")) echo "<span style='color:#ff6600;'>*</span> ";
			$str = $list[$i]['content'];
			if (strstr($list[$i]['wr_option'], "secret"))
				$str = "<span class='small' style='color:#ff6600;'>$str</span>";

			$str = preg_replace("/\[\<a\s.*href\=\"(http|https|ftp|mms)\:\/\/([^[:space:]]+)\.(mp3|wma|wmv|asf|asx|mpg|mpeg)\".*\<\/a\>\]/i", "<script>doc_write(obj_movie('$1://$2.$3'));</script>", $str);
			$str = preg_replace("/\[\<a\s.*href\=\"(http|https|ftp)\:\/\/([^[:space:]]+)\.(swf)\".*\<\/a\>\]/i", "<script>doc_write(flash_movie('$1://$2.$3'));</script>", $str);
			$str = preg_replace("/\[\<a\s*href\=\"(http|https|ftp)\:\/\/([^[:space:]]+)\.(gif|png|jpg|jpeg|bmp)\"\s*[^\>]*\>[^\s]*\<\/a\>\]/i", "<img src='$1://$2.$3' id='target_resize_image[]' onclick='image_window(this);' border='0'>", $str);
			echo $str;
			$query_string = clean_query_string($_SERVER['QUERY_STRING']);
		
	
			if($w == 'cu') {
				$sql = " select wr_id, wr_content, mb_id from $write_table where wr_id = '$comment_id' and wr_is_comment = '1' ";
				$cmt = sql_fetch($sql);
				if (!($is_admin || ($member['mb_id'] == $cmt['mb_id'] && $cmt['mb_id'])))
					$cmt['wr_content'] = '';
				$c_wr_content = $cmt['wr_content'];
			}

			$c_edit_href = './board.php?'.$query_string.'&amp;comment_id='.$comment_id.'&amp;wr_id='.$wr_id.'w=cu';

			?>
		</div>
		<?if($list[$i]['is_edit']||$list[$i]['is_del']){?>
		<p>
			<em>&nbsp;</em>
			<strong>
				<? if ($list[$i]['is_edit']) { ?><span><a href="javascript:comment_box('<?=$wr_id?>','<? echo $comment_id ?>', 'cu');" class="v7">M</a></span><? } ?>
				<? if ($list[$i]['is_del'])  { echo "<span class=\"v7\"><a href=\"javascript:comment_delete('{$list[$i]['del_link']}');\">D</a></span>&nbsp;"; } ?>
			</strong>
		</p>
		<?}?>
			<span id="edit_<? echo $comment_id ?>"></span><!-- 수정 -->

			<input type="hidden" value="<? echo strstr($list[$i]['wr_option'],"secret") ?>" id="secret_comment_<? echo $comment_id ?>">
			<textarea id="save_comment_<? echo $comment_id ?>" style="display:none"><? echo get_text($list[$i]['wr_content'], 0) ?></textarea>
	</li>
<? } ?>
</ul> 
<? if ($is_comment_write) { 
	if($w == '') $w = 'c';
	?>
<div class="ui-write-area" id="comment_write<?=$wr_id?>" style="display:none;">
	<!-- 코멘트 입력테이블시작 -->
	<form name="fviewcomment" method="post" action="./write_comment_update.php" autocomplete="off">
		<input type="hidden" name="w"			value='<?=$w?>' class="w">
		<input type="hidden" name="bo_table"	value='<?=$bo_table?>'>
		<input type="hidden" name="wr_id"		value='' class="wr_id">
		<input type="hidden" name="comment_id"	value='' class="co_id">
		<input type="hidden" name="token"		value='<?=$comment_token?>'>
		<input type="hidden" name="sca"			value='<?=$sca?>' >
		<input type="hidden" name="sfl"			value='<?=$sfl?>' >
		<input type="hidden" name="stx"			value='<?=$stx?>'>
		<input type="hidden" name="spt"			value='<?=$spt?>'>
		<input type="hidden" name="page"		value='<?=$page?>'>
		<input type="hidden" name="cwin"		value='<?=$cwin?>'>
		<input type="hidden" name="is_good"		value=''>
		<input type="hidden" name="url"			value='./board.php?bo_table=<?=$bo_table?>&page=<?=$page?>'>
		

		<textarea class="wr_content" name="wr_content" rows=4 itemname="내용" required
		<? if ($comment_min || $comment_max) { ?>onkeyup="check_byte('wr_content', 'char_count');"<?}?> style='width:100%; word-break:break-all;' class='tx'><?=$list[$i]['wr_content']?></textarea>
		<? if ($comment_min || $comment_max) { ?><script type="text/javascript"> check_byte('wr_content', 'char_count'); </script><?}?>

		<div class="txt-right" style="padding-bottom:5px;">
			<button type="submit"  class="btn_submit ui-btn" accesskey='s'>ENTER</button>
		</div>
	</form>
</div>
<? } ?>

<script language='JavaScript'>
function fviewcomment_submit(f)
{
	$('.btn_submit').prop('disabled',true);
    return true;
}
var save_before = '';
function comment_box(wr_id, comment_id, work)
{
var save_html = document.getElementById('comment_write'+ wr_id).innerHTML;
	var el_id;
	// 댓글 아이디가 넘어오면 답변, 수정
	if (comment_id)
	{
		if (work == 'c')
			el_id = 'reply_' + comment_id;
		else
			el_id = 'edit_' + comment_id;
	}
	else
		el_id = wr_id;

	if (save_before != el_id)
	{
		if (save_before)
		{
			document.getElementById(save_before).style.display = 'none';
			document.getElementById(save_before).innerHTML = '';
		}

		document.getElementById(el_id).style.display = '';
		document.getElementById(el_id).innerHTML = save_html;
		// 댓글 수정
		if (work == 'cu')
		{
			$('#' + el_id+' .wr_content').val($('#save_comment_'+comment_id).val());  
		}
		$('#' + el_id + ' .wr_id').val(wr_id);
		$('#' + el_id + ' .co_id').val(comment_id);
		$('#' + el_id + ' .w').val(work); 

		if(save_before) 

		save_before = el_id;
	}
}
 
</script>
<? 
include_once("$board_skin_path/view_skin_js.php");
?>