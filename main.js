
function toggleSidebar(){
  console.log("klik" + $('.sidebar').css('left'))

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
//////// incomes data ////////
var incomesArray = [
  ['Incomes', 'Items of insome'],
  ['Wynagrodzenie', 3000],
  ['Sprzedaż na allegro', 300],
  ['Odsetki bankowe', 100],
  ['Inne', 0]
]
//////// expenses data ////////
var expensesArray = [
  ['Expenses', 'Items of espense'],
  ['Mieszkanie', 1600],
  ['Jedzenie', 800],
  ['Transport', 400],
  ['Wycieczka', 250],
  ['Ubranie', 200],
  ['Oszczędności', 100],
  ['Na złotą jesień, czyli emeryturę', 100],
  ['Higiena', 80],
  ['Telekomunikacja', 30],
  ['Opieka zdrowotna', 30],
  ['Szkolenia', 30],
  ['Książki', 25],
]



function sumOfItem(partOfBudget){
  var partOfBudgetLength = partOfBudget.length -1;
  var sumOfIncomes = 0;
  for(var i = 1; i <= partOfBudgetLength; i++) {
    sumOfIncomes += partOfBudget[i][1];
  }
  return sumOfIncomes;
}

//////// generate table ////////
function generateTable(incomesArray){
  var insomesHTML = "";
  var incomesArrayLength = incomesArray.length -1;
  var sumOfIncomes = 0;
  for(var i = 1; i <= incomesArrayLength; i++) {
    console.log(incomesArray[i][0]);
    var tablePart = "<tr>"+
    								"<td>" + i + "</td>"+
    									"<td>" + incomesArray[i][0] + "</td>"+
    									"<td>" + incomesArray[i][1] + "</td>"+
    							"</tr>";
    sumOfIncomes += incomesArray[i][1];
    insomesHTML = insomesHTML + tablePart;
  }
  insomesHTML += '<tr>' +
                    '<td colspan="2">Suma</td>' +
  									'<th>' + sumOfIncomes +'</th>' +
  								'</tr>';
  return insomesHTML;
}

$('.incomes-table tbody').html(generateTable(incomesArray));
$('.expeses-table tbody').html(generateTable(expensesArray));

var sumOfIncomes = sumOfItem(incomesArray);
var sumOfExpenses = sumOfItem(expensesArray);
//////// GUDGET MESSAGE ///////
var message = "";
if(sumOfIncomes > sumOfExpenses){
  message = "<strong>Gratulacje!</strong><br />" +
        "Wspaniale zarządzasz finansami. Posiadasz <strong>" +
        (sumOfIncomes -  sumOfExpenses) +
        " zł</strong> wolnych środków. ";
} else {
  message = "<strong>Ostrożnie!</strong><br />" +
        "Wpadasz w dług. Twoje saldo to <strong>" +
        (sumOfIncomes -  sumOfExpenses) +
        " zł</strong>.";
}

$("#summary-message").html(message);

//////// PIE CHART ////////
//////// Load google charts

google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(pieCharts);

// Draw the chart and set the chart values
function pieCharts() {
  var dataIncomes = google.visualization.arrayToDataTable(incomesArray);
  var dataExpenses = google.visualization.arrayToDataTable(expensesArray);

  var options = {
    fontName:'Open Sans',
    width: 280,
    height: 300,
    pieHole: 0.4,
    backgroundColor: 'transparent',
    slices:{
      0:{color: 'rgb(57,129,130)'}
    },
    chartArea:{
      left:10,
      top:50,
      width:'80%',
      height:'80%'
    },
    titleTextStyle:{
      color: '#398182',
    },
    legend:{
      position: 'top',
      maxLines: 10,
      textStyle:{
        color: '#398182'
      }
    },
    animation: {
       duration: 1000,
       easing: 'out'
    }
  };

  // Display the chart inside the <div> element with id="piechart"
  var chart1 = new google.visualization.PieChart(document.getElementById('piechart-incomes'));
  chart1.draw(dataIncomes, options);

  var chart2 = new google.visualization.PieChart(document.getElementById('piechart-espenses'));
  chart2.draw(dataExpenses, options);
}
//////// END - PIE CHART ////////

//////// COLUM CHART ////////

google.charts.setOnLoadCallback(drawChart);
 google.charts.load("current", {packages: ["bar"]});
function drawChart() {
  var data = google.visualization.arrayToDataTable([
    ["Budget", "Density", { role: "style" } ],
    ["Przychody", sumOfIncomes, "#398182"],
    ["Wydatki", sumOfExpenses, "#DC3912"]
  ]);

  var view = new google.visualization.DataView(data);
  view.setColumns([0, 1,
                   { calc: "stringify",
                     sourceColumn: 1,
                     type: "string",
                     role: "annotation" },
                   2]);

  var options = {
    width: 300,
    height: 300,
    top:0,
    backgroundColor: 'transparent',
    bar: {groupWidth: "80%"},
    legend: { position: "none" },
    isStacked: true,
    animation: {
       duration: 1000,
       easing: 'out'
    }

  };
  var chart = new google.visualization.ColumnChart(document.getElementById("columnchart"));
  chart.draw(view, options);
}
//////// END COLUM CHART ////////

//////// ADD EXPENSE ////////
$(function() {
  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth()+1;
  var yyyy = today.getFullYear();
  if(mm < 10) mm = "0" + mm;
  if(dd < 10) dd = "0" + dd;
  today = yyyy + '-' + mm + '-' + dd;
  $(".date").val(today);
  $( ".date" ).datepicker({
     dateFormat:"yy-mm-dd",
     minDate: new Date(2001, 1 - 1, 1),
     maxDate: "m",
     monthNames: [ "Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień" ],
     dayNames: [ "Poniedziałek", "Wtorek", "Środa", "Czwartek", "Piątek", "Sobota", "Niedziela" ],
     dayNamesMin: [ "Pn", "Wt", "Śr", "Cz", "Pt", "So", "Sn" ],
   });
});
//////// END ADD EXPENSE ////////
