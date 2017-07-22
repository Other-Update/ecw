<section class="content">

      <!-- Default box -->
      <div class="box box-default">
			<!-- /.box-header -->
			<div class="box-body">
			  <div class="row">
				<form method="post" action="" id="idUserForm" >
				<input type="hidden" value="Upsert" name="Action" />
				<input type="hidden" name="UserID" id="UserID" value="<?php echo isset($_GET['userid'])?($_GET['userid']==""?0:$_GET['userid']):0; ?>" />
				
 				<div class="col-md-6">
					  <div class="form-group">
						<label>Parent ID</label><span class="ParentIDErr error">*</span>
						<select class="form-control select2" name="ParentID" id="parentId" style="width: 100%;">
						</select>
					  </div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>User Type</label><span class="userTypeErr error">*</span>
						<select class="form-control select2" name="RoleID" id="userType" style="width: 100%;">
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Username</label><span class="usernameErr error">*</span>
						<input type="text" name="Name" id="username" class="form-control"  placeholder="Enter Username...">
					</div>
				</div>
				<div class="col-md-6 Business_user clientLimitField">
					<div class="form-group">
						<label>Client Limit</label><span class="clientLimitErr error">*</span>
						<input type="text" class="form-control" name="ClientLimit" id="clientLimit" >
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Mobile No</label><span class="mobileNoErr error">*</span>
						<input type="text" name="Mobile"  id="mobileNo" class="form-control" maxlength="10" autoComplete="off">
					</div>
				</div>
				<div class="col-md-6 Business_user" id="distFee">
					<div class="form-group">
						<label>Distributor Fees</label><span class="distFeeErr error">*</span>
						<input type="text" class="form-control" name="DistributorFee" id="distributorFee" >
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Email ID</label><span class="emailIdErr error"></span>
						<input type="email" class="form-control" name="Email" id="emailId" placeholder="Enter email id">
					</div>
				</div>
				<div class="col-md-6 Business_user" id="subDistFee">
					<div class="form-group">
						<label>Sub-Distributor Fees</label><span class="subDistFeeErr error">*</span>
						<input type="text" class="form-control" name="MandalFee" id="subDistributorFee" >
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Gender</label>
						<select class="form-control select2" name="Gender" id="gender" style="width: 100%;">
							<option value="Male">Male</option>
							<option value="Female">Female</option>
							<option value="Both">Both</option>
						</select>
					</div>
				</div>
				<div class="col-md-6 Business_user" id="retailerFee" >
					<div class="form-group">
						<label>Retailer Fees</label><span class="retailerFeeErr error">*</span>
						<input type="text" name="RetailerFee" id="retailerFees" class="form-control" >
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Date Of Birth:</label>
						<input type="text" name="DOB" id="dob" class="form-control" >
					</div>
				</div>
				<div class="col-md-6 Business_user">
					<div class="form-group">
						<label>Balance Level</label><span class="balanceLevelErr error">*</span>
						<input type="text" name="BalanceLevel" id="balanceLevel" class="form-control" value="0">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>PAN No.</label>
						<input type="text" name="PAN" id="PAN" class="form-control"  placeholder="Enter PAN Number">
					</div>
				</div>
				<div class="col-md-6 depositAmtField">
					<div class="form-group">
						<label>Deposit Amount </label><span class="depositeAmtErr error">*</span>
						<input type="text" name="Deposit" id="depositeAmt" class="form-control" >
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>ID Proof</label> (<span class="error">PDF Only</span>)
						<input type="file" class="form-control" name="ID" id="idProof" >
					</div>
				</div>
				<div class="col-md-6 Business_user">
					<div class="form-group">
						<label>Deposit Type</label>
						<select class="form-control select2" name="Refundable" id="refundable" style="width: 100%;">
							<option value="1">Refundable</option>
							<option value="2">Non-Refundable</option>
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Address</label>
						<textarea class="form-control" rows="3" name="Address" id="address" placeholder="Enter Address..."></textarea>
					</div>
				</div>
				<div class="col-md-6 Business_user">
					<div class="form-group">
						<label>Remarks</label>
						<textarea class="form-control" rows="3" name="Remarks" id="remarks" placeholder="Enter Remarks..."></textarea>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Password</label><span class="passwordErr error"></span>
						<input type="text" name="Password" id="password" placeholder="Enter Password" class="form-control">
					</div>
				</div>
				<div class="col-md-6 ">
					<div class="form-group">
						<label>Allowed IPs </label><span class="allowedIPErr error">*</span>
						<input type="text" name="AllowedIPs" id="allowedIP" class="form-control" >
					</div>
				</div>
				<div class="col-md-6 ">
					<div class="form-group">
					</div>
				</div>
				
				<div class="col-md-6">
				<div class="form-group">
					<label>&nbsp;</label>
					<input type="submit" value="Submit" class="btn btn-success " id="idBtnUserForm"/> 
					<!--a href="<?php echo $WebsiteUrl; ?>/AdminWorld/User/index.php" class="btn btn-warning ">Back</a--> 
				</div>
				</div>
				</form>
			  </div>
			</div>
			<div class="box-footer validate_msg" style="color:red; text-align:center;display:none ">
				Please fill all the required fields!
			</div>
			<div id="errorMsg"></div>
		  </div>

      <!-- /.box -->

    </section>