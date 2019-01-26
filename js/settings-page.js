var passingValue = {};
//ADD NEW INCOME SUBCATEGORY
$('body').on("click", ".income-category-edit .add-new-subcategory", function(){
  // SHOW SUBCATEGORY INCOME MODAL
  passingValue.categoryType = categoryType($(this));
  loadCategory(passingValue.categoryType, ".addNewIncomeSubcategory");
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

//ADD NEW INCOME CATEGORY
$('body').on("click", ".income-category-edit .add-new-category", function(){
  // SHOW CATEGORY INCOME MODAL
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

//ADD NEW EXPENSE SUBCATEGORY
$('body').on("click", ".expense-category-edit .add-new-subcategory", function(){
  // SHOW SUBCATEGORY EXPENSE MODAL;
  passingValue.categoryType = categoryType($(this));
  loadCategory(passingValue.categoryType, ".addNewExpenseSubcategory");
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

//ADD NEW CATEGORY
$('body').on("click", ".expense-category-edit .add-new-category", function(){
  // SHOW CATEGORY EXPENSE MODAL
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
  passingValue.categoryID = categoryID;
  passingValue.subCategory = isSubCategory($(this));
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

// EDIT CATEGORY MODAL
$('body').on("click", ".category .edit", function(){
  var categoryID           = $(this).siblings('input').val();
  var categoryName         = $.trim($(this).parent().text());
  var subcategoryClass     = $(this).closest("div").parent("div").attr("class");
  var parentCategoryID       = $(this).closest(".subCategory").prev().find("input").val();
  passingValue.parentCategoryID =parentCategoryID;
  passingValue.categoryID   = categoryID;
  passingValue.subCategory  = isSubCategory($(this));
  passingValue.categoryType = categoryType($(this));
  $('.editCategory').attr("id",categoryID);
  $('.editCategory input[name="categoryName"]').val(categoryName);
  if(passingValue.subCategory) {
    $(".category-section").show();
    loadCategory(passingValue.categoryType, ".editCategory");
  }
  else $(".category-section").hide();
  prepareModal(".editCategory");
  $('.editCategory').modal();
  $('.editCategory').on('shown.bs.modal', function () {
    $('.editCategory input[value=' + parentCategoryID + ']').prop('checked', true);
  });
})

//SEND EDIT CATEGORY TO DB
$('body').on("click", ".editCategory button[type='submit']", function(){
  passingValue.categoryName     = $('.editCategory input[name="categoryName"]').val();
  passingValue.parentCategoryID = $('.editCategory input[name="categorys"]:checked').val();
  sendAjaxFromModal(".editCategory", "index.php?action=edit-category" , passingValue);
});

//Add peymenth method
$('body').on("click", ".payment-type-edit .add-new-payment-method", function(){
  console.log("OK")
  $('.addNewPayentMethod').modal();
  prepareModal(".addNewPayentMethod");
})

//SEND NEW PAYMENTH METHOD TO DB
$('body').on("click", ".addNewPayentMethod button[type='submit']", function(){
  passingValue.operation      = 'add'
  passingValue.paymentMethod  = $('.addNewPayentMethod input[name="paymentMethod"]').val();
  console.dir(passingValue);
  sendAjaxFromModal(".addNewPayentMethod", "index.php?action=modification-payment-method" , passingValue);
});

function isSubCategory(hook){
  var subcategoryClass  = hook.closest("div").parent("div").attr("class");
  var subCategory = false
  if(subcategoryClass == "subCategory") subCategory = true;
  return subCategory;
}

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
  if((modalWindowName.search("add")) > -1)
      $(modalWindowName + " input[type='text']").val("");
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

function loadCategory(categoryType, where){
  $.ajax({
    type: "POST",
    url: "index.php?action=load-category",
    data: {
      categoryType :categoryType,
      where        : where
    },
    dataType: "html",
    cache: false,
    success: function(data){
      var hook =""
      //console.log("where = " + where + " cat= " + categoryType );
      if(where !== undefined) hook = where;
      else if(categoryType == "income") hook = ".income-category-edit";
      else if(categoryType == "expense") hook = ".expense-category-edit";
      $(hook + " .category").html(data);
    },
    error: function(msg){
      console.log('Exception:', msg);
    }
  });
}
