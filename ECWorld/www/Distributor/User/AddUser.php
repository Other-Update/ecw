<?php
include '../../Header/Header.php';
?>
<style>
.form-group { margin-bottom: 5px; }
</style>
  <div class="content-wrapper">
    <section class="content-header">
      <h1>Create User</h1>
    </section>
	<section class="content">
      <div class="box box-default">
			<div class="box-body">
			  <div class="row">
				<form method="post" action="" id="idUserForm" >
				<input type="hidden" value="Upsert" name="Action" />
				<input type="hidden" name="UserID" id="UserID" value="<?php echo isset($_GET['userid'])?($_GET['userid']==""?0:$_GET['userid']):0; ?>" />
				<div class="col-md-3"></div>
 				<div class="col-md-6">
 					<div class="hiddenfields">
 					<input type="hidden" value="0" name="ClientLimit" id="clientLimit" >
 					<input type="hidden" name="Email" id="emailId">
 					<input type="hidden"  name="MandalFee" id="subDistributorFee" value="0">
 					<input type="hidden" name="DistributorFee" id="distributorFee" value="0" >
 					<input type="hidden" name="RetailerFee" id="retailerFees"  >
 					<input type="hidden" name="BalanceLevel" id="balanceLevel" value="0">
 					<input type="hidden" name="Deposit" id="depositeAmt" value="0">
 					<input type="hidden" value="2" name="Refundable" id="refundable" >
 					<input type="hidden" name="Password" id="password" >
 					<input type="hidden" name="Remarks" id="remarks" >
 					<input type="hidden" name="Address" id="address">
 					<input type="hidden" name="PAN" id="PAN">
 					</div>
					  <div class="form-group" style="display: none;">
						<label>Parent ID</label><span class="ParentIDErr error">*</span>
						<select class="form-control select2" name="ParentID" id="parentId" style="width: 100%;">
						</select>
					  </div>
				
					<div class="form-group">
						<label>User Type</label><span class="userTypeErr error">*</span>
						<select class="form-control select2" name="RoleID" id="userType" style="width: 100%;">
						</select>
					</div>
				
					<div class="form-group">
						<label>Username</label><span class="usernameErr error">*</span>
						<input type="text" name="Name" id="username" class="form-control"  placeholder="Enter Username...">
					</div>
				
					<div class="form-group">
						<label>Mobile No</label><span class="mobileNoErr error">*</span>
						<input type="text" name="Mobile"  id="mobileNo" class="form-control" maxlength="10" autoComplete="off">
					</div>
				
					<div class="form-group">
						<label>Gender</label>
						<select class="form-control select2" name="Gender" id="gender">
							<option value="Male">Male</option>
							<option value="Female">Female</option>
							<option value="Both">Both</option>
						</select>
					</div>
				
					<div class="form-group">
						<label>Date Of Birth</label>
						<input type="text" name="DOB" id="dob" class="form-control" >
					</div>
					
					<div class="form-group" style="text-align: center;margin-top: 10px;">
						<label>&nbsp;</label>
						<input type="submit" value="Submit" class="btn btn-success " id="idBtnDistUserForm"/> 
					</div>
				</div>
				<div class="col-md-3"></div>
				</form>
			  </div>
			</div>
			<div class="box-footer validate_msg" style="color:red; text-align:center;display:none ">
				Please fill all the required fields!
			</div>
			<div id="errorMsg"></div>
		  </div>
    </section>
  </div>
<?php
	include '../../Header/Footer.php';
?>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/User/user_ilaiya.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/User/distributor_user.js"></script>
