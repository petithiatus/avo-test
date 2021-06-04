<?php
$sub_menu = '100320';
include_once('./_common.php');

check_demo();

if ($w == 'd')
	auth_check($auth[$sub_menu], "d");
else
	auth_check($auth[$sub_menu], "w");


$banner_path = G5_DATA_PATH."/banner";
$banner_url = G5_DATA_URL."/banner";

@mkdir($banner_path, G5_DIR_PERMISSION);
@chmod($banner_path, G5_DIR_PERMISSION);



if ($w=="" || $w=="u") {
	if ($w=="") { 
		$sql = " insert into {$g5['banner_table']}
					set bn_img        = '$bn_img',
						bn_m_img      = '$bn_m_img',
						bn_alt        = '$bn_alt',
						bn_url        = '$bn_url',
						bn_new_win    = '$bn_new_win',
						bn_begin_time = '$bn_begin_time',
						bn_end_time   = '$bn_end_time',
						bn_order      = '$bn_order'";
		sql_query($sql);
		$bn_id = sql_insert_id();

	} else if ($w=="u") {

		$sql = " update {$g5['banner_table']}
					set bn_img        = '$bn_img',
						bn_m_img      = '$bn_m_img',
						bn_alt        = '$bn_alt',
						bn_url        = '$bn_url',
						bn_new_win    = '$bn_new_win',
						bn_begin_time = '$bn_begin_time',
						bn_end_time   = '$bn_end_time',
						bn_order      = '$bn_order'
				  where bn_id = '$bn_id' ";
		sql_query($sql);

	}

	// 이미지 등록 시, 이미지를 업로드한 뒤 - 해당 이미지 경로를 삽입
	if ($_FILES['bn_img_file']['name']) {
		
		// 확장자 따기
		$exp = explode(".", $_FILES['bn_img_file']['name']);
		$exp = $exp[count($exp)-1];

		$banner_name = "banner_".$bn_id.".".$exp;
		upload_file($_FILES['bn_img_file']['tmp_name'], $banner_name, $banner_path);
		$bn_img = $banner_url."/".$banner_name;

		$sql = " update {$g5['banner_table']}
				set bn_img	= '$bn_img'
			  where bn_id = '{$bn_id}' ";
		sql_query($sql);
	}

	// 이미지 등록 시, 이미지를 업로드한 뒤 - 해당 이미지 경로를 삽입
	if ($_FILES['bn_m_img_file']['name']) {
		// 확장자 따기
		$exp = explode(".", $_FILES['bn_m_img_file']['name']);
		$exp = $exp[count($exp)-1];

		$banner_name = "banner_".$bn_id."_m.".$exp;
		upload_file($_FILES['bn_m_img_file']['tmp_name'], $banner_name, $banner_path);
		$bn_img = $banner_url."/".$banner_name;

		$sql = " update {$g5['banner_table']}
				set bn_m_img	= '$bn_img'
			  where bn_id = '{$bn_id}' ";
		sql_query($sql);
	}

} else if ($w=="d") {

	@unlink($banner_path."/banner_".$bn_id);
	@unlink($banner_path."/banner_".$bn_id."_m");

	$sql = " delete from {$g5['banner_table']} where bn_id = $bn_id ";
	$result = sql_query($sql);
}

goto_url("./banner_list.php");

?>
