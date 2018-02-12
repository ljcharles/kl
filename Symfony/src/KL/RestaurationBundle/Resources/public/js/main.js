$(document).ready(function () {
  $('#chevron').click(function() {
    $('html, body').animate({
      scrollTop: $("#about").offset().top
    }, 1000)
  });

  // get current URL path and assign 'active' class
	var pathname = window.location.pathname;
  $('.nav > li > a').parent().removeClass("active"); 
  $('.nav > li > a[href="'+pathname+'"]').parent().addClass('active');
});
