var passingValue = {};
var obtainedValue = {};

var startFunction;
$('body').on( "change paste keyup focusout", "input[name='expenseAmount']", function(){
  clearTimeout(startFunction);
  startFunction = setTimeout(function(){ generateLimitMessage(); }, 1000);
});

$('body').on( "change paste keyup focusout", "input[name='expenseDate']", function(){
  clearTimeout(startFunction);
  startFunction = setTimeout(function(){ generateLimitMessage(); }, 1000);
});


function generateLimitMessage(){
  if(!$('input[name="categorys"]').is(':checked')){
    var limitMessage = "Nie wybrano kategorii";
    $('.limit-value').hide(200);
  } else if ($("input[name='expenseAmount']").val()=="") {
    var limitMessage = "Wpisz kwotę";
    $('.limit-value').hide(200);
  } else if ($("input[name='expenseDate']").val() == "" ) {
    var limitMessage = "Wprowadź datę";
    $('.limit-value').hide(200);
  } else {
    sendData();
    $('.limit-value').show(100);
  }
  $('.limit-message').html(limitMessage);
}

$('body').on( "change", 'input[name="categorys"]:checked', function(){
  if($("input[name='expenseAmount']").val()){
    sendData();
  }
});

function openModal(message){
  $('.limitAlert .message').html(message);
  $('.limitAlert').modal();
}

function sendData(){
  passingValue.amount   = $("input[name='expenseAmount']").val();
  passingValue.category = $('input[name="categorys"]:checked').val();
  passingValue.date     = $('input[name="expenseDate"]').val();
  console.log(passingValue);
  sendAjax('index.php?action=checkHowManySpentInCategoryAndLimit',passingValue);
}

function sendAjax(url, value){
  $.ajax({
    type: "POST",
    url: url,
    data: value,
    dataType: "json",
    cache: false,
    success: function(data){
      obtainedValue = data;
      console.log(obtainedValue)
      $('.limit-message').html(obtainedValue['message']);
      $('.limit-amount').html(obtainedValue['amountOfCategoryLimit']);
      $('.spent-amount').html(obtainedValue['HowManySpentInCategory']);
      $('.limit-subtraction').html(obtainedValue['exceedingLimitValue']);
      $('.limit-value').css('background-color',obtainedValue['info-color'] )
      if(obtainedValue['limitSet']) $('.limit-value').show(100);
      else $('.limit-value').hide(100);
    },
    error: function(msg){
      console.log('Exception:', msg);
    }
  });
}
