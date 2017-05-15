<div class="box box-info">
	<form class="form-horizontal" id="GeneralSettingsUserBalance" method="post" >
	 <input type="hidden" name="Action" value="UpsertUserBalance" />
	  <div class="box-body">
		<div class="form-group">
		  <label class="col-sm-2 control-label"><?php echo $lang['label_user_dist']; ?></label>
		  <div class="col-sm-1">
			<input type="checkbox" name="UB_Distributor_AlertEnable" id="UB_Distributor_AlertEnable" class="flat-red" >
		  </div>
		  <div class="col-sm-4">
			<input type="number" class="form-control" id="UB_Distributor_MinAmt"  name="UB_Distributor_MinAmt">
		  </div>
		   <div class="col-sm-4">
			<input type="number" class="form-control" id="UB_Distributor_MaxAmt"  name="UB_Distributor_MaxAmt">
		  </div>
		</div>
		<div class="form-group">
		  <label class="col-sm-2 control-label"><?php echo $lang['label_user_subdist']; ?></label>
		    <div class="col-sm-1">
			<input type="checkbox" id="UB_SubDistributor_AlertEnable" name="UB_SubDistributor_AlertEnable" class="flat-red">
		  </div>
		  <div class="col-sm-4">
			<input type="number" class="form-control" id="UB_SubDistributor_MinAmt"  name="UB_SubDistributor_MinAmt">
		  </div>
		   <div class="col-sm-4">
			<input type="number" class="form-control" id="UB_SubDistributor_MaxAmt"  name="UB_SubDistributor_MaxAmt">
		  </div>
		</div>
		<div class="form-group">
		  <label class="col-sm-2 control-label"><?php echo $lang['label_user_retailar']; ?></label>
		   <div class="col-sm-1">
			<input type="checkbox" id="UB_Retailer_AlertEnable" name="UB_Retailer_AlertEnable" class="flat-red">
		  </div>
		  <div class="col-sm-4">
			<input type="number" class="form-control" id="UB_Retailer_MinAmt"  name="UB_Retailer_MinAmt">
		  </div>
		   <div class="col-sm-4">
			<input type="number" class="form-control" id="UB_Retailer_MaxAmt"  name="UB_Retailer_MaxAmt">
		  </div>
		</div>
	  </div>
	  <div class="box-footer">
		
		<div class="form-group">
		  <label class="col-sm-2 control-label">&nbsp;</label>
		  <div class="col-sm-6">
			<div id="UserBalanceMsg1" ></div>
		  </div>
		  <div class="col-sm-3">
			<button type="submit" id="idBtnUpdateUserBalance" class="btn btn-success pull-right"><?php echo $lang['label_submit']; ?></button>
		  </div>
		</div>
	  </div>
	</form>
</div>