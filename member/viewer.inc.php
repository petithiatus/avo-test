<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/style.profile.css">', 0);

/** 스탯 이용 시 스탯 설정값 가져오기 **/
if($article['ad_use_status']) { 
	$status = array();
	$st_result = sql_query("select * from {$g5['status_config_table']} order by st_order asc");
	for($i = 0; $row = sql_fetch_array($st_result); $i++) {
		$status[$i] = $row;
	}
}
/** 추가 항목 설정값 가져오기 **/
$ch_ar = array();
$str_secret = ' where (1) ';

if($member['mb_id'] == $mb['mb_id']) {
	$str_secret .= " and ar_secret != 'H' ";
} else {
	$str_secret .= " and ar_secret = '' ";
}

$ar_result = sql_query("select * from {$g5['article_table']} {$str_secret} order by ar_order asc");
for($i = 0; $row = sql_fetch_array($ar_result); $i++) {
	$ch_ar[$i] = $row;
}


/* --------------------------------------------------------------
	프로필 양식에서 추가한 캐릭터의 데이터를 임의로 뿌리고 싶을 때
	$ch['고유코드'] 로 해당 데이터를 가져올 수 있습니다.

	--
	
	스탯 설정에서 추가한 캐릭터의 데이터를 임의로 뿌리고 싶을 때
	$변수 = get_status_by_name($ch['ch_id'], 스탯명);
	* $변수['has']	: 현재 캐릭터가 가지고 있는 전체값 (ex. 캐릭터의 최대 HP 값)
	* $변수['drop']	: 현재 캐릭터의 스탯 차감 수치 (ex. 캐릭터의 부상 수치, HP 감소)
	* $변수['now']	: 현재 캐릭터에게 적용되어 있는 값 (ex. 캐릭터의 현재 HP 값 (캐릭터의 원래 HP값 - 부상))
	* $변수['max']	: 입력할 수 있는 최대값
	* $변수['min']	: 필수 최소값
	--
	
	자동으로 출력 되는게 아닌 임의로 레이아웃을 수정하고 싶을 땐
	위쪽의 설정값 가져 오는 부분을 지우셔도 무방합니다.
	
	--


------------------------------------------------------------------ */

// --- 캐릭터 별 추가 항목 값 가져오기
$av_result = sql_query("select * from {$g5['value_table']} where ch_id = '{$ch['ch_id']}'");
for($i = 0; $row = sql_fetch_array($av_result); $i++) {
	$ch[$row['ar_code']] = $row['av_value'];
}

// ------- 캐릭터 의상 정보 가져오기
$temp_cl = sql_fetch("select * from {$g5['closthes_table']} where ch_id = '{$ch_id}' and cl_use = '1'");
if($temp_cl['cl_path']) { 
	$ch['ch_body'] = $temp_cl['cl_path'];
}
?>

<div id="character_profile">

	<nav id="profile_menu" >
	<? if($article['ad_use_closet'] && $article['ad_use_body']) { ?>
		<a href="<?=G5_URL?>/member/closet.php?ch_id=<?=$ch['ch_id']?>" onclick="window.open(this.href, 'big_viewer', 'width=800 height=800 menubar=no status=no toolbar=no location=no scrollbars=yes resizable=yes'); return false;" class="ui-btn ico point camera circle big">
				옷장보기
		</a>
	<? } ?>
	<? if($article['ad_use_exp']) { ?>
		<a href="<?=G5_URL?>/member/exp.php?ch_id=<?=$ch['ch_id']?>" onclick="popup_window(this.href, 'exp', 'width=400, height=500'); return false;" class="ui-btn ico point exp circle big">
				경험치 내역 보기
		</a>
	<? } ?>
	</nav>


<!-- 캐릭터 비쥬얼 (이미지) 출력 영역 -->

	<? if($article['ad_use_body'] && $ch['ch_body']) { ?>
	<div class="visual-area">
		<div id="character_body">
			<img src="<?=$ch['ch_body']?>" alt="캐릭터 전신" />
		</div>
	<? } ?>

	<? if($article['ad_use_head'] && $ch['ch_head']) { ?>
		<div id="character_head" class="open">
			<div class="contents">
				<img src="<?=$ch['ch_head']?>" alt="캐릭터 흉상" />
			</div>

			<a href="#character_head" class="ui-btn point full small toggle-head-pannel">
				흉상 <span class="on">접기</span><span class="off">펼치기</span>
			</a>
			<script>
				$('.toggle-head-pannel').on('click', function() {
					$('#character_head').toggleClass('open');
					return false;
				});
			</script>
		</div>
	<? } ?>

	<? if($article['ad_use_body'] && $ch['ch_body']) { ?>
	</div>
	<? } ?>

<!-- //캐릭터 비쥬얼 (이미지) 출력 영역 -->

	<hr class="padding" />

