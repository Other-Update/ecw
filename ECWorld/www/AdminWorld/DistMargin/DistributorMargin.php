<?php
	include '../../../Resource/DistMargin.php';
	include '../../Header/Header.php';
?>
<style>
hr {
	margin-top: 0px;
    margin-bottom: 0px;
	border: 1px solid #3c8dbc;
}
.clsTabHeader {
	background-color: #dcdcdc;
}
table{
	background-color: #dcdcdc;
	color:#000;
}
label{
	color:#000;
}
.clsPaddingSm2{
	padding-right: 2px;
    padding-left: 2px;
}
/* Data table - Opening balance*/
#idTblDistributorMarginUsers td:nth-child(6), td:nth-child(7), td:nth-child(9) {
    text-align: right;
}
</style>
<div class="content-wrapper">
<section class="content-header">
  <h1><?php echo $lang['title_Dist_Margin']; ?></h1>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
	<div class="col-xs-12">	
	  <div class="box">
		<div class="box-body">
			<div class="row">
				<label class="col-md-9">&nbsp;</label>
				<button id="idBtnSelectUsers" type="button" class="col-md-2 btn btn-success">Select</button>
			</div>
			<br/>
		  <table id="idTblDistributorMarginUsers" class="table table-bordered table-striped">
			<thead>
			<tr>
				<th>1</th>
				<th>2</th>
				<th>3</th>
				<th>4</th>
				<th>5</th>
				<th>6</th>
				<th>7</th>
				<th>8</th>
				<th>9</th>
				<th>10</th>
				<th>11</th>
				<th>12</th>
				<th>12</th>
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
	include 'EditMargin.php';
?>

<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Common/Common.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/DistMargin/DistMargin.js"></script>
<!--<script src="<?php echo $WebsiteUrl; ?>/Assets/js/DistMargin/margin.js"></script>-->
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