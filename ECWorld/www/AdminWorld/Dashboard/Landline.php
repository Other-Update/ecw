<form method="post" id="idFrmRcLandline"  style="padding-top: 15px;">
	<input type="hidden" name="Action" value="UpsertLandline" />
	<input type="hidden" name="RechargeType" value="Landline" />
	<div class="col-md-12">
		<label>Landline Number<span class="LandlineNumberErr error "></span></label>
		<div class="input-group">
		  <span class="input-group-addon"><i class="fa fa-desktop"></i></span>
		  <input type="text" maxlength="11" data-type="5" class="form-control" name="landlineNumber" id="landlineNumber" >
		</div>
	</div>
	<div class="col-md-12 fieldTop">
		<label>Operator<span class="LandlineOperatorErr error "></span></label>
		<div class="input-group">
		<span class="input-group-addon"><i class="fa fa-phone"></i></span>
		<select id="idSelectLandlineOperator" class="form-control SelectedNetwork" name="landlineOperator" style="width: 100%;">
		</select>
		</div>
	</div>
	<div class="col-md-12 fieldTop">
		<label>Amount<span class="LandlineAmountErr error "></span></label>
		<div class="input-group">
		  <span class="input-group-addon"><i class="fa fa-inr"></i></span>
		  <input type="text"  class="form-control" name="landlineAmount" id="landlineAmount" >
		</div>
	</div>
	<div class="col-md-12" id="errorMsgLandline" style="text-align: center;"></div>
	<div class="col-md-12">
	  <div class="form-group">
	  <label>&nbsp;</label>
	   <input type="submit" id="idBtnRcLandline" value="Proceed" class="form-control btn btn-success" > 
	  </div>
	</div>
	</form>