<!-- 캐릭터 기본정보 출력 영역 -->
	<table class="theme-form">
		<colgroup>
			<col style="width: 110px;">
			<col>
		</colgroup>
		<tbody>

		<? if($article['ad_use_name']) { ?>
			<tr>
				<th scope="row"><?=$article['ad_text_name']?></th>
				<td>
					<?php echo $ch['ch_name'] ?>
				</td>
			</tr>
		<? } ?>
		<? if($config['cf_side_title']) {
			// 소속 정보 출력
		?>
			<tr>
				<th><?=$config['cf_side_title']?></th>
				<td>
					<?=get_side_name($ch['ch_side'])?>
				</td>
			</tr>
		<? } ?>
		<? if($config['cf_class_title']) {
			// 종족 정보 출력
		?>
			<tr>
				<th><?=$config['cf_class_title']?></th>
				<td>
					<?=get_class_name($ch['ch_class'])?>
				</td>
			</tr>
		<? } ?>
		<? if($article['ad_use_rank']) { 
			// 랭킹정보 출력
		?>
			<tr>
				<th scope="row"><?=$config['cf_rank_name']?></th>
				<td>
					<?php echo get_rank_name($ch['ch_rank']); ?>
				</td>
			</tr>
		<? } ?>
		<? if($article['ad_use_exp']) { 
			// 경험치 정보 출력
		?>
			<tr>
				<th scope="row"><?=$config['cf_exp_name']?></th>
				<td>
					<?=$ch['ch_exp']?>
					<?=$config['cf_exp_pice']?>
				</td>
			</tr>
		<? } ?>
		<? for($i=0; $i < count($ch_ar); $i++) { 
			// 추가 프로필 항목 출력
			$ar = $ch_ar[$i];
			$key = $ar['ar_code'];
		?>
			<tr>
				<th>
					<?=$ar['ar_name']?>
				</th>
				<?
					if($ar['ar_type'] == 'file' || $ar['ar_type'] == 'url') { 
						// 이미지 타입의 파일
				?>

					<td>
						<img src="<?=$ch[$key]?>" />
					</td>

				<? } else { ?>
					<td>
					<?
						if($ar['ar_type'] == 'textarea') 
							echo nl2br($ch[$key]);
						else 
							echo $ch[$key];

						if($ar['ar_type'] != 'textarea' && $ar['ar_type'] != 'select')
							echo $ar['ar_text'];
					?>
					</td>
				<? } ?>
			</tr>
			<? } ?>

		</tbody>
	</table>
<!-- // 캐릭터 기본정보 출력 영역 -->


<? if($article['ad_use_closet'] && $article['ad_use_body'] && $mb['mb_id'] == $member['mb_id']) { 
	// 옷장 설정
	// 옷장 사용 및 캐릭터 소유주일시에 출력
	// -- 옷장 출력형태 변경을 원할 시, mypage/character/closet.inc.php 파일을 수정해 주시길 바랍니다.
?>
	<hr class="padding" />
	<h3>CLOSET</h3>
	<div class="theme-box">
		<? include_once(G5_PATH."/mypage/character/closet.inc.php"); ?>
	</div>
<? } ?>


<? if($article['ad_use_status']) { 
	// 스탯 설정
	// -- 스탯 출력형태 변경을 원하실 시, mypage/character/status.inc.php 파일을 수정해 주시길 바랍니다.
?>
	<hr class="padding" />
	<h3>
		STATUS
		<span style="float:right;">
			<em class="txt-point" data-type="point_space"><?=get_space_status($ch['ch_id'])?></em>
			/
			<?=$ch['ch_point']?>
		</span>
	</h3>
	<div class="theme-box">
		<? include_once(G5_PATH."/mypage/character/status.inc.php"); ?>
	</div>
<? } ?>

<? if($article['ad_use_title']) { 
	// 타이틀 설정
	// -- 타이틀 출력형태 변경을 원하실 시, mypage/character/title.inc.php 파일을 수정해 주시길 바랍니다.
?>
	<hr class="padding" />
	<h3>
		TITLE
	</h3>
	<div class="theme-box">
		<? include_once(G5_PATH."/mypage/character/title.inc.php"); ?>
	</div>
<? } ?>

<? if($article['ad_use_inven']) { 
	// 인벤토리 출력
	// -- 인벤토리 출력형태 변경을 원하실 시, mypage/inventory/list.inc.php 파일을 수정해 주시길 바랍니다.
?>
	<hr class="padding" />
	<h3>
		INVENTORY
	<? if($article['ad_use_money']) { 
		// 소지금 사용시 현재 보유 중인 소지금 출력
	?>
		<span style="float:right;">
			<em class="txt-point"><?=$mb['mb_point']?></em><?=$config['cf_money_pice']?>
		</span>
	<? } ?>
	</h3>
	<div class="theme-box">
		<? include_once(G5_PATH."/mypage/inventory/list.inc.php"); ?>
	</div>
<? } ?>



<? if($ch['ch_state'] == '승인') {
	// 관계란 출력
	// 승인된 캐릭터만 출력됩니다.
	// -- 관계란 출력형태 변경을 원하실 시, mypage/character/relation_list.php 파일을 수정해 주시길 바랍니다.
?>
	<hr class="padding" />
	<h3>STORY</h3>
	<div class="relation-box">
		<? include(G5_PATH.'/mypage/character/relation_list.php'); ?>
	</div>
<? } ?>


	<div class="ui-btn point small full">
		오너 : <?=$mb['mb_name']?>
	</div>

	<hr class="padding" />
	<hr class="padding" />

</div>


