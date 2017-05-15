//Ajax for user  - Ilaiyaalert()
var editUserObj;
function AddUser(){
	ajaxRequest({
		type: 'post',
		//url: 'UserAction.php',
		url: "../../Action/User/UserAction.php",
		data: $('form#idUserForm').serialize(),
		success: function(data){				
			var jsonData = JSON.parse(data);
			$('#errorMsg').fadeIn(100,function(){});
			$('#errorMsg').fadeOut(5000,function(){});
			if(jsonData.isSuccess && $("#UserID").val()==0){
				$('.form-control').val('');
				$("#errorMsg").html("<div class='alert alert-success alert-dismissible'><p>"+jsonData.message+"</p></div>").show().fadeOut(5000,function(){
					window.location.href="../User";
				});
			} else if(!jsonData.isSuccess) { 
				$("#errorMsg").html("<div class='alert alert-danger alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
				$('#idBtnUserForm').attr('disabled', false);
			} else {
				$("#errorMsg").html("<div class='alert alert-success alert-dismissible'><p>"+jsonData.message+"</p></div>").show().fadeOut(5000,function(){
					window.location.href="../User";
				});
			}
			//getParents();
		},
		error: function(error){
			alert('Error:Unable to add user');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}
function getLoggedInUser(){
	ajaxRequest({
		type: 'post',
		//url: 'UserAction.php',
		url: "../../Action/User/UserAction.php",
		data: "Action=GetLoggedInUser",
		success: function(data){
			var jsondata = JSON.parse(data);
			jsondata = JSON.parse(jsondata);
		},
		error: function(error){
			alert(0);
		}
	},{
		isLoader:0,
		loaderElem:$('body')
	});
}
function getParents(RoleIDs){
	ajaxRequest({
		type: 'post',
		//url: 'UserAction.php',
		url: "../../Action/User/UserAction.php",
		data: "Action=GetUsersByRoles&RoleIDs="+RoleIDs,
		success: function(data){
			parentsList = data;
			var jsondata = JSON.parse(data);
			$parentContainer = $("#parentId");
			$parentContainer.html('');
			$(jsondata).each(function(index,value){
				//if(index==0) getRoles(value.RoleID);
				if(isEditUser() && value.UserID==editUserObj.ParentID)
					$parentContainer.append("<option value='"+value.UserID+"' selected>"+value.DisplayID+"-"+value.Name+"</option>");
				else
					$parentContainer.append("<option value='"+value.UserID+"'>"+value.DisplayID+"-"+value.Name+"</option>");
			});
			$parentContainer.trigger('change');
		},
		error: function(error){
			alert(0);
		}
	},{
		isLoader:0,
		loaderElem:$('body')
	});
}
function setRoles(data){
	var parentId=$("#parentId").val();
	var jsondata = JSON.parse(data);
	$roleContainer = $("#userType");
	$roleContainer.html('');
	$(jsondata).each(function(index,value){
		if(isEditUser() && value.RoleID==editUserObj.RoleID)
			$roleContainer.append("<option value='"+value.RoleID+"' selected>"+value.Name+"</option>");
		else
			$roleContainer.append("<option value='"+value.RoleID+"'>"+value.Name+"</option>");
	});
	$roleContainer.trigger('change');
	
}
function getRoles(roleID){
	ajaxRequest({
		type: 'post',
		//url: 'UserAction.php',
		url: "../../Action/User/UserAction.php",
		data: "Action=GetRoles&RoleID="+roleID,
		success: function(data){
			//alert(data);
			roles=data;
			setRoles(roles);
		},
		error: function(error){
			alert(0);
		}
	},{
		isLoader:0,
		loaderElem:$('body')
	});
}
function getGeneralSettings(){
	ajaxRequest({
		type: 'post',
		//url: 'UserAction.php',
		url: "../../Action/User/UserAction.php",
		data: "Action=GetGeneralSettings",
		success: function(data){
			var jsondata = JSON.parse(data);
			//$("#clientLinmit").val(jsondata.ClientLimit);
			$("#distributorFee").val(jsondata.DistributorFee);
			$("#subDistributorFee").val(jsondata.SubDistributorFee);
			$("#retailerFees").val(jsondata.RetailerFee);
		},
		error: function(error){
			alert(0);
		}
	},{
		isLoader:0,
		loaderElem:$('body')
	});
}
function isMobileExists(mobileNo){
	var UserId = $('#UserId').val();
	ajaxRequest({
		type: 'post',
		//url: 'UserAction.php',
		url: "../../Action/User/UserAction.php",
		data: "Action=IsMobileExists&Mobile="+mobileNo+"&UserId="+UserId,
		success: function(data){
			//var jsondata = JSON.parse(data);
			//alert(data==1);
			$("#mobileNo").data('isExists',data);
			var phoneExist1 = $("#mobileNo").data('isExists');
			if(phoneExist1 == 0) {
				$("span.mobileNoErr").html("");
			} else {
				$("span.mobileNoErr").html(" Mobile number already exist.").addClass('validate');
			} 
		},
		error: function(error){
			alert('Error');
		}
	},{
		isLoader:0,
		loaderElem:$('body')
	});
}

function getEditUserDetails(callbackfn){
	var userID = $('#UserID').val();
	if(userID =='') 
		return false;
	ajaxRequest({
		type: 'post',
		//url: 'UserAction.php',
		url: "../../Action/User/UserAction.php",
		data: "Action=GetByID&UserID="+userID,
		success: function(data){		
			var jsonData = JSON.parse(data);
			callbackfn(jsonData);
		},
		error: function(error){
			alert('Error:Unable to get user data');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}
function isEditUser(){
	var userID = $('#UserID').val();
	return (userID !=0);
}
function showEditUserDetails(jsonData){
	$("#customerId").val(jsonData.Name);
	$("#username").val(jsonData.Name);
	$("#mobileNo").val(jsonData.Mobile);
	$("#emailId").val(jsonData.Email);
	$("#gender").val(jsonData.Gender);
	$("#dob").val(jsonData.DOB);
	$("#PAN").val(jsonData.PAN);
	$("#address").val(jsonData.Address);
	$("#remarks").val(jsonData.Remarks);
	$('#password').val('********');
	$("#mobileNo").data('isExists',0);
	var RoleID = jsonData.RoleID;
	
	if(RoleID != 2){
		$('.Business_user').show();
		$("#clientLimit").val(jsonData.ClientLimit);
		$("#distributorFee").val(jsonData.DistributorFee);
		$("#subDistributorFee").val(jsonData.MandalFee);
		$("#retailerFees").val(jsonData.RetailerFee);
		$("#depositeAmt").val(jsonData.Deposit);
		$("#refundable").select2('val',jsonData.Refundable);
		$("#balanceLevel").val(jsonData.BalanceLevel);
	} else {
		$('.Business_user').hide();
		$("#clientLimit").val('0');
		$("#distributorFee").val('0');
		$("#subDistributorFee").val('0');
		$("#retailerFees").val('0');
		$("#depositeAmt").val('0');
		$("#refundable").val(1);
		$("#balanceLevel").val('0');
	}
}
$(function(){
	getLoggedInUser();
	if(isEditUser()) 
	{
		getEditUserDetails(function(user){
			editUserObj=user;
			var UserType = editUserObj.RoleID;
			if(UserType == 3){ var RoleId = '1'; }
			else if(UserType == 4){ var RoleId = '1,3'; }
			else if(UserType == 5){ var RoleId = '1,3,4'; }
			else if(UserType == 6){ var RoleId = '1,3,4,5'; }
			else { var RoleId = '1'; }
			getParents(RoleId)
			showEditUserDetails(editUserObj);
		});
	}else{
		getParents('1,3,4,5');
		getGeneralSettings();	
	}
	
	$("#mobileNo").change(function(){
		isMobileExists($(this).val());
	});
	
	$("#parentId").change(function(){
		//alert(parentsList);
		var parentsJson = JSON.parse(parentsList);
		//var selectedParentID = $(this).val();
		function setRolesForTheParent(selectedParentID){
			$(parentsJson).each(function(index,parent){
				//console.log(index+'-> selectedParentID='+selectedParentID+','+parent.UserID);
				if(selectedParentID == parent.UserID){
					getRoles(parent.RoleID);
				}
			});
		}
		setRolesForTheParent($(this).val());
		if(!isEditUser()){
			getUserDetailsByID($(this).val(),function(user){
				//alert(JSON.stringify(user));
				$("#distributorFee").val(user.DistributorFee);
				$("#subDistributorFee").val(user.MandalFee);
				$("#retailerFees").val(user.RetailerFee);
			},function(){});			
		}
	});
});