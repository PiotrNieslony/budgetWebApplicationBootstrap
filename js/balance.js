function sumOfItem(partOfBudget){
    var partOfBudgetLength = partOfBudget.length -1;
    var sumOfIncomes = 0;
    for(var i = 1; i <= partOfBudgetLength; i++) {
        sumOfIncomes += partOfBudget[i][1];
    }
    return sumOfIncomes;
}

//tables buttons
$('body').on('click', '.table tr td .extend',function(){
  if($(this).closest('tr').hasClass("active")){
    $(this).removeClass('extended');
    $(this).closest('tr').next('tr').hide(300);
    $(this).closest('tr').removeClass('active');
    $(this).closest('tr').next('tr').find(".active").next('tr').hide(300);
    $(this).closest('tr').next('tr').find(".active").removeClass('active');

  } else {
    $(this).closest('tr').next('tr').show(300);
    var handle = $(this).closest('tr')
    handle.addClass("active");
    handle.siblings('.active').next('tr').hide(300);
    handle.siblings('.active').removeClass('active');
    $(this).closest('tr').next('tr').find(".active").next('tr').hide(300);
    $(this).closest('tr').next('tr').find(".active").removeClass('active');
  }
})

//fills in the form fields in modal edit
$('body').on('click','.table tr td .edit', function(){
  $(".editExpenseModal .success-content").hide();
  $(".editExpenseModal .proper-content").show();
  $(".editExpenseModal .alert.alert-danger").hide();
  var obtainedValues =[];
  var counter = 0;
  $(this).closest('tr').find('td').each(function(index, element){
    if(counter == 0) obtainedValues.push($( this ).closest('tr').attr('id'));
    else if(counter == 5) obtainedValues.push(
      $( this ).closest('table').closest('tr').prev().attr('id')
    );
    else obtainedValues.push($( this ).text());
    counter ++;
  });
  console.log(obtainedValues);
  $('.editExpenseModal').modal();
  $('.editExpenseModal').attr('id', obtainedValues[0] );
  $("input[name='expenseAmount']").val(obtainedValues[3]);
  $("input[name='expenseDate']").val(obtainedValues[1]);
  $("select[name='paymentType'] option:contains('"+obtainedValues[2]+"')").prop('selected', true);
  $('input[value=' + obtainedValues[5] + ']').prop('checked', true);
  $(".subCategory").hide();
  $('input[value=' + obtainedValues[5] + ']').closest(".subCategory").show(0, function(){
      $(this).prev().addClass("active");
  });
  $("input[name='expenseComment']").val(obtainedValues[4]);
})

// send edited expense to db
$(".editExpenseModal button[type='submit']").click(function(){
  $.ajax({
    type: "POST",
    url: "index.php?action=edit-expense-modal",
    data: {
      expenseID:        $('.editExpenseModal').attr('id'),
      expenseAmount:    $("input[name='expenseAmount']").val(),
      expenseDate:      $("input[name='expenseDate']").val(),
      paymentType:      $("select[name='paymentType'] option:selected").val(),
      categorys:        $("input[name='categorys']:checked").val(),
      expenseComment:   $("input[name='expenseComment']").val()

    },
    dataType: "json",
    cache: false,
    success: function(data){
      $(".editExpenseModal .alert.alert-danger").hide();
      for(key in data){
        $("." + key).text(data[key]);
        $("." + key).show(200);
        console.log(data[key]);
        loadExpenses();
        $(".editExpenseModal .success-content").show();
        $(".editExpenseModal .proper-content").hide();
      }
    },
    error: function(msg){
      console.log('Exception:', msg);
    }
  });
});

//LOAD EXPENSES
function loadExpenses(){
  $.ajax({
    type: "POST",
    url: "index.php?action=load-expenses",
    dataType: "html",
    cache: false,
    success: function(data){
      $(".expeses-table2 tbody").html(data);
    },
    error: function(msg){
      console.log('Exception:', msg);
    }
  });
}
//////// generate table ////////
function generateTable(incomesArray){
    var insomesHTML = "";
    var incomesArrayLength = incomesArray.length -1;
    var sumOfIncomes = 0;
    for(var i = 1; i <= incomesArrayLength; i++) {
        var tablePart = "<tr>"+
            "<td>" + i + "</td>"+
            "<td>" + incomesArray[i][0] + "</td>"+
            "<td>" + incomesArray[i][1] + "</td>"+
            "</tr>";
        sumOfIncomes += incomesArray[i][1];
        insomesHTML = insomesHTML + tablePart;
    }
    sumOfIncomes = round(sumOfIncomes,2);
    insomesHTML += '<tr>' +
        '<td colspan="2">Suma</td>' +
        '<th>' + sumOfIncomes +'</th>' +
        '</tr>';
    return insomesHTML;
}

var sumOfIncomes = sumOfItem(incomesArray);
var sumOfExpenses = sumOfItem(expensesArray);

$('.incomes-table tbody').html(generateTable(incomesArray));
$('.expeses-table tbody').html(generateTable(expensesArray));

function round(value, precision) {
    var aPrecision = Math.pow(10, precision);
    return Math.round(value*aPrecision)/aPrecision;
}

//////// GUDGET MESSAGE ///////
var message = "";
if(sumOfIncomes > sumOfExpenses){
    message = "<strong>Gratulacje!</strong><br />" +
        "Wspaniale zarządzasz finansami. Posiadasz <strong>" +
        (round((sumOfIncomes -  sumOfExpenses),2)) +
        " zł</strong> wolnych środków. ";
} else {
    message = "<strong>Ostrożnie!</strong><br />" +
        "Wpadasz w dług. Twoje saldo to <strong>" +
        (round((sumOfIncomes -  sumOfExpenses),2)) +
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

//Select date scope
$( "#date-scope" ).on("change", function () {
    if($(this).val() == "custom"){
        $('#dateModal').modal();
    } else {
        $("form:first").submit();
    }
});


$('#dateModalForm').on('submit', function () {
    $('#dateModalForm').trigger('submit', [ 'variable_name']);
})
