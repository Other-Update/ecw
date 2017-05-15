<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-info">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><?php echo $lang['payment_collection_title']; ?></h4>
			</div>
			<div class="modal-body" style="background-color: #ffffff !important;">
			<form role="form" id="idFrmAddCollection" method="post">
				<input type="hidden" name="Action" value="AddCollection" />
              <div class="box-body">
				<div class="col-md-6">
					<div class="form-group">
					  <label style="color:#000"><?php echo $lang['from']; ?></label><span class="serviceNameErr error"></span>
					   <select class="form-control select2" name="FromUserID" id="idSelectFromUser" style="width: 100%;">
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
					  <label style="color:#000"><?php echo $lang['to']; ?></label><span class="serviceNameErr error"></span>
					  
					  <select class="form-control select2" name="ToUserID" id="idSelectToUser" style="width: 100%;">
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
					  <label style="color:#000"><?php echo $lang['prev_balance']; ?></label><span class="serviceNameErr error"></span>
					  <input type="text"  class="form-control" id="idPrevBalanceToUser"  name="PrevBalanceToUser" disabled>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
					  <label style="color:#000"><?php echo $lang['pay_received']; ?></label><span class="serviceNameErr error"></span>
					  <input type="text"  class="form-control" id="idPaidAmount"  name="PaidAmount" >
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
					  <label style="color:#000"><?php echo $lang['balance']; ?></label><span class="serviceNameErr error"></span>
					  <input type="text"  class="form-control" id="idBalanceToBePaid"  name="BalanceToBePaid" disabled>
					</div>
				</div>
				
				<div class="col-md-6">
					<div class="form-group">
						<label style="color:#000"><?php echo $lang['payment_mode']; ?></label><span class="typeErr error"></span>
						<select class="form-control select2" name="Mode" id="idMode" style="width: 100%;">
							<option value="1">Normal</option>
							<option value="2">Cash</option>
							<option value="3">Cheque</option>
							<option value="4">Bank Deposit</option>
							<option value="5">Bank Transfer</option>
							<option value="6">Other</option>
						</select>
					</div>
				</div>
              
			  <div class="col-md-12">
					<div class="form-group">
					  <label style="color:#000"><?php echo $lang['remark']; ?></label><span class="serviceNameErr error"></span>
					  <textarea   class="form-control" id="idRemark"  name="Remark" ></textarea>
					</div>
				</div>
				
			</div>
              <div class="box-footer">
                <button id="idBtnAddCollection" type="submit" class="btn btn-success"><?php echo $lang['submit_button']; ?></button>
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