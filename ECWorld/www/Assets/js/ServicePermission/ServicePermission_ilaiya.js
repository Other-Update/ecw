//Ajax - Ilaiya
var _currentPage = 'UserPermission';
var _curFormElem = null;
var _curTabContentElem = null;

function loadUsers_new(){

	var elem = $("#idSelectUserID");
	elem.html();
	loadUsers(elem,{
			"Action":"GetUsersByParent",
			"ParentID":1,
			"IncludeParent":1,
			"IncludeAllSubUsers":1,
			"ExcludeRoleIDs":"2",
			"IncludeFeatureAccess":1
	},true,function(isSuccess){
		
		loadServicePermission($(elem).val());
	});
}		
function loadUsers_old(){
	ajaxRequest({
		type: 'post',
		//url: '../User/UserAction.php',
		url:_actionUrl+'/User/UserAction.php',
		//data: "Action=GetUsersByRoles&RoleIDs=1,3,4,5",
		data:{
			"Action":"GetAllUsers",
			"ParentID":1,
			"IncludeParent":1,
			"IncludeAllSubUsers":1,
			"ExcludeRoleIDs":"2",
			"IncludeFeatureAccess":1
		},
		success: function(data){
			//console.log(data);
			var jsondata = JSON.parse(JSON.parse(data));
			//console.log(JSON.parse(jsondata));
			$userContainer = $("#idSelectUserID");
			$userContainer.html('');
			var isFirstUserAdded=false;
			$(jsondata.users).each(function(index,value){
				//Don't show Admin (UserID is 1)
				//TODO: Actually shoudln't show admin. Since general is not for admin(general is fro system ) we have show admin . This is for only service permission. Actuall we have to change it like rc gateway.
				//if(value.UserID!=1){
					$userContainer.append("<option value='"+value.UserID+"'>"+value.DisplayID+"-"+value.Mobile+"-"+value.Name+"</option>");
					if(!isFirstUserAdded)
					{
						isFirstUserAdded=true;
						loadServicePermission(value.UserID);
					}
					//alert($userContainer.html());
				//}
			})
		},
		error: function(error){
			alert('Failed to get users');
		}
	},{
		isLoader:0,
		loaderElem:$('body')
	});
}
function loadServicePermission(userID){
	//alert('loadServicePermission=userID='+userID);
	
	_curTabContentElem.find("#idUserID").val(userID);
	ajaxRequest({
		type: 'post',
		url: _actionUrl+'/RCPermission/PermissionAction.php',
		data: "Action=GetByUserID&UserID="+userID,
		success: function(data){
			var jsondata = JSON.parse(data);
			if(jsondata.isSuccess==true){
				jsondata=JSON.parse(jsondata.data);
				var spObj=jsondata.ServicePermission;
				_curTabContentElem.find("#idServicePermissionID").val(spObj.ServicePermissionID);
				var spaObjList=jsondata.ServicePermissionAssign;
				//console.log('spaObjList='+JSON.stringify(spaObjList));
				function getServicePermissionElem(obj){
					var checked=(obj.IsEnabled==true)?"checked":"";
					var servicePermissionAssignID = obj.ServicePermissionAssignID>0 ? obj.ServicePermissionAssignID:0;
					// var servicePermissionID = obj.ServicePermissionID>0 ? obj.ServicePermissionID:0;
					return '<div class="col-md-4">'
							+'<div class="input-group">'
								+'<input type="hidden" name="ServicePermissionAssignID[]" value="'+servicePermissionAssignID+'">'
								+'<input type="hidden" name="ServiceID[]" value="'+obj.ServiceID+'">'
								/* +'<input type="hidden" name="ServicePermissionID[]" value="'+servicePermissionID+'">' */
								+'<span class="input-group-addon"><input name="IsEnabled[]" type="checkbox" '+checked+' name="idChkEnable" class="clsChkEnable"></span>'
								+'<input type="text" class="form-control" value="'+obj.Name+'" readonly>'
							+'</div>'
						+'</div>';
				}
				function getMinChargeElem(obj){
					if(obj.Commission == null || obj.Commission == ''){
					return '<div class="col-md-4">'
							+'<div class="form-group">'
								+'<input type="text" name="MinCharge[]" class="form-control" value="0.00">'
							+'</div>'
						+'</div>';
					} else {
						return '<div class="col-md-4">'
							+'<div class="form-group">'
								+'<input type="text" name="MinCharge[]" class="form-control" value="'+obj.MinCharge+'">'
							+'</div>'
						+'</div>';
					}
				}
				function getNeworkCommissionElem(obj){
					if(obj.Commission == null || obj.Commission == ''){
						return '<div class="col-md-4">'
							+'<div class="form-group">'
								+'<input type="text" name="Commission[]" class="form-control" value="0.00">'
							+'</div>'
						+'</div>';
					}else{
					return '<div class="col-md-4">'
							+'<div class="form-group">'
								+'<input type="text" name="Commission[]" class="form-control" value="'+obj.Commission+'">'
							+'</div>'
						+'</div>';
					}
						
				}
				var htmlContent='';
				var sequence='';
				$(spaObjList).each(function(index,spaObj){
					htmlContent+=getServicePermissionElem(spaObj)+getMinChargeElem(spaObj)+getNeworkCommissionElem(spaObj);
					if(spaObj.IsEnabled != null)
						sequence+=spaObj.IsEnabled;
					else
						sequence+=0;
				});
				_curTabContentElem.find("#idCheckSequence").val(sequence);
				_curTabContentElem.find("#idServiceContainer").html(htmlContent);
				_curTabContentElem.find("#idIsOTFMinCharge").prop('checked',spObj.IsOTFMinCharge>0);
				_curTabContentElem.find("#idIsOTFCommission").prop('checked',spObj.IsOTFCommission>0);
				_curTabContentElem.find("#idIsFirstSMSCost").prop('checked',spObj.IsFirstSMSCost>0);
				_curTabContentElem.find("#idOTFMinCharge").val(spObj.OTFMinCharge ? spObj.OTFMinCharge:0);
				_curTabContentElem.find("#idFirstSMSCost").val(spObj.FirstSMSCost ? spObj.FirstSMSCost:0);
				if(isUserPermissionPage()){
					_curTabContentElem.find("#idIsAppliedForGroup").prop('checked',spObj.IsAppliedForGroup>0);
					_curTabContentElem.find("#idIsAppliedForSubGroup").prop('checked',spObj.IsAppliedForSubGroup>0);
				}
			}else{
				//alert(_curFormElem.attr('id'));
				//_curFormElem[0].reset();
				//_curTabContentElem.find('input').val('');
				alert(jsondata.message);
				_curTabContentElem.hide();
			}
		},
		error: function(error){
			alert('Failed to load services');
		}
	},{
		isLoader:0,
		loaderElem:$('body')
	});
}
function updateUserServicePermission(){
	//alert(isUserPermissionPage());
	ajaxRequest({
		type: 'post',
		url: _actionUrl+'/RCPermission/PermissionAction.php',
		data: _curFormElem.serialize(),
		success: function(data){
			console.log('updateUserServicePermission='+data);
			if(data==1) alert('Updated');
			else alert('Failed');
			loadServicePermission(_curTabContentElem.find("#idUserID").val());
			//window.location.reload();
			//var jsondata = JSON.parse(data);
		},
		error: function(error){
			alert('Failed to update Recharge Permission');
		}
	},{
		isLoader:1,
		loaderElem:$('.content')
	});
}
function setCurrentPage(pageName){
	_currentPage = pageName;
	if(pageName=='GeneralPermission'){
		_curFormElem = $("#idGeneralServicePermissionForm");
		_curTabContentElem=$("#idGeneralTabContent");
		_currentPage = pageName;//'GeneralPermission';
	}
	else{
		_curFormElem = $("#idUserServicePermissionForm");
		_curTabContentElem=$("#idUserTabContent");
		_currentPage = pageName;//'UserPermission';
	}
}
function isUserPermissionPage(){
	return _currentPage == 'UserPermission';
}
function initPage(){
	setCurrentPage('UserPermission');
	//If it is General recharge permission(Contains word 'General') then 
	//load users and then load general recharge permission(UserID is -1)
	/* if(_currentPage == 'GeneralPermission')
		loadServicePermission(-1);//-1 is userID for GeneralRecharge
	else
		loadUsers(); */
	loadUsers_new();
}
$(function(){
	initPage();
	$("#idSelectUserID").change(function(){	
		loadServicePermission($(this).val());
	});
	//alert(_curTabContentElem.find("#clsBtnServicePermission").attr('id'));
	$(".clsBtnServicePermission").click(function(){
	
		updateUserServicePermission();
		return false;
	});
	
	//Keep track of '.clsChkEnable' checks in a textbox(#idCheckSequence)
	//From backend access this textbox instead of checkboxes. 
	//Bcoz, checkboxes won't send anything if it is unchecked
	//which is difficult to calculate which check check box is for which service
	$("body").on('click','.clsChkEnable',function(){
		var sequence='';
		_curTabContentElem.find("#idServiceContainer").find('.clsChkEnable').each(function(index,elem){
			sequence+=$(elem).prop('checked')?1:0;
		});
		console.log('Updated sequence='+sequence);
		_curTabContentElem.find("#idCheckSequence").val(sequence);
	});
	$("#UserTab").click(function(){
		setCurrentPage('UserPermission');
		loadUsers();
	});
	$("#GeneralTab").click(function(){
		setCurrentPage('GeneralPermission');
		loadServicePermission(-1);//-1 is userID for GeneralRecharge
	});
	
});