<?php
	include_once '../../../Resource/RCGateway.php';
	include '../../Header/Header.php';
  include '../Connect/config.php';
  $query = "SELECT u.UserID,u.DisplayID as UserDisplayID,u.Name as UserName,req.DisplayID as RechargeID, req.RequestType, r.ReachargeNo, r.NetworkProviderName, r.Amount, r.RcResOpTransID AS Txn_Id, req.Status, trans.ClosingBalance as Balance, r.CreatedDate, req.ReqDateTime   FROM t_recharge as r 
        left join m_users as u on 
          r.CreatedBy=u.UserID
        left join t_request as req on
            r.RequestID = req.RequestID
        left join t_transaction as trans on
          r.RequestID = trans.RequestID  WHERE u.Active=1 AND  (req.Status=1 OR req.Status=2) AND req.CreatedDate>='2018-08-01'
          GROUP BY trans.RequestID ORDER BY r.RechargeID DESC " ;
	// CreatedDate>='2018-08-01' is to fix many pending and suspense records on local server slow issue
    $res = $connect->query($query);
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
                <tbody>
                  <?php $i = 1; while($row = $res->fetch()){ ?>
                  <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row['UserDisplayID'].'-'.$row['UserName']; ?></td>
                    <td><?php echo $row['RechargeID']; ?></td>
                    <td><?php echo $row['RequestType']; ?></td>
                    <td><?php echo $row['ReqDateTime']; ?></td>
                    <td><?php echo $row['ReachargeNo']; ?></td>
                    <td><?php echo $row['NetworkProviderName']; ?></td>
                    <td><?php echo $row['Amount']; ?></td>
                    <td><?php echo $row['Txn_Id']; ?></td>
                    <td><?php echo getStatus($row['Status']); ?></td>
                    <td><?php echo $row['Balance']; ?></td>
                  </tr>
                  <?php $i++; } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  
<?php
 function getStatus($status){
  if($status == 1){
    return 'Pending';
  } else if($status == 2){
    return 'Suspense';
  } else if($status == 3){
    return 'Success';
  } else if($status == 4){
    return 'Failed';
  } else {
    return 'Other';
  }
}
	include '../../Header/Footer.php';
?>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Common/Common.js"></script>
<!-- <script src="<?php echo $WebsiteUrl; ?>/Assets/js/Dashboard/Dashboard.js"></script> -->
<script>
$(function () {
    $(".select2").select2();
	
	$('#fromDate, #toDate').datepicker({
	  format: 'yyyy-mm-dd',
      autoclose: true
	});

  $('#idTblRecharge').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
	
});
  
</script>
