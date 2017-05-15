<div class="box box-info">
	<form class="form-horizontal" id="GeneralSettingsRCAmt" method="post" >
		<input type="hidden" name="Action" value="UpsertRCAmt" />
	  <div class="box-body">
		<div class="form-group">
		  <label class="col-sm-3 control-label"><?php echo $lang['label_min_amt']; ?></label>
		  <div class="col-sm-4">
			<input type="number" class="form-control" id="RA_MinAmt"  name="RA_MinAmt">
		  </div>
		</div>
		<div class="form-group">
		  <label class="col-sm-3 control-label"><?php echo $lang['label_max_amt']; ?></label>
		  <div class="col-sm-4">
			<input type="number" class="form-control" id="RA_MaxAmt"  name="RA_MaxAmt">
		  </div>
		</div>
	  </div>
	  <div class="box-footer">
		
		<div class="form-group">
		  <label class="col-sm-1 control-label">&nbsp;</label>
		   <div class="col-sm-4">
			<div id="RechargeAmtMsg1" ></div>
		  </div>
		  <div class="col-sm-2">
			<button type="submit" id="idBtnUpdateRCAmt"class="btn btn-success pull-right"><?php echo $lang['label_submit']; ?></button>
		  </div>
		</div>
	  </div>
	</form>
</div>