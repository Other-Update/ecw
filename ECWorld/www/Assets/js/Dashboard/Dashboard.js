//Search network 
$(document).on("keyup",".SearchNetwork",function(){
	var mobile = $(this).val();
	var RCType= $(this).data('type');
	
	if(mobile.length >= 4){
	ajaxRequest({
		type: 'post',
		url: '../../Action/Services/ServiceAction.php',
		dataType: 'json',
		data: "Action=GetServiceOperator&Mobile="+mobile+"&RCType="+RCType,
		success: function(data){
			var jsonData = JSON.parse(data);
			if(data!="null" && jsonData.length>0){
				var  ServiceID= jsonData[0].RechargeCode;
				var  ServiceID2= jsonData[0].TopupCode;
				if(ServiceID != ''){
					if(RCType == 1) { 
						$("#idSelectMobilePrepaidOperator").val(ServiceID).change(); 
					} 
					else if(RCType == 2) { 
						$("#idSelectMobilePostpaidOperator").val(ServiceID).change(); 
					} 
					else if(RCType == 3) { 
						$("#idSelectDthOperator").val(ServiceID).change(); 
					}
				}else {
					if(RCType == 1) { 
						$("#idSelectMobilePrepaidOperator").val(ServiceID2).change(); 
					}else if(RCType == 2) { 
						$("#idSelectMobilePostpaidOperator").val(ServiceID2).change(); 
					}else if(RCType == 3) { 
						$("#idSelectDthOperator").val(ServiceID2).change(); 
					}
				}
			}
				
		},
		error: function(error){
			alert('Error');
		}
	},{
		isLoader:0,
		loaderElem:$('body')
	}); } /* else {
		if(RCType == 1) { $("#idSelectMobilePrepaidOperator").val('').change(); } 
		else if(RCType == 2) { $("#idSelectMobilePostpaidOperator").val('').change(); }
	} */
});

var serviceList = {};
function loadServiceList(mode,dropdownID){
	dd = $("#"+dropdownID);
	dd.html('');
	dd.append("<option value='' > --Select Operator-- </option>");
	$(serviceList).each(function(index,value){
		/* console.log(value.NetworkMode==mode);
		console.log(value.Name);
		console.log("----------"); */
		//debugger;
		if(value.NetworkMode==mode){
			if(value.RechargeCode != ''){
			dd.append("<option style='color: #000;font-weight: 700;' value='"+value.RechargeCode+"' >"+value.Name+"</option>");
			} else {
			dd.append("<option style='color: #000;font-weight: 700;' value='"+value.TopupCode+"' >"+value.Name+"</option>");
			}
		}
	});
}
function GetServiceList(){
	ajaxRequest({
		type: 'post',
		url:  '../../Action/Services/ServiceAction.php',
		data: {Action:"GetServiceList"},
		success: function(data){
			parentsList = data;
			serviceList = JSON.parse(data);
			loadServiceList(1,"idSelectMobilePrepaidOperator");
			//loadServiceList(4,"idSelectDatacardOperator");
		},
		error: function(error){
			alert(0);
		}
	},{
		isLoader:0,
		loaderElem:$('body')
	});
}

/* My Code -- Reghu */
function GetDashboardData(){
	ajaxRequest({
		type: 'post',
		url:  '../../Action/Dashboard/DashboardAction.php',
		data: "Action=GetDashboardData",
		success: function(data){
			//console.log(data);
			var jsonData = JSON.parse(data);
			$('#AvailableBalance').html(jsonData.WalletAmount.Wallet);
			$('#OpeningBalance').html(jsonData.OpeningAmount);
			$('#PurchaseBalance').html(jsonData.PurchaseTotal[0].Amount);
			var SalesAmt = -1 * (jsonData.SalesTotal[0].TotalSalesAmount);
			var SalesAmt2 = jsonData.SalesTotal[0].TotalSalesPlusAmount;
			//alert(SalesAmt2);
			$('#SalesBalance').html(SalesAmt);
		},
		error: function(error){
			alert(0);
		}
	},{
		isLoader:0,
		loaderElem:$('body')
	});
}

