<div class="tab-content cus-inside">
<form method="post" id="idFrmRcDTH" style="padding-top: 15px;">
	<input type="hidden" name="Action" value="UpsertDTH" />
	<input type="hidden" name="RechargeType" value="DTH" />
	<div class="row">
	<div class="col-md-12">
		<label>Account Number <span class="accountNoErr error "></span> </label>
		<div class="input-group">
		  <span class="input-group-addon"><i class="fa fa-desktop"></i></span>
		  <input type="text" maxlength="12" data-type="3" name="dthNumber" id="dthNumber" class="RechargeNumber form-control SearchNetwork" >
		</div>
	</div>
	</div>
	<div class="row">
	<div class="col-md-12">
		<label>DTH operator<span class="operatorDthErr error "></span></label>
		<div class="input-group">
		<span class="input-group-addon"><i class="fa fa-phone"></i></span>
		<select id="idSelectDthOperator" name="dthOperator" class="form-control SelectedNetwork" >
		</select>
		</div>
	</div>
	</div>
	<div class="row">
	<div class="col-md-12">
		<label>Amount<span class="DthAmountErr error "></span></label>
		<div class="input-group">
		  <span class="input-group-addon"><i class="fa fa-inr"></i></span>
		  <input type="text"  class="form-control" name="dthAmount" id="dthAmount" >
		</div>
	</div>
	</div>
	
	<div class="row">
	<div class="col-md-3">&nbsp;</div>
	<div class="col-md-6">
	  <div class="form-group">
	  <label>&nbsp;</label>
	  <input type="submit" id="idBtnRcDTH" value="Confirm Recharge" class="form-control btn btn-success btnColor" > 
	  </div>
	</div>
	</div>
	<div class="row">
	<div class="col-md-3">&nbsp;</div>
	<div class="col-md-12" id="errorMsgDth" style="text-align: center;"></div>
	</div>
	</form>
</div>