<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">', 0);
?>

<div class="rolling-latest">
	<div class="content">
		<ul>
		<?php for ($i=0; $i<count($list); $i++) {  ?>
			<li> 
				<?=$list[$i][wr_content]?> 
			</li>
		<?php }  ?>
		<?php if (count($list) == 0) { //게시물이 없을 때  ?>
			<li>Comming Soon...</li>
		<?php }  ?>
		</ul>
	</div>
</div>