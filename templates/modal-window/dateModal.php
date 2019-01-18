<?php if(!isset($budget)) die();?>
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
                  <div class="row">
                    <div class="col-xs-6">
                      <button type="submit" class="btn btn-primary btn-block">OK</button>
                    </div>
                    <div class="col-xs-6">
                      <button type="button" class="btn btn-warning btn-block" data-dismiss="modal">Anuluj</button>
                    </div>
                  </div>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
