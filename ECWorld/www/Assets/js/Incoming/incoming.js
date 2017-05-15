//Ajax - Ilaiya
var datatable;
var _rechargeTypes;
var dt;
var count=1;
function loadIncomingRequest(){//alert(1);
	function getComplaintStatus(value){
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
				return value;
		}
	}
	count=1;
	dt=ecwDTAdv.init(
		$("#idTblIncomingList"),
		{			
			"url" : _actionUrl+'/Incoming/IncomingAction.php',
			"type" : "POST",
			"data" : {
				"Action" : "GetIncoming_DT",
				"userId" 	: function(d){ return $("#idSelectUserID").val();}, 
				"message" 	: function(d){ return $("#message_like").val();},
				"server_no" : function(d){ return $("#server_no").val();}, 
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
				{"mData" : null,'sTitle':'Mobile','mRender':function(data){
					return data[3];
				}},
				{"mData" : null,'sTitle':'ServerNo','mRender':function(data){
					return data[8];
				}},
				{"mData" : null,'sTitle':'DateTime','mRender':function(data){
					return data[7];
				}},
				{"mData" : null,'sTitle':'ReceivedMsg','mRender':function(data){
					return data[4];
				}},
				{"mData" : null,'sTitle':'Req.Type','mRender':function(data){
					return getComplaintStatus(data[5]);
				}},
				{"mData" : null,'sTitle':'Remark','mRender':function(data){
					return getComplaintStatus(data[6]);
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
function refreshIncomingRequest(){
	if(isDTLoaded)	{
		count =1;
		$('#idTblIncomingList').DataTable().ajax.reload();
		
	}
	else {
		loadIncomingRequest();
		isDTLoaded = true;
		count =1;
	}

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
			$parentContainer.append("<option value=''>Select User</option>");
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

$(function(){
	getParents('1,3,4,5');
	$("#Search_Incoming").click(function(e){
		refreshIncomingRequest();
		return false;
	});
});