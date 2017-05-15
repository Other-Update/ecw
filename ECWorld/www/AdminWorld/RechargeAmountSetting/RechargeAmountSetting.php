<?php
	include_once '../../../Resource/RcAmountSetting.php';
	include '../../Header/Header.php';
?>
<style>
.errorMsg { display:none; color:red;}
</style>
<div class="content-wrapper">
    <section class="content-header">
      <h1><?php echo $lang['page_title']; ?></h1>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-xs-12">	
          <div class="box">
			<div class="box-body">
              <table id="idTblRCAmtSetting" class="table table-bordered table-striped">
                <thead>
                <tr>
				  <th><?php echo $lang['serial_no']; ?></th>
                  <th><?php echo $lang['service_name_label']; ?></th>
                  <th><?php echo $lang['service_type_name_label']; ?></th>
				  <th><?php echo $lang['recharge_label']; ?></th>
                  <th><?php echo $lang['topup_label']; ?></th>
				  <th><?php echo $lang['invalid_amt']; ?></th>
				  <th>ACtion</th>
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
	include '../../Header/Footer.php';
?>
<?php include 'EditAmount.php'; ?>	
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/RcAmountSetting/RcAmountSetting.js"></script>

<script>
$(function () {
	$(".select2").select2();
});
</script>