<? if($config['cf_twitter']) { ?>
<div class="twitter">
	<a class="twitter-timeline" href="https://twitter.com/<?=$config['cf_twitter']?>">알림용 트위터</a><script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
</div>
<? } ?>

<script>
$(function() {
	$(window).load(function() {
		setTimeout(load_twitter, 1000);
	});
	function load_twitter() {
		var text_Data1 = $('#twitter-widget-0').contents().find('body').clone();
		 if(text_Data1.text() != '') {
			text_Data1.find('a').attr('target', '_blank');
			$('#twitter-widget-0').remove();
			$('.twitter').empty().append(text_Data1.html()).css('opacity', 1);;
		 } else {
			 setTimeout(load_twitter, 100);
		 }
	};
});
</script>
