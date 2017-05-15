<?php
	include_once '../../../Resource/UserRequest.php';
	include '../../Header/Header.php';
?>
<style>
hr {
	margin-top: 0px;
    margin-bottom: 0px;
	border: 1px solid #3c8dbc;
}
.DeSelectedTap{
	background-color: #dcdcdc;
}
.form-group { margin-bottom: 5px; }
/* .ui-autocomplete {
	z-index: 10001 !important;
}*/
table tr th, tr td {
	font-size: 12px !important; 
}
</style>
<div class="content-wrapper">
    <section class="content">
	<div class="row">
        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active DeSelectedTap">
				<a href="#Pending_1" id="pending" data-toggle="tab"><h5><?php echo $lang['label_pending']; ?></h5></a>
			</li>
			<li class="DeSelectedTap" >
				<a href="#Complaint_2" id="complaint" data-toggle="tab"><h5><?php echo $lang['label_complaint']; ?></h5></a>
			</li>
			<a href="#" class="btn btn-success  pull-right" data-toggle="modal" id="myModalAddRequest" data-target="#myModal" style="margin-top: 10px; margin-right: 60px;">Request</a> 
            </ul>
			<div class="box-header">
			<form action="" method="post">
				<div class="col-md-2">
					<div class="form-group">
					 <select class="form-control select2" name="userId" id="idSelectUserID" style="width: 100%;">
					</select>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
					  <input type="text" maxlength="10"  class="form-control" id="mobile"  name="mobile" placeholder="Mobile Number" >
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
					  <input type="text"  class="form-control " id="requestId"  name="requestId" placeholder="Request ID" >
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
					 <select class="form-control" name="Status" id="StatusVal">
						<option value="1"> Pending </option>
						<option value="3"> Success </option>
						<option value="4"> Failed </option>
						<option value="0"> All </option>
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
				<div class="col-md-1">
					<div class="form-group">
					  <button type="submit"  class="btn btn-primary" id="Search_PayCollection" ><i class="fa fa-search"></i></button>
					</div>
				</div>
			</form>
            </div><hr>
            <div class="tab-content">
              <div class="tab-pane active" data-id="0" id="Pending_1">
					<?php include 'Pending.php'; ?>
              </div>
              <div class="tab-pane " data-id="1" id="Complaint_2">
					<?php include 'Complaint.php'; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  
<?php
	include '../../Header/Footer.php';
	include 'AddRequest.php';
?>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Common/Common.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/UserRequest/UserRequest.js"></script>
<script>
$(function(){		
	$(".select2").select2();
	
	$('.fromDate, .toDate').datepicker({
	  format: 'yyyy-mm-dd',
      autoclose: true
	});
});
</script>