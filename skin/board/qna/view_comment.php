<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
 

$list = array();

$is_comment_write = false;
if ($member['mb_level'] >= $board['bo_comment_level'])
	$is_comment_write = true;

// 코멘트 출력
$sql = " select * from {$write_table} where wr_parent = '{$wr_id}' and wr_is_comment = 1 order by wr_comment desc, wr_comment_reply ";
$result = sql_query($sql);
for ($i=0; $c_row=sql_fetch_array($result); $i++)
{
	$list[$i] = $c_row;

	//$list[$i]['name'] = get_sideview($c_row['mb_id'], cut_str($c_row['wr_name'], 20, ''), $c_row['wr_email'], $c_row['wr_homepage']);

	$tmp_name = get_text(cut_str($c_row['wr_name'], $config['cf_cut_name'])); // 설정된 자리수 만큼만 이름 출력
	if ($board['bo_use_sideview'])
		$list[$i]['name'] = get_sideview($c_row['mb_id'], $tmp_name, $c_row['wr_email'], $c_row['wr_homepage']);
	else
		$list[$i]['name'] = '<span class="'.($c_row['mb_id']?'member':'guest').'">'.$tmp_name.'</span>';



	// 공백없이 연속 입력한 문자 자르기 (way 보드 참고. way.co.kr)
	//$list[$i]['content'] = eregi_replace("[^ \n<>]{130}", "\\0\n", $c_row['wr_content']);

	$list[$i]['content'] = $list[$i]['content1']= '비밀글 입니다.';
	if (!strstr($c_row['wr_option'], 'secret') ||
		$is_admin ||
		($write['mb_id']==$member['mb_id'] && $member['mb_id']) ||
		($c_row['mb_id']==$member['mb_id'] && $member['mb_id'])) {
		$list[$i]['content1'] = $c_row['wr_content'];
		$list[$i]['content'] = conv_content($c_row['wr_content'], 0, 'wr_content');
		$list[$i]['content'] = search_font($stx, $list[$i]['content']);
	} else {
		$ss_name = 'ss_secret_comment_'.$bo_table.'_'.$list[$i]['wr_id'];

		if(!get_session($ss_name))
			$list[$i]['content'] = '<a href="./password.php?w=sc&amp;bo_table='.$bo_table.'&amp;wr_id='.$list[$i]['wr_id'].$qstr.'" class="s_cmt">댓글내용 확인</a>';
		else {
			$list[$i]['content'] = conv_content($c_row['wr_content'], 0, 'wr_content');
			$list[$i]['content'] = search_font($stx, $list[$i]['content']);
		}
	}

	$list[$i]['datetime'] = substr($c_row['wr_datetime'],2,14);

	// 관리자가 아니라면 중간 IP 주소를 감춘후 보여줍니다.
	$list[$i]['ip'] = $c_row['wr_ip'];
	if (!$is_admin)
		$list[$i]['ip'] = preg_replace("/([0-9]+).([0-9]+).([0-9]+).([0-9]+)/", G5_IP_DISPLAY, $c_row['wr_ip']);

	$list[$i]['is_reply'] = false;
	$list[$i]['is_edit'] = false;
	$list[$i]['is_del']  = false;
	if ($is_comment_write || $is_admin)
	{
		$token = '';

		if ($member['mb_id'])
		{
			if ($c_row['mb_id'] == $member['mb_id'] || $is_admin)
			{
				set_session('ss_delete_comment_'.$c_row['wr_id'].'_token', $token = uniqid(time()));
				$list[$i]['del_link']  = './delete_comment.php?bo_table='.$bo_table.'&amp;comment_id='.$c_row['wr_id'].'&amp;token='.$token.'&amp;page='.$page.$qstr;
				$list[$i]['is_edit']   = true;
				$list[$i]['is_del']    = true;
			}
		}
		else
		{
			if (!$c_row['mb_id']) {
				$list[$i]['del_link'] = './password.php?w=x&amp;bo_table='.$bo_table.'&amp;comment_id='.$c_row['wr_id'].'&amp;page='.$page.$qstr;
				$list[$i]['is_del']   = true;
			}
		}

		if (strlen($c_row['wr_comment_reply']) < 5)
			$list[$i]['is_reply'] = true;
	}

	// 05.05.22
	// 답변있는 코멘트는 수정, 삭제 불가
	if ($i > 0 && !$is_admin)
	{
		if ($c_row['wr_comment_reply'])
		{
			$tmp_comment_reply = substr($c_row['wr_comment_reply'], 0, strlen($c_row['wr_comment_reply']) - 1);
			if ($tmp_comment_reply == $list[$i-1]['wr_comment_reply'])
			{
				$list[$i-1]['is_edit'] = false;
				$list[$i-1]['is_del'] = false;
			}
		}
	}
}

//  코멘트수 제한 설정값
if ($is_admin)
{
	$comment_min = $comment_max = 0;
}
else
{
	$comment_min = (int)$board['bo_comment_min'];
	$comment_max = (int)$board['bo_comment_max'];
}

include($board_skin_path.'/view_comment.skin.php');

?>