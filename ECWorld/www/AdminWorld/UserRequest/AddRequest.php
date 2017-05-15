<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-info">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Pending Request</h4>
			</div>
			<div class="modal-body" style="background-color: #ffffff !important;">
			<form role="form" id="idFrmAddComplaint" method="post">
				<input type="hidden" id="idComplaintID" name="ComplaintID" value="0" />
				<input type="hidden" name="Action" value="Upsert" />
              <div class="box-body">
                <div class="form-group">
                  <label style="color:#000"><?php echo $lang['request_id']; ?></label><span class="requestErr error"></span>
                  <input type="text"  class="form-control" id="SearchRequest"  placeholder="Search Request ID">
				   <input type="hidden"  class="form-control" id="RequestID"  name="RequestID" >
				   <input type="hidden" id="FromTable"  name="FromTable" value="0" >
                </div>
				<div class="form-group">
                  <label style="color:#000"><?php echo $lang['transx_id']; ?></label><span class="transErr error"></span>
                  <input type="text"  class="form-control" id="Transaction"  name="Transaction" value="sdfdf" readonly >
                </div>
				<div class="form-group">
                  <label style="color:#000"><?php echo $lang['mobile_no']; ?></label><span class="mobileErr error"></span>
                  <input type="text"  class="form-control" id="Mobile_no"  name="Mobile_no" readonly >
                </div>
				<div class="form-group">
                  <label style="color:#000"><?php echo $lang['amount']; ?></label><span class="amountErr error"></span>
                  <input type="text"  class="form-control" id="Amount"  name="Amount" readonly >
                </div>
				<input type="hidden"  class="form-control" id="PrevStatus"  name="PrevStatus" value="0" readonly >
				<div class="form-group">
					<label style="color:#000"><?php echo $lang['status']; ?></label><span class="statusErr error"></span>
					<select class="form-control" name="Status" id="Status" style="width: 100%;">
						<option value="1">Pending</option>
						<option value="3">Success</option>
						<option value="4">Failed</option>
					</select>
				</div>

                <div class="form-group">
                  <label style="color:#000"><?php echo $lang['remark']; ?></label><span class="remarkErr error"></span>
                  <input type="text"  class="form-control" id="Remark" name="Remark" placeholder="Notes">
                </div>
				<div class="form-group">
					<div class="input-group">
					<span class="input-group-addon"><input id="SendSms" value="1" name="SendSms" type="checkbox"></span>
					<input type="text" class="form-control" id="SMSValue" value="Send SMS" readonly>
					</div>
                </div>
				
              </div>

              <div class="box-footer">
				<div class="col-md-4">
                <button id="idBtnAddComaplaint" type="submit" class="btn btn-success"><?php echo $lang['label_submit']; ?></button>
				</div>
				<div id="idSuccessErrMsg"></div>
              </div>
            </form>
			<div class="box-footer validate_msg" style="color:red; text-align:center;display:none ">
				Please fill all the required fields!
			</div>
			
			</div>
		</div>
	</div>
</div>