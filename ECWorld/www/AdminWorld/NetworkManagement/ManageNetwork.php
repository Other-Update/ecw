<?php
include '../../../Resource/ManageNetwork.php';
include '../../Header/Header.php';
?>
<style>
.col-md-2 { margin-bottom: 5px; }
</style>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
     <section class="content-header">
      <h1><?php echo $lang['manage_network_title']; ?></h1>
    </section>
    <section class="content">
		<div class="box box-default">
			<div class="box-body" >
			<form method="post" action="" id="idManageNetworkForm" >
				<input type="hidden" class="form-control" name="Action" value="UpdateManageNetwork">
				<input type="hidden" value="" name="CheckSequence" id="idCheckSequence"/>
				<div class="row" id="idServiceListContent">
					<div class="col-md-2">
						<div class="input-group">
							<span class="input-group-addon"><input type="checkbox"></span>
							<input type="text" class="form-control" value="Airtel" readonly>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
					<div class="form-group">
						<label><?php echo $lang['curr_msg_label']; ?></label>
						<textarea class="form-control" rows="3" name="ServiceProblemMsgCur" id="idServiceProblemMsgCur" placeholder="Enter Current Message..."></textarea>
					</div>
					</div>
					<div class="col-md-6">
					<div class="form-group">
						<label><?php echo $lang['prev_msg_label']; ?></label>
						<textarea class="form-control" rows="3" name="ServiceProblemMsgPrev" id="idServiceProblemMsgPrev" readonly></textarea>
					</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 text-center"><div class="form-group">&nbsp;</div></div>
					<div class="col-md-4 text-center" style="margin-top:10px;">
						<div class="form-group">
							<input type="submit" value="<?php echo $lang['submit_button']; ?>" class="btn btn-success" id="idBtnManageNetwork"/> 
						</div>
					</div>
					<div class="col-md-4 text-center"><div class="form-group">&nbsp;</div></div>
				</div>
			</form>
			</div>
		</div>
    </section>
    <!-- /.content -->
  </div>
<?php
	include '../../Header/Footer.php';
?>

<script src="<?php echo $WebsiteUrl; ?>/Assets/js/ManageNetwork/ManageNetwork_ilaiya.js"></script>