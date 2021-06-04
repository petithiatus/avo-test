<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$banner_skin_url.'/style.css">', 0);

if (!empty($banner)) {
?>
<div class="ban-basic flexslider" data-start="<?=$start?>" data-effect="<?=$effect?>" data-speed="<?=$speed?>" data-mode="<?=$mode?>" data-control="<?=$control?>" data-animationspeed="<?=$animationspeed?>">
	<ul class="slides">
	<?php for ($i=0; $i<count($banner); $i++) {
		$bn = $banner[$i];

		$bimg_pc = $bn['bn_img'];
		$bimg_m = $bn['bn_m_img'];

		$banner_image = "<img src='".$bimg_pc."' alt='".$bn['bn_alt']."' ";
		if($bimg_m) {
			$banner_image .= "class='only-pc' /><img src='".$bimg_m."' alt='".$bn['bn_alt']."' class='not-pc'";
		}
		$banner_image .= " />";

		// 주소
		$is_link = true;
		if($bn['bn_url'] == 'http://' || $bn['bn_url'] == 'https://' || $bn['bn_url'] == '#') {
			$is_link = false;
		}

		// 새창 띄우기인지
		$bn_new_win = '';
		
		if($bn['bn_new_win'] == '1') {
			$bn_new_win = ' target="_blank"';
		}
		if($bn['bn_new_win'] == '2') {
			$bn_new_win = ' onclick=\'window.open("'.$bn['bn_url'].'", "popup", "width=1020, height=800");\'';
			$bn['bn_url'] = '#';
		}
	?>
		<li>
		<?php
			if($is_link) { echo "<a href='".$bn['bn_url']."' {$bn_new_win}>"; }
			echo $banner_image;
			if($is_link) { echo "</a>"; }
		?>
		</li>
	<?php }  ?>
	</ul>
</div>
<? } ?>