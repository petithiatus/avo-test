<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$connect_skin_url.'/style.css">', 0);
?>

<div class="cc-title"><p><h2>현재 접속자 목록</h2></p>
  <p>최근 5분간 접속한 방문자가 표시됩니다.</p>
</div>

<!-- 현재접속자 목록 시작 { -->
<div class="tbl_head01 tbl_wrap">
    <table id="current_connect_tbl" class="theme-list">
		<colgroup>
			<col style="width: 80px;" />
			<col style="width: 120px;" />
			<col />
		</colgroup>
		<thead>
			<tr>
				<th scope="col">번호</th>
				<th scope="col">이름</th>
				<th scope="col">위치</th>
			</tr>
			</thead>
			<tbody>
			<?php
			for ($i=0; $i<count($list); $i++) {
				//$location = conv_content($list[$i]['lo_location'], 0);
				$location = $list[$i]['lo_location'];
				// 최고관리자에게만 허용
				// 이 조건문은 가능한 변경하지 마십시오.
				if ($list[$i]['lo_url'] && $is_admin == 'super') $display_location = "<a href=\"".$list[$i]['lo_url']."\">".$location."</a>";
				else $display_location = $location;
			?>
				<tr>
					<td class="td_num txt-center"><?php echo $list[$i]['num'] ?></td>
					<td class="td_name"><?php echo $list[$i]['name'] ?></td>
					<td><?php echo $display_location ?></td>
				</tr>
			<?php
			}
			if ($i == 0)
				echo "<tr><td colspan=\"3\" class=\"empty_table\">현재 접속자가 없습니다.</td></tr>";
			?>
		</tbody>
    </table>
</div>
<!-- } 현재접속자 목록 끝 -->