/* End Code */



function callRechargeAjax(data,callbackFnSuccess,callbackFnFailure){
	ajaxRequest({
		type: 'post',
		url:  '../../Action/Dashboard/DashboardAction.php',
		data: data,
		success: function(data){
			callbackFnSuccess(data);
			showTransactionPane();
			reloadDashRcReport();
		},
		error: function(error){
			callbackFnFailure(error);
			showTransactionPane();
			reloadDashRcReport();
		}
	},{
		isLoader:1,
		loaderElem:$('.clsRechargeContainer')
	});
}


function transferFund(){//TODO:Need to cahge all code in this fn.
	$("#idBtnFT").prop('disabled', true);
	//alert($('form#idFormFundTransfer').serialize());
	callRechargeAjax($('form#idFormFundTransfer').serialize(),function(data){
		//alert(data);debugger;
		var jsonData = JSON.parse(data);
		alert(jsonData.message);
		if(jsonData.isSuccess==true){
			$("#idAmount").val('');
		}
		/* $('#errorMsgFT').fadeIn(100,function(){});
		$('#errorMsgFT').fadeOut(10000,function(){});
		alert(jsonData.Message);
		$("#rcAmountPrepaid").val('');
		$("#idBtnFT").prop('disabled', false);
		GetDashboardData(); */
		$("#idBtnFT").prop('disabled', false);
		GetDashboardData();
	},function(error){
		alert('Failed to transfer fund');
		$("#idBtnFT").prop('disabled', false);
	});
	return false;
}
//Prepaid number
function rechargePrepaidNumber(){
	$("#idBtnRcPrepaid").prop('disabled', true);
	callRechargeAjax($('form#idFrmRCPrepaid').serialize(),function(data){
		var jsonData = JSON.parse(JSON.parse(JSON.parse(data)));
		$('#errorMsgPrepaid').fadeIn(100,function(){});
		$('#errorMsgPrepaid').fadeOut(10000,function(){});
		alert(jsonData.Message);
		$("#rcAmountPrepaid").val('');
		$('#idFrmRCPrepaid')[0].reset();
		$("#idBtnRcPrepaid").prop('disabled', false);
		GetDashboardData();
	},function(error){
		alert('Failed to recharge');
		$("#idBtnRcPrepaid").prop('disabled', false);
	});
	/* 
	ajaxRequest({
		type: 'post',
		url: 'DashboardAction.php',
		data: $('form#idFrmRCPrepaid').serialize(),
		success: function(data){
			var jsonData = JSON.parse(JSON.parse(JSON.parse(data)));
			$('#errorMsgPrepaid').fadeIn(100,function(){});
			$('#errorMsgPrepaid').fadeOut(10000,function(){});
			alert(jsonData.Message);
		},
		error: function(error){
			alert('Failed to recharge');
		}
	},{
		isLoader:1,
		loaderElem:$('.clsRechargeContainer')
	}); */
}

//Postpaid number
function rechargePostpaidNumber(){
	callRechargeAjax($('form#idFrmRCPostpaid').serialize(),function(data){		
		var jsonData = JSON.parse(JSON.parse(JSON.parse(data)));
		$('#errorMsgPostpaid').fadeIn(100,function(){});
		$('#errorMsgPostpaid').fadeOut(5000,function(){});
		alert(jsonData.Message);
		$('#idFrmRCPostpaid')[0].reset();
		GetDashboardData();
	},function(error){
		alert('Failed to recharge');
	});
	/* ajaxRequest({
		type: 'post',
		url: 'DashboardAction.php',
		data: $('form#idFrmRCPostpaid').serialize(),
		success: function(data){
			var jsonData = JSON.parse(JSON.parse(JSON.parse(data)));
			$('#errorMsgPostpaid').fadeIn(100,function(){});
			$('#errorMsgPostpaid').fadeOut(5000,function(){});
			alert(jsonData.Message);
		},
		error: function(error){
			alert('Failed to recharge');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	}); */
}

