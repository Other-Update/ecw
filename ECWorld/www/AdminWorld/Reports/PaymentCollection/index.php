<?php
	include_once '../../../../Resource/Reports/PayemntCollection.php';
	$report =true;
	include '../../../Header/Header.php';
?>
<style>
.form-group { margin-bottom: 5px !important; }
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
				<div class="col-md-3">
					<div class="form-group">
					 <select class="form-control select2" name="userId" id="idSelectUserID" style="width: 100%;">
					</select>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
					  <input type="number"  class="form-control" id="mobile_no"  name="mobile_no" placeholder="Mobile Number" >
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
					  <input type="text"  class="form-control" id="fromDate"  name="fromDate" value="<?php echo date('Y-m-d'); ?>" readonly >
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
					  <input type="text"  class="form-control" id="toDate"  name="toDate" value="<?php echo date('Y-m-d'); ?>" readonly >
					</div>
				</div>
				
				<div class="col-md-1">
					<div class="form-group">
					  <input type="submit"  class="btn btn-primary" id="Search_PayCollection" value="Search" >
					</div>
				</div>
				<!--div class="col-md-1" style="margin-top: 5px;">
					<div class="form-group"><input type="checkbox" id="subUser" name="subUser"  class="flat-red"> <label> SUB</label> </div>
				</div>
				<div class="col-md-1">
					<div class="form-group">
					  <a href="#"class="btn btn-info" ><i class="fa fa-download"></i></a>
					</div>
				</div-->
            </div>
			<div class="box-body">
              <table id="idPaymentCollection" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><?php echo $lang['date_time']; ?></th>
                  <th><?php echo $lang['user']; ?></th>
                  <th><?php echo $lang['prev_bal']; ?></th>
				  <th><?php echo $lang['paid_amt']; ?></th>
                  <th><?php echo $lang['curr_bal']; ?></th>
                  <th><?php echo $lang['pay_mode']; ?></th>
                  <th><?php echo $lang['remark']; ?></th>
				  <th><?php echo $lang['type']; ?></th>
				  <th></th>
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
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Reports/PaymentCollection/PaymentCollection.js"></script>
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
