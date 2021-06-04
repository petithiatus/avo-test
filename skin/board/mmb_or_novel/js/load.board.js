// 레이아웃 셋팅 - 반응형
fn_layout_setting();

// 화면 사이즈가 변경 될 시, 레이아웃 셋팅 실행
window.onresize = function() { fn_layout_setting(); };

// 즐겨찾기 추가 - Ajax
$('a[data-function="favorite"]').on('click', function() {
	var formData = new FormData();
	var idx = $(this).data('idx');
	var obj = $(this);
	formData.append("wr_id", idx);
	formData.append("bo_table", g5_bo_table);
	formData.append("mb_id", avo_mb_id);

	$.ajax({
		url:avo_board_skin_url + '/ajax/add_favorite.php'
		, data: formData
		, processData: false
		, contentType: false
		, type: 'POST'
		, success: function(data){
			if(data == 'on') { 
				obj.removeClass('on');
				obj.addClass(data);
			}else if(data == 'off') { 
				obj.removeClass('on');
			}
		}
	});

	return false;
});


$('.img a.ui-open-log').on('click', function() {

	var obj = $(this).closest('.pic-data').children('div');
	var state = $(obj).hasClass('on');
	var original_height = $(obj).find('img').height();
	var setting_height = 470;

	if(state){ 
		//닫기
		$(obj).stop().animate({height: setting_height + "px"}, 1000);
		$(obj).removeClass('on');
		$(this).text("OPEN");
	} else {
		// 열기
		$(obj).stop().animate({height: original_height + "px"}, 1000);
		$(obj).addClass('on');
		$(this).text("CLOSE");
	}

	return false;
});

$('.story a.ui-open-log').on('click', function() {
	var obj = $(this).closest('.pic-data');
	var state = $(obj).hasClass('on');
	if(state){ 
		//닫기
		$(obj).removeClass('on');
		$(obj).find('.inner').scrollTop(0);
		$('body').removeClass('log-slide-open');
		$(this).text("OPEN");
	} else {
		// 열기
		$(obj).addClass('on');
		$(obj).find('.inner').scrollTop(0);
		$('body').addClass('log-slide-open');
		$(this).text("CLOSE");
	}

	return false;
});

$('a.ui-remove-blind').on('click', function() {
	$(this).closest('.pic-data').removeClass('ui-blind');
	$(this).fadeOut();
	return false;
});

$('.send_memo').on('click', function() {
	var target = $(this).attr('href');
	window.open(target, 'memo', "width=500, height=300");
	return false;
});

$('.btn-search-guide').on('click', function() {
	$('#searc_keyword').toggleClass('on');
	return false;
});

$(window).ready(function() {
	$('#load_log_board').css('opacity', '1.0');
});

function fn_layout_setting() {
	$('#log_list > .item').each(function(){
		var comment_width = $('#log_list .item-inner').width() - $(this).find('.ui-pic').data('width') + 10;
		var pic_width = $(this).find('.ui-pic').data('width');

		if(comment_width > 320) {
			$(this).removeClass('both');
			$(this).find('.ui-comment').css('width', comment_width - 20 + "px");
			$(this).find('.ui-pic').css('width', pic_width + "px");
		} else {
			$(this).addClass('both');
			$(this).find('.ui-comment').css('width', "auto");
			$(this).find('.ui-pic').css('width', "100%");
		}
	});
};

$('.new_win').on('click', function() {
	var target = $(this).attr('href');
	window.open(target, 'emoticon', "width=400, height=600");
	return false;
});