//Ajax - Ilaiya
var datatable;
var _rechargeTypes;
var dt;
var count=1;
function loadOutgoingRequest(){//alert(1);
	count=1;
	dt=ecwDTAdv.init(
		$("#idTblOutgoingList"),
		{			
			"url" : _actionUrl+'/Outgoing/OutgoingAction.php',
			"type" : "POST",
			"data" : {
				"Action" : "GetOutgoing_DT",
				"userId" 	: function(d){ return $("#idSelectUserID").val();}, 
				"message" 	: function(d){ return $("#message_like").val();},
				"api_name"  : function(d){ return $("#api_name").val();}, 
				"fromDate"	: function(d){ return $("#fromDate").val();},
				"toDate"	: function(d){ return $("#toDate").val();} 
			}
		},
		{
			"PageLength":500,
			"Columns":[
				{"mData" : null,'bVisible':false,'sTitle':'RequestID','mRender':function(data){
					return data[0];
				}},
				{"mData" : null,'sTitle':'UserID','mRender':function(data){
					return data[1]+'-'+data[2];
				}},
				{"mData" : null,'sTitle':'RequestID','mRender':function(data){
					return data[3];
				}},
				{"mData" : null,'sTitle':'Mobile','mRender':function(data){
					return data[4];
				}},
				{"mData" : null,'sTitle':'Request Time','mRender':function(data){
					return data[6];
				}},
				{"mData" : null,'sTitle':'Message','mRender':function(data){
					return data[5];
				}},
				{"mData" : null,'sTitle':'API Details','mRender':function(data){
					return 'API Name';
				}}
			]
		},
		{
			"Edit":{
				"IsEnabled":true
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

function getParents(RoleIDs){
	ajaxRequest({
		type: 'post',
		url: "../../Action/User/UserAction.php",
		data: "Action=GetUsersByRoles&RoleIDs="+RoleIDs,
		success: function(data){
			parentsList = data;
			var jsondata = JSON.parse(data);
			$parentContainer = $("#idSelectUserID");
			$parentContainer.html('');
			//$parentContainer.append("<option value=''>Select User</option>");
			$(jsondata).each(function(index,value){
				$parentContainer.append("<option value='"+value.UserID+"'>"+value.DisplayID+"-"+value.Name+"-"+value.Mobile+"</option>");
			});
		},
		error: function(error){
			alert(0);
		}
	},{
		isLoader:0,
		loaderElem:$('body')
	});
}

var isDTLoaded =false;
function refreshOutgoingRequest(){
	if(isDTLoaded)	{
		count =1;
		$('#idTblOutgoingList').DataTable().ajax.reload();
		
	}
	else {
		loadOutgoingRequest();
		isDTLoaded = true;
		count =1;
	}

}

$(function(){
	getParents('1,3,4,5');
	$("#Search_Outcoming").click(function(e){
		refreshOutgoingRequest();
		return false;
	});
});