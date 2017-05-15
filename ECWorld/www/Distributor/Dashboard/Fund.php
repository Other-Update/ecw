<div class="tab-content cus-inside">
<form id="idFormFundTransfer" method="post" action="" style="padding-top: 10px;">
	<input type="hidden" name="Action" value="FundTransfer" />
	<div class="row">
	<div class="col-md-12">
		<label>To User</label>
		<div class="input-group">
		  <span class="input-group-addon"><i class="fa fa-user"></i></span>  
		  <select name="ToUserID" id="idFTToUser" class="form-control "  style="width: 100%;">
		  </select>
		</div>
	</div>
	</div>
	<div class="row">
	<div class="col-md-12">
		<label>Balance</label>
		<div class="input-group">
		 <span class="input-group-addon"><i class="fa fa-inr"></i></span>
		  <input type="text"  class="form-control" id="idTxtUserWalletFT" disabled>
		</div>
	</div>
	</div>
	<div class="row">
	<div class="col-md-12">
		<label>Transfer Amount</label>
		<div class="input-group">
		  <span class="input-group-addon"><i class="fa fa-inr"></i></span>
		  <input id="idAmount" name="Amount" type="text"  class="form-control" >
		</div>
	</div>
	</div>
	<div class="row">
	<div class="col-md-3">&nbsp;</div>
	<div class="col-md-6 fieldTop">
	  <div class="form-group">
	  <label>&nbsp;</label>
	  <input id="idBtnFT" type="submit" value="Confirm Transfer" class="form-control btn btn-success btnColor" > 
	  </div>
	</div>
	</div>
</form>
</div>