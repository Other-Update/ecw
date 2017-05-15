<?php
	include_once '../../../Resource/RCGateway.php';
	include '../../Header/Header.php';
?>
<link rel="stylesheet" href="TabStyle.css">
<div class="content-wrapper">
	<div class="container">
		<div class="row">
			<div class="col-lg-5 col-md-5 col-sm-8 col-xs-9 bhoechie-tab-container clsRechargeContainer">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 bhoechie-tab-menu">
				  <div class="list-group">
					<a href="#" class="list-group-item active text-center">
					  <h4 class="fa fa-money"></h4>&nbsp; Fund
					</a>
					<a href="#" data-networkmode="12" class="clsnetworkmode list-group-item text-center">
					  <h4 class="fa fa-mobile"></h4>&nbsp; Mobile
					</a>
					<a href="#" data-networkmode="3" class="clsnetworkmode list-group-item text-center">
					  <h4 class="fa fa-desktop"></h4>&nbsp; DTH
					</a>
					<a href="#" data-networkmode="4" class="clsnetworkmode list-group-item text-center">
					  <h4 class="fa fa-signal"></h4>&nbsp; Datacard
					</a>
					<a href="#" data-networkmode="5" class="clsnetworkmode list-group-item text-center">
					  <h4 class="fa fa-phone"></h4>&nbsp; Landline
					</a>
					<a href="#" class="list-group-item text-center">
					  <h4 class="fa fa-lightbulb-o"></h4>&nbsp; Electricity 
					</a>
				  </div>
				</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 bhoechie-tab">
					
					<!-- Fund section -->
					<div class="bhoechie-tab-content active">
						<?php include 'Fund.php'; ?>
					</div>
					
					<!-- Mobile section -->
					<div class="bhoechie-tab-content">
					  <div class="nav-tabs-custom">
						<ul class="nav nav-tabs pull-left">
						  <li id="idMobilePrePaid" class="clsmobileoperator active"><a href="#prepaid" data-toggle="tab">Prepaid</a></li>
						  <li id="idMobilePostPaid" class="clsmobileoperator"><a href="#postpaid" data-toggle="tab">Postpaid</a></li>
						</ul>
						<?php include 'Mobile.php'; ?>
					  </div>
					</div>
					
					<!-- DTH section -->
					<div class="bhoechie-tab-content">
						<?php include 'DTH.php'; ?>
					</div>
		
					<!-- Data Card search -->
					<div class="bhoechie-tab-content">
						<?php include 'DataCard.php'; ?>
					</div>
					
					<!-- Landline search -->
					<div class="bhoechie-tab-content">
						<?php include 'Landline.php'; ?>
					</div>
					
					<!-- Electricity search -->
					<div class="bhoechie-tab-content">
						<div class="nav-tabs-custom">
						<ul class="nav nav-tabs pull-left">
						  <li  class="active"><a href="#TNEB" data-toggle="tab">TNEB</a></li>
						</ul>
						<?php include 'Electricity.php'; ?>
					  </div>
					</div>
				</div>
			</div>
			<div class="col-lg-5 col-md-5 col-sm-8 col-xs-9 bhoechie-tab-container">
			
				<?php 
					include 'Transaction.php';
					include 'SearchPlans.php';
				?>
			
			</div>
	  </div>
	</div>
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
}); 
</script>