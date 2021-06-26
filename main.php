<?php
include_once('./_common.php');
define('_MAIN_', true);
include_once(G5_PATH.'/head.php');
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/main.css">', 0);
include_once(G5_PATH."/intro.php");
?>

<div id="main_body">

<?
$main_content = get_site_content('site_main');
if($main_content) {
	echo $main_content;
} else {
?>
	<div id="no_design_main">
		<div id="main_visual_box">
			<? include(G5_PATH."/templete/txt.visual.php"); ?>
		</div>

		<div id="main_twitter_box" class="theme-box">
			<? include(G5_PATH."/templete/txt.twitter.php"); ?>
		</div>
		<div id="main_image_box" class="theme-box">
			<!--<img src="<?=G5_IMG_URL?>/temp_main_image.png" alt="임시 메인 이미지" />-->
		</div>
		<div id="main_side_box">
			<div id="main_login_box" class="theme-box">
				<? include(G5_PATH."/templete/txt.outlogin.php"); ?>
			</div>
			<div id="main_banner_box" class="theme-box">
				<p>장외규격</p>
				<p><a href="mailto:admin@outsidestandard.xyz?subject=feedback">메일로 연락하기</a></p>
				<!--<img src="<?=G5_URL?>/adm/img/logo.png" alt="임시 메인 배너이미지" />
				<p>AVOCADO EDITION</p>
				<p><a href="http://bytheallspark.cafe24.com/" target="_blank">http://bytheallspark.cafe24.com/</a></p>-->
			</div>
		</div>
		<!--<div id="main_copyright_box" class="theme-box txt-center">
			COPYRIGHT since 2021 &copy; 장외규격
		</div> -->
	</div>
<?php } ?>
</div>

<script>
$(function() {
	window.onload = function() {
		$('#body').css('opacity', 1);
	};
});
</script>

<?
include_once(G5_PATH.'/tail.php');
?>
