var isBalanceDtInitialized = false;
var isCollectionDtInitialized = false;

function getPaymentModeName(val){
	switch(val){
		case '1':	
			return 'Normal';
		break;
		case '2':
			return 'Cash';
		break;
		case '3':
			return 'Cheque';
		break;
		case '4':
			return 'Bank Deposit';
		break;
		case '5':
			return 'Bank Transfer';
		break;
		case '6':
			return 'Other';
		break;
		default:
			return 'Normal';
		return val;
	}
}
function loadBalanceList_DT(){
	dt=ecwDTAdv.init(
		$("#idTblBalanceList"),
		{			
			"url" : "../../Action/Payment/PaymentAction.php",
			"type" : "POST",
			"data" : {
				"Action" : "GetBalanceToBePaidByParent_DT",
				"SelectedUserID" : function(d){ return $("#idSelectUserID").val();}
			}
		},
		{
			"PageLength":500,
			"Columns":[
				{"mData" : null,'sTitle':'S.No','mRender':function(data){
					return data[0];
				}},
				{"mData" : null,'sTitle':'UserID','mRender':function(data){
					return data[1];
				}},
				{"mData" : null,'sTitle':'User Name','mRender':function(data){
					return data[2];
				}},
				{"mData" : null,'sTitle':'ParentID','mRender':function(data){
					return data[3];
				}},
				{"mData" : null,'sTitle':'Balance','mRender':function(data){
					return data[4];
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
			"UniqueIdColumnIndex":0,
			"NameColumnIndex":0,
			"ExcludeColumnIndex":0,
			"OrderByColumnIndex":1
		},function(e,id,name,all){
			openEditDistMargin(all);
			//editDistMargin(all);
		},function(e,id,name,all){
			if(confirm("Are you sure want to delete "+name+"?"))
				alert(id);//deleteDistMargin(id);
		},function(e,id,all){
			alert(id);
		});
	isBalanceDtInitialized=true;
}

function reloadBalance_DT(){
	$('#idTblBalanceList').DataTable().ajax.reload();
}
function loadCollection_DT(){
	dt=ecwDTAdv.init(
		$("#idTblCollectionList"),
		{			
			"url" : "../../Action/Payment/PaymentAction.php",
			"type" : "POST",
			"data" : {
				"Action" : "GetCollectionByParent_DT",
				"SelectedUserID" : function(d){ return $("#idSelectUserID").val();}
			}
		},
		{
			"PageLength":10,
			"Columns":[
				{"mData" : null,'sTitle':'S.No','mRender':function(data){
					return data[0];
				}},
				{"mData" : null,'sTitle':'Date&Time','mRender':function(data){
					return data[1];
				}},
				{"mData" : null,'sTitle':'From','mRender':function(data){
					return data[2];
				}},
				{"mData" : null,'sTitle':'To','mRender':function(data){
					return data[3];
				}},
				{"mData" : null,'sTitle':'Previous Balance','mRender':function(data){
					return data[4];
				}},
				{"mData" : null,'sTitle':'Amount','mRender':function(data){
					return data[5];
				}},
				{"mData" : null,'sTitle':'Current Balance','mRender':function(data){
					return data[6];
				}},
				{"mData" : null,'sTitle':'Payment Mode','mRender':function(data){
					return getPaymentModeName(data[7]);
				}},
				{"mData" : null,'sTitle':'Remarks','mRender':function(data){
					return data[8];
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
			"UniqueIdColumnIndex":0,
			"NameColumnIndex":0,
			"ExcludeColumnIndex":0,
			"OrderByColumnIndex":1
		},function(e,id,name,all){
			openEditDistMargin(all);
			//editDistMargin(all);
		},function(e,id,name,all){
			if(confirm("Are you sure want to delete "+name+"?"))
				alert(id);//deleteDistMargin(id);
		},function(e,id,all){
			alert(id);
		});
	isCollectionDtInitialized=true;
}

function reloadCollection_DT(){
	$('#idTblCollectionList').DataTable().ajax.reload();
}
function getUserDetailsForCollection(elem,userID,parentID,callbackFn){
	paymentAjax("Action=GetUserDetailsForTranser&UserID="+userID+"&ParentID="+parentID,function(json){
		//alert(JSON.stringify(json));
		if(json.isSuccess){
			var data=JSON.parse(json.data);
			//alert(data.balanceToBePaid);
			elem.val(isNaN(data.BalanceToBePaid)?0:data.BalanceToBePaid);
			//elem.data("balancetobepaid",isNaN(data.BalanceToBePaid)?0:data.BalanceToBePaid);
			$("#idPaidAmount").val(0);
			if(callbackFn) callbackFn(data.BalanceToBePaid);
		}else{
			alert('Error getting previous balance');
		}
	});
}
function onAddCollectionOpeningPoup(){
	var selectedUser = $("#idSelectUserID").val();
	loadDDusers($("#idSelectFromUser"),selectedUser,false,"6",function(){
		$("#idSelectFromUser").val(selectedUser).select2();
	});
	loadDDusers($("#idSelectToUser"),selectedUser,false,"0",function(){
		getUserDetailsForCollection($("#idPrevBalanceToUser"),$("#idSelectToUser").val(),$("#idSelectFromUser").val(),function(){
			
			calculateBalanceToPay($("#idPaidAmount"));
		});
	});
	//alert(selectedUser);
	$("#idPaidAmount").val(0);
	$("#idBalanceToBePaid").val(0);
	//clearPopupErrors();
}

function showError(elem,msg){
	elem.html(msg).css({"color":"red"}).show();
}
function clearPopupErrors(){
	$("#idPaidAmount").parent().find(".error").hide();
	$("#idBalanceToBePaid").parent().find(".error").hide();
	$("#idSpnSuccessErr").html('').hide();
}
function validateAddCollectionForm(){
	clearPopupErrors();
	var isValidated = true;
	//debugger;
	var paidAmount = parseFloat($("#idPaidAmount").val());
	
	if(paidAmount=="" || isNaN(paidAmount)){
		showError($("#idPaidAmount").parent().find(".error"),"Invalid");
		isValidated = false;
	}else{
		$("#idPaidAmount").parent().find(".error").hide();
	}
	return isValidated;
}
function addCollection(){
	if(validateAddCollectionForm()){
		paymentAjax($('form#idFrmAddCollection').serialize(),function(res){
			//var jsonData = JSON.parse(data);
			//alert(res.isSuccess);
			if(res.isSuccess){
				onAddCollectionOpeningPoup();
			}
			$("#idSpnSuccessErr").html(res.message).css({'color':'green'}).show();
				
			reloadBalance_DT();
			reloadCollection_DT();
		},function(){});
	}
	return false;
}
function calculateBalanceToPay(elem){
	var pevBal=parseFloat($("#idPrevBalanceToUser").val());
	var paidAmount = parseFloat(elem.val());
	if(isNaN(paidAmount))
		$("#idBalanceToBePaid").val(pevBal.toFixed(2));
	else
		$("#idBalanceToBePaid").val((pevBal-paidAmount).toFixed(2));
}
$(function(){
	loadDDusers($("#idSelectUserID"),1,false,"6",function(){
		loadBalanceList_DT();
		loadCollection_DT();
	});
	
	$("#idBtnPayTransferPopup").click(function(){
		onAddCollectionOpeningPoup();
	});
	$("#idBtnAddCollection").click(function(e){
		addCollection();
		e.preventDefault();
	});
	
	$("#idPaidAmount").keyup(function(){
		calculateBalanceToPay($(this));
	});
	$("#idSelectUserID").change(function(){
		reloadBalance_DT();
		reloadCollection_DT();
	});
	
	$("#idSelectToUser").change(function(){
		//changeUserSelectionPopup("to","#idSelectToUser","#idSelectFromUser");
		
		getUserDetailsForCollection($("#idPrevBalanceToUser"),$("#idSelectToUser").val(),$("#idSelectFromUser").val(),function(){
			
			calculateBalanceToPay($("#idPaidAmount"));
		});
	});
});