<?php if(!isset($budget)) die();?>
<div class="modal fade editCategory" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Edycja kategorii</h4>
        </div>
        <div class="modal-body success-content" style="display:none;">
          <p class='alert alert-success'>Kategoria została dodana</p>
        </div>
        <div class="modal-body proper-content">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <strong>Nazwa kategorii</strong>
                <input name="categoryName" class="form-control" type="text" />
                <p class='e_categoryName e_db alert alert-danger' style="display:none;"></p>
              </div>
            </div>
          </div>
          <div class="row limit-sction">
            <div class="col-md-6">
              <label><input name="disableLimit" type="checkbox" value=""> Włącz limit dla kategorii</label>
              <p class='e_disableLimit alert alert-danger' style="display:none;"></p>
            </div>
          </div>
          <div class="row limit-sction">
            <div class="col-md-6">
              <strong>Ustaw miesięczny limit wydatków dla kategorii.</strong>
              <input name="categoryLimit" class="form-control" type="number" />
              <p class='e_categoryLimit alert alert-danger' style="display:none;"></p>
            </div>
          </div>
          <div class="row category-section">
            <div class="col-md-6">
              <button type="button" class="btn btn-primary btn-block collapsed" data-toggle="collapse" data-target="#select-category">
                Zmień kategorię nadrzędną
                <span class="glyphicon glyphicon-chevron-up" ></span>
              </button>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <div id="select-category" class="collapse">
                  <div class="category">
                  </div>
                  <p class='e_parentCategory alert alert-danger' style="display:none;"></p>
                </div>
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
