<div class="row">
        <div class="col-xs-12">
          <div class="box" style="border-top: none; ">
			<div class="box-header">
			<form role="form" id="idFrmAddMNP" method="post">
				<input type="hidden" id="MnpNeworkID" name="NeworkID" value="0" />
				<input type="hidden" name="Action" value="UpsertMnp" />
				<div class="col-md-3">
				<input type="text" class="form-control SearchNetwork" data-type="0" maxlength="10" name="MNPMobileNo" 
				id="MNPMobileNo" required >
				</div>
				<div class="col-md-3">
				<select class="form-control SelectedNetwork" name="MNPNetwork" id="MNPNetwork" style="width: 100%;" required >
				</select>
				</div>
				<div class="col-md-3" id="othresMNPField" style="display:none">
				<input class="form-control" name="othresMNP" id="othresMNP" type="text">
				</div>
				<div class="col-md-3">
					 <button id="idBtnAddMNP" type="submit" class="btn btn-success"><?php echo $lang['label_submit']; ?></button><span id="errorMsgMnp"></span>
				</div>
			</form>
            </div><hr>
			<div class="box-body">
              <table id="idTblMNPRecharge" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><?php echo $lang['serial_no']; ?></th>
                  <th><?php echo $lang['mobile_no']; ?></th>
                  <th><?php echo $lang['network']; ?></th>
                  <th><?php echo $lang['action']; ?></th>
               
                </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>