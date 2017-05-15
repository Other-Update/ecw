<form method="post" id="idFrmRcDatacard" style="padding-top: 15px;">
	<input type="hidden" name="Action" value="UpsertDatacard" />
	<input type="hidden" name="RechargeType" value="Datacard" />
	<div class="col-md-12">
		<label>Account Number <span class="DataAccNoErr error "></span></label>
		<div class="input-group">
		  <span class="input-group-addon"><i class="fa fa-desktop"></i></span>
		  <input type="text" maxlength="10" data-type="4" class="form-control" name="datacardNumber" id="datacardNumber" >
		</div>
	</div>
	<div class="col-md-12 fieldTop">
		<label>Datacard operator<span class="DataOperatorErr error "></span></label>
		<div class="input-group">
		<span class="input-group-addon"><i class="fa fa-phone"></i></span>
		<select id="idSelectDatacardOperator" class="form-control SelectedNetwork" name="datacardOperator">
		</select>
		</div>
	</div>
	<div class="col-md-12 fieldTop">
		<label>Amount<span class="DataAmountErr error "></span></label>
		<div class="input-group">
		  <span class="input-group-addon"><i class="fa fa-inr"></i></span>
		  <input type="text"  class="form-control" name="datacardAmount" id="datacardAmount" >
		</div>
	</div>
	<div class="col-md-12" id="errorMsgDatacard" style="text-align: center;"></div>
	<div class="col-md-12">
	  <div class="form-group">
	  <label>&nbsp;</label>
	  <input type="submit" id="idBtnRcDatacard" value="Proceed" class="form-control btn btn-success" > 
	  </div>
	</div>
	</form>