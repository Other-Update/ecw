<div class="row">
	<div class="col-xs-12">
	  <div class="box" style="border-top: none; ">
		<div class="box-header clsDivUserDerails">
			<div class="col-md-6">
				<select class="form-control select2" name="selectUserID" id="idSelectUserID" style="width: 100%;">
		 
				</select>
			</div>
			<div class="col-md-6">
				<input type="text" id="idMobileNo" class="form-control" readonly />
			</div>
			
		</div><hr>
		<div class="box-body">
		  <table id="idTblDistMargin" class="table table-bordered table-striped">
			<thead>
			<tr>
			  <th></th>
			  <th></th>
			  <th></th>
			  <th></th>
			  <th></th>
			  <th></th>
			  <th></th>
			</tr>
			</thead>
			<tbody>
				<!--<tr>
				  <td>10</td>
				  <td>20</td>
				  <td>3</td>
				  <td>6</td>
				  <td>edit</td>
				</tr>-->
			</tbody>
		  </table>
		  <div class="box" style="">
			<div class="box-header">
				<h3 style="margin-left:3%;">Add New Margin</h3>
			</div>
			<div class="box-body">
			<form method="post" action="" id="idFrmAddEditMargin" >			
				<input type="hidden" class="form-control" name="Action" id="idFromAction" value="Upsert">
				<input type="hidden" class="form-control" name="UserID" id="idUserID" value="0" >
				<input type="hidden" class="form-control" name="DistMarginID" id="idDistMarginID" value="0" >
				<div class="col-xs-12">
					<div class="col-sm-2">
						<div class="form-group">
							<label><?php echo $lang['lbl_from_amount']; ?></label>
							<input type="number" class="form-control" name="FromAmount" id="idFromAmount" >
							<span class="error"></span>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label><?php echo $lang['lbl_to_amount']; ?></label>
							<input type="number" class="form-control" name="ToAmount" id="idToAmount" >
							<span class="error"></span>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label><?php echo $lang['lbl_normal_billing']; ?></label>
							<input type="number" class="form-control" name="NormalBilling" id="idNormalBilling" >
							<span class="error"></span>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label><?php echo $lang['lbl_regular_billing']; ?></label>
							<input type="number" class="form-control" name="RegularBilling" id="idRegularBilling" >
							<span class="error"></span>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label>&nbsp;</label>
							<input type="submit" value="Submit" class="btn btn-success form-control" id="idBtnAddMargin"/> 
							<span class="error"></span>
						</div>
					</div>
				</div>
			</form>
			</div>
		</div>
		</div>
	  </div>
	</div>
</div>