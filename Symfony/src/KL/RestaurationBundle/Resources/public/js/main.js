$(document).ready(function () {
  chevronAction();
  activeAction();
  hideMdpAction();
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

function myMap() {
  var mapCanvas = document.getElementById("map");
  var mapOptions = {
    // Personnaliser vos coordonnées ici latitudepuis longitude
    center: new google.maps.LatLng(16.1547308, -61.9560547),
    zoom: 10,
    disableDefaultUI: true
  }
  var map = new google.maps.Map(mapCanvas, mapOptions);
}

function hideMdpAction() {
  $('.show-password').click(function() {
    if($(this).prev('input').prop('type') == 'password') {
      $(this).prev('input').prop('type','text');
      $(this).html('<i class="fa fa-eye-slash doree"></i>');
    } else {
      $(this).prev('input').prop('type','password');
      $(this).html('<i class="fa fa-eye doree"></i>');
    }
  });
}
