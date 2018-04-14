<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-info">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><?php echo $lang['payment_transfer_title']; ?></h4>
			</div>
			<div class="modal-body" style="background-color: #ffffff !important;">
			<form role="form" id="idFrmAddTransfer" method="post">
				<input type="hidden" name="Action" value="AddTransfer" />
				<input type="hidden" id="idCommissionAmountPrevPur" name="CommissionAmountPrevPur" value="0" />
				<input type="hidden" id="idExtraCommForDebit" name="ExtraCommForDebit" value="0" />
              <div class="box-body">
				<div class="col-md-6">
					<div class="form-group">
					  <label style="color:#000"><?php echo $lang['from']; ?></label><span class="error"></span>
					   <!--<select class="form-control select2" name="FromUserID" id="idSelectFromUser" style="width: 100%;">
						</select>-->
						<input type="text" class="form-control" name="FromUserIDSearch" id="idSelectFromUserSearch" style="width: 100%;" placeholder="User ID/Mobile" data-isuserloaded="0" value="1">
						<input type="hidden" class="form-control" name="FromUserID" id="idSelectFromUser" style="width: 100%;" placeholder="User ID/Mobile" data-isuserloaded="0" value="1">
						</input>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
					  <label style="color:#000"><?php echo $lang['balance']; ?></label><span class="error"></span>
					  <input type="text"  class="form-control" id="idBalanceFromUser"  name="BalanceToBePaidFromUser" disabled>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
					  <label style="color:#000"><?php echo $lang['to']; ?></label><span class="error"></span>
					  <!--<select class="form-control select2" name="ToUserID" id="idSelectToUser" style="width: 100%;">
						</select>-->
						<input type="text" class="form-control" name="ToUserIDSearch" id="idSelectToUserSearch" style="width: 100%;" placeholder="User ID/Mobile" data-isuserloaded="0">
						<input type="hidden" class="form-control" name="ToUserID" id="idSelectToUser" style="width: 100%;" placeholder="User ID/Mobile" data-isuserloaded="0" value="1">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
					  <label style="color:#000"><?php echo $lang['balance']; ?></label><span class="error"></span>
					  <input type="text"  class="form-control" id="idBalanceToUser"  name="BalanceToBePaidFromUser" disabled>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
					  <label style="color:#000"><?php echo $lang['transfer_amount']; ?></label><span class="error"></span>
					  <input type="text"  class="form-control" id="idAmount"  name="Amount" >
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
					  <label style="color:#000"><?php echo $lang['commission']; ?></label><span class="error"></span>
					  <input type="text"  class="form-control" id="idCommissionPercent" name="CommissionPercent" >
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
					  <label style="color:#000"><?php echo $lang['comm_amount']; ?></label><span class="serviceNameErr error"></span>
					  <input type="text"  class="form-control" id="idCommissionAmount" name="CommissionAmount" disabled>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
					  <label style="color:#000"><?php echo $lang['tot_amount']; ?></label><span class="error"></span>
					  <input type="text"  class="form-control" id="idTotalAmount"  name="TotalAmount" disabled>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label style="color:#000"><?php echo $lang['payment_type']; ?></label><span class="typeErr error"></span>
						<select class="form-control select2" name="Type" id="idType" style="width: 100%;">
							<option value="1">Credit</option>
							<option value="2">Debit</option>
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label style="color:#000"><?php echo $lang['payment_mode']; ?></label><span class="typeErr error"></span>
						<select class="form-control select2" name="Mode" id="idMode" style="width: 100%;">
							<option value="1" class="clsCreditModes">Normal</option>
							<option value="2" class="clsCreditModes">Cash</option>
							<option value="3" class="clsCreditModes">Cheque</option>
							<option value="4" class="clsCreditModes">Bank Deposit</option>
							<option value="5" class="clsCreditModes">Bank Transfer</option>
							<option value="7" class="clsDebitModes clsDebitModes">Other</option>
						</select>
						<div style="width: 100%; display:none;">
						<select class="form-control select2" id="idModeOptionsBackup" style="width: 100%;">
							<option value="1" class="clsCreditModes">Normal</option>
							<option value="2" class="clsCreditModes">Cash</option>
							<option value="3" class="clsCreditModes">Cheque</option>
							<option value="4" class="clsCreditModes">Bank Deposit</option>
							<option value="5" class="clsCreditModes">Bank Transfer</option>
							<option value="6" class="clsDebitModes">Wrong Transfer</option>
							<option value="7" class="clsDebitModes clsCreditModes">Other</option>
						</select>
						</div>
					</div>
				</div>
              
			  <div class="col-md-6">
					<div class="form-group">
					  <label style="color:#000"><?php echo $lang['remark']; ?></label><span class="serviceNameErr error"></span>
					  <input type="text"  class="form-control" id="idRemark"  name="Remark" >
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
					  <label style="color:#000"><?php echo $lang['paid_amount']; ?></label><span class="serviceNameErr error"></span>
					  <input type="text"  class="form-control" id="idPaidAmount"  name="PaidAmount" >
					</div>
				</div>
			</div>
				<div class="col-md-6">
					<div class="form-group">
					<input type="checkbox" id="idTransSendSMS" name="TransSendSMS"  class="flat-red">
					<label style="color:#000" ><?php echo $lang['send_msg']; ?></label>
					</div>
				</div>
			
              <div class="box-footer">
                <button id="idBtnAddTransfer" type="submit" class="btn btn-success"><?php echo $lang['submit_button']; ?></button>
              </div>
				<div id="idSpnSuccessErr"></div>
            </form>
			<div class="box-footer validate_msg" style="color:red; text-align:center;display:none ">
				Please fill all the required fields!
			</div>
			</div>
		</div>
	</div>
</div>