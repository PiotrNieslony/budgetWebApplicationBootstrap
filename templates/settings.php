<?php if(!isset($budget)) die();?>
<header>
  <h1>Ustawienia</h1>
</header>
<div class="settings">
  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-primary income-category-edit">
      <div class="panel-heading" role="tab">
        <h4 class="panel-title">
          <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
            <span class="glyphicon glyphicon-ice-lolly" aria-hidden="true"></span> Kategorie przychodów
          </a>
        </h4>
      </div>
      <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
        <div class="panel-body">
          <strong>Edytuj istniejące kategorie:</strong>
          <div class="category">
            <?php $budget->showIncomsCategory('settings'); ?>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <button class="btn btn-primary btn-block add-new-category">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Dodaj nową kategorię główną
              </button>
            </div>
            <div class="col-sm-6">
              <button class="btn btn-primary btn-block add-new-subcategory">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Dodaj nową podkategorię
              </button>
            </div>
          </div>

        </div>
      </div>
    </div>
    <div class="panel panel-primary expense-category-edit">
      <div class="panel-heading" role="tab">
        <h4 class="panel-title">
          <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            <span class="glyphicon glyphicon-ice-lolly-tasted" aria-hidden="true"></span> Kategorie wydatków
          </a>
        </h4>
      </div>
      <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
        <div class="panel-body">
          <strong>Istniejące kategorie:</strong>
          <div class="category">
            <?php $budget->showExpensCategory('settings'); ?>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <button class="btn btn-primary btn-block add-new-category">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Dodaj nową kategorię główną</button>
            </div>
            <div class="col-sm-6">
              <button class="btn btn-primary btn-block add-new-subcategory">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Dodaj nową podkategorię</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="panel panel-primary payment-type-edit">
      <div class="panel-heading" role="tab">
        <h4 class="panel-title">
          <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsePaymentType" aria-expanded="false" aria-controls="collapsePaymentType">
            <span class="glyphicon glyphicon-credit-card" aria-hidden="true"></span> Sposoby płatności
          </a>
        </h4>
      </div>
      <div id="collapsePaymentType" class="panel-collapse collapse" role="tabpanel" aria-labelledby="payment-type-edit">
        <div class="panel-body">
          <strong>Istniejące sposoby płatności:</strong>
          <ul id="listOfPayentMethod" class="list-group ">
            <?php $budget->showExpensPaymentMethod('settings') ?>
          </ul>
          <div class="row">
            <div class="col-sm-6">
              <button class="btn btn-primary btn-block add-new-payment-method">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Dodaj metodę płatności</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="panel panel-primary user">
      <div class="panel-heading" role="tab">
        <h4 class="panel-title">
          <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
              <span class="glyphicon glyphicon-user" aria-hidden="true"></span> Użytkownik
          </a>
        </h4>
      </div>
      <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="user">
        <div class="panel-body">
          <div class="row">
            <div class="col-sm-6">
              <p>Login: <strong id="user-name"><?= $_SESSION['loggedUser']['username'] ?></strong></p>
              <p>Email: <strong id="user-email"><?= $budget->getEmailAdress()?></strong></p>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <button class="btn btn-primary  btn-block edit-user-data">
                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edytuj swoje dane
              </button>
              <button class="btn btn-primary btn-block edit-password">
                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Zmień hasło
              </button>
            </div>
            <div class="col-sm-6">
              <button class="btn btn-danger btn-block edit-password">
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Usuń wszystkie przychody i wydatki
              </button>
              <button class="btn btn-danger btn-block edit-password">
                <span class="glyphicon glyphicon-fire" aria-hidden="true"></span> Usuń moje konto

              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
  include "templates\setting-modal-window\addNewIncomeSubcategory.php";
  include "templates\setting-modal-window\addNewIncomeCategory.php";
  include "templates\setting-modal-window\addNewExpenseSubcategory.php";
  include "templates\setting-modal-window\addNewExpenseCategory.php";
  include "templates\setting-modal-window\deleteCategory.php";
  include "templates\setting-modal-window/editCategory.php";
  include "templates\setting-modal-window/addNewPaymentMethod.php";
  include "templates\setting-modal-window/deletePaymentMethod.php";
  include "templates\setting-modal-window/editPaymentMethod.php";
 ?>
 <script src="js/settings-page.js"	></script>
