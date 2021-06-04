<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>

<section id="bo_w">


	<!-- 게시물 작성/수정 시작 { -->
	<form name="fwrite" id="fwrite" action="<? echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
	<input type="hidden" name="uid" value="<? echo get_uniqid(); ?>">
	<input type="hidden" name="w" value="<? echo $w ?>">
	<input type="hidden" name="bo_table" value="<? echo $bo_table ?>">
	<input type="hidden" name="wr_id" value="<? echo $wr_id ?>">
	<input type="hidden" name="sca" value="<? echo $sca ?>">
	<input type="hidden" name="sfl" value="<? echo $sfl ?>">
	<input type="hidden" name="stx" value="<? echo $stx ?>">
	<input type="hidden" name="spt" value="<? echo $spt ?>">
	<input type="hidden" name="sst" value="<? echo $sst ?>">
	<input type="hidden" name="sod" value="<? echo $sod ?>">
	<input type="hidden" name="page" value="<? echo $page ?>">
	<?
	$option = '';
	$option_hidden = '';
	if ($is_notice || $is_html || $is_secret || $is_mail) {
		$option = '';
		if ($is_notice) {
			$option .= "\n".'<input type="checkbox" id="notice" name="notice" value="1" '.$notice_checked.'>'."\n".'<label for="notice">공지</label>';
		}

		if ($is_html) {
			if ($is_dhtml_editor) {
				$option_hidden .= '<input type="hidden" value="html1" name="html">';
			} else {
				$option .= "\n".'<input type="checkbox" id="html" name="html" onclick="html_auto_br(this);" value="'.$html_value.'" '.$html_checked.'>'."\n".'<label for="html">html</label>';
			}
		}

		if ($is_secret) {
			if ($is_admin || $is_secret==1) {
				$option .= "\n".'<input type="checkbox" id="secret" name="secret" value="secret" '.$secret_checked.'>'."\n".'<label for="secret">비밀글</label>';
			} else {
				$option_hidden .= '<input type="hidden" name="secret" value="secret">';
			}
		}

		if ($is_mail) {
			$option .= "\n".'<input type="checkbox" id="mail" name="mail" value="mail" '.$recv_email_checked.'>'."\n".'<label for="mail">답변메일받기</label>';
		}
	}

	echo $option_hidden;
	?>

	<div class="board-write">
		<? if ($is_category) { ?>
		<nav id="write_category">
			<select name="ca_name" id="ca_name" required class="required" >
				<option value="">선택하세요</option>
				<? echo $category_option ?>
			</select>
			&nbsp;&nbsp;
			<? echo $option ?>
		</nav>
		<? } ?>

		<div class="wr_subject" style="margin-top: 20px;">
			<input type="text" name="wr_subject" value="<? echo $subject ?>" id="wr_subject" required class="frm_input required" size="50" maxlength="255" placeholder="제목">
		</div>

		<? if($board['bo_1']) { ?>
		<div class="write-notice">
			<?=$board['bo_1']?>
		</div>
		<? } ?>

		<br />

		<div class="wr_content">
			<? if($write_min || $write_max) { ?>
			<!-- 최소/최대 글자 수 사용 시 -->
			<p id="char_count_desc">이 게시판은 최소 <strong><? echo $write_min; ?></strong>글자 이상, 최대 <strong><? echo $write_max; ?></strong>글자 이하까지 글을 쓰실 수 있습니다.</p>
			<? } ?>
			<? echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
			<? if($write_min || $write_max) { ?>
			<!-- 최소/최대 글자 수 사용 시 -->
			<span class="ui-btn help check" onclick="checkbtn()">글자 수<span id="char_count"></span></span>
			<? } ?>
		</div>
		<span class="ui-btn help check" onclick="checkbtn()">글자 수<span id="char_count"></span></span>
		<div class="files">
			<input type="file" name="bf_file[]" title="파일첨부 <? echo $i+1 ?> : 용량 <? echo $upload_max_filesize ?> 이하만 업로드 가능" class="frm_file frm_input">
			<? if ($is_file_content) { ?>
			<input type="text" name="bf_content[]" value="<? echo ($w == 'u') ? $file[$i]['bf_content'] : ''; ?>" title="파일 설명을 입력해주세요." class="frm_file frm_input" size="50">
			<? } ?>
			<? if($w == 'u' && $file[$i]['file']) { ?>
			<input type="checkbox" id="bf_file_del<? echo $i ?>" name="bf_file_del[<? echo $i;  ?>]" value="1"> <label for="bf_file_del<? echo $i ?>"><? echo $file[$i]['source'].'('.$file[$i]['size'].')';  ?> 파일 삭제</label>
			<? } ?>
		</div>

	</div>

	<hr class="padding" />
	<div class="btn_confirm txt-center">
		<input type="submit" value="작성완료" id="btn_submit" accesskey="s" class="btn_submit ui-btn point">
		<a href="./board.php?bo_table=<? echo $bo_table ?>" class="btn_cancel ui-btn">취소</a>
	</div>
	</form>

	<script>
	function checkbtn() {
		$(".check").click(function() {
			var txtlength = $("#wr_content").val().length;
			$("#char_count").empty();
			$("#char_count").append(": ", txtlength, " 자");
		});
	}
	<? if($write_min || $write_max) { ?>
	// 글자수 제한
	var char_min = parseInt(<? echo $write_min; ?>); // 최소
	var char_max = parseInt(<? echo $write_max; ?>); // 최대
	check_byte("wr_content", "char_count");

	$(function() {
		$("#wr_content").on("keyup", function() {
			check_byte("wr_content", "char_count");
		});
	});
	<? } ?>
	function html_auto_br(obj)
	{
		if (obj.checked) {
			result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
			if (result)
				obj.value = "html2";
			else
				obj.value = "html1";
		}
		else
			obj.value = "";
	}

	function fwrite_submit(f)
	{
		<? echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

		var subject = "";
		var content = "";
		$.ajax({
			url: g5_bbs_url+"/ajax.filter.php",
			type: "POST",
			data: {
				"subject": f.wr_subject.value,
				"content": f.wr_content.value
			},
			dataType: "json",
			async: false,
			cache: false,
			success: function(data, textStatus) {
				subject = data.subject;
				content = data.content;
			}
		});

		if (subject) {
			alert("제목에 금지단어('"+subject+"')가 포함되어있습니다");
			f.wr_subject.focus();
			return false;
		}

		if (content) {
			alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
			if (typeof(ed_wr_content) != "undefined")
				ed_wr_content.returnFalse();
			else
				f.wr_content.focus();
			return false;
		}

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

		<? echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함  ?>

		document.getElementById("btn_submit").disabled = "disabled";

		return true;
	}
	</script>
</section>
<!-- } 게시물 작성/수정 끝 -->