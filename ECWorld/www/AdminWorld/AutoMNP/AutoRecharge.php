<div class="row">
	<div class="col-xs-12">
	  <div class="box" style="border-top: none; ">
		<div class="box-header">
		<form role="form" id="idFrmAddAuto" method="post">
			<input type="hidden"  id="AutoNeworkID" name="NeworkID" value="0" />
			<input type="hidden" name="Action" value="UpsertAuto" />
			<div class="col-md-3">
			<input type="text" class="form-control SearchNetwork" data-type="1" maxlength="6" name="AutoMobileNo" 
			id="AutoMobileNo" required >
			</div>
			<div class="col-md-3">
			<select class="form-control SelectedNetwork" name="AutoNetwork" id="AutoNetwork" style="width: 100%;" required >
			</select>
			</div>
			<div class="col-md-3" id="othresAutoField" style="display:none">
			<input class="form-control" name="othresAuto" id="othresAuto"  type="text">
			</div>
			<div class="col-md-3">
				 <button id="idBtnAddAuto" type="submit" class="btn btn-success"><?php echo $lang['label_submit']; ?></button>
				 <span id="errorMsgAuto"></span>
			</div>
		</form>
		</div><hr>
		<div class="box-body">
		  <table id="idTblAutoRecharge" class="table table-bordered table-striped">
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