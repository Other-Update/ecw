<?php
	include '../../Header/Header.php';
?>
<div class="content-wrapper">
  <section class="content-header" style="padding-top: 0px;">
    <div class="service_header" style="width:80%; float:left;"><h3>Outgoing Request</h3></div>
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
        <div class="col-md-3">
          <div class="form-group">
            <input type="text"  class="form-control" id="message_like"  name="message_like" placeholder="Message" >
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <input type="text"  class="form-control " id="api_name"  name="api_name" placeholder="API Name" >
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
            <button type="submit"  class="btn btn-primary" id="Search_Outcoming" ><i class="fa fa-search"></i></button>
          </div>
        </div>
            </div>
          <div class="box-body">
            <div class="table-responsive">
            <table id="idTblOutgoingList" class="table table-bordered table-striped" style="width:100%;">
              <thead>
                <tr>
                  <th>S.no</th>
                  <th>UserId</th>
                  <th>RequestID</th>
                  <th>Mobile</th>
                  <th>Request Time</th>
                  <th>Message</th>
                  <th>API Details</th>
                  <th>Status</th>
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
</div
<?php
	include '../../Header/Footer.php';
?>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Outgoing/outgoing.js"></script>
<script>
$(function () {
  $(".select2").select2();
  $('#fromDate, #toDate').datepicker({
    format: 'yyyy-mm-dd',
      autoclose: true
  });
});
  
</script>