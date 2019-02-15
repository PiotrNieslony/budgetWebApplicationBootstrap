<?php
if(!isset($budget)) die();?>
<div class="modal fade limitAlert danger-alert-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Alert</h4>
        </div>
        <div class="modal-body proper-content">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group text-center">
                <p class="message">Przekroczyłeś miesięczny limit dla kategorii X o kwotę Y.</p>
                <!--<label><input type="checkbox" name="exceeded-limit-remember"> Nie pokazuj tego okna dla wybranej kategorii</label>-->
              </div>
            </div>
          </div>
          </div>
          <div class="modal-footer proper-content">
            <div class="row">
              <div class="col-md-6 col-xs-6">
                <button type="submit" class="btn btn-danger btn-block ">Dodaj wydatek</button>
              </div>
              <div class="col-md-6 col-xs-6">
                <button type="button" class="btn btn-warning btn-block" data-dismiss="modal">Anuluj</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
