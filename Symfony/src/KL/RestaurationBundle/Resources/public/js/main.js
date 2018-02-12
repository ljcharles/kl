$(document).ready(function () {
  chevronAction();
  activeAction();
});

function activeAction() {
  var pathname = window.location.pathname;
  $('.nav > li > a').parent().removeClass("active");
  $('.nav > li > a[href="'+pathname+'"]').parent().addClass('active');
}

function chevronAction() {
  $('#chevron').click(function() {
    $('html, body').animate({
      scrollTop: $("#about").offset().top
    }, 1000);
  });
}
