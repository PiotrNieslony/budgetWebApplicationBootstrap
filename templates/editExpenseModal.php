<?php if(!isset($budget)) die();?>
<div class="modal fade editModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Edycja</h4>
      </div>
      <div class="modal-body">
            <div class="form-group">
              <strong>Kwota</strong>
              <input name="expenseAmount" class="form-control" type="number" step="0.01"/>
            </div>
            <div class="form-group">
              <strong>Data</strong>
              <input name="expenseDate" class="date form-control" type="text" placeholder="RRRR-MM-DD" required
              pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"
              title="Wpisz datę w formacie YYYY-MM-DD"/>
            </div>
            <div class="form-group">
              <strong>Sposób płatności</strong>
              <select name="paymentType" class="form-control">
                <?php
                  $budget->showExpensPaymentMethod();
                ?>
              </select>
            </div>
            <div class="form-group">
              <strong>Kategoria</strong>
              <?php
                $budget->showExpensCategory();
              ?>
            </div>
            <strong>Komentarz:</strong>
            <input name="expenseComment" type="text" class="form-control" />
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary btn-block ">Dodaj</button>
          <button type="button" class="btn btn-warning btn-block " data-dismiss="modal">Anuluj</button>
        </div>
      </div>
    </div>
  </div>
  </div>
</div>
