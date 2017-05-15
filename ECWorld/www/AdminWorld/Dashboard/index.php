<?php
	include_once '../../../Resource/RCGateway.php';
	include '../../Header/Header.php';
?>
<style>
.form-group { margin-bottom: 5px !important; }
</style>
<div class="content-wrapper">
    <section class="content-header">
      <h1>Current Recharge Report</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">	
          <div class="box">
            <div class="box-header">
				
            </div>
			<div class="box-body">
              <table id="idTblRecharge" class="table table-bordered table-striped" style="width:100%">
                <thead>
                <tr>
				  <th>S.No</th>
				  <th>userID-Name</th>
				  <th>Recahrge ID</th>
				  <th>Type</th>
				  <th>DateTime</th>
				  <th>Service No.</th>
				  <th>Operator</th>
				  <th>Amount</th>
				  <th>Txn.Id</th>
				  <th>Status</th>
				  <th>Balance</th>
                </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  
<?php
	include '../../Header/Footer.php';
?>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Common/Common.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Dashboard/Dashboard.js"></script>
<script>
$(function () {
    $(".select2").select2();
	
	$('#fromDate, #toDate').datepicker({
	  format: 'yyyy-mm-dd',
      autoclose: true
	});
	
});
  
</script>
