
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

//Data picker
$(function() {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1;
    var yyyy = today.getFullYear();
    var yyyy2 = yyyy;
    var mm2 = today.getMonth();
    if(mm2 == 0) {
        mm2 = 12;
        --yyyy2;
    }
    console.log(mm2);
    if(mm < 10) mm = "0" + mm;
    if(mm2 < 10) mm2 = "0" + mm2;
    if(dd < 10) dd = "0" + dd;
    today = yyyy + '-' + mm + '-' + dd;
    var firstDayOfPriviusMonth = yyyy2 + '-' + mm2 + '-01';
    $(".date").val(today);
    $(".date-from").val(firstDayOfPriviusMonth);
    $(".date-to").val(today);
    $( ".date" ).datepicker({
        dateFormat:"yy-mm-dd",
        minDate: new Date(2001, 1 - 1, 1),
        maxDate: "m",
        monthNames: [ "Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień" ],
        dayNames: [ "Poniedziałek", "Wtorek", "Środa", "Czwartek", "Piątek", "Sobota", "Niedziela" ],
        dayNamesMin: [ "Pn", "Wt", "Śr", "Cz", "Pt", "So", "Sn" ],
    });
});




