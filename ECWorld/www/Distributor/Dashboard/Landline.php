<div class="tab-content cus-inside">
<form method="post" id="idFrmRcLandline"  style="padding-top: 15px;">
	<input type="hidden" name="Action" value="UpsertLandline" />
	<input type="hidden" name="RechargeType" value="Landline" />
	<div class="row">
	<div class="col-md-12">
		<label>Landline Number<span class="LandlineNumberErr error "></span></label>
		<div class="input-group">
		  <span class="input-group-addon"><i class="fa fa-desktop"></i></span>
		  <input type="text" maxlength="11" data-type="5" class="form-control" name="landlineNumber" id="landlineNumber" >
		</div>
	</div>
	</div>
	<div class="row">
	<div class="col-md-12">
		<label>Operator<span class="LandlineOperatorErr error "></span></label>
		<div class="input-group">
		<span class="input-group-addon"><i class="fa fa-phone"></i></span>
		<select id="idSelectLandlineOperator" class="form-control SelectedNetwork" name="landlineOperator" style="width: 100%;">
		</select>
		</div>
	</div>
	</div>
	<div class="row">
	<div class="col-md-12">
		<label>Amount<span class="LandlineAmountErr error "></span></label>
		<div class="input-group">
		  <span class="input-group-addon"><i class="fa fa-inr"></i></span>
		  <input type="text"  class="form-control" name="landlineAmount" id="landlineAmount" >
		</div>
	</div>
	</div>
	
	<div class="row">
	<div class="col-md-3">&nbsp;</div>
	<div class="col-md-6">
	  <div class="form-group">
	  <label>&nbsp;</label>
	   <input type="submit" id="idBtnRcLandline" value="Confirm Recharge" class="form-control btn btn-success btnColor" disabled> 
	  </div>
	</div>
	</div>
	<div class="row">
	<div class="col-md-3">&nbsp;</div>
	<div class="col-md-12" id="errorMsgLandline" style="text-align: center;"></div>
	</div>
</form>
</div>