<div class="tab-content cus-inside">
<form method="post" id="idFrmRcDatacard" style="padding-top: 15px;">
	<input type="hidden" name="Action" value="UpsertDatacard" />
	<input type="hidden" name="RechargeType" value="Datacard" />
	<div class="row">
	<div class="col-md-12">
		<label>Account Number <span class="DataAccNoErr error "></span></label>
		<div class="input-group">
		  <span class="input-group-addon"><i class="fa fa-desktop"></i></span>
		  <input type="text" maxlength="10" data-type="4" class="RechargeNumber form-control SearchNetwork" name="datacardNumber" id="datacardNumber" >
		</div>
	</div>
	</div>
	<div class="row">
	<div class="col-md-12">
		<label>Datacard operator<span class="DataOperatorErr error "></span></label>
		<div class="input-group">
		<span class="input-group-addon"><i class="fa fa-phone"></i></span>
		<select id="idSelectDatacardOperator" class="form-control SelectedNetwork" name="datacardOperator">
		</select>
		</div>
	</div>
	</div>
	<div class="row">
	<div class="col-md-12">
		<label>Amount<span class="DataAmountErr error "></span></label>
		<div class="input-group">
		  <span class="input-group-addon"><i class="fa fa-inr"></i></span>
		  <input type="text"  class="form-control" name="datacardAmount" id="datacardAmount" >
		</div>
	</div>
	</div>
	<div class="row">
	<div class="col-md-3">&nbsp;</div>
	<div class="col-md-6">
	  <div class="form-group">
	  <label>&nbsp;</label>
	  <input type="submit" id="idBtnRcDatacard" value="Confirm Recharge" class="form-control btn btn-success btnColor"> 
	  </div>
	</div>
	</div>
	<div class="row">
	<div class="col-md-3">&nbsp;</div>
	<div class="col-md-6" id="errorMsgDatacard" style="text-align: center;"></div>
	</div>
	</form>
</div>