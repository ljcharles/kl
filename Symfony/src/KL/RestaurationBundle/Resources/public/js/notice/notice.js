function flash_notice(message, type) {
	notice = $('<div class="alert-' + type + '"></div>').
	attr('id', 'flash_notice').
	html('<i class="fa fa-bell"></i> ' + message);
	$('body').append(notice.hide());
	notice.fadeIn();
	function remove_notice() { notice.fadeOut(function() { notice.remove() }); }
	setTimeout(remove_notice, 3000);
}
