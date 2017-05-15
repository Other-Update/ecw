<?php
	include_once '../../../Resource/RCGateway.php';
	include '../../Header/Header.php';
?>
<style>
table.dataTable  {
	margin-top: -2px !important;
}
</style>
<div class="content-wrapper">	
<section class="content-header">
	<h1> Dashboard </h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-aqua">
				<div class="inner">
					<h3><i class="fa fa-rupee"></i> <span id="OpeningBalance"></span></h3>
					<p>Opening Balance</p>
				</div>
			<div class="icon">
				<i class="fa fa-money"></i>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-yellow">
				<div class="inner">
					<h3><i class="fa fa-rupee"></i> <span id="AvailableBalance"></span></h3>
					<p>Available Balance</p>
				</div>
			<div class="icon">
				<i class="fa fa-times"></i>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-green">
				<div class="inner">
					<h3><i class="fa fa-rupee"></i> <span id="PurchaseBalance"></span></h3>
					<p>Purchase</p>
				</div>
			<div class="icon">
				<i class="fa fa-shopping-cart"></i>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-red">
				<div class="inner">
					<h3><i class="fa  fa-rupee"></i> <span id="SalesBalance"></span></h3>
					<p>Sales </p>
				</div>
			<div class="icon">
				<i class="fa fa-exchange"></i>
				</div>
			</div>
		</div>
	</div>
		
	<div class="row">
	<div class="col-xs-12">
		<div class="box box-success">
		<div class="box-body table-responsive no-padding">
		    <table class="table table-bordered table-striped RecentTransaction" style="width:100%">
				<thead>
					<tr style="color: white; background-color: #00a65a;">
					  <th>S.No</th>
					  <th>Recahrge ID</th>
					  <th>Type</th>
					  <th>Service No.</th>
					  <th>Operator</th>
					  <th>Amount</th>
					  <th>Txn.Id</th>
					  <th>Status</th>
					  <th>Balance</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>1</td>
						<td>546546</td>
						<td>Mobile</td>
						<td>7896541230</td>
						<td>Airtel</td>
						<td>200</td>
						<td>SDF3564565</td>
						<td><span class="label label-success">Success</span></td>
						<td>1200</td>
					</tr>
					<tr>
						<td>2</td>
						<td>546546</td>
						<td>Mobile</td>
						<td>7896541230</td>
						<td>Airtel</td>
						<td>200</td>
						<td>SDF3564565</td>
						<td><span class="label label-success">Success</span></td>
						<td>1200</td>
					</tr>
					<tr>
						<td>3</td>
						<td>546546</td>
						<td>Mobile</td>
						<td>7896541230</td>
						<td>Airtel</td>
						<td>200</td>
						<td>SDF3564565</td>
						<td><span class="label label-primary">Suspend</span></td>
						<td>1200</td>
					</tr>
					<tr>
						<td>4</td>
						<td>546546</td>
						<td>Mobile</td>
						<td>7896541230</td>
						<td>Airtel</td>
						<td>200</td>
						<td>SDF3564565</td>
						<td><span class="label label-warning">Pending</span></td>
						<td>1200</td>
					</tr>
				</tbody>
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
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Common/Common.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Dashboard/Dashboard.js"></script>
<script>
$(function(){
	$('#DataTables_Table_0_filter, #DataTables_Table_0_paginate, #DataTables_Table_0_info').hide();
});
</script>