//Recharge DTH
function rechargeDthNumber(){
	callRechargeAjax($('form#idFrmRcDTH').serialize(),function(data){			
		var jsonData = JSON.parse(data);
		$('#errorMsgDth').fadeIn(100,function(){});
		$('#errorMsgDth').fadeOut(5000,function(){});
		alert(jsonData.Message);
		$('#idFrmRcDTH')[0].reset();
		GetDashboardData();
	},function(error){
		alert('Failed to recharge');
	});
	/* ajaxRequest({
		type: 'post',
		url: 'DashboardAction.php',
		data: $('form#idFrmRcDTH').serialize(),
		success: function(data){
			var jsonData = JSON.parse(data);
			$('#errorMsgDth').fadeIn(100,function(){});
			$('#errorMsgDth').fadeOut(5000,function(){});
			alert(jsonData.Message);
		},
		error: function(error){
			alert('Failed to recharge');
		}
	},{
		isLoader:1,
		loaderElem:$('.clsRechargeContainer')
	}); */
}

//Recharge Datacard
function rechargeDatacardNumber(){
	$("#idBtnRcDatacard").prop('disabled', true);
	callRechargeAjax($('form#idFrmRcDatacard').serialize(),function(data){		
		var jsonData = JSON.parse(JSON.parse(JSON.parse(data)));
		$('#errorMsgDatacard').fadeIn(100,function(){});
		$('#errorMsgDatacard').fadeOut(5000,function(){});
		alert(jsonData.Message);
		$('#idFrmRcDatacard')[0].reset();
		$("#idBtnRcDatacard").prop('disabled', false);
		GetDashboardData();
	},function(error){
		$("#idBtnRcDatacard").prop('disabled', false);
		alert('Failed to recharge');
	});
	/* ajaxRequest({
		type: 'post',
		url: 'DashboardAction.php',
		data: $('form#idFrmRcDatacard').serialize(),
		success: function(data){
			var jsonData = JSON.parse(data);
			$('#errorMsgDatacard').fadeIn(100,function(){});
			$('#errorMsgDatacard').fadeOut(5000,function(){});
			alert(jsonData.Message);
		},
		error: function(error){
			alert('Failed to recharge');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	}); */
}

function rechargeLandlineNumber(){
	callRechargeAjax($('form#idFrmRcLandline').serialize(),function(data){		
		var jsonData = JSON.parse(data);
		$('#errorMsgLandline').fadeIn(100,function(){});
		$('#errorMsgLandline').fadeOut(5000,function(){});
		alert(jsonData.Message);
		GetDashboardData();
	},function(error){
		alert('Failed to recharge');
	});
	/* ajaxRequest({
		type: 'post',
		url: 'DashboardAction.php',
		data: $('form#idFrmRcLandline').serialize(),
		success: function(data){
			var jsonData = JSON.parse(data);
			$('#errorMsgLandline').fadeIn(100,function(){});
			$('#errorMsgLandline').fadeOut(5000,function(){});
			alert(jsonData.Message);
		},
		error: function(error){
			alert('Failed to recharge');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	}); */
}


