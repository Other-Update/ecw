<div class="box box-info">
	<form class="form-horizontal" id="GeneralSettingsSMSSetting" method="post" >
	 <input type="hidden" name="Action" value="UpsertSMSSetting" />
	  <div class="box-body">
		<div class="form-group">
		  <label class="col-sm-3 control-label">&nbsp;</label>	
		  <div class="col-sm-4">
			<input type="checkbox" id="SS_Success_Msg" name="SS_Success_Msg" class="flat-red"><label><?php echo $lang['label_send_success']; ?></label>
		  </div>
		</div>
		<div class="form-group">
		  <label class="col-sm-3 control-label">&nbsp;</label>	
		  <div class="col-sm-4">
			<input type="checkbox" id="SS_Failed_Msg" name="SS_Failed_Msg"  class="flat-red"><label><?php echo $lang['label_send_failed']; ?></label>
		  </div>
		</div>
		<div class="form-group">
		  <label class="col-sm-3 control-label">&nbsp;</label>	
		  <div class="col-sm-4">
			<input type="checkbox" id="SS_Suspense_Msg" name="SS_Suspense_Msg"  class="flat-red"><label><?php echo $lang['label_send_suspense']; ?></label>
		  </div>
		</div>
		<div class="form-group">
		  <label class="col-sm-3 control-label">&nbsp;</label>	
		  <div class="col-sm-4">
			<input type="checkbox" id="SS_AfterSuspence_Msg" name="SS_AfterSuspence_Msg"  class="flat-red"><label><?php echo $lang['label_send_after_suspense']; ?></label>
		  </div>
		</div>
		<div class="form-group">
		  <label class="col-sm-4 control-label"><?php echo $lang['label_time_delay']; ?></label>
		 <div class="col-sm-1">
			<input type="number" class="form-control" id="SS_Time_Delay"  name="SS_Time_Delay">
		  </div>
		</div> 
	  </div>
	  <div class="box-footer">
		
		<div class="form-group">
		  <label class="col-sm-1 control-label">&nbsp;</label>
		  <div class="col-sm-4">
			<div id="SMSSettingMsg1" ></div>
		  </div>
		  <div class="col-sm-2">
			<button type="submit" id="idBtnUpdateSMSSetting" class="btn btn-success pull-right"><?php echo $lang['label_submit']; ?></button>
		  </div>
		</div>
	  </div>
	</form>
</div>