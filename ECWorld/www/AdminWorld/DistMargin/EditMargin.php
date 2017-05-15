<div class="modal fade" id="idEdiMarginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-info">
		<div class="modal-content" style="width:800px;left:-14%;">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><?php echo $lang['title_user_margin']; ?></h4>
			</div>
			<div class="modal-body" style="background-color: #ffffff !important;">
			<div class="box-body">
			<form role="form" id="idFormUpdateMargin" method="post">
				<input type="hidden" name="Action" value="UpdateMargins" />
				<input type="hidden" name="UserID" id="idPopUserID"/>
				<div class="col-md-4">
					<div class="form-group">
						<input type="text" class="form-control" id="idPopUserDisplayID" readonly />
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<input type="text" class="form-control" id="idPopUserName" name="Name" readonly />
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<input type="text" class="form-control" id="idPopUserMobile" name="Mobile" readonly />
					</div>
				</div>
				
				<table id="idTblDistMargin" class="table table-bordered table-striped">
					<thead>
					<tr>
					  <th>From Amount</th>
					  <th>To Amount</th>
					  <th>Normal Billing</th>
					  <th>Regular Billing</th>
					  <th>Delete</th>
					</tr>
					</thead>
					<tbody id="idTblDistMarginBody">
						<tr>
						  <td>
							<input type="number"  class="form-control" name="FromAmount" placeholder="From Amount"></td>
						  <td>
							<input type="number"  class="form-control"  name="ToAmount" placeholder="To Amount"></td></td>
						  <td>
							<input type="number"  class="form-control" name="NormalBilling" placeholder="Normal Billing"></td></td>
						  <td>
							<input type="number" class="form-control" name="RegularBilling" placeholder="Regular Billing"></td></td>
						  <td>
							<a href="#" class="clsBtnDeleteMargin" id="idBtnDeleteMargin">
								<i class="fa fa-trash-o"></i>
							</a>							
							<span class="error"></span>
						  </td>
						</tr>
						<tr>
						  <td>
							<input type="number"  class="form-control" name="FromAmount" placeholder="From Amount"></td>
						  <td>
							<input type="number"  class="form-control"  name="ToAmount" placeholder="To Amount"></td></td>
						  <td>
							<input type="number"  class="form-control" name="NormalBilling" placeholder="Normal Billing"></td></td>
						  <td>
							<input type="number" class="form-control" name="RegularBilling" placeholder="Regular Billing"></td></td>
						  <td>
							<a href="#" class="clsBtnDeleteMargin" id="idBtnDeleteMargin">
								<i class="fa fa-trash-o"></i>
							</a>							
							<span class="error"></span>
						  </td>
						</tr>
					</tbody>
				  </table>
				<div class="col-xs-12">  
					  <label class="col-sm-3 control-label">Opening balance</label>
					  <div class="col-sm-3">
						<input type="text" class="form-control" name="idPopOpeningBalance" id="idPopOpeningBalance"  />
					  </div>
					<div class="col-sm-4">
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-addon"><input type="checkbox" class="clsChkEnable" name="SMSCheck[]" ></span>
								<input type="text" class="form-control" value="SMS" readonly />
							</div>
						</div>
					</div> 
					<div class="col-sm-2">
						<div class="form-group">
							<input type="button" class="btn btn-success form-control" id="idPopBtnUpdate" value="Update" />
						</div>
					</div>
					
					<div class="col-sm-2">
						<div class="form-group">
							<div id="idSpnSuccessErr"></div>
						</div>
					</div>
				</div>
			</form>
			<form id="idFormAddMargin">
				<div class="col-xs-12">  
					<div class="col-sm-2 clsPaddingSm2">
						<div class="form-group">
							<label style="color:#000"><?php echo $lang['lbl_from_amount']; ?></label><span class="error"></span>
							<input type="number" class="form-control" id="idPopTxtNewFrom" value="0" />
						</div>
					</div>
					<div class="col-sm-2 clsPaddingSm2">
						<div class="form-group">
							<label style="color:#000"><?php echo $lang['lbl_to_amount']; ?></label><span class="error"></span>
							<input type="number" class="form-control" id="idPopTxtNewTo" value="0" />
						</div>
					</div>
					<div class="col-sm-2 clsPaddingSm2">
						<div class="form-group">
							<label style="color:#000"><?php echo $lang['lbl_normal_billing']; ?></label><span class="error"></span>
							<input type="number" class="form-control" id="idPopTxtNewNormal" value="0" />
						</div>
					</div>
					<div class="col-sm-2 clsPaddingSm2">
						<div class="form-group">
							<label style="color:#000"><?php echo $lang['lbl_regular_billing']; ?></label><span class="error"></span>
							<input type="number" class="form-control" id="idPopTxtNewRegular" value="0" />
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label style="color:#000">&nbsp;</label><span class="error"></span>
							<input type="button" class="btn btn-success form-control" id="idPopBtnAddMargin" value="Add Margin" />
						</div>
					</div>
				</div>
            </form>
			</div>
			<div class="box-footer validate_msg" style="color:red; text-align:center;display:none ">
				Please fill all the required fields!
			</div>
		</div>
	</div>
</div>