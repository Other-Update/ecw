
function loadPaymentCollection(){
/* 	var userId 		= $('#idSelectUserID').val();
	var fromDate	= $('#fromDate').val();
	var toDate		= $('#toDate').val();
	var mobile_no	= $('#mobile_no').val(); */
	var dt=ecwDatatable.init(
		$("#idPaymentCollection"),
		{			
			"url" : "../../../Action/Reports/PaymentCollection/PaymentCollectionAction.php",
			"type" : "POST",
			"data" : {
				"Action" 	: "PayCollectionReport_DT",
				"userId" 	: function(d){ return $("#idSelectUserID").val();}, 
				"mobile" 	: function(d){ return $("#mobile_no").val();},
				"fromDate"	: function(d){ return $("#fromDate").val();},
				"toDate"	: function(d){ return $("#toDate").val();} 
			}
		},
		10,
		false,false,false,0,1,0,
		[
			{"mData" : null,'bVisible':false,'sTitle':'PaymentID','mRender':function(data){
				return data[0];
			}},
			{"mData" : null,'sTitle':'Date & Time','mRender':function(data){
				return data[1];
			}},
			{"mData" : null,'sTitle':'Username','mRender':function(data){
				return data[2];
			}},
			{"mData" : null,'sTitle':'Prev.Day Bal','mRender':function(data){
				return data[3];
			}},
			{"mData" : null,'sTitle':'Curr Balance','mRender':function(data){
				return data[4];
			}},
			{"mData" : null,'sTitle':'Paid Amount','mRender':function(data){
				return data[5];  
			}},
			
			{"mData" : null,'sTitle':'Payment Mode','mRender':function(data){
				return data[6];
			}},
			{"mData" : null,'sTitle':'Remark','mRender':function(data){
				return data[7];
			}},
			{"mData" : null,'sTitle':'Type','mRender':function(data){
				return data[8];
			}}
		]);
}  
var isDTLoaded =false;
function refreshPaymentCollection(){
	if(isDTLoaded)	{
		$('#idPaymentCollection').DataTable().ajax.reload();
	}
	else {
		loadPaymentCollection();
		isDTLoaded = true;
	}
	
}
 
function showUsers(){
	loadUsersForReport($("#idSelectUserID"),{
		"Action":"GetUsersByParent",
		"ParentID":"1"
	},false,function(isSuccess){
	});
}

$(function(){
	showUsers();
	$("#Search_PayCollection").click(function(e){
		refreshPaymentCollection();
		return false;
	});
});

$('#idSelectUserID').change(function(){

	$('#mobile_no').val($("#idSelectUserID").select2().find(":selected").data("mobile"));

});

