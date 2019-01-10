<header>
  <h1>Dodaj wydatek</h1>
</header>
<div>
  <form role="form .form-horizontal" method="post">
    <div class="row">
      <div class="col-md-8">
        <?= (isset($_SESSION['success'])) ? "<p class=\"alert alert-success\">".$_SESSION['success']."</p>" : "";
        unset($_SESSION['success']); ?>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <strong>Kwota</strong>
              <input name="expenseAmount" class="form-control" type="number" step="0.01"/>
              <?= (isset($_SESSION['e_expenseAmount'])) ? "<p class='alert alert-danger'>".$_SESSION['e_expenseAmount']."</p>" : "";
              unset($_SESSION['e_expenseAmount']); ?>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <strong>Data</strong>
              <input name="expenseDate" class="date form-control" type="text" placeholder="RRRR-MM-DD" required
              pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"
              title="Wpisz datę w formacie YYYY-MM-DD"/>
              <?= (isset($_SESSION['e_expenseDate'])) ? "<p class='alert alert-danger'>".$_SESSION['e_expenseDate']."</p>" : "";
              unset($_SESSION['e_expenseDate']); ?>
            </div>
          </div>
          <diV class="col-md-4">
            <div class="form-group">
              <strong>Sposób płatności</strong>
              <select name="paymentType" class="form-control">
                <?php
                  $budget->showExpensPaymentMethod();
                ?>
              </select>
            </div>
          </div>
        </div>
        <div>
          <div class="form-group">
            <strong>Kategoria</strong>
            <?php
              $budget->showExpensCategory();
            ?>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <strong>Komentarz:</strong>
            <input name="expenseComment" type="text" class="form-control" />
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <button type="submit" class="btn btn-default btn-block ">Dodaj</button>
          </div>
          <div class="col-md-4">
            <button type="button" class="btn btn-warning btn-block ">Anuluj</button>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
