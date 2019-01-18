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

//EDIT INCOME MODAL
$('body').on('click','.incomes-table2 .table tr td .edit', function(){
  $(".editIncomeModal .success-content").hide();
  $(".editIncomeModal .proper-content").show();
  $(".editIncomeModal .alert.alert-danger").hide();
  var obtainedValues =[];
  var counter = 0;
  $(this).closest('tr').find('td').each(function(index, element){
    if(counter == 0) obtainedValues.push($( this ).closest('tr').attr('id'));
    else if(counter == 4) obtainedValues.push(
      $( this ).closest('table').closest('tr').prev().attr('id')
    );
    else obtainedValues.push($( this ).text());
    counter ++;
  });
  //fills the form fields in modal edit
  $('.editIncomeModal').modal();
  $('.editIncomeModal').attr('id', obtainedValues[0] );
  $("input[name='incomeAmount']").val(obtainedValues[2]);
  $("input[name='incomeDate']").val(obtainedValues[1]);
  $('input[value=' + obtainedValues[4] + ']').prop('checked', true);
  $(".subCategory").hide();
  $('input[value=' + obtainedValues[4] + ']').closest(".subCategory").show(0, function(){
      $(this).prev().addClass("active");
  });
  $("input[name='incomeComment']").val(obtainedValues[3]);
})

// SEND EDITED INCOME MODAL TO DB
$(".editIncomeModal button[type='submit']").click(function(){
  var testArray = [$('.editIncomeModal').attr('id'), $("input[name='incomeAmount']").val(), $("input[name='incomeDate']").val(), $("input[name='categorys']:checked").val(), $("input[name='incomeComment']").val()];
  console.log(testArray);
  $.ajax({
    type: "POST",
    url: "index.php?action=edit-income-modal",
    data: {
      incomeID:        $('.editIncomeModal').attr('id'),
      incomeAmount:    $("input[name='incomeAmount']").val(),
      incomeDate:      $("input[name='incomeDate']").val(),
      categorys:        $("input[name='categorys']:checked").val(),
      incomeComment:   $("input[name='incomeComment']").val()
    },
    dataType: "json",
    cache: false,
    success: function(data){
      $(".editIncomeModal .alert.alert-danger").hide();
      for(key in data){
        $("." + key).text(data[key]);
        $("." + key).show(200);
        console.log(data[key]);
      }
      loadIncomes();
      $(".editIncomeModal .success-content").show();
      $(".editIncomeModal .proper-content").hide();
    },
    error: function(msg){
      console.log('Exception:', msg);
    }
  });
});

//DELETE INCOME MODAL
$('body').on('click','.incomes-table2 .table tr td .delete', function(){
  $(".deleteIncomeModal .success-content").hide();
  $(".deleteIncomeModal .proper-content").show();
  $(".deleteIncomeModal .alert.alert-danger").hide();
  var obtainedValues =[];
  var counter = 0;
  $(this).closest('tr').find('td').each(function(index, element){
    if(counter == 0) obtainedValues.push($( this ).closest('tr').attr('id'));
    else if(counter == 4) obtainedValues.push(
      $( this ).closest('table').closest('tr').prev().find('td:nth-child(2)').text()
    );
    else obtainedValues.push($( this ).text());
    counter ++;
  });
  console.log(obtainedValues);
  $('.deleteIncomeModal').modal();
  $('.deleteIncomeModal').attr('id', obtainedValues[0] );
  //generate message
  var message = "Czy na pewno chcesz usunąć wydatek z kategorii <b>"
  + obtainedValues[4] + "</b>, na kwotę <b>"
  + obtainedValues[2] + "</b>, z dnia <b>" + obtainedValues[1];
  if (obtainedValues[3]) message = message + "</b>, opatrzony komentarzem <b>" + obtainedValues[3] +"</b>?";
  else message = message +"</b>?";
  $('.deleteIncomeModal .proper-content p').html(message);
})

// send delete comand Income to db
$(".deleteIncomeModal button[type='submit']").click(function(){
  $.ajax({
    type: "POST",
    url: "index.php?action=delete-income-modal",
    data: {
      incomeID:   $('.deleteIncomeModal').attr('id')
    },
    dataType: "json",
    cache: false,
    success: function(data){
      loadIncomes();
      $(".deleteIncomeModal .success-content").show();
      $(".deleteIncomeModal .proper-content").hide();
    },
    error: function(msg){
      console.log('Exception:', msg);
    }
  });
});

//EDIT EXPENSE MODAL
$('body').on('click','.expeses-table2 .table tr td .edit', function(){
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
  //fills the form fields in modal edit
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
      }
      loadExpenses();
      $(".editExpenseModal .success-content").show();
      $(".editExpenseModal .proper-content").hide();
    },
    error: function(msg){
      console.log('Exception:', msg);
    }
  });
});

//DELETE EXPENSE MODAL
$('body').on('click','.expeses-table2 .table tr td .delete', function(){
  $(".deleteExpenseModal .success-content").hide();
  $(".deleteExpenseModal .proper-content").show();
  $(".deleteExpenseModal .alert.alert-danger").hide();
  var obtainedValues =[];
  var counter = 0;
  $(this).closest('tr').find('td').each(function(index, element){
    if(counter == 0) obtainedValues.push($( this ).closest('tr').attr('id'));
    else if(counter == 5) obtainedValues.push(
      $( this ).closest('table').closest('tr').prev().find('td:nth-child(2)').text()
    );
    else obtainedValues.push($( this ).text());
    counter ++;
  });
  $('.deleteExpenseModal').modal();
  $('.deleteExpenseModal').attr('id', obtainedValues[0] );
  //generate message
  var message = "Czy na pewno chcesz usunąć wydatek z kategorii <b>"
  + obtainedValues[5] + "</b>, na kwotę <b>"
  + obtainedValues[3] + "</b>, z dnia <b>" + obtainedValues[1];
  if (obtainedValues[4]) message = message + "</b>, opatrzony komentarzem <b>" + obtainedValues[4] +"</b>?";
  else message = message +"</b>?";
  $('.deleteExpenseModal .proper-content p').html(message);
})

// send delete comand expense to db
$(".deleteExpenseModal button[type='submit']").click(function(){
  $.ajax({
    type: "POST",
    url: "index.php?action=delete-expense-modal",
    data: {
      expenseID:   $('.deleteExpenseModal').attr('id')
    },
    dataType: "json",
    cache: false,
    success: function(data){
      loadExpenses();
      $(".deleteExpenseModal .success-content").show();
      $(".deleteExpenseModal .proper-content").hide();
    },
    error: function(msg){
      console.log('Exception:', msg);
    }
  });
});

//LOAD INCOMES
function loadIncomes(){
  $.ajax({
    type: "POST",
    url: "index.php?action=load-incomes",
    dataType: "html",
    cache: false,
    success: function(data){
      $(".incomes-table2 tbody").html(data);
    },
    error: function(msg){
      console.log('Exception:', msg);
    }
  });
}

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

//////// GENERATE MESSAGE ////////
function sumOfItem(partOfBudget){
    var partOfBudgetLength = partOfBudget.length -1;
    var sumOfIncomes = 0;
    for(var i = 1; i <= partOfBudgetLength; i++) {
        sumOfIncomes += partOfBudget[i][1];
    }
    return sumOfIncomes;
}

var sumOfIncomes = sumOfItem(incomesArray);
var sumOfExpenses = sumOfItem(expensesArray);

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
    $('#dateModalForm').trigger('submit');
})
