<?php
include_once('./_common.php');
$ch = sql_fetch("select * from {$g5['character_table']} where ch_id=".$ch_id);
if(!$ch['ch_id']) {
	alert("캐릭터 정보가 존재하지 않습니다.");
}

$g5['title'] = $ch['ch_name']." 옷장";

include_once('./_head.sub.php');
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/style.closet.css">', 0);

$cl = array();
$cl_result = sql_query("select * from {$g5['closthes_table']} where ch_id = '{$ch_id}' order by cl_type desc, cl_id asc");
for($i=0; $row=sql_fetch_array($cl_result); $i++) { 
	$cl[$i] = $row;
}

?>

<div id="closet_page">
	<a href="#" id="open_header">열기</a>
	<div class="closet-list">
		<div class="inner">
			<ul id="submenu">
		<? for($i=0; $i < count($cl); $i++) { ?>
				<li>
					<a href="#<?=$i?>" data-index="<?=$i?>">
						<span><?=$cl[$i]['cl_subject']?></span>
					</a>
				</li>
		<? } ?>
			</ul>
		</div>
	</div>
	<a href="#" id="close_header">닫기</a>


	<div id="closet_viewer">
		<div class="flexslider">
			<ul class="slides">
	<? for($i=0; $i < count($cl); $i++) { ?>
				<li>
					<span>
						<em><a href="<?=$cl[$i]['cl_path']?>" onclick="window.open(this.href, 'big_viewer', 'width=500 height=800 menubar=no status=no toolbar=no location=no scrollbars=yes resizable=yes'); return false;"><img src="<?=$cl[$i]['cl_path']?>" /></a></em>
					</span>
				</li>
	<? } ?>
			</ul>
		</div>
	</div>
</div>

<script src="<?php echo G5_JS_URL ?>/jquery.flexslider.js"></script>
<script>
$(function() {
	$(window).load(function() {
		$('.flexslider').flexslider({
			animation: "slide",
			pausePlay: true,
			slideshowSpeed: 5000,
			start: function() {
				$('#closet_viewer').css('opacity', 1);
			}
		});
	});

	var client_height  = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
	$('.flexslider .slides img').css('max-height', client_height + "px");

	window.onresize= function() { 
		var client_height  = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
		$('.flexslider .slides img').css('max-height', client_height + "px");
	};

	$('#submenu a').on('click', function() {
		var index = $(this).data('index');
		$('.flexslider').flexslider(index);
		$('#submenu a').removeClass('on');
		$(this).addClass('on');
		return false;
	});

	$('#open_header').on('click', function() {
		$('body').addClass('sub-on');
		return false;
	});
	$('#close_header').on('click', function() {
		$('body').removeClass('sub-on');
		return false;
	});
});
</script>

<?php
include_once('./_tail.sub.php');
?>
