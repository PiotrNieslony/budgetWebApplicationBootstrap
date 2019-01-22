var passingValue = {};

// SHOW SUBCATEGORY INCOME MODAL
$('body').on("click", ".income-category-edit .add-new-subcategory", function(){
  passingValue.categoryType = categoryType($(this));
  prepareModal(".addNewIncomeSubcategory");
  $('.addNewIncomeSubcategory').modal();
})

//SEND INCOUM SUBCATEGORY TO DB
$(".addNewIncomeSubcategory button[type='submit']").click(function(){
  var categoryName =  $(".addNewIncomeSubcategory input[name='categoryName']").val();
  var categorys    =  $(".addNewIncomeSubcategory input[name='categorys']:checked").val();
  passingValue.categoryName = categoryName;
  passingValue.categorys    = categorys;
  sendAjaxFromModal(".addNewIncomeSubcategory", "index.php?action=add-income-subcategory" ,passingValue);
});

// SHOW CATEGORY INCOME MODAL
$('body').on("click", ".income-category-edit .add-new-category", function(){
  passingValue.categoryType = categoryType($(this));
  prepareModal(".addNewIncomeCategory");
  $('.addNewIncomeCategory').modal();
})

//SEND INCOUM CATEGORY TO DB
$(".addNewIncomeCategory button[type='submit']").click(function(){
  var categoryName = $(".addNewIncomeCategory input[name='categoryName']").val();
  passingValue.categoryName = categoryName;
  sendAjaxFromModal(".addNewIncomeCategory", "index.php?action=add-income-category" ,passingValue);
});

// SHOW SUBCATEGORY EXPENSE MODAL
$('body').on("click", ".expense-category-edit .add-new-subcategory", function(){
  passingValue.categoryType = categoryType($(this));
  console.log(passingValue);
  prepareModal(".addNewExpenseSubcategory");
  $('.addNewExpenseSubcategory').modal();
})

//SEND EXPENSE SUBCATEGORY TO DB
$(".addNewExpenseSubcategory button[type='submit']").click(function(){
  var categoryName =  $(".addNewExpenseSubcategory input[name='categoryName']").val();
  var categorys    =  $(".addNewExpenseSubcategory input[name='categorys']:checked").val();
  passingValue.categoryName = categoryName;
  passingValue.categorys    = categorys;
  sendAjaxFromModal(".addNewExpenseSubcategory", "index.php?action=add-expense-subcategory" ,passingValue);
});

// SHOW CATEGORY EXPENSE MODAL
$('body').on("click", ".expense-category-edit .add-new-category", function(){
  passingValue.categoryType = categoryType($(this));
  $('.addNeweExpenseCategory').modal();
  prepareModal(".addNeweExpenseCategory");
})

//SEND EXPENSE CATEGORY TO DB
$(".addNeweExpenseCategory button[type='submit']").click(function(){
  var categoryName = $(".addNeweExpenseCategory input[name='categoryName']").val();
  passingValue.categoryName = categoryName;
  sendAjaxFromModal(".addNeweExpenseCategory", "index.php?action=add-expense-category" ,passingValue);
});

// DELETE CATEGORY MODAL
$('body').on("click", ".category .delete", function(){
  var categoryID = $(this).siblings('input').val();
  var categoryName = $(this).parent().text();
  var subcategoryClass  = $(this).closest("div").parent("div").attr("class");
  passingValue.categoryID = categoryID;
  passingValue.subCategory = false
  if(subcategoryClass == "subCategory") passingValue.subCategory = true;
  passingValue.categoryType = categoryType($(this));
  console.dir(passingValue);
  $('.deleteCategory').attr("id",categoryID);
  var message = "Czy na pewno chcesz usunąć";
  if(passingValue.subCategory ) message += " podkategorię ";
  else message += " kategorię główną ";
  message +=  "  <strong>" + categoryName +"</strong>?"
  if(passingValue.subCategory) message += " Spowoduje to usunięcie wszystkich do niej wartości.";
  if(!passingValue.subCategory) message += " Spowoduje to usunięcie wszystkich podkategorii i przypisanych do niej wartości.";
  $('.deleteCategory .alert-warning').html(message);
  prepareModal(".deleteCategory");
  $('.deleteCategory').modal();
})

//SEND DELETE CATEGORY TO DB
$(".deleteCategory button[type='submit']").click(function(){
  sendAjaxFromModal(".deleteCategory", "index.php?action=delete-category" , passingValue);
});

function categoryType(hook){
  var categoryType = hook.closest(".panel ").attr("class");
  categoryType = categoryType.search("income");
  if (categoryType > -1) categoryType = "income";
  else categoryType = "expense";
  return categoryType;
}

function prepareModal(modalWindowName){
  $(modalWindowName + " .alert.alert-danger").hide();
  $('.success-content').hide()
  $('.proper-content').show();
  $(modalWindowName).on('shown.bs.modal', function (e) {
    $(modalWindowName + " input[type='text']").focus();
    })
}

//SEND CATEGORY TO DB
function sendAjaxFromModal(modalForm, url, value){
  console.log(value);
  $.ajax({
    type: "POST",
    url: url,
    data: value,
    dataType: "json",
    cache: false,
    success: function(data){
      console.log(data);
      $(modalForm + " .alert.alert-danger").hide();
      if(data[0] == "ok"){
        loadCategory(value.categoryType);
        $(modalForm + " .success-content").show();
        $(modalForm + " .proper-content").hide();
      } else {
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

function loadCategory(categoryType){
  $.ajax({
    type: "POST",
    url: "index.php?action=load-category",
    data: {
      categoryType :categoryType
    },
    dataType: "html",
    cache: false,
    success: function(data){
      if(categoryType == "income")
        $(".income-category-edit .category").html(data);
      else if(categoryType == "expense")
        $(".expense-category-edit .category").html(data);
    },
    error: function(msg){
      console.log('Exception:', msg);
    }
  });
}
