<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$customer_sql = '';

// ******************** 호출 관련, 호출 시 알람테이블에 기재한다. 확인은 Y / N
// 멤버 닉네임 서칭
// -- 치환 문자로 사용될 문자가 본문에 사용 시 지워준다. (오류 방지)
$str = str_replace("||", "", $wr_content);
$str = str_replace("&&", "", $str);

// -- 괄호를 치환한다.
$str = str_replace("[[", "||&&", $str);
$str = str_replace("]]", "&&", $str);

// explode 로 해당 문자만 추출 할 수 있도록 작업한다.
$str = explode("||", $str);
// -- 추출한 배열을 토대로 정규식으로 닉네임을 추출한다.

$call_pattern = "/&&(.*)&&/";
$mb_nick_array = array();

for($i=0; $i < count($str); $i++) { 
	preg_match_all($call_pattern, $str[$i], $matches);
	if($matches[1]) {
		$mb_nick_array[] = $matches[1][0];
	}
}

// 배열 중복값 처리
$mb_nick_array = array_unique($mb_nick_array);


if(count($mb_nick_array) > 0) { 
	// -- 괄호를 치환한다.
	$memo = str_replace("[[", "", $wr_content);
	$memo = str_replace("]]", "", $memo);

	for($i=0; $i < count($mb_nick_array); $i++) { 
		// 회원 정보 있는지 여부 확인
		$memo_search = sql_fetch("select mb_id, mb_name from {$g5['member_table']} where mb_nick = '{$mb_nick_array[$i]}' or  mb_name = '{$mb_nick_array[$i]}'");
		if($memo_search['mb_id']) { 
			// 회원정보가 있을 시, 알람테이블에 저장한다.
			// 저장하기 전에 동일한 정보가 있는지 확인한다.
			// 저장정보 : wr_id / wr_num / bo_table/ mb_id / mb_name / re_mb_id / re_mb_name / ch_side / memo / bc_datetime

			$bc_sql_common = "
				wr_id = '{$temp_wr_id}',
				wr_num = '{$wr_num}',
				bo_table = '{$bo_table}',
				mb_id = '{$member[mb_id]}',
				mb_name = '{$member[mb_nick]}',
				re_mb_id = '{$memo_search['mb_id']}',
				re_mb_name = '{$memo_search['mb_name']}',
				ch_side = '{$character[ch_side]}',
				memo = '{$wr_subject}',
				bc_datetime = '".G5_TIME_YMDHIS."'
			";

			
			// 동일 정보 있는지 확인 - wr_id/ bo_table / re_mb_id 로 판별
			$bc = sql_fetch(" select bc_id from {$g5['call_table']} where wr_id= '{$temp_wr_id}' and bo_table= '{$bo_table}' and re_mb_id = '{$memo_search[mb_id]}' and mb_id = '{$member[mb_id]}' ");
			
			if($bc['bc_id']) { 
				// 정보가 있을 경우
				$sql = " update {$g5['call_table']} set {$bc_sql_common} where bc_id = '{$bc[bc_id]}' ";
				sql_query($sql);
			} else { 
				// 정보가 없을 경우
				$sql = " insert into {$g5['call_table']} set {$bc_sql_common} ";
				sql_query($sql);

				// 회원 테이블에서 알람 업데이트를 해준다.
				// 실시간 호출 알림 기능
				$log_link = G5_BBS_URL."/board.php?bo_table=".$bo_table."&log=".($wr_num * -1);
				$sql = " update {$g5['member_table']} 
							set mb_board_call = '".$member['mb_nick']."',
								mb_board_link = '{$log_link}'
						where mb_id = '".$memo_search['mb_id']."' ";
				sql_query($sql);
			}
		} else { 
			// 회원정보가 없을 시, content 에 해당 닉네임을 블러 처리 하고
			// content 를 업데이트 한다.
			$wr_content = str_replace("[[".$mb_nick_array[$i]."]]", "[[???]]", $wr_content);
			$customer_sql .= " , wr_content = '{$wr_content}' ";
		}
	}
}
// ******************** 호출 관련, 호출 시 해당 멤버에게 쪽지 보내기 기능 종료
?>