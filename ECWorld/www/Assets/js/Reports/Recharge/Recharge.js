
function loadRechargeReport(){
	function getRechargeStatus(value){
		switch(parseInt(value)){
			case 1:
				return "Pending"; break;
			case 2:
				return "Suspense";break;
			case 3:
				return "Success";break;
			case 4:
				return "Failed";break;
			default:
				return "Other";break;
		}
	}
	dt=ecwDTAdv.init(
		$("#idTblRecharge"),
		{			
			"url":  '../../../Action/Reports/Recharge/RechargeAction.php',
			"type" : "POST",
			"data" : {
				"Action" 	: "RechargeReport_DT",
				"userId" 	: function(d){ return $("#idSelectUserID").val();}, 
				"mobile" 	: function(d){ return $("#mobile_no").val();},
				"fromDate"	: function(d){ return $("#fromDate").val();},
				"toDate"	: function(d){ return $("#toDate").val();} 
			}
		},
		{
			"PageLength":1000,
			"Columns":[
				{"mData" : null,'bVisible':true,'sTitle':'User','mRender':function(data){
					return data[1]+'-'+data[2];
				}},
				{"mData" : null,'bVisible':true,'sTitle':'RequestID','mRender':function(data){
					return data[3];
				}},
				{"mData" : null,'sTitle':'Type','mRender':function(data){
					return data[4];
				}},
				{"mData" : null,'sTitle':'DateTime','mRender':function(data){
					return data[11];
				}},
				{"mData" : null,'sTitle':'ServiceNo','mRender':function(data){
					return data[5];
				}},
				{"mData" : null,'sTitle':'Operator','mRender':function(data){
					return data[6];
				}},
				{"mData" : null,'sTitle':'Amount','mRender':function(data){
					return data[7];
				}},
				{"mData" : null,'sTitle':'Txn.Id','mRender':function(data){
					return data[8];
				}},
				{"mData" : null,'sTitle':'Status','mRender':function(data){
					return getRechargeStatus(data[9]);
				},"createdCell": function(td, cellData, rowData, row, col) {
				var color;
				switch(cellData[9]) {
					case "1":
						color = '#E4E400';
						break;
					case "2":
						color = '#57AEE0';
						break;
					case "3":
						color = '#45CA3F';
						break;
					case "4":
						color = '#EA1F22';
						break;
					default:
						color = '#FF3229';
						break;
				}
				$(td).css({"color":color, "font-weight": "700"});
				}},
				{"mData" : null,'sTitle':'Balance','mRender':function(data){
					return data[10];
				}}
			]
		},
		{
			"Edit":{
				"IsEnabled":false
			},
			"Delete":{
				"IsEnabled":false
			},
			"Checkbox":{
				"IsEnabled":false
			},
			"SerialNo":{
				"IsEnabled":true
			},
			"UniqueIdColumnIndex":0,
			"NameColumnIndex":1,
			"ExcludeColumnIndex":0,
			"OrderByColumnIndex":0
		});
	isDtInitialized=true;
}
var isDTLoaded =false;
function refreshRechargeReport(){
	if(isDTLoaded)	{
		$('#idTblRecharge').DataTable().ajax.reload();
	}
	else {
		loadRechargeReport();
		isDTLoaded = true;
	}
	
}
 
/* function showUsers(){
	loadUsersForReport($("#idSelectUserID"),{
		"Action":"GetUsersByParent",
		"ParentID":"1",
		"IncludeParent":"1",
		"ExcludeRoleIDs":"0"
	},true,function(isSuccess){
		$('#idSelectUserID').prepend('<option selected="selected" value=""> Select User </option>');
	});
} */

function showUsers(){
	loadUsersForReport($("#idSelectUserID"),{
		"Action":"GetAllUsers",
		"ParentID":"0",
		"IncludeParent":"1",
		"IncludeAllSubUsers":"1",
		"ExcludeRoleIDs":"2"
	},true,function(isSuccess){
		$('#idSelectUserID').prepend('<option selected="selected" value=""> Select User </option>');
	});
}

$(function(){
	showUsers();
	//getNetworkList();
	$("#Search_PayCollection").click(function(e){
		refreshRechargeReport();
		return false;
	});
});



