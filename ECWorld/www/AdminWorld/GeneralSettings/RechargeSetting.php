<div class="box box-info">
	<form class="form-horizontal" id="GeneralSettingsRCSetting" method="post" >
	 <input type="hidden" name="Action" value="UpsertRCSetting" />
	  <div class="box-body">
		<div class="form-group">
		  <label class="col-sm-3 control-label"><?php echo $lang['label_rs_same']; ?></label>
		  <div class="col-sm-4">
			<input type="number" class="form-control" id="RS_SmNo_SmAmt_Delay"  name="RS_SmNo_SmAmt_Delay">
		  </div>
		</div>
		<div class="form-group">
		  <label class="col-sm-3 control-label"><?php echo $lang['label_sms_same_diff']; ?></label>
		  <div class="col-sm-4">
			<input type="number" class="form-control" id="RS_SmNo_DiffAmt_Delay"  name="RS_SmNo_DiffAmt_Delay">
		  </div>
		</div>
		<div class="form-group">
		  <label class="col-sm-3 control-label"><?php echo $lang['label_rs_mnp']; ?></label>
		  <div class="col-sm-1">
			<input type="checkbox" class="flat-red" id="RS_MNP_AutoRC_Enable" name="RS_MNP_AutoRC_Enable" >
		  </div>
		</div>
		<div class="form-group">
		  <label class="col-sm-3 control-label"><?php echo $lang['label_rs_otp']; ?></label>
		 <div class="col-sm-1">
			<input type="checkbox" class="flat-red" id="RS_OTPRC_Enable" name="RS_OTPRC_Enable">
		  </div>
		</div> 
	  </div>
	  <div class="box-footer">
		<div class="form-group">
		  <label class="col-sm-1 control-label">&nbsp;</label>
		  <div class="col-sm-4">
			<div id="RCSettingMsg1" ></div>
		  </div>
		  <div class="col-sm-2">
			<button type="submit" id="idBtnUpdateRCSetting" class="btn btn-success pull-right"><?php echo $lang['label_submit']; ?></button>
		  </div>
		</div>
	  </div>
	</form>
</div>