<?php
	include_once '../../../Resource/Payment.php';
	include '../../Header/Header.php';
?>
<style>
hr {
	margin-top: 0px;
    margin-bottom: 0px;
	border: 1px solid #3c8dbc;
}
#userBalance,#latestCollection {
	background-color: #dcdcdc;
}
</style>
<div class="content-wrapper">
    <section class="content">
	<div class="row">
        <div class="col-md-12">
			<div class="box">
            <div class="box-header">
				<div class="col-md-3">
					<div class="form-group">
					 <select class="form-control select2" name="userId" id="idSelectUserID" style="width: 100%;">
						</select>
					</div>
				</div>
				
			<a href="#" id="idBtnPayTransferPopup" class="btn btn-success right" data-toggle="modal" data-target="#myModal" 
			style=" width: 10%; float: right; margin-top: 10px;">Pay Collection</a> &nbsp;&nbsp;
			</div>
			</div>
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs" style="width: 88%; float: left;">
              <li class="active" id="userBalance"><a href="#tab_balance" data-toggle="tab"><h4><?php echo $lang['balance']; ?></h4></a></li>
              <li class="DeSelectedTap" id="latestCollection"><a href="#tab_collection" data-toggle="tab"><h4><?php echo $lang['latest_collection']; ?></h4></a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_balance">
					<?php include 'BalanceList.php'; ?>
              </div>
              <div class="tab-pane" id="tab_collection">
					<?php include 'LatestCollection.php'; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  
<?php
	include '../../Header/Footer.php';
	include 'AddCollection.php'; 
?>

<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Common/Common.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Payment/Payment.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Payment/Collection.js"></script>
<script>
$(function(){		
	$(".select2").select2();
});


$(function () {
  /* $('#idTblBalanceList, #idTblCollectionList').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });  */
  }); 

</script>