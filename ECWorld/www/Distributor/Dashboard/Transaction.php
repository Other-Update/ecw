<style>
/*Styles related to transaction in dashboard page*/
#RecentTransaction{
font-size: 12px;
}
</style>
<div  id="TransactionPage">
	<div class="box-header">
	  <h3 class="box-title">Recent Transaction</h3>
	  <div class="box-tools">
			<button id="idBtnDashRcReprtRefresh" class="table-btn btn btn-success btnColor" type="button">Refresh</button>
	  </div>
	</div>
	  <table class="table" id="RecentTransaction" style="width:100%">
		<tr>
		  <th>RC.Id</th>
		  <th>Number</th>
		  <th>Operator</th>
		  <th>Amount</th>
		  <th>Status</th>
		</tr>
	  </table>
</div>