<div class="box box-info">
	<form class="form-horizontal" id="GeneralSettingsFees" method="post" >
		<input type="hidden" name="Action" value="UpsertFees" />
	  <div class="box-body">
		<div class="form-group">
		  <label class="col-sm-3 control-label"><?php echo $lang['label_fees_dist']; ?></label>
		  <div class="col-sm-6">
			<input type="number" class="form-control" id="DistributorFees"  name="DistributorFees">
		  </div>
		</div>
		<div class="form-group">
		  <label class="col-sm-3 control-label"><?php echo $lang['label_fees_subdist']; ?></label>
		  <div class="col-sm-6">
			<input type="number" class="form-control" id="SubDistributorFees"  name="SubDistributorFees">
		  </div>
		</div>
		<div class="form-group">
		  <label class="col-sm-3 control-label"><?php echo $lang['label_fees_retailar']; ?></label>
		  <div class="col-sm-6">
			<input type="number" class="form-control" id="RetailerFees"  name="RetailerFees">
		  </div>
		</div>
	  </div>
	  <div class="box-footer">
		
		<div class="form-group">
		  <label class="col-sm-3 control-label">&nbsp;</label>
		  <div class="col-sm-4">
			<div id="FeesMsg1" ></div>
		  </div>
		  <div class="col-sm-2">
			<button type="submit" id="idBtnAddFees" class="btn btn-success pull-right"><?php echo $lang['label_submit']; ?></button>
		  </div>
		</div>
	  </div>
	</form>
</div>