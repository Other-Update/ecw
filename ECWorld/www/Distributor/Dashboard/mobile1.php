<div class="tab-content cus-inside">
	<div id="prepaid" class="tab-pane fade in active" style="margin-top: -8px;"><br>
		<form method="post" id="idFrmRCPrepaid">
			<input type="hidden" name="Action" value="UpsertPrepaid" />
			<input type="hidden" name="RechargeType" value="Prepaid" />
			<div class="row">
			<div class="col-md-12">
				<label>Prepaid number <span class="PreNumberErr error "></span></label>
				<div class="input-group">
				  <span class="input-group-addon"><i class="fa fa-mobile"></i></span>
				  <input type="text" maxlength="10" pattern="[789][0-9]{9}" maxlength="10" class="RechargeNumber form-control SearchNetwork" data-type="1" name="Pre_mobile" id="Pre_mobile">
				</div>
			</div>
			</div>
			<div class="row">
			<div class="col-md-12">
				<label>Operator <span class="OperatorErr error "></span></label>
				<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-phone"></i></span>
				<select id="idSelectMobilePrepaidOperator" class="form-control SelectedNetwork" name="Pre_operator" >
				</select>
				</div>
			</div>
			</div>
			<div class="row">
			<div class="col-md-12">
				<label>Amount<span class="PreAmountErr error "></span></label>
				<div class="input-group">
				  <span class="input-group-addon"><i class="fa fa-inr"></i></span>
				  <input type="text"  class="form-control" id="rcAmountPrepaid" name="rcAmountPrepaid" >
				</div>
			</div>
			</div>
			<div class="row">
			<div class="col-md-3">&nbsp;</div>
			<div class="col-md-6">
			  <div class="form-group">
			  <label>&nbsp;</label>
			  <input type="submit" id="idBtnRcPrepaid" value="Confirm Recharge" class="form-control btn btn-success btnColor" > 
			  </div>
			</div>
			</div>
			<div class="row">
			<div class="col-md-3">&nbsp;</div>
			<div class="col-md-6" id="errorMsgPrepaid" style="text-align: center;"></div>
			</div>
		</form>
	</div>
	<div id="postpaid" class="tab-pane fade">
	  	<form method="post" id="idFrmRCPostpaid" style="padding-top: 15px;" >
			<input type="hidden" name="Action" value="UpsertPostpaid" />
			<input type="hidden" name="RechargeType" value="Postpaid" />
			<div class="row">
			<div class="col-md-12">
				<label>Postpaid number <span class="PostNumberErr error "></span></label>
				<div class="input-group">
				  <span class="input-group-addon"><i class="fa fa-mobile"></i></span>
				  <input type="text" maxlength="10" class="form-control SearchNetwork " data-type="2" name="Post_Mobile" id="Post_Mobile" >
				</div>
			</div>
			</div>
			<div class="row">
			<div class="col-md-12">
				<label>Mobile operator <span class="PostOperatorErr error "></span></label>
				<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-phone"></i></span>
				<select id="idSelectMobilePostpaidOperator" class="form-control SelectedNetwork"  name="Post_operator">
				</select>
				</div>
			</div>
			</div>
			<div class="row">
			<div class="col-md-12">
				<label>Amount <span class="PostAmountErr error "> </label>
				<div class="input-group">
				  <span class="input-group-addon"><i class="fa fa-inr"></i></span>
				  <input type="text"  class="form-control" id="rcAmountPostpaid" name="rcAmountPostpaid">
				</div>
			</div>
			</div>
			<div class="row">
			<div class="col-md-3">&nbsp;</div>
			<div class="col-md-6">
			  <div class="form-group">
			  <label>&nbsp;</label>
			  <input type="submit" id="idBtnRcPostpaid" value="Confirm Recharge" class="form-control btn btn-success btnColor" > 
			  </div>
			</div>
			</div>
			<div class="row">
			<div class="col-md-3">&nbsp;</div>
			<div class="col-md-12" id="errorMsgPostpaid" style="text-align: center;" ></div>
			</div>
		</form>
	</div>
</div>