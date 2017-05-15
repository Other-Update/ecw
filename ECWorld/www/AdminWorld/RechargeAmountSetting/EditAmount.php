<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-info">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body" style="background-color: #ffffff !important;">
			<form role="form" id="idFrmAddAmount" method="post">
				<input type="hidden" id="RcAmountID" name="RcAmountID" value="0" />
				<input type="hidden" name="Action" value="Upsert" />
              <div class="box-body">
                <div class="form-group">
                  <label style="color:#000"><?php echo $lang['service_name_label']; ?></label>
                  <input type="text"  class="form-control" id="Name"  name="Name" readonly >
				  <input type="hidden"  class="form-control" id="ServiceID"  name="ServiceID" readonly >
                </div>
				<div class="form-group">
					<label style="color:#000"><?php echo $lang['service_type_name_label']; ?></label>
					<select class="form-control select2" name="RechargeTypeID" id="DefaultType" style="width:100%;">
					</select>
				</div>
                <div class="form-group">
                  <label style="color:#000"><?php echo $lang['recharge_label']; ?></label>
                  <input type="text"  class="form-control" id="RCDenomination" name="RCDenomination" >
                </div>
				<div class="form-group">
				  <label style="color:#000"><?php echo $lang['topup_label']; ?></label> 
                  <input type="text"  class="form-control" id="TPDenomination" name="TPDenomination">
                </div>
				<div class="form-group">
				  <label style="color:#000"><?php echo $lang['invalid_amt']; ?></label> 
                  <input type="text"  class="form-control" id="InvalidAmount" name="InvalidAmount">
                </div>
              </div>

              <div class="box-footer">
                <button id="idBtnAddAmount" type="submit" class="btn btn-success">Submit</button>
              </div>
				<div id="errorMsg"></div>
            </form>
			</div>
		</div>
	</div>
</div>