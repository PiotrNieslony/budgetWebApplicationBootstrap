<?php if(!isset($budget)) die();?>
<div class="modal fade editExpenseModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edycja</h4>
      </div>
      <div class="modal-body success-content" style="display:none;">
        <p class='alert alert-success'>Dane zostały zmienione</p>
      </div>
      <div class="modal-body proper-content">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <strong>Kwota</strong>
              <input name="expenseAmount" class="form-control" type="number" step="0.01"/>
              <p class='e_expenseAmount alert alert-danger' style="display:none;"></p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <strong>Data</strong>
              <input name="expenseDate" class="date form-control" type="text" placeholder="RRRR-MM-DD" required
              pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"
              title="Wpisz datę w formacie YYYY-MM-DD"/>
              <p class='e_expenseDate alert alert-danger' style="display:none;"></p>
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
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <strong>Kategoria</strong>
              <?php
                $budget->showExpensCategory('show');
              ?>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <strong>Komentarz:</strong>
            <input name="expenseComment" type="text" class="form-control" />
          </div>
        </div>
        </div>
        <div class="modal-footer success-content">
          <div class="row">
            <div class="col-md-12">
              <button type="submit" class="btn btn-primary btn-block " data-dismiss="modal">Zamknij</button>
            </div>
          </div>
        </div>
        <div class="modal-footer proper-content">
          <div class="row">
            <div class="col-md-6 col-xs-6">
              <button type="submit" class="btn btn-primary btn-block ">Zmień</button>
            </div>
            <div class="col-md-6 col-xs-6">
              <button type="button" class="btn btn-warning btn-block" data-dismiss="modal">Anuluj</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
