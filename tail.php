<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
		<hr class="padding" />
	</div>
</section>

<a href="#header" id="goto_top" class="scroll-fix">
	<img src="<?=G5_IMG_URL?>/btn_top.png" />
</a>
<script>
$('#goto_top').click(function () {
	$('body,html').animate({
		scrollTop: 0
	}, 800);
	return false;
});
</script>

<div id="main_copyright_box" class="theme-box txt-center">
	COPYRIGHT since 2021 &copy; 장외규격
</div>

<script src="<?php echo G5_JS_URL ?>/jquery.flexslider.js"></script>
<script src="<?php echo G5_JS_URL ?>/_custom.js"></script>

<?
if($is_member) {
	include_once(G5_PATH."/ajax/memo_call.php");
	include_once(G5_PATH."/ajax/board_call.php");
}
include_once(G5_PATH."/ajax/inventory_popup.php");
?>

<?php
include_once(G5_PATH."/tail.sub.php");
?>
