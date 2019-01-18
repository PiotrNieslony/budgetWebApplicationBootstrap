<?php if(!isset($budget)) die();?>
<div class="modal fade deleteExpenseModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Usuwanie wydatku</h4>
      </div>
      <div class="modal-body success-content" style="display:none;">
        <p class='alert alert-success'>Dane zostały zmienione</p>
      </div>
      <div class="modal-body proper-content">
        <p class='alert alert-warning'>Message regarding the deleted item</p>
        Operacji nie będzie można cofnąć.
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
            <button type="submit" class="btn btn-danger btn-block">Usuń</button>
          </div>
          <div class="col-md-6 col-xs-6">
            <button type="button" class="btn btn-warning btn-block" data-dismiss="modal">Anuluj</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
