// SHOW SUBCATEGORY INCOME MODAL
$('body').on("click", ".income-category-edit .add-new-subcategory", function(){
  $(".addNewIncomeSubcategory .alert.alert-danger").hide();
  $('.success-content').hide()
  $('.proper-content').show()
  $('.addNewIncomeSubcategory').modal();
})

//SEND INCOUM SUBCATEGORY TO DB
$(".addNewIncomeSubcategory button[type='submit']").click(function(){
  var categoryName =  $(".addNewIncomeSubcategory input[name='categoryName']").val();
  var categorys    =  $(".addNewIncomeSubcategory input[name='categorys']:checked").val();
  var value = {categoryName :   categoryName,
               categorys     :  categorys};
  addCategory(".addNewIncomeSubcategory", "index.php?action=add-income-subcategory" , value);
});

// SHOW CATEGORY INCOME MODAL
$('body').on("click", ".income-category-edit .add-new-category", function(){
  $(".addNewIncomeCategory .alert.alert-danger").hide();
  $('.success-content').hide()
  $('.proper-content').show()
  $('.addNewIncomeCategory').modal();
})

//SEND INCOUM CATEGORY TO DB
$(".addNewIncomeCategory button[type='submit']").click(function(){
  var categoryName = $(".addNewIncomeCategory input[name='categoryName']").val();
  var value = {categoryName :   categoryName};
  addCategory(".addNewIncomeCategory", "index.php?action=add-income-category" , value);
});

// SHOW SUBCATEGORY EXPENSE MODAL
$('body').on("click", ".expense-category-edit .add-new-subcategory", function(){
  $(".addNewExpenseSubcategory .alert.alert-danger").hide();
  $('.success-content').hide()
  $('.proper-content').show()
  $('.addNewExpenseSubcategory').modal();
})

//SEND EXPENSE SUBCATEGORY TO DB
$(".addNewExpenseSubcategory button[type='submit']").click(function(){
  var categoryName =  $(".addNewExpenseSubcategory input[name='categoryName']").val();
  var categorys    =  $(".addNewExpenseSubcategory input[name='categorys']:checked").val();
  var value = {categoryName :   categoryName,
               categorys     :  categorys};
  addCategory(".addNewExpenseSubcategory", "index.php?action=add-expense-subcategory" , value);
});

// SHOW CATEGORY EXPENSE MODAL
$('body').on("click", ".expense-category-edit .add-new-category", function(){
  console.log("ok");
  $(".addNeweExpenseCategory .alert.alert-danger").hide();
  $('.success-content').hide()
  $('.proper-content').show()
  $('.addNeweExpenseCategory').modal();
})

//SEND EXPENSE CATEGORY TO DB
$(".addNeweExpenseCategory button[type='submit']").click(function(){
  var categoryName = $(".addNeweExpenseCategory input[name='categoryName']").val();
  var value = {categoryName :   categoryName};
  addCategory(".addNeweExpenseCategory", "index.php?action=add-income-category" , value);
});

//SEND CATEGORY TO DB
function addCategory(modalForm, url, value){
  console.log(value);
  $.ajax({
    type: "POST",
    url: url,
    data: value,
    dataType: "json",
    cache: false,
    success: function(data){
      console.log(data);
      if(data[0] == "ok"){
        $(modalForm + " .success-content").show();
        $(modalForm + " .proper-content").hide();
      } else {
        $(modalForm + " .alert.alert-danger").hide();
        for(key in data){
          $("." + key).text(data[key]);
          $("." + key).show(200);
          console.log(data[key]);
        }
      }
    },
    error: function(msg){
      console.log('Exception:', msg);
    }
  });
}
