<?php
	include_once '../../../../Resource/Payment.php';
	$report =true;
	include '../../../Header/Header.php';
?>
<style>
.errorMsg { display:none; color:red;}
.form-group { margin-bottom: 5px !important; }
</style>
<div class="content-wrapper">
    <section class="content-header">
      <h1>Payment Report</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">	
          <div class="box">
            <div class="box-header">
				<div class="col-md-3">
					<div class="form-group">
					 <select class="form-control select2" name="userId" id="idSelectUserID" style="width: 100%;">
						</select>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
					  <input type="text"  class="form-control" id="fromDate"  name="fromDate" placeholder="From Date">
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
					  
					  <input type="text"  class="form-control" id="toDate"  name="toDate" placeholder="To Date" >
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
					  <input type="submit"  class="btn btn-primary" id="search"  name="search" value="Search" >
					</div>
				</div>
               <!--div align="right" style="padding:2px">
				<a href="#" id="idBtnPayTransferPopup" class="btn btn-success" data-toggle="modal" data-target="#myModal" >Pay Transfer</a> &nbsp;&nbsp;
				</div-->
            </div>
			<div class="box-body">
              <table id="idPaymentTransfer" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><?php echo $lang['s_no']; ?></th>
                  <th><?php echo $lang['s_no']; ?></th>
                  <th><?php echo $lang['date_time']; ?></th>
                  <th><?php echo $lang['to_user']; ?></th>
                  <th><?php echo $lang['desc']; ?></th>
				  <th><?php echo $lang['amount']; ?></th>
                  <th><?php echo $lang['commission']; ?></th>
                  <th><?php echo $lang['credit']; ?></th>
                  <th><?php echo $lang['debit']; ?></th>
				  <th><?php echo $lang['balance']; ?></th>
                  <th><?php echo $lang['remark']; ?></th>
                  <th><?php echo $lang['payment_mode']; ?></th>
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
	include '../../../Header/Footer.php';
?>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Common/Common.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Reports/Payment/Payment.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Reports/Payment/Transfer.js"></script>
<script>
  $(function () {
    $(".select2").select2();
  });
$('#fromDate, #toDate').datepicker({
	  format: 'dd-mm-yyyy',
      autoclose: true
 });
$(function () {
   $('#idPaymentTransfer').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  }); 
</script>
