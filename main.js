
function toggleSidebar(){
  if($('.sidebar').css('left') == '0px'){
    $('.sidebar').addClass('hide-sidebar');
    $('.content').addClass('content-full');
    $('.switcher').addClass('switcher-rotate');
  } else {
    $('.sidebar').removeClass('hide-sidebar');
    $('.content').removeClass("content-full");
    $('.switcher').removeClass('switcher-rotate')
  }
}

function setFixedSidbar(){
    if($( document ).width() >= 992){
        $('.sidebar').removeClass('hide-sidebar');
        $('.content').removeClass("content-full");
        $('.switcher').removeClass('switcher-rotate');
        $('.switcher').addClass('hidden');
    } else {
        $('.sidebar').addClass('hide-sidebar');
        $('.content').addClass('content-full');
        $('.switcher').addClass('switcher-rotate');
        $('.switcher').removeClass('hidden');
    }
}

$( document ).ready(function() {
  toggleSidebar();
  setFixedSidbar();
});

$('.switcher').click(function(){
  toggleSidebar();
});

$('.content').click(function(){
  if($('.sidebar').css('left') == '0px' && ($( document ).width() < 992)) toggleSidebar();
});

$( window ).resize(function() {
    setFixedSidbar();
});

var NavY = $('.sidebar').offset().top;
var stickyNav = function(){
var ScrollY = $(window).scrollTop();

if (ScrollY > NavY) {
  $('.sidebar').addClass('sticky');
} else {
  $('.sidebar').removeClass('sticky');
}
};

stickyNav();

$(window).scroll(function() {
  stickyNav();
});





