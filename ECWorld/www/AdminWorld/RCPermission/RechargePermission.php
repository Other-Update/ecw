<?php
include_once '../../../Resource/RechargePermission.php';
include '../../Header/Header.php';
?>
<style>
.form-group { margin-bottom: 5px; }
.form-control { height: 30px; }
#form-permission{ height: 340px;overflow-x: hidden;overflow-y: auto; }
.header_label { background-color:#00a65a; color:#ffffff;margin-bottom: 1px;}
.otp-enable { margin-top:5px;}
#UserTab,#GeneralTab {
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
				
              <li class="active" id="UserTab"><a href="#idUserTabContent" data-toggle="tab"><h4><?php echo $lang['user_recharge_title']; ?></h4></a></li>
              <li id="GeneralTab"><a href="#idGeneralTabContent" data-toggle="tab"><h4><?php echo $lang['general_recharge_title']; ?></h4></a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="idUserTabContent">
					<?php include 'UserRechargePermission.php'; ?>
              </div>
              <div class="tab-pane" id="idGeneralTabContent">
					<?php include 'GeneralRCPermission.php'; ?>
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
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/ServicePermission/ServicePermission_ilaiya.js"></script>

<script>
	$(function(){		
		$(".select2").select2();
	});
</script>