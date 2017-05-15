<?php
	include_once '../../../Resource/SMSGateway.php';
	include '../../Header/Header.php';
?>
<style>
hr {
	margin-top: 0px;
    margin-bottom: 0px;
	border: 1px solid #3c8dbc;
}
#UserTab,#GeneralTab, #ApiTab {
	background-color: #dcdcdc;
}
</style>
<div class="content-wrapper">
    <section class="content">
	<div class="row">
        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
				
              <li class="active" id="UserTab"><a href="#tab_1" data-toggle="tab"><h5><?php echo $lang['user_gateway_title']; ?></h5></a></li>
              <li class="DeSelectedTap" id="GeneralTab"><a href="#tab_2" data-toggle="tab"><h5><?php echo $lang['general_gateway_title']; ?></h5></a></li>
              <li class="DeSelectedTap" id="ApiTab"><a href="#tab_3" data-toggle="tab"><h5><?php echo $lang['api_gateway_title']; ?></h5></a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
					<?php include 'SMSGatewayUser.php'; ?>
              </div>
              <div class="tab-pane" id="tab_2">
					<?php include 'SMSGatewayGeneral.php'; ?>
              </div>
              <div class="tab-pane" id="tab_3">
					<?php include 'SMSGatewayAPI.php'; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  
<?php
	include '../../Header/Footer.php';
	//include 'UpdateUserAmount.php'; 
	//include 'AddGateway.php'; 
	//include 'UpdateRCCode.php';
	//include 'UpdateAPI.php';
	//include 'AssignUsers.php';
?>

<script src="<?php //echo $WebsiteUrl; ?>/Assets/js/Common/Common.js"></script>
<script src="<?php //echo $WebsiteUrl; ?>/Assets/js/RechargeGateway/RechargeGateway.js"></script>
<script>
$(function(){		
	$(".select2").select2();
});
$(function () {
    $('input[type="checkbox"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });
}); 

</script>