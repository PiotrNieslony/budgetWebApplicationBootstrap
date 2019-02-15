<?php if(!isset($budget)) die();
$budget->setBalanceDateToSession();
?>
<header>
  <h1>Bilans </h1>
  <h4>Za okres<?= "od ".$_SESSION['selected-date-from']." do ".$_SESSION['selected-date-to'] ?></h4>
</header>
<div class="row">
  <div class="
    col-lg-3 col-lg-push-9
    col-md-3 col-md-push-9">
      <form method="post">
          <select id="date-scope" class="select-date form-control" name="date-scope">
              <option value="current-month" <?php echo (isset($_SESSION['selected-date'] ) && $_SESSION['selected-date'] == 'current-month') ? "selected" : "" ;?> >Bieżący miesiąc</option>
              <option value="previous-month" <?php echo (isset($_SESSION['selected-date'] ) &&  $_SESSION['selected-date'] == 'previous-month') ? "selected" : "";?> >Poprzedni miesiąc</option>
              <option value="custom">Niestandardowy</option>
              <?php echo (isset($_SESSION['selected-date']) &&  $_SESSION['selected-date']  == 'custom') ? "<option selected >".$_SESSION['selected-date-from']." - ".$_SESSION['selected-date-to']."</option>" : ""; unset($_SESSION['selected-date-from'], $_SESSION['selected-date-to']);?>
          </select>
      </form>
  </div>
  <div class="
    col-lg-3 col-lg-pull-3
    col-md-3 col-md-pull-3
    summary">
    <div id="columnchart" class="chart">
      <img class="loader" src="img/ajax-loader.gif" alt="loader"/>
    </div>
  </div>
  <div id="summary-message" class="
    col-lg-4 col-lg-pull-3 col-lg-offset-0
    col-md-5 col-md-pull-3 col-md-offset-1">
    <strong>Gratulacje!</strong><br />
    Wspaniale zarządzasz finansami.
    Posoztało ci <strong> 200 zł</strong> wolnych środków. <br />
  </div>
</div>
<div class="row incomes">
    <div class="col-md-12">
      <h2>Przychody</h2>
    </div>
    <div class="
    col-lg-3 col-lg-push-9
    col-md-5 col-md-push-7">
      <div id="piechart-incomes" class="chart">
        <img class="loader" src="img/ajax-loader.gif" alt="loader"/>
      </div>
    </div>
    <div class="
      col-lg-9 col-lg-pull-3
      col-md-7 col-md-pull-5">
      <div class="budget-table incomes-table">
        <table class="table table-bordered table-striped table-hover">
            <?php $incomes = $budget->showIncomes();?>
        </table>
      </div>
    </div>
</div>
<div class="row expenses">
  <div class="col-md-12">
    <h2>Wydatki</h2>
  </div>
  <div class="
  col-lg-3 col-lg-push-9
  col-md-5 col-md-push-7">
    <div id="piechart-espenses" class="chart">
      <img class="loader" src="img/ajax-loader.gif" alt="loader"/>
    </div>
  </div>
  <div class="
    col-lg-9 col-lg-pull-3
    col-md-7 col-md-pull-5">
    <div class="budget-table expeses-table">
      <table class="table table-striped table-bordered table-hover">
        <?php $expenses = $budget->showExpenses();?>
      </table>
    </div>
  </div>
</div>
<?php
include "budget-modal-window/dateModal.php";
include "budget-modal-window/editExpenseModal.php";
include "budget-modal-window/deleteExpenseModal.php";
include "budget-modal-window/editIncomeModal.php";
include "budget-modal-window/deleteIncomeModal.php";
?>
<script>
    //incomes data
    var incomesArray = [['Category', 'Amount']];
    incomesArray.push(<?php foreach($incomes as $income){echo "[\"$income[1]\", $income[2]],";} ?>);
    console.log(incomesArray);
    //expenses data
    var expensesArray = [['Category', 'Amount']];
    expensesArray.push(<?php foreach($expenses as $expens){echo "[\"$expens[1]\", $expens[2]],";} ?>);
</script>
<script src="js/balance.js"	></script>
