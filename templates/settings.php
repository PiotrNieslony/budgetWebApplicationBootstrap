<?php if(!isset($budget)) die();?>
<header>
  <h1>Ustawienia</h1>
</header>
<div class="settings">
  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default income-category-edit">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
          Edycja kategorii przychodów
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">
        <strong>Edytuj istniejące kategorie:</strong>
        <?php $budget->showIncomsCategory('settings'); ?>
        <div class="row">
          <div class="col-sm-6">
            <button class="btn btn-primary btn-block add-new-category">Dodaj nową kategorię główną</button>
          </div>
          <div class="col-sm-6">
            <button class="btn btn-primary btn-block add-new-subcategory">Dodaj nową podkategorię</button>
          </div>
        </div>

      </div>
    </div>
  </div>
  <div class="panel panel-default expense-category-edit">
    <div class="panel-heading" role="tab" id="headingTwo">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Edycja kategorii wydatków
        </a>
      </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
      <div class="panel-body">
        <strong>Istniejące kategorie:</strong>
        <?php $budget->showExpensCategory('settings'); ?>
        <div class="row">
          <div class="col-sm-6">
            <button class="btn btn-primary btn-block add-new-category">Dodaj nową kategorię główną</button>
          </div>
          <div class="col-sm-6">
            <button class="btn btn-primary btn-block add-new-subcategory">Dodaj nową podkategorię</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingThree">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Użytkownik
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
      <div class="panel-body">
        <p>Login: <strong><?= $_SESSION['loggedUser']['username'] ?></strong></p>
        <p>Email: <strong><?= $budget->getEmailAdress()?></strong></p>
        <div class="row">
          <div class="col-xs-6">
            <button class="btn btn-primary  btn-block edit-user-data">
              <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edytuj swoje dane
            </button>
          </div>
          <div class="col-xs-6">
            <button class="btn btn-primary btn-block edit-password">
              <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Zmień hasło
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
 ?>
 <script src="js/settings-page.js"	></script>
