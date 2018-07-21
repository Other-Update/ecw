<?php
	include_once '../../../Resource/GeneralSettings.php';
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
</style>
<div class="content-wrapper">
    <section class="content">
	<div class="row">
        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
				
              <li class="active DeSelectedTap"><a href="#Feestab_1" data-toggle="tab"><h5><?php echo $lang['label_fees']; ?></h5></a></li>
              <li class="DeSelectedTap" ><a href="#Balance_2" data-toggle="tab"><h5><?php echo $lang['label_user_balance']; ?></h5></a></li>
			  <li class="DeSelectedTap" ><a href="#SMSCost_3" data-toggle="tab"><h5><?php echo $lang['label_sms_cost']; ?></h5></a></li>
			  <li class="DeSelectedTap" ><a href="#RCSetting_4" data-toggle="tab"><h5><?php echo $lang['label_recharge_setting']; ?></h5></a></li>
			  <li class="DeSelectedTap" ><a href="#SMSSetting_5" data-toggle="tab"><h5><?php echo $lang['label_sms_setting']; ?></h5></a></li>
              <li class="DeSelectedTap" ><a href="#RCAmount_6" data-toggle="tab"><h5><?php echo $lang['label_recharge_amt']; ?></h5></a></li>
			  <li class="DeSelectedTap"><a href="#TransferAmt_7" data-toggle="tab"><h5><?php echo $lang['label_transfer_amt']; ?></h5></a></li>
			  <li class="DeSelectedTap" ><a href="#DTHAmount_8" data-toggle="tab"><h5><?php echo $lang['label_dth_amt']; ?></h5></a></li>
			  <li class="DeSelectedTap" ><a href="#PAYAmount_9" data-toggle="tab"><h5><?php echo $lang['label_pay_amt']; ?></h5></a></li>
			  
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="Feestab_1">
					<?php include 'Fees.php'; ?>
              </div>
              <div class="tab-pane " id="Balance_2">
					<?php include 'UserBalance.php'; ?>
              </div>
			  <div class="tab-pane " id="SMSCost_3">
					<?php include 'SMSCost.php'; ?>
              </div>
			  <div class="tab-pane" id="RCSetting_4">
					<?php include 'RechargeSetting.php'; ?>
              </div>
			  <div class="tab-pane" id="SMSSetting_5">
					<?php include 'SMSSetting.php'; ?>
              </div>
			  <div class="tab-pane" id="RCAmount_6">
					<?php include 'RechargeAmount.php'; ?>
              </div>
			  <div class="tab-pane" id="TransferAmt_7">
					<?php include 'TransferAmount.php'; ?>
              </div>
			  <div class="tab-pane" id="DTHAmount_8">
					<?php include 'DTHAmount.php'; ?>
              </div>
			  <div class="tab-pane" id="PAYAmount_9">
					<?php include 'PAYAmount.php'; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  
<?php
	include '../../Header/Footer.php';
?>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/GeneralSettings/GeneralSettings.js"></script>


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