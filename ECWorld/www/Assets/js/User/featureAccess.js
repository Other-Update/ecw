var features=[
	{"ID":"1","Name":"UserAccess","DisplayName":"User Access","Read":"0","AddUpdate":"0"},
	{"ID":"2","Name":"ServiceList","DisplayName":"Service List","Read":"0","AddUpdate":"0"},
	{"ID":"3","Name":"RechargeGateway","DisplayName":"Recharge Gateway","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"SMSGateway","DisplayName":"SMS Gateway","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"GeneralSettings","DisplayName":"General Settings","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"RechargePermission","DisplayName":"Recharge Permission","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"DistributorMargin","DisplayName":"Distributor Margin","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"NetworkManagement","DisplayName":"Network Management","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"Vendor","DisplayName":"Vendor","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"VendorPayment","DisplayName":"Vendor Payment","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"PaymentTransfer","DisplayName":"Payment Transfer","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"PaymentCollection","DisplayName":"Payment Collection","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"BankDetails","DisplayName":"Bank Details","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"MNPSettings","DisplayName":"MNP Settings","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"AutoRechargeSettings","DisplayName":"Auto Recharge Settings","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"ComplaintRequest","DisplayName":"Complaint Request","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"PendingRequest","DisplayName":"Pending Request","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"SMSOffer","DisplayName":"SMS Offer","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"WebOffer","DisplayName":"Web Offer","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"IncentiveOffer","DisplayName":"Incentive Offer","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"MoveUser","DisplayName":"Move User","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"RechargeAmountSettings","DisplayName":"Recharge Amount Settings","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"ManageTransaction","DisplayName":"Manage Transaction","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"LoginSettings","DisplayName":"Login Settings","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"GovernmentHolidays","DisplayName":"Government Holidays","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"Recharge","DisplayName":"Recharge","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"PaymentReport","DisplayName":"Payment Report","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"PaymentCollectionReport","DisplayName":"Payment Collection Report","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"RechargeReport","DisplayName":"Recharge Report","Read":"0","AddUpdate":"0"},
	{"ID":"4","Name":"TransactionReport","DisplayName":"Transaction Report","Read":"0","AddUpdate":"0"}
];
var userAndFatJson;
function showFeatureList(userID,fatList){
	$("#idTxtUserID").val(userID);
	$container = $("#idFeaturesListContainer");
	$container.html("<tr>"
					  +"<th>#</th>"
					  +"<th>Feature</th>"
					  +"<th>Read</th>"
					  +"<th>Add/Update</th>"
					+"</tr>");
	var userFat ;var isFatFound = 0;
	$(fatList).each(function(index,data){
		if(data.UserID==userID){
			userFat=data;
			isFatFound=1;
		}
	});
	if(isFatFound==0) return false;
	function getReadAccess(val){
		return val.split(',').length > 0 ? val.split(',')[0] : 0;
	}
	function getAddUpdateAccess(val){
		return val.split(',').length > 1 ? val.split(',')[1] : 0;
	}
	function getMeRow(index,fa){
		//alert(fa.Name);
		var readAccess = userFat[fa.Name]?getReadAccess(userFat[fa.Name]):0;
		var addUpdateAccess = userFat[fa.Name]?getAddUpdateAccess(userFat[fa.Name]):0;
		var readAccessChecked = readAccess=="1"?"checked":"";
		var addUpdateAccessChecked = addUpdateAccess=="1"?"checked":"";
		return "<tr>"
				  +"<td>"
					+"<div class='clsLblFeatureSNoContainer'>"+(index+1)+"</div>"
				  +"</td>"
				  +"<td>"
					+"<div class='clsLblFeatureNameContainer'>"+fa.DisplayName+"</div>"
				  +"</td>"
				  +"<td>"
					+"<div class='clsChkFeatureReadContainer'>"
						+"<input type='checkbox' name='"+fa.Name+"_r' class='clsChkFeatureRead' "+readAccessChecked+"/>"
					+"</div>"
				  +"</td>"
				  +"<td>"
					+"<div class='clsChkFeatureAddupdateContainer'>"
						+"<input type='checkbox' name='"+fa.Name+"_au' class='clsChkFeatureAddupdate' "+addUpdateAccessChecked+"/>"
					+"</div>"
				  +"</td>"
				+"</tr>";
	}
	$(features).each(function(index,data){
		$container.append(getMeRow(index,data));
	});
}
function updateFat(){
	//alert($('form#idFormUpdateFat').serialize());
	ajaxRequest({
		type: 'post',
		//url: 'UserAction.php',
		url: '../../Action/User/UserAction.php',
		data: $('form#idFormUpdateFat').serialize(),
		success: function(data){
			var jsonData = JSON.parse(data);
			jsonData = JSON.parse(jsonData);
			if(jsonData.IsSuccess) alert('Successfully updated');
			else alert("Something went wrong");
		},
		error: function(error){
			alert('Failed to load services');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}
$(function(){
	loadUsers($("#idSelectUser"),{
		"Action":"GetAllUsers",
		"ParentID":1,
		"IncludeParent":0,
		"IncludeAllSubUsers":0,
		"ExcludeRoleIDs":"1,3,4,5,6",
		"IncludeFeatureAccess":1
	},true,function(isSuccess,resultData){
		//alert();//loadUsers_DT();
		userAndFatJson = resultData;
		showFeatureList($("#idSelectUser").val(),userAndFatJson.fat);
	});
	$("#idSelectUser").change(function(){
		showFeatureList($(this).val(),userAndFatJson.fat);
	});
	$("#idUpdateFat").click(function(){
		updateFat();
		return false;
	});
});