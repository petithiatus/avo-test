<?php
include_once('./_common.php');
$g5['title'] = "신청자 대기 목록";
include_once('./_head.php');
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/style.member.css">', 0);

// 추가 항목 설정 데이터
// -- 권한 설정에 따라 가져오기
// -- 관리자 권한 제외 하고 가져온다.
$ch_ar = array();
$ar_result = sql_query("select * from {$g5['article_table']} where ar_secret != 'H' order by ar_order asc");
for($i = 0; $row = sql_fetch_array($ar_result); $i++) {
	$ch_ar[$i] = $row;
}

/** 세력 정보 **/
if($config['cf_side_title']) {
	$ch_si = array();
	$side_result = sql_query("select si_id, si_name from {$g5['side_table']} where si_auth < 3  order by si_id asc");
	for($i=0; $row = sql_fetch_array($side_result); $i++) { 
		$ch_si[$i]['name'] = $row['si_name'];
		$ch_si[$i]['id'] = $row['si_id'];
	}
}

/** 종족 정보 **/
if($config['cf_class_title']) {
	$ch_cl = array();
	$class_result = sql_query("select cl_id, cl_name from {$g5['class_table']} where cl_auth < 3 order by cl_id asc");
	for($i=0; $row = sql_fetch_array($class_result); $i++) { 
		$ch_cl[$i]['name'] = $row['cl_name'];
		$ch_cl[$i]['id'] = $row['cl_id'];
	}
}

$search_arcode = false;
if($stx) {
	$check_sfl = explode("||", $sfl);
	if($check_sfl[0] == 'arcode') {
		// 추가 필드 검색
		$search_arcode = true;
		$sql_common = "from {$g5['character_table']} ch join {$g5['value_table']} cv on ch.ch_id = cv.ch_id where (ch.ch_state = '대기' OR ch.ch_state = '수정중'  OR ch.ch_state != '삭제') and cv.av_value like '%{$stx}%' and cv.ar_code = '{$check_sfl[1]}' ";
		if($s_class) { 
			$sql_search .= "and ch.ch_class = '{$s_class}'";
		}

		if($s_side) { 
			$sql_search .= "and ch.ch_side = '{$s_side}'";
		}

		$sql_order = "order by ch.ch_id desc ";
	}
}
if(!$search_arcode) { 
	$sql_common = "from {$g5['character_table']} where ch_state != '승인' and ch_state != '삭제' ";
	if($s_class) { 
		$sql_search .= "and ch_class = '{$s_class}'";
	}

	if($s_side) { 
		$sql_search .= "and ch_side = '{$s_side}'";
	}

	if($stx) { 
		if($sfl == 'mb_nick') {
			$temp_search = "";
			$connect_str = "";

			$temp = sql_query("select mb_id from {$g5['member_table']} where mb_nick like '%".$stx."%'");
			for($t = 0; $row = sql_fetch_array($temp); $t++) { 
				$temp_search .= $connect_str."mb_id = '".$row['mb_id']."'";
				$connect_str = " OR ";
			}
			
			if($temp_search) 
				$sql_search .= "and ( ".$temp_search." ) ";
			else 
				$sql_search .= "and ( mb_id = '' ) ";
		} else {
			$sql_search .= " and $sfl like '%".$stx."%' ";
		}
	}

	$sql_order = "order by ch_id desc";
}

$all_count = sql_fetch("select count(*) as cnt $sql_common");
$all_count = $all_count['cnt'];

$total_count = sql_fetch("select count(*) as cnt $sql_common $sql_search $sql_order");
$total_count = $total_count['cnt'];

$page_rows = 10;
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$total_page  = ceil($total_count / $page_rows);  // 전체 페이지 계산
$from_record = ($page - 1) * $page_rows; // 시작 열을 구함

$sql = "select * $sql_common $sql_search $sql_order limit {$from_record}, $page_rows ";
$rank_result = sql_query($sql);
$write_pages = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, './ready.php?s_class='.$s_class.'&s_side='.$s_side.'&sfl='.$sfl.'&stx='.$stx.'&page=');




$side_link = "<li class='menu-first'><a href='".G5_URL."/member/ready.php'><span>ALL({$all_count})</span></a></li>";