var dashRcReportDT;
function loadRechargeReportDashboard(){
	//alert("loadRechargeReportDashboard1");
	function getRechargeStatus(value){
		//Display Pending for both Pending(1) & Suspense(2). This applies only to dashboard page recent transaction UI.
		switch(parseInt(value)){
			case 1:
				return "Pending";
			case 2:
				return "Pending";break;
			case 3:
				return "Success";break;
			case 4:
				return "Failed";break;
			default:
				return "Other";break;
		}
	}
	dashRcReportDT=ecwDTAdv.init(
		$("#RecentTransaction"),
		{			
			"url":  '../../Action/Dashboard/DashboardAction.php',
			"type" : "POST",
			"data" : {
				"Action" 	: "getRechargeReportDashboard"
				
			}
		},
		{
			"PageLength":20,
			"Columns":[
				{"mData" : null,'bVisible':false,'sTitle':'ReferenceID','mRender':function(data){
					return data[0];
				}},
				{"mData" : null,'bVisible':false,'sTitle':'Type','mRender':function(data){
					return data[1];
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
				{"mData" : null,'bVisible':true,'sTitle':'Txn.Id','mRender':function(data){
					return data[8];
				}},
				{"mData" : null,'sTitle':'Status','mRender':function(data){
					return getRechargeStatus(data[9]);
				},"createdCell": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
					var row_class;
					console.log('cellData='+aData[9]);
					switch(aData[9]) {
						case "1":
							row_class = 'row_pending';
							break;
						case "2":
							row_class = 'row_suspense';
							break;
						case "3":
							row_class = 'row_success';
							break;
						case "4":
							row_class = 'row_failed';
							break;
						default:
							row_class = 'row_others';
							break;
					}
					$(nRow).parent().addClass(row_class);
					//$(td).css({"color":color, "font-weight": "700"});
				}},
				{"mData" : null,'bVisible':false,'sTitle':'Balance','mRender':function(data){
					return data[7];
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
				"IsEnabled":false
			},
			"UniqueIdColumnIndex":0,
			"NameColumnIndex":1,
			"ExcludeColumnIndex":0,
			"OrderByColumnIndex":-1
		});
	isDtInitialized=true;
}

function loadUsersFundTransafer(){
	loadUsers($("#idFTToUser"),{
		"Action":"GetAllUsers",
		"ParentID":0,//0 Means loggedinuserID
		"IncludeParent":0,
		"IncludeAllSubUsers":1,
		"ExcludeRoleIDs":"0"
	},true,function(isSuccess){
		getUserDetails($("#idFTToUser").val());
		//loadUsers_DT();
	});
}

function loadRechargeReportRecharge_NIU(){//alert(1);
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
		$("#RecentTransaction"),
		{			
			"url":  '../../Action/Dashboard/DashboardAction.php',
			"type" : "POST",
			"data" : {
				"Action" 	: "getRechargeReportRecharge"
				
			}
		},
		{
			"PageLength":20,
			"Columns":[
				{"mData" : null,'bVisible':true,'sTitle':'ReferenceID','mRender':function(data){
					return data[0];
				}},
				{"mData" : null,'sTitle':'ServiceNo','mRender':function(data){
					return data[2];
				}},
				{"mData" : null,'sTitle':'Operator','mRender':function(data){
					return data[3];
				}},
				{"mData" : null,'sTitle':'Amount','mRender':function(data){
					return data[4];
				}},
				{"mData" : null,'sTitle':'Status','mRender':function(data){
					return getRechargeStatus(data[6]);
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
				"IsEnabled":false
			},
			"UniqueIdColumnIndex":0,
			"NameColumnIndex":1,
			"ExcludeColumnIndex":0,
			"OrderByColumnIndex":0
		});
	isDtInitialized=true;
}

function reloadDashRcReport(){
	//$("#RecentTransaction").DataTable().columns.adjust().draw();
	$("#RecentTransaction").DataTable().ajax.reload();
}

function getUserDetails(userID){
	callRechargeAjax({
		Action:'GetUserDetails',
		UserID:userID
	},function(data){
		var jsonData = JSON.parse(data);
		$("#idTxtUserWalletFT").val(jsonData.Wallet);
	},function(error){
		alert('Failed to recharge');
	});
};
function isRetailer(roleID){
	return roleID==6;
}
var userRoleID = 6;
$(function(){
	userRoleID = $(".clsRechargeContainer").data("userrole");
	if(!isRetailer(userRoleID)){
		loadUsersFundTransafer();
	}
	GetServiceList();
	GetDashboardData();
	loadRechargeReportDashboard();
	//loadRechargeReportRecharge();
	$("#idBtnFT").click(function(e){
		transferFund();return false;
	});
	//Recahrge Prepaid mobile number
	$("#idBtnRcPrepaid").click(function(e){
		var validation_holder = 0;
		var mobile   		= $("#Pre_mobile").val();
		var operator	 	= $("#idSelectMobilePrepaidOperator").val();
		var rechargeAmount 	= $("#rcAmountPrepaid").val(); 
		var mobile_regex    = /^[789]\d{9}$/;

		if(mobile_regex.test(mobile) && mobile.length==10){
			$("span.PreNumberErr").html("");
		} else {
			$("span.PreNumberErr").html(" is not valid."); validation_holder = 1;
		}
		
		if($.trim(operator) == '' ) {
			$("span.OperatorErr").html(" is required."); validation_holder = 1;
		} else { $("span.OperatorErr").html(""); }
		
		if($.trim(rechargeAmount) == '' ) {
			$("span.PreAmountErr").html(" is not valid."); validation_holder = 1;
		} else { $("span.PreAmountErr").html(""); }
		
		if(validation_holder == 1) { 
			return false;
		}  else { 
			validation_holder = 0;
			rechargePrepaidNumber();
			return false;
		}
		
	});
	
	//Recharge Postpaid mobile number
	$("#idBtnRcPostpaid").click(function(e){
		var validation_holder = 0;
		var mobile   		= $("#Post_Mobile").val();
		var operator	 	= $("#idSelectMobilePostpaidOperator").val();
		var rechargeAmount 	= $("#rcAmountPostpaid").val(); 
		var mobile_regex    = /^[789]\d{9}$/;

		if(mobile_regex.test(mobile) && mobile.length==10){
			$("span.PostNumberErr").html("");
		} else {
			$("span.PostNumberErr").html(" is not valid."); validation_holder = 1;
		}
		
		if($.trim(operator) == '' ) {
			$("span.PostOperatorErr").html(" is required."); validation_holder = 1;
		} else { $("span.PostOperatorErr").html(""); }
		
		if($.trim(rechargeAmount) == '' ) {
			$("span.PostAmountErr").html(" is not valid.."); validation_holder = 1;
		} else { $("span.PostAmountErr").html(""); }
		
		if(validation_holder == 1) { 
			return false;
		}  else { 
			validation_holder = 0;
			rechargePostpaidNumber();
			return false;
		}
		
	});
	
	//Recharge DTH
	$("#idBtnRcDTH").click(function(e){
		var validation_holder = 0;
		var accNo   		= $("#dthNumber").val();
		var operator	 	= $("#idSelectDthOperator").val();
		var rechargeAmount 	= $("#dthAmount").val(); 
		var regex = new RegExp(/^\+?[0-9(),.-]+$/);
		
		if($.trim(accNo) == '' || (!accNo.match(regex)) ){
			$("span.accountNoErr").html(" is not valid"); validation_holder = 1;
		} else { $("span.accountNoErr").html(""); }
		
		if($.trim(operator) == '' ) {
			$("span.operatorDthErr").html(" is required."); validation_holder = 1;
		} else { $("span.operatorDthErr").html(""); }
		
		if($.trim(rechargeAmount) == '' ) {
			$("span.DthAmountErr").html(" is not valid.."); validation_holder = 1;
		} else { $("span.DthAmountErr").html(""); }
		
		if(validation_holder == 1) { 
			return false;
		}  else { 
			validation_holder = 0;
			rechargeDthNumber();
			return false;
		}
		
	});
	
	//Recharge Datacard
	$("#idBtnRcDatacard").click(function(e){
		var validation_holder = 0;
		var accNo   		= $("#datacardNumber").val();
		var operator	 	= $("#idSelectDatacardOperator").val();
		var rechargeAmount 	= $("#datacardAmount").val(); 
		var regex = new RegExp(/^\+?[0-9(),.-]+$/);
		
		if($.trim(accNo) == '' || (!accNo.match(regex)) ){
			$("span.DataAccNoErr").html(" is not valid"); validation_holder = 1;
		} else { $("span.DataAccNoErr").html(""); }
		
		if($.trim(operator) == '' ) {
			$("span.DataOperatorErr").html(" is required."); validation_holder = 1;
		} else { $("span.DataOperatorErr").html(""); }
		
		if($.trim(rechargeAmount) == '' ) {
			$("span.DataAmountErr").html(" is not valid.."); validation_holder = 1;
		} else { $("span.DataAmountErr").html(""); }
		
		if(validation_holder == 1) { 
			return false;
		}  else { 
			validation_holder = 0;
			rechargeDatacardNumber();
			return false;
		}
		
	});
	
	//Landline
	$("#idBtnRcLandline").click(function(e){
		var validation_holder = 0;
		var accNo   		= $("#landlineNumber").val();
		var operator	 	= $("#idSelectLandlineOperator").val();
		var rechargeAmount 	= $("#landlineAmount").val(); 
		var regex = new RegExp(/^\+?[0-9(),.-]+$/);
		
		if($.trim(accNo) == '' || (!accNo.match(regex)) ){
			$("span.LandlineNumberErr").html(" is not valid"); validation_holder = 1;
		} else { $("span.LandlineNumberErr").html(""); }
		
		if($.trim(operator) == '' ) {
			$("span.LandlineOperatorErr").html(" is required."); validation_holder = 1;
		} else { $("span.LandlineOperatorErr").html(""); }
		
		if($.trim(rechargeAmount) == ''  ) {
			$("span.LandlineAmountErr").html(" is not valid."); validation_holder = 1;
		} else { $("span.LandlineAmountErr").html(""); }
		
		if(validation_holder == 1) { 
			return false;
		}  else { 
			validation_holder = 0;
			rechargeLandlineNumber();
			return false;
		}
		
	}); 
	
	$(".clsnetworkmode").click(function(e){
		var networkmode = $(this).data('networkmode');
		//alert(networkmode);
		switch(networkmode){
			case 12:
				if($("#idMobilePostPaid").hasClass("active")){
					loadServiceList(2,"idSelectMobilePostpaidOperator");
				}else{
					loadServiceList(1,"idSelectMobilePrepaidOperator");
				}
			break;
			case 3:
				loadServiceList(3,"idSelectDthOperator");
			break;
			case 4:
				loadServiceList(4,"idSelectDatacardOperator");
			break;
			case 5:
				loadServiceList(5,"idSelectLandlineOperator");
			break;
		}
	});
	
	$(".clsmobileoperator").click(function(e){
		//Called before tab chnage. So values are opposite
		if($("#idMobilePostPaid").hasClass("active")){
			loadServiceList(1,"idSelectMobilePrepaidOperator");
		}else{
			loadServiceList(2,"idSelectMobilePostpaidOperator");
		}
	});
	//$(".clsmobileoperator").trigger('click');
	$("#idBtnDashRcReprtRefresh").click(function(){
		reloadDashRcReport();
		GetDashboardData();
	});

	$("#idFTToUser").change(function(){
		getUserDetails($(this).val());
	});
});

function showTransactionPane(){
	$('#SearchPlansPage').hide();
	$('#TransactionPage').show();
}
function showSearchplansPane(){
	$('#SearchPlansPage').show();
	$('#TransactionPage').hide();
}
$(document).on("keyup change",".RechargeNumber",function(){
	var value = $(this).val();
	if(value.length >= 4){
		showSearchplansPane();
	}else{
		showTransactionPane();
	}
});




//Admin Dashboard
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
			"url":  '../../Action/Dashboard/DashboardAction.php',
			"type" : "POST",
			"data" : {
				"Action" 	: "getCurrentRechargeReport"
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
 

$(function(){
	refreshRechargeReport();
});