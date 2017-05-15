<?php
	include_once '../../../Resource/RCGateway.php';
	include '../../Header/Header.php';
?>
<style>
.text-center {
	color:#FFFFFF;
}
.nav-tabs>li.active>a {
	border:none !important;
}
.nav-tabs>li>a {
	border-radius: 0px !important; 
	border: 0px solid transparent !important; 
}

table.dataTable  {
	margin-top: -2px !important;
}
table tr th, tr td {
	font-size: 12px !important; 
}
</style>
<div class="content-wrapper">
<section class="content">
<div class="row">
	<div class="col-md-3 col-sm-6 col-xs-12">
	  <div class="info-box">
		<span class="info-box-icon bg-aqua"><i class="fa fa-rupee"></i></span>
		<div class="info-box-content">
		  <span class="info-box-text">Opening Balance</span>
		  <span class="info-box-number" id="OpeningBalance"></span>
		</div>
	  </div>
	</div>
	<!-- /.col -->
	<div class="col-md-3 col-sm-6 col-xs-12">
	  <div class="info-box">
		<span class="info-box-icon bg-red"><i class="fa fa-rupee"></i></span>
		<div class="info-box-content">
		  <span class="info-box-text">Available Balance</span>
		  <span class="info-box-number" id="AvailableBalance"></span>
		</div>
	  </div>
	</div>
	<!-- /.col -->

	<div class="clearfix visible-sm-block"></div>
	<div class="col-md-3 col-sm-6 col-xs-12">
	  <div class="info-box">
		<span class="info-box-icon bg-yellow"><i class="fa fa-rupee"></i></span>
		<div class="info-box-content">
		  <span class="info-box-text">Purchase</span>
		  <span class="info-box-number" id="PurchaseBalance"></span>
		</div>
	  </div>
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12">
	  <div class="info-box">
		<span class="info-box-icon bg-green"><i class="fa fa-rupee"></i></span>
		<div class="info-box-content">
		  <span class="info-box-text">Sales</span>
		  <span class="info-box-number" id="SalesBalance"></span>
		</div>
	  </div>
	</div>
</div>
<div class="row">
	<div class="col-md-6" style="background: #FFFFFF;height:373px;">
		<div class="tabs-left">
			<ul class="nav nav-tabs" style="background-color: #07c11e;">
			  <li class="active"><a href="#fund" class="active text-center" data-toggle="tab"><span class="fa fa-money"></span>&nbsp; Fund</a></li>
			  <li><a href="#mobile"  data-networkmode="12" class="clsnetworkmode text-center" data-toggle="tab"><span class="fa fa-mobile"></span>&nbsp; Mobile</a></li>
			  <li><a href="#dth" data-networkmode="3" class="clsnetworkmode  text-center" data-toggle="tab"><span class="fa fa-desktop"></span>&nbsp; DTH</a></li>
			  <li><a href="#datacard" data-networkmode="4" class="clsnetworkmode  text-center" data-toggle="tab"><span class="fa fa-signal"></span>&nbsp; Datacard</a></li>
			  <li><a href="#landline" data-networkmode="5" class="clsnetworkmode  text-center" data-toggle="tab"><span class="fa fa-phone"></span>&nbsp; Landline</a></li>
			  <li><a href="#electricity" class="clsnetworkmode  text-center" data-toggle="tab"><span class="fa fa-lightbulb-o"></span>&nbsp; Electricity</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="fund"><!-- Start Fund -->
					<?php include 'Fund.php'; ?>
				</div><!-- End Fund -->
				<div class="tab-pane" id="mobile"><!-- Start Mobile -->
					<div class="nav-tabs-custom" style="background: #FFFFFF;">
					<div class="row">
						<div class="col-md-3">&nbsp;</div>
						<div class="col-md-6 fieldTop" >
						<ul class="nav nav-tabs">
						  <li id="idMobilePrePaid" class="clsmobileoperator active"><a href="#prepaid" data-toggle="tab">Prepaid</a></li>
						  <li id="idMobilePostPaid" class="clsmobileoperator"><a href="#postpaid" data-toggle="tab">Postpaid</a></li>
						</ul>
						</div>
						<div class="col-md-3">&nbsp;</div>
					</div>
					<?php include 'Mobile.php'; ?>
					</div>
				</div><!-- End Mobile -->
				<div class="tab-pane" id="dth"><!-- Start DTH -->
					<?php include 'DTH.php'; ?>
				</div><!-- End DTH -->
				<div class="tab-pane" id="datacard"><!-- Start Datacard -->
					<?php include 'DataCard.php'; ?>
				</div><!-- End Datacard -->
				<div class="tab-pane" id="landline"><!-- Start Landline -->
					<?php include 'Landline.php'; ?>
				</div><!-- End Landline -->
				<div class="tab-pane" id="electricity"><!-- Start EB -->
					<?php include 'Electricity.php'; ?>
				</div><!-- End EB -->		
			</div>
			</div>
    </div>
	<div class="col-md-6" style="background: #dee6ea;height: 373px;"><!-- Start Transaction -->
		<?php 
			include 'Transaction.php';
			include 'SearchPlans.php';
		?>
	</div><!-- End Transaction -->
</div>
</section>
</div>
<?php	
	include '../../Header/Footer.php';
?>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Common/Common.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Dashboard/Dashboard.js"></script>
<script>
$(document).ready(function() {
	$(".select2").select2();
    $("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
        e.preventDefault();
        $(this).siblings('a.active').removeClass("active");
        $(this).addClass("active");
        var index = $(this).index();
        $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
        $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
    });
	$('#RecentTransaction_filter, #RecentTransaction_paginate, #RecentTransaction_info').hide();
}); 
</script>