for($i=0; $i < count($ch_si); $i++) { 
	if($s_side == $ch_si[$i]['id']) { 
		$class = "  style='color: rgb(255, 213, 0);'  ";
	} else { 
		$class = "";
	}

	$count = sql_fetch("select count(*) as cnt ".$sql_common." and ch_side = '{$ch_si[$i]['id']}'");
	$side_link .= "
		<li>
			<a href='".G5_URL."/member/ready.php?s_class={$ch_cl[$j]['id']}&amp;s_side={$ch_si[$i]['id']}&amp;sfl=".$sfl."&amp;stx=".$stx."' $class>
				<span style='font-size:11px;font-family:\"Dotum\";letter-spacing: -1px;'>".$ch_si[$i]['name']." ".$ch_cl[$j]['name']."({$count['cnt']})</span>
			</a>";

	if(count($ch_cl) > 0) { 
		$side_link .= "<ul>";

		for($k=0; $k < count($ch_cl); $k++) { 
			if($s_class == $ch_cl[$k]['id'] && $s_side == $ch_si[$i]['id']) { 
				$class = "  style='color: rgb(255, 213, 0);'  ";
			} else { 
				$class = "";
			}

			$c_count = sql_fetch("select count(*) as cnt ".$sql_common." and ch_side = '{$ch_si[$i]['id']}' and ch_class = '{$ch_cl[$k]['id']}'");
			$side_link .= "<li>
				<a href='".G5_URL."/member/ready.php?s_class={$ch_cl[$k]['id']}&amp;s_side={$ch_si[$i]['id']}&amp;sfl=".$sfl."&amp;stx=".$stx."' $c_class>
					<span style='font-size:11px;font-family:\"Dotum\";letter-spacing: -1px;'>{$ch_cl[$k]['name']}({$c_count['cnt']})</span>
				</a>
			</li>";
		}
		
		$side_link .= "</ul>";
	}
	$side_link .= "</li>";
}


?>


<nav id="submenu" class="scroll-fix">
	<ul>
		<?=$side_link?>
	</ul>
</nav>

<div id="subpage">

	<div class="searc-sub-box" style="padding: 10px 5px;">
		<div class="ui-search-box">
			<form name='frmSearch' method='get'>
				<fieldset class="sch_category">
					<select name="sfl">
						<option value="mb_nick" <?=$sfl == 'mb_nick' ? "selected" : ""?>>오너명</option>
						<option value="ch_name" <?=$sfl == 'ch_name' ? "selected" : ""?>>캐릭명</option>
					<? if(count($ch_ar) > 0) { 
						for($i=0; $i < count($ch_ar); $i++) { 
					?>
						<option value="arcode||<?=$ch_ar[$i]['ar_code']?>" <?=$sfl == 'arcode||'.$ch_ar[$i]['ar_code'] ? "selected" : ""?>><?=$ch_ar[$i]['ar_name']?></option>
					<? } } ?>
					</select>
				</fieldset>
				<fieldset class="sch_text">
					<input type="text" name="stx" value="<?=$stx?>" />
				</fieldset>
				<fieldset class="sch_button">
					<input type="submit" value="Search" class="ui-btn"/>
				</fieldset>
			</form>
		</div>
	</div>

	<div class="ui-page txt-center">
		<?=$write_pages?>
	</div>

	<ul class="ready-member-list">
<?
	for($i=0; $row=sql_fetch_array($rank_result); $i++) {
?>
		<li>
			<div class="item theme-box">
				<div class="ui-thumb">
					<a href="./viewer.php?ch_id=<?=$row['ch_id']?>">
						<? if($row['ch_thumb']) { ?>
							<img src="<?=$row['ch_thumb']?>" />
						<? } ?>
					</a>
				</div>
				<div class="ui-profile">
					<p class="name">
						<a href="./viewer.php?ch_id=<?=$row['ch_id']?>">
							<strong>[<?=$row['ch_state']?>] <?=$row['ch_name']?></strong>
						</a>
					</p>
					<span>
						<?
							if($config['cf_side_title']) {
								echo get_side_name($row['ch_side']);
							}
							if($config['cf_class_title']) { 
								if($config['cf_side_title']) { echo " / "; }
								echo get_class_name($row['ch_class']);
							}
						?>
					</span>
					<span class="owner">
						<?=get_member_name($row['mb_id'])?>
					</span>
				</div>
			</div>
		</li>


<?
	}
	if($i == 0) { 
		echo "<li class='empty'>대기자가 없습니다.</li>";
	}
	unset($rank_result);
	unset($row);
?>
	</ul>

	<? if($is_add_character && ($i == 0 || $i < $config['cf_character_count'])) { ?>
	<div class="txt-center">
		<a href="<?=G5_URL?>/mypage/character/character_form.php" class="ui-btn point">신규 캐릭터 등록</a>
	</div><?}?>

	<div class="ui-page">
		<?=$write_pages?>
	</div>

</div>

<?php
include_once('./_tail.php');
?>

