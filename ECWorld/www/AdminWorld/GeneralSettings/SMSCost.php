<div class="box box-info">
	<form class="form-horizontal" id="GeneralSettingsSMSCost" method="post" >
	 <input type="hidden" name="Action" value="UpsertSMSCost" />
	  <div class="box-body">
		<div class="form-group">
		  <label class="col-sm-3 control-label"><?php echo $lang['label_sms_firstcost']; ?></label>
		  <div class="col-sm-4">
			<input type="number" class="form-control" id="SC_FirstSMS_Cost"  name="SC_FirstSMS_Cost">
		  </div>
		  <div class="col-sm-1">
			<input type="checkbox" id="SC_FirstSMS_Enable" name="SC_FirstSMS_Enable" class="flat-red">
		  </div>
		</div>
		<div class="form-group">
		  <label class="col-sm-3 control-label"><?php echo $lang['label_sms_failedrc']; ?></label>
		  <div class="col-sm-4">
			<input type="number" class="form-control" id="SC_FailedRecharge_Cnt"  name="SC_FailedRecharge_Cnt">
		  </div>
		   <div class="col-sm-4">
			<input type="number" class="form-control" id="SC_FailedRecharge_Cost"  name="SC_FailedRecharge_Cost">
		  </div>
		</div>
		<div class="form-group">
		  <label class="col-sm-3 control-label"><?php echo $lang['label_sms_offercost']; ?></label>
		  <div class="col-sm-4">
			<input type="number" class="form-control" id="SC_OfferSMS_Cnt"  name="SC_OfferSMS_Cnt">
		  </div>
		   <div class="col-sm-4">
			<input type="number" class="form-control" id="SC_OfferSMS_Cost"  name="SC_OfferSMS_Cost">
		  </div>
		</div>
		<div class="form-group">
		  <label class="col-sm-3 control-label"><?php echo $lang['label_sms_otp']; ?></label>
		  <div class="col-sm-4">
			<input type="number" class="form-control" id="SC_OTP_Cnt"  name="SC_OTP_Cnt">
		  </div>
		   <div class="col-sm-4">
			<input type="number" class="form-control" id="SC_OTP_Cost"  name="SC_OTP_Cost">
		  </div>
		</div> 
	  </div>
	  <div class="box-footer">
		
		<div class="form-group">
		  <label class="col-sm-2 control-label">&nbsp;</label>
		  <div class="col-sm-6">
			<div id="SMSCostMsg1" ></div>
		  </div>
		  <div class="col-sm-3">
			<button type="submit" id="idBtnUpdateSMSCost" class="btn btn-success pull-right"><?php echo $lang['label_submit']; ?></button>
		  </div>
		</div>
	  </div>
	</form>
</div>