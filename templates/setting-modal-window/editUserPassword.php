<?php if(!isset($budget)) die();?>
<div class="modal fade editUserPassword" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Edycja danych użytkownika</h4>
        </div>
        <div class="modal-body success-content" style="display:none;">
          <p class='alert alert-success'>Dane zostały zmienione</p>
        </div>
        <div class="modal-body proper-content">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <p>UWAGA. Zapamiętaj lub zapisz nowe hasło, przyda Ci się przy kolejnym logowaniu do aplikacji ;)</p>
                <strong>Stare hasło:</strong>
                <input name="oldPass" class="form-control" type="password"/>
                <p class='e_wrong_pass alert alert-danger' style="display:none;"></p>
                <strong>Nowe hasło:</strong>
                <input name="pass1" class="form-control" type="password"/>
                <p class='e_pass alert alert-danger' style="display:none;"></p>
                <strong>Powtórz hasło:</strong>
                <input name="pass2" class="form-control" type="password"/>
              </div>
            </div>
          </div>
          </div>
          <div class="modal-footer success-content">
            <div class="row">
              <div class="col-md-12">
                <button class="btn btn-primary btn-block " data-dismiss="modal">Zamknij</button>
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
