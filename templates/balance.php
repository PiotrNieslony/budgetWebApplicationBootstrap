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
<<<<<<< HEAD
                    <form method="post">
                        <select id="date-scope" class="select-date form-control" name="date-scope">
                            <option value="current-month" <?= (isset($_SESSION['current-month'])) ? "selected" : "" ; unset($_SESSION['current-month']);?> >Bieżący miesiąc</option>
                            <option value="previous-month" <?= (isset($_SESSION['previous-month'])) ? "selected" : ""; unset($_SESSION['previous-month']);?> >Poprzedni miesiąc</option>
                            <option value="custom">Niestandardowy</option>
                            <?= (isset($_SESSION['custom'])) ? "<option selected >".$_SESSION['selected-date-from']." - ".$_SESSION['selected-date-to']."</option>" : ""; unset($_SESSION['custom'], $_SESSION['selected-date-from'], $_SESSION['selected-date-to']);?>
                        </select>
                    </form>
                    <div class="modal fade" id="dateModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form id="dateModalForm" method="post">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Wybierz zakres dat</h4>
                                    </div>
                                    <div class="modal-body">
                                            <div class="form-group">
                                                <strong>Data od</strong>
                                                <input name="dateFrom" class="date date-from form-control" type="text" placeholder="RRRR-MM-DD" required
                                                       pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"
                                                       title="Wpisz datę w formacie YYYY-MM-DD"/>
                                            </div>
                                            <div class="form-group">
                                                <strong>Data do</strong>
                                                <input name="dateTo" class="date date-to form-control" type="text" placeholder="RRRR-MM-DD" required
                                                       pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"
                                                       title="Wpisz datę w formacie YYYY-MM-DD"/>
                                            </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>
                                        <button type="submit" class="btn btn-primary">OK</button>
                                    </div>
                                </form>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
=======
      <form method="post">
          <select id="date-scope" class="select-date form-control" name="date-scope">
              <option value="current-month" <?php echo (isset($_SESSION['selected-date'] ) && $_SESSION['selected-date'] == 'current-month') ? "selected" : "" ;?> >Bieżący miesiąc</option>
              <option value="previous-month" <?php echo (isset($_SESSION['selected-date'] ) &&  $_SESSION['selected-date'] == 'previous-month') ? "selected" : "";?> >Poprzedni miesiąc</option>
              <option value="custom">Niestandardowy</option>
              <?php echo (isset($_SESSION['selected-date']) &&  $_SESSION['selected-date']  == 'custom') ? "<option selected >".$_SESSION['selected-date-from']." - ".$_SESSION['selected-date-to']."</option>" : ""; unset($_SESSION['selected-date-from'], $_SESSION['selected-date-to']);?>
          </select>

      </form>
>>>>>>> balance
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
      <div class="budget-table incomes-table2">
        <table class="table table-bordered table-striped table-hover">
          <caption>Tabela przychodów</caption>
          <thead>
            <tr>
              <th>l.p.</th>
              <th>Kategoria</th>
              <th>Wartość</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php $incomes = $budget->showIncomes();?>
          </tbody>
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
    <div class="budget-table expeses-table2">
      <table class="table table-striped table-bordered table-hover">
        <caption>Tabela wydatków</caption>
        <thead>
          <tr>
            <th>l.p.</th>
            <th>Kategoria</th>
            <th>Wartość</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php $expenses = $budget->showExpenses();?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<<<<<<< HEAD
=======
<?php
include "modal-window/dateModal.php";
include "modal-window/editExpenseModal.php";
include "modal-window/deleteExpenseModal.php";
include "modal-window/editIncomeModal.php";
include "modal-window/deleteIncomeModal.php";
?>
<script>
    //incomes data
    var incomesArray = [['Category', 'Amount']];
    incomesArray.push(<?php foreach($incomes as $income){echo "[\"$income[1]\", $income[2]],";} ?>);
>>>>>>> balance

    console.log(incomesArray);
    //expenses data
    var expensesArray = [['Category', 'Amount']];
    expensesArray.push(<?php foreach($expenses as $expens){echo "[\"$expens[1]\", $expens[2]],";} ?>);
</script>
<script src="js/balance.js"	></script>
