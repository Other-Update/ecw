<?php
	include_once '../../../Resource/RCGateway.php';
	include '../../Header/Header.php';
?>
<style>
/*.text-center {
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
}*/


  .tabs-left, .tabs-right {
    border-bottom: none;
    padding-top: 2px;
  }
  .tabs-left {
    border-right: 1px solid #ddd;
  }
  .tabs-right {
    border-left: 1px solid #ddd;
  }
  .tabs-left>li, .tabs-right>li {
    float: none;
    margin-bottom: 2px;
  }
  .tabs-left>li {
    margin-right: -1px;
  }
  .tabs-right>li {
    margin-left: -1px;
  }
  .tabs-left>li.active>a,
  .tabs-left>li.active>a:hover,
  .tabs-left>li.active>a:focus {
    border-bottom-color: #ddd;
    border-right-color: transparent;
  }

  .tabs-right>li.active>a,
  .tabs-right>li.active>a:hover,
  .tabs-right>li.active>a:focus {
    border-bottom: 1px solid #ddd;
    border-left-color: transparent;
  }
  .tabs-left>li>a {
    border-radius: 4px 0 0 4px;
    margin-right: 0;
    display:block;
  }
  .tabs-right>li>a {
    border-radius: 0 4px 4px 0;
    margin-right: 0;
  }

  .box-warpper{
  background-color: white;
  box-shadow: 10px 10px 5px #888888;
}
html {
    background: rgb(197, 198, 199) none repeat scroll 0 0;
    font-size: 10px;

}

.tab-content {
    background: rgb(250, 250, 250) none repeat scroll 0 0;
}
.ccform {
    padding: 1vw;
}
.custom-select{
  width: 100%;
      height: 35px;

}
/*.custom-input {
    height: 45px;
}*/
.cus-inside {
    padding: 1vw;
       background: rgb(255,255,255);
     }
.custom-right{
  border: 1px solid rgb(204,204,204);
}
tbody {
    /*background: rgb(114, 141, 0) none repeat scroll 0 0;
    color: white;*/
}

.btn-span {
    border-bottom: 1px solid rgb(225, 225, 225);
    float: right;
    padding: 1em 0;
    width: 100%;
}

.custom-select {
    background: transparent none repeat scroll 0 0;
    border: 1px solid lightgray;
    appearance:none;
    -moz-appearance:none; /* Firefox */
    -webkit-appearance:none; /* Safari and Chrome */
    -moz-appearance: none;
    background: transparent url("./index.png") no-repeat scroll 0 0 / 100% auto;
    border: 1px solid lightgray;


}
.table-btn {
    float: right;
}
.custom-tab-f {
    /* background: rgb(245, 245, 245) none repeat scroll 0 0; */
	background: linear-gradient(to bottom, rgb(224, 224, 224) 0%,rgba(218, 216, 216, 0.4) 100%);
    box-shadow: 0 5px 15px 0 rgb(210, 208, 208);
    padding: 1vw;
}
.btnColor {
	background: #00c0ef;
	border: 1px solid #00c0ef;
}

.btnColor:hover {
	background: #0492b5;
	border: 1px solid #00c0ef;
}
.col-md-12 {
	padding-bottom: 10px;
}

.odd{
	background: #f5f4f4;
} 
.row_success{
	color:#0ee448;
	font-weight: 700;
}
.row_suspense{
	color:#00c0ef;
	font-weight: 700;
}
.row_others{
	color:#E4E400;
	font-weight: 700;
}
.row_failed{
	color:#f96810;
	font-weight: 700;
}
.row_pending{
	color:#0500e4;
	font-weight: 700;	
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
	<div class="col-md-12" style="background-color: white; padding-top: 15px;">
    <div class="row">
        <div class="col-md-6">
            <div class="col-xs-3"> 
				<ul class="nav nav-tabs tabs-left">
				  <?php if(!isRetailer($role)){?>
				  <li class="active"><a href="#fund" class="active" data-toggle="tab"><span class="fa fa-money"></span>&nbsp; Fund</a></li>
				  <?php } ?>
				  <li <?php if(isRetailer($role)) echo 'class="active"';?>><a href="#mobile"  data-networkmode="12" class="clsnetworkmode" data-toggle="tab"><span class="fa fa-mobile"></span>&nbsp; Mobile</a></li>
				  <li><a href="#dth" data-networkmode="3" class="clsnetworkmode " data-toggle="tab"><span class="fa fa-desktop"></span>&nbsp; DTH</a></li>
				  <li><a href="#datacard" data-networkmode="4" class="clsnetworkmode" data-toggle="tab"><span class="fa fa-signal"></span>&nbsp; Datacard</a></li>
				  <li><a href="#landline" data-networkmode="5" class="clsnetworkmode" data-toggle="tab"><span class="fa fa-phone"></span>&nbsp; Landline</a></li>
				  <li><a href="#electricity" class="clsnetworkmode" data-toggle="tab"><span class="fa fa-lightbulb-o"></span>&nbsp; Electricity</a></li>
				</ul>
			</div>
			<div class="col-xs-9">
				<div class="tab-content custom-tab-f clsRechargeContainer" data-userrole="<?php echo $role->RoleID;?>">
				  <?php if(!isRetailer($role)){?>
					<div class="tab-pane active" id="fund">
						<ul class="nav nav-tabs cus-inside-tab">
						  <li class="active"><a href="#" data-toggle="tab">Fund Transfer</a></li>
						</ul>
						<?php include 'Fund.php'; ?>
					</div>
					<?php } ?>
					<div class="tab-pane <?php if(isRetailer($role)) echo 'active';?>" id="mobile">
						<ul class="nav nav-tabs cus-inside-tab">
						  <li id="idMobilePrePaid" class="clsmobileoperator active"><a href="#prepaid" data-toggle="tab">Prepaid</a></li>
						  <li id="idMobilePostPaid" class="clsmobileoperator"><a href="#postpaid" data-toggle="tab">Postpaid</a></li>
						</ul>
						<?php include 'mobile1.php'; ?>
					
					</div>
				  	<div class="tab-pane" id="dth"><!-- Start DTH -->
				  		<ul class="nav nav-tabs cus-inside-tab">
						  <li class="active"><a href="#" data-toggle="tab">DTH Recharge</a></li>
						</ul>
						<?php include 'DTH.php'; ?>
					</div><!-- End DTH -->

					<div class="tab-pane" id="datacard"><!-- Start Datacard -->
						<ul class="nav nav-tabs cus-inside-tab">
						  <li class="active"><a href="#" data-toggle="tab">Datacard Recharge</a></li>
						</ul>
						<?php include 'DataCard.php'; ?>
					</div><!-- End Datacard -->

					<div class="tab-pane" id="landline"><!-- Start Landline -->
						<ul class="nav nav-tabs cus-inside-tab">
						  <li class="active"><a href="#" data-toggle="tab">Landline Recharge</a></li>
						</ul>
						<?php include 'Landline.php'; ?>
					</div><!-- End Landline -->
					<div class="tab-pane" id="electricity"><!-- Start EB -->
						<ul class="nav nav-tabs cus-inside-tab">
						  <li class="active"><a href="#" data-toggle="tab">TNEB	</a></li>
						</ul>
						<?php include 'Electricity.php'; ?>
					</div><!-- End EB -->		
				</div>
			</div>
		</div>
		<div class="col-md-6 col-sm-6 col-xs-12 custom-right">
			<?php 
				include 'Transaction.php';
				include 'SearchPlans.php';
			?>
		</div>
    </div>
	</div>
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