
function paymentAjax(postData,successFn,errorFn){
	ajaxRequest({
		type: 'post',
		url: '../../Action/Payment/PaymentAction.php',
		data: postData,
		success: function(data){
			var jsonData = JSON.parse(data);
			successFn(jsonData);
		},
		error: function(error){
			alert('Failed');
			errorFn(error);
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}
function getUserDetails(userID,callbackFn){
	paymentAjax("Action=GetUserDetailsForTranser&UserID="+userID+"&SearchStr="+userID,function(json){
		callbackFn(json);
	});
}
function loadDDusers(elem,parentID,addSystemUser,excludeRoleIDs,callbackFn){
	
	//loadUsers($("#idSelectUserID, #idSelectFromUser, #idSelectToUser"),{
	elem.html();
	loadUsers(elem,{
		"Action":"GetUsersByParent",
		"ParentID":parentID,
		"IncludeParent":1,
		"IncludeAllSubUsers":1,
		"ExcludeRoleIDs":excludeRoleIDs
	},true,function(isSuccess){
		if(addSystemUser==true && parentID==1){
			var systemUserElem="<option value='0'>0-System</option>";
			$("#idSelectFromUser").append(systemUserElem);
		}
		//By default FROM&TO are selected with same user. So changing the second
		//select dd to next value if both are same
		if($("#idSelectFromUser").val()==$("#idSelectFromUser").val())
			$("#idSelectToUser > option:selected").prop("selected", false).next().prop("selected", true);
			
		if(callbackFn) callbackFn();
		
	});
}