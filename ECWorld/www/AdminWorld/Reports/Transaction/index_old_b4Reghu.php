<?php
	include_once '../../../../Resource/Reports/Transaction.php';
	$report =true;
	include '../../../Header/Header.php';
?>
<style>
.form-group { margin-bottom: 5px !important; }
.col-md-1, .col-md-2 { padding-right: 3px !important;  padding-left: 3px !important; }
/* table.dataTable thead .sorting:after,
table.dataTable thead .sorting_asc:after,
table.dataTable thead .sorting_desc:after {
    display: none;
}*/
table tr th, tr td {
	font-size: 12px !important; 
}
</style>
<div class="content-wrapper">
    <section class="content-header">
      <h1><?php echo $lang['page_title']; ?></h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">	
          <div class="box">
            <div class="box-header">
				<div class="col-md-2">
					<div class="form-group">
					 <select class="form-control select2" name="userId" id="idSelectUserID" style="width: 100%;">
					</select>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
					  <input type="number"  class="form-control" id="mobile_no"  name="mobile_no" placeholder="User mobile no" >
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
					  <input type="text"  class="form-control " id="requestId"  name="requestId" placeholder="Request ID" >
					</div>
				</div>
				<div class="col-md-2" >
					<div class="form-group">
					 <select class="form-control SelectedNetwork" name="network" id="network">
					</select>
					</div>
				</div>
				<div class="col-md-2" style="width: 12%;">
					<div class="form-group">
					  <input type="text"  class="form-control fromDate" id="fromDate"  name="fromDate" placeholder="From Date" value="<?php echo date('Y-m-d'); ?>" readonly >
					</div>
				</div>
				<div class="col-md-2" style="width: 12%;" >
					<div class="form-group">
					  <input type="text"  class="form-control toDate" id="toDate"  name="toDate" placeholder="To Date" value="<?php echo date('Y-m-d'); ?>" readonly >
					</div>
				</div>
				<div class="col-md-1" style="width: 4%;" >
					<div class="form-group">
					  <button type="submit"  class="btn btn-primary" id="Search_PayCollection" ><i class="fa fa-search"></i></button>
					</div>
				</div>
				<!--div class="col-md-1" style="width: 4%;" >
					<div class="form-group">
					  <a href="#"class="btn btn-info" ><i class="fa fa-download"></i></a>
					</div>
				</div-->
            </div>
			<div class="box-body">
			<div class="table-responsive">
              <table id="idTblTransaction" class="table table-bordered table-striped" style="width:100%">
                <thead>
                <tr>
				  <th><?php echo $lang['userid']; ?></th>	
				  <th><?php echo $lang['requestid']; ?></th>	
                  <th><?php echo $lang['date_time']; ?></th>
                  <th><?php echo $lang['resp_id']; ?></th>
                  <th><?php echo $lang['transId']; ?></th>
				  <th><?php echo $lang['mobile']; ?></th>
				  <th><?php echo $lang['amount']; ?></th>
                  <th><?php echo $lang['network']; ?></th>
                  <th><?php echo $lang['description']; ?></th>
                  <th><?php echo $lang['status']; ?></th>
				  <th><?php echo $lang['crdit']; ?></th>
                  <th><?php echo $lang['debit']; ?></th>
                  <th><?php echo $lang['balance']; ?></th>	
				  <th><?php echo $lang['type']; ?></th>
				  <th></th>
				  <th></th>
                </tr>
                </thead>
              </table>
			</div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  
<?php
	include '../../../Header/Footer.php';
?>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Common/Common.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Reports/Transaction/Transaction.js"></script>
<script>
$(function () {
    $(".select2").select2();
	
	$('#fromDate, #toDate').datepicker({
	  format: 'yyyy-mm-dd',
      autoclose: true
	});
	
	$('input[type="checkbox"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

	
});
  
</script>
