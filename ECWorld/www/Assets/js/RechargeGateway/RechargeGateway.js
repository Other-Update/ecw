//Common
function submitForm(form,callbackFn){
	ajaxRequest({
		type: 'post',
		url: _actionUrl+'/RechargeGateway/RCGatewayAction.php',
		data: $(form).serialize(),
		success: function(data){
			callbackFn(data);
		},
		error: function(error){
			alert('Failed to load services');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}
/* *********************************
	    User/General Gateway
********************************* */
function initUserGatewayDetails(tableID,userSelectionID,functionName){	
	function getCommaSeperated(val){
		if(val) return val.replace(/#/g , ",");
		else return "";
	}
	var dt=ecwDTAdv.init(
		$("#"+tableID),
		{			
			"url" : _actionUrl+"/RechargeGateway/RCGatewayAction.php",
			"type" : "POST",
			"data" : {
				"Action" : "GetUserGateway_DT",
				"UserID" : function(d){ return $("#"+userSelectionID).val();}
			}
		},
		{
			"PageLength":1000,
			"Columns":[
				{"mData" : null,'bVisible':false,'sTitle':'RCUserGatewayID','mRender':function(data){
					return data[0];
				}},
				{"mData" : null,'bVisible':false,'sTitle':'UserID','mRender':function(data){
					return data[1];
				}}, 
				{"mData" : null,'bVisible':false,'sTitle':'ServiceID','mRender':function(data){
					return data[2];
				}},
				{"mData" : null,'sTitle':'Network','mRender':function(data){
					return data[3];
				}},
				{"mData" : null,'sTitle':'PrimaryGateway','mRender':function(data){
					return data[4];
				}},
				{"mData" : null,'sTitle':'SecondaryGateway','mRender':function(data){
					return data[5];
				}},
				{"mData" : null,'sTitle':'Amount','mRender':function(data){
					return getCommaSeperated(data[6]);
					/* if(data[6])
						return data[6].replace(/#/g , ","); 
					else return ""; */
				}}
			]
		},
		{
			"Edit":{
				"IsEnabled":true
			},
			"Delete":{
				"IsEnabled":true
			},
			"Checkbox":{
				"IsEnabled":true
			},
			"UniqueIdColumnIndex":0,
			"NameColumnIndex":0,
			"ExcludeColumnIndex":0,
			"OrderByColumnIndex":1
		},function(e,id,name,all){
			//alert(id);
			openUpdateUserGatewayAmountPop(false,all,functionName);
		},function(e,id,name,all){
			if(confirm("Are you sure want to delete "+name+"?"))
				deleteUserRCGateway(id);
		},function(e,id,all){
			alert(id);
		});
}

function deleteUserRCGateway(id){
	ajaxRequest({
		type: 'post',
		url: _actionUrl+"/RechargeGateway/RCGatewayAction.php",
		data: "Action=DeleteUserGatewayService&RCUserGatewayID="+id,
		success: function(data){
			var jsonData = JSON.parse(data);
			alert(jsonData.message);
			refreshUserGatewayDetails("idTblUserGateway");
			/* if(jsonData.isSuccess) {
				//refreshUserGatewayDetails("idTblUserGateway");
				//refreshUserGatewayDetails("idTblGeneralGateway");
			} else {
			} */
		},
		error: function(error){
			alert('Failed to load services');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}
function refreshUserGatewayDetails(tableID){	
	$('#'+tableID).DataTable().ajax.reload();
}
function showUsers(){
	loadUsers($("#idSelectUserID"),{
		"Action":"GetUsersByParent",
		"ParentID":"1",
		"ExcludeRoleIDs":"2"
	},false,function(isSuccess){
		initUserGatewayDetails("idTblUserGateway","idSelectUserID","User");
		initUserGatewayDetails("idTblGeneralGateway","idSelectAdminID","General");
	});
}

function openUpdateUserGatewayAmountPop(isAdd,data,functionName){	
	$modalPopup = $("#idPopUpdateUserRCAmount");
	$modalPopup.modal("toggle");
	$('#idFrmUpdateUserRCAmount .form-control').val('');
	$("#idUpdateAmountResult").html('');
	if(!isAdd){
		dataArr = data.split(',');
		$modalPopup.find(".modal-title").html("Update "+functionName+" Recharge Amount").attr("data-functionname",functionName);
		$modalPopup.find("#idRCUserGatewayID").val(dataArr[0]);
		var userOrgeneral = $("#idPopUpdateUserRCAmount").find(".modal-title").attr("data-functionname");
		if(userOrgeneral=="User")
		$modalPopup.find("#idUserID").val($("#idSelectUserID").val());
		else
		$modalPopup.find("#idUserID").val(1);
		$modalPopup.find("#idServiceID").val(dataArr[2]);
		$modalPopup.find("#idServiceName").val(dataArr[3]);
		$modalPopup.find("#idAmount").val(dataArr[6].replace(/#/g , ","));
		//dataArr[6].replace(/#/g , ",");
	}else{
		//There is no add popup for user rc amount
		//$('form#idFrmAddService').reset();
		$('#idFrmUpdateUserRCAmount .form-control').val('');
	}
}

function upsertUserRechargeAmount(){
	//alert($("#idSelectUserID").val());
	//return false;
	ajaxRequest({
		type: 'post',
		url: _actionUrl+"/RechargeGateway/RCGatewayAction.php",
		data: $('form#idFrmUpdateUserRCAmount').serialize(),
		success: function(data){
			var jsonData = JSON.parse(data);
			if(jsonData.isSuccess) {
				$("#idUpdateAmountResult").html("<div class='alert alert-success alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
				//$modalPopup = $("#idPopUpdateUserRCAmount");
				//$modalPopup.modal("toggle");
				$('#idUpdateAmountResult').fadeOut(2000,function(){
					$('#idPopUpdateUserRCAmount').modal('hide');
					$('#idBtnUpdateRCUserAmount').attr('disabled', false);
				});
				var userOrgeneral = $("#idPopUpdateUserRCAmount").find(".modal-title").attr("data-functionname");
				if(userOrgeneral=="User")
					refreshUserGatewayDetails("idTblUserGateway");
				else
					refreshUserGatewayDetails("idTblGeneralGateway");
			} else {
				$("#idUpdateAmountResult").html("<div class='alert alert-danger alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
				$('#idBtnUpdateRCUserAmount').attr('disabled', false);
			}
		},
		error: function(error){
			alert('Failed to load services');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}

function loadUsersToAssignToGeneral(){
	ajaxRequest({
		type: 'post',
		url: _actionUrl+"/RechargeGateway/RCGatewayAction.php",
		data: "Action=GetAssignedUsers",
		success: function(data){
			function getUserCheckHtml(obj){
				var checked=(obj.IsAssigned==true)?"checked":"";
				//alert(user.Name);
				return '<div class="col-md-4">'
							+'<div class="input-group">'
								+'<input type="hidden" name="RCGenralGatewayAssignID[]" value="'+obj.RCGenralGatewayAssignID+'">'
								+'<input type="hidden" name="RCUserGatewayUD[]" value="'+obj.RCUserGatewayUD+'">'
								+'<input type="hidden" name="UserID[]" value="'+obj.UserID+'">'
								+'<span class="input-group-addon"><input type="checkbox" class="clsChkEnable" name="UserCheck[]" value="'+obj.UserID+'" '+checked+' ></span>'
								+'<input type="text" class="form-control" value="'+obj.Name+'" readonly>'
							+'</div>'
						+'</div>';
			}
			
			var jsonData = JSON.parse(data);
			var htmlContent='';
			$(jsonData).each(function(index,user){
				//console.log(service.Name);
				htmlContent+=getUserCheckHtml(user);
			});
			$("#idSelectUsersContent").html('').html(htmlContent);
		},
		error: function(error){
			alert('Failed to load services');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}
function upsertAssignUsers(form){
	submitForm(form,function(data){
		var jsonData = JSON.parse(data);
		if(jsonData.isSuccess){
			//$('form#idFrmAssignUsers')[0].reset();
			$("#idFrmAssignUsers #errorMsg").html("<div class='alert alert-success alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
		}else {
			$("#idFrmAssignUsers #errorMsg").html("<div class='alert alert-danger alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
		}
	});
	
}
/* *********************************
	    RC Gateway API
********************************* */
function initGatewayAPIs_DT(){	
	//alert('loadSelectedGateway='+gatewayID);
	var dt=ecwDatatable.init(
		$("#idTblGatewayAPI"),
		{			
			"url" : _actionUrl+"/RechargeGateway/RCGatewayAction.php",
			"type" : "POST",
			"data" : {
				"Action" : "GetGatewayAPIDetails_DT",
				"RcGatewayID" : function(d){ return $("#selectGatewayID").val();}
			}
		},
		500,
		true,false,false,0,1,0,
		[
			{"mData" : null,'bVisible':false,'sTitle':'RCGatewayDetailsID','mRender':function(data){
				return data[0];
			}},
			{"mData" : null,'sTitle':'Name','mRender':function(data){
				return data[3];
			}},
			{"mData" : null,'sTitle':'RechargeCode','mRender':function(data){
				return data[4];
			}},
			{"mData" : null,'sTitle':'TopupCode','mRender':function(data){
				return data[5];
			}}
		],function(e,id,name,all){
			openPopupUpsertGatewayServiceCode(false,all);
			e.preventDefault();
		},function(e,id,name,all){
			if(confirm("Are you sure want to delete "+name+"?")) 
				deleteGatewayNetwork(id);
			e.preventDefault();
		});
}
function refreshGatewayAPIDetails(){	
	$('#idTblGatewayAPI').DataTable().ajax.reload();
}
function showGateway(){
	ajaxRequest({
		type: 'post',
		url: _actionUrl+"/RechargeGateway/RCGatewayAction.php",
		data: "Action=GetGatewayAPIList",
		success: function(data){
			var jsonData = JSON.parse(data);
			$('.selectGatewayID').html("");
			$(jsonData).each(function(index,value){  
				$('.selectGatewayID').append("<option data-url='"+value.URL+"' value='"+value.RCGatewayID+"'>"+value.Name+"</option>");
			});
			$("#idDivSelectedGatewayURL").html("API: "+$(".selectGatewayID option:selected").data('url'));
			initGatewayAPIs_DT();
		},
		error: function(error){
			alert('Failed to load gateway');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}

function openPopupUpsertGatewayServiceCode(isAdd,data){	
	//alert(data);
	$modalPopup = $("#updateRC_Code");
	$modalPopup.modal('show');
	//$modalPopup.modal("toggle");
	if(!isAdd){
		dataArr = data.split(',');
		$modalPopup.find(".modal-title").html("Update Recharge Code");
		//alert($("#selectGatewayID").val());
		$modalPopup.find("#idRCGatewayDetailsID").val(dataArr[0]);
		$modalPopup.find("#idRcGatewayID").val($("#selectGatewayID").val());
		$modalPopup.find("#idServiceID").val(dataArr[2]);
		$modalPopup.find("#Name").val(dataArr[3]);
		$modalPopup.find("#RechargeCode").val(dataArr[4]);
		$modalPopup.find("#TopupCode").val(dataArr[5]);
	}else{
		//This function is not being called for opening add popup
		//$('form#idFrmAddService').reset();
		$('.form-control').val('');
	}
}
function upsertGatewayAPI(){
	ajaxRequest({
		type: 'post',
		url: _actionUrl+"/RechargeGateway/RCGatewayAction.php",
		data: $('form#idFrmAddGateway').serialize(),
		success: function(data){
			var jsonData = JSON.parse(data);
			$('#errorMsg').fadeIn(100,function(){});
			$('#errorMsg').fadeOut(5000,function(){});
			if(jsonData.isSuccess && $("#idGatewayID").val()==0){
				$('form#idFrmAddGateway')[0].reset();
				$("#errorMsg").html("<div class='alert alert-success alert-dismissible'><p>"+jsonData.message+"</p></div>");
				showGateway();
			}else if(jsonData.isSuccess && $("#idGatewayID").val()>0){
				$("#errorMsg").html("<div class='alert alert-success alert-dismissible'><p>"+jsonData.message+"</p></div>");
				showGateway();
			}else {
				$("#errorMsg").html("<div class='alert alert-danger alert-dismissible'><p>"+jsonData.message+"</p></div>");
			}
		},
		error: function(error){
			alert('Failed to load services');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}

//Rcharge gateway api Serivce code function 
function upsertAPIServiceCode(){
	ajaxRequest({
		type: 'post',
		url: _actionUrl+"/RechargeGateway/RCGatewayAction.php",
		data: $('form#idFrmUpdateRCCode').serialize(),
		success: function(data){
			var jsonData = JSON.parse(data);
			if(jsonData.isSuccess) {
				$("#errorMsg1").html("<div class='alert alert-success alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
				refreshGatewayAPIDetails();
				$('#errorMsg1').fadeOut(2000,function(){
					$('#updateRC_Code').modal('hide');
					$('#idBtnUpdateRCCode').attr('disabled', false);
				});
			} else {
				$("#errorMsg1").html("<div class='alert alert-danger alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
				$('#idBtnUpdateRCCode').attr('disabled', false);
			}
		},
		error: function(error){
			alert('Failed to load services');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}

//General Api edit multiple api 
function idBtnUpdateGeneralApi(){
	var UserID = $('#UserID').val();
	ajaxRequest({
		type: 'post',
		url: _actionUrl+"/RechargeGateway/RCGatewayAction.php",
		data: $('form#idFrmUpdateGeneralApi').serialize(),
		success: function(data){
			var jsonData = JSON.parse(data);
			if(jsonData.isSuccess) {
				$("#errorMsgApi").html("<div class='alert alert-success alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
				if(UserID == 1) {
				$('#idTblGeneralGateway').DataTable().ajax.reload();
				} else {
					$('#idTblUserGateway').DataTable().ajax.reload();
				}
				$('#errorMsgApi').fadeOut(2000,function(){
					$('#myModalApi').modal('hide');
					$('#idBtnUpdateGeneralApi').attr('disabled', false);
				});
			} else {
				$("#errorMsgApi").html("<div class='alert alert-danger alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
				$('#idBtnUpdateGeneralApi').attr('disabled', false);
			}
		},
		error: function(error){
			alert('Failed to load services');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}

function deleteGateway(gatewayID){
	//alert(gatewayID);
	ajaxRequest({
		type: 'post',
		url: _actionUrl+"/RechargeGateway/RCGatewayAction.php",
		data: "Action=DeleteGatewayApi&RCGatewayID="+gatewayID,
		success: function(data){
			var jsonData = JSON.parse(data);
			if(jsonData.isSuccess) {
				showGateway();
			}
			alert(jsonData.message);
		},
		error: function(error){
			alert('Failed to load services');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}
/* *********************************
	    Document Ready Calls
********************************* */
$(function(){
	showUsers();
	showGateway();
	$("#idFrmUpdateUserRCAmount").submit(function(e){
		$('#idBtnUpdateRCUserAmount').attr('disabled', true);
		upsertUserRechargeAmount();
		e.preventDefault();
		return false;
	}); 
	$("#idSelectUserID").change(function(){
		refreshUserGatewayDetails("idTblUserGateway");
	});
	
	//Gateway API
	$("#selectGatewayID").change(function(){
		$("#idDivSelectedGatewayURL").html("API: "+$("#selectGatewayID option:selected").data('url'));
		refreshGatewayAPIDetails();
	});
	$("#idBtnAddGatewayPopup").click(function(e){
		$('#idFrmAddGateway .form-control').val('');
	});
	$("#idBtnEditGatewayPopup").click(function(e){
		$modalPopup = $("#idPopupAddEditGateway");
		$('#idFrmAddGateway .form-control').val('');
		$modalPopup.find(".modal-title").html("Edit Gateway");
		$modalPopup.find("#idGatewayID").val($("#selectGatewayID").val());
		$modalPopup.find("#idAddGatewayTxtName").val($("#selectGatewayID option:selected").text());
		$modalPopup.find("#idAddgatewayTxtURL").val($("#selectGatewayID option:selected").data('url'));
	});
	$("#idBtnDeleteGatewayPopup").click(function(e){
		if(confirm("Are you sure want to delete "+$("#selectGatewayID option:selected").text()+"?"))
			deleteGateway($("#selectGatewayID").val());
	});
	$("#idFrmAddGateway").submit(function(e){
		upsertGatewayAPI();
		e.preventDefault();
		return false;
	}); 
	$("#idBtnUpdateRCCode").click(function(e){
		$(this).attr('disabled', true);
		upsertAPIServiceCode();
		e.preventDefault();
	});
	$("#idBtnAssignUsers").click(function(e){
		loadUsersToAssignToGeneral();
	});
	$("#idFrmAssignUsers").submit(function(e){
		upsertAssignUsers(this);
		e.preventDefault();
		return false;
	}); 
	
	//Reghu
	$("#idBtnUpdateGeneralApi").click(function(e){
		$(this).attr('disabled', true);
		idBtnUpdateGeneralApi();
		e.preventDefault();
	});
	
	
});

//Get General & User EditApi value
$("#myModalApiGeneral, #myModalApiUser").click(function(e){
	$('#idBtnUpdateGeneralApi').attr('disabled', false);
	$modalPopup = $("#myModalApi");
	$('#idFrmUpdateApi .form-control').val('');
	var checkVal = [];
	var networkNames="";
	var ServiceID="";
	var RCUserGatewayID="";
	
	var userOrgeneral = $(this).data('type')
	
	//alert(userOrgeneral);
	if(userOrgeneral=="User"){
		var selectedChecks = $("#idTblUserGateway").find("input[name='DTChkSelect']:checked");
		$("#UserID").val($('#idSelectUserID').val());
	}
	else {
		var selectedChecks = $("#idTblGeneralGateway").find("input[name='DTChkSelect']:checked");
		$("#UserID").val(1);
	}
	$.each(selectedChecks, function(index,elem){            
		checkVal.push($(this).data('all'));
		var dataArr = $(this).data('all');
		console.log(dataArr);
		
		var data = $(this).data('all').split(',');
		//alert(data[data.length-1]);
		$("#PrimaryGateway").val(data[data.length-2]).change();
		$("#SecondaryGateway").val(data[data.length-1]).change();
		if(index==0){
			networkNames+=data[3];
			ServiceID+=data[2];
			RCUserGatewayID+=data[0];
		}else {
			networkNames+=","+data[3];
			ServiceID+=","+data[2];
			RCUserGatewayID+=","+data[0];
		}	
	});
	$("#NetworkName").val(networkNames);
	$("#ServiceID").val(ServiceID);
	$("#RCUserGatewayID").val(RCUserGatewayID);
});
