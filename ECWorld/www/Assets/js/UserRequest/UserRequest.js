//Data table 
function getAll_DT(id,fromtype,edit,del,editFn){
	function getComplaintStatus(value){
		switch(parseInt(value)){
			case 1:
				return "Pending"; break;
			case 3:
				return "Success";break;
			case 4:
				return "Failed";break;
			default:
				return value;
		}
	}
	var dt=ecwDatatable.init(
		$(id),
		{			
			"url" : _actionUrl+"/UserRequest/UserRequestAction.php",
			"type" : "POST",
			"data" : {
				"Action" : "GetAll_DT",
				"fromTable" : fromtype,
				"status" 	: function(d){ return $("#StatusVal").val();},
				"userId" 	: function(d){ return $("#idSelectUserID").val();}, 
				"mobile" 	: function(d){ return $("#mobile").val();},
				"requestId" : function(d){ return $("#requestId").val();},
				"fromDate"	: function(d){ return $("#fromDate").val();},
				"toDate"	: function(d){ return $("#toDate").val();} 
			}
		},
		1000,
		edit,del,false,0,1,0,
		[

			{"mData" : null,'bVisible':false,'sTitle':'ComplaintID','mRender':function(data){
				return data[0];
			}},
			{"mData" : null,'sTitle':'UserID','mRender':function(data){
				return data[1];
			}},
			{"mData" : null,'sTitle':'Recharge_Date','mRender':function(data){
				return data[2];
			}},
			{"mData" : null,'sTitle':'RequestID','mRender':function(data){
				return data[3];
			}},
			{"mData" : null,'sTitle':'TransactionID','mRender':function(data){
				return data[4];
			}},
			{"mData" : null,'sTitle':'MobileNo','mRender':function(data){
				return data[5];
			}},
			{"mData" : null,'sTitle':'Amount','mRender':function(data){
				return data[6];
			}},
			{"mData" : null,'sTitle':'Network','mRender':function(data){
				return data[7];
			}},
			{"mData" : null,'sTitle':'Request_Date','mRender':function(data){
				return data[8];
			}},
			{"mData" : null,'sTitle':'Status','mRender':function(data){
				return getComplaintStatus(data[9]);
			}},
			{"mData" : null,'sTitle':'Remark','mRender':function(data){
				return data[10];
			}},
			{"mData" : null,'sTitle':'TakenBy','mRender':function(data){
				return data[11];
			}}
			
		],editFn,function(e,id,name,all){
			/*if(confirm("Are you sure? Do you want to delete Mobile No:"+name+"?")) deleteAtuoMnp(id);
			e.preventDefault(); */
		});
}


$("#pending").click(function(){
	$('.modal-title').html('Pending Request');
	$('#SendSms').prop('checked', false);
	$('#FromTable').val(0);
});

$("#complaint").click(function(){
	$('.modal-title').html('Complaint Request');
	$('#SendSms').prop('checked', true);
	$('#FromTable').val(1);
});

$("#myModalAddRequest").click(function(){
	$("#SearchRequest").attr('readonly', false); 
	$("#Transaction").attr('readonly', true); 
	$('.form-control').val('');
	$('#SMSValue').val('Send SMS');
	$("#Status").val(1).change();
	$('#PrevStatus').val(0);
});




//Onchange event for search request id
$("#SearchRequest").on('change', function() {
	var SearchRequest = $(this).val();
	ajaxRequest({
		type: 'post',
		url: _actionUrl+"/UserRequest/UserRequestAction.php",
		data: "Action=GetUserRequest&SearchRequest="+SearchRequest,
		success: function(data){
			var jsondata = JSON.parse(data);
			if(jsondata.length !=''){
				$("span.requestErr").html(""); 
				$('#RequestID').val(jsondata[0].RequestID);
				$('#Mobile_no').val(jsondata[0].ReachargeNo);
				$('#Amount').val(jsondata[0].Amount);
				$('#Transaction').val(jsondata[0].RcResOpTransID);
			} else {
				alert('Enter Valid RequestID');
				$('#RequestID').val('');
				$('#Mobile_no').val('');
				$('#Amount').val('');
			}
		
		},
		error: function(error){
			alert('Failed to add service');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
});

/*
function refreshAllData_DT(){	
	$("#idTblComplaintData").DataTable().ajax.reload();
	$("#idTblPendingData").DataTable().ajax.reload();
}
*/

var isDTLoaded =false;
function refreshComplaintData_DT(){
	if(isDTLoaded){
		var active = $('#FromTable').val();
		if(active == 1)
			$("#idTblComplaintData").DataTable().ajax.reload();
		else
			$("#idTblPendingData").DataTable().ajax.reload();
	}
	else {
		//Data table 
	getAll_DT("#idTblComplaintData",1,true,false,function(e,id,name,all){
		e.preventDefault();
		var dataArr = all.split(',');
		upsertComplaintPopup(false,all);
	});
	getAll_DT("#idTblPendingData",0,true,false,function(e,id,name,all){
		e.preventDefault();
		var dataArr = all.split(',');
		upsertComplaintPopup(false,all);
	});
		isDTLoaded = true;
	}
	
}


//Upsert function
function upsertUserComplaint(){
	ajaxRequest({
		type: 'post',
		url: _actionUrl+"/UserRequest/UserRequestAction.php",
		data: $('form#idFrmAddComplaint').serialize(),
		success: function(data){
			var jsonData = JSON.parse(data);
			var idComplaintID = $('#idComplaintID').val();
			$('#idSuccessErrMsg').fadeIn(100,function(){});
			$('#idSuccessErrMsg').fadeOut(2000,function(){});
			
			if(jsonData.isSuccess && idComplaintID==0){
				$("#idSuccessErrMsg").html("<span class='alert alert-success'>"+jsonData.message+"</span>").show();
				$('.form-control').val('');
				$('#idSuccessErrMsg').fadeOut(2000,function(){
					$('#myModal').modal('hide');
					$('#idBtnAddComaplaint').attr('disabled', false);
				});
			} else if(jsonData.isSuccess && idComplaintID != 0){
				$("#idSuccessErrMsg").html("<span class='alert alert-success'>Successfully Updated</span>").show();
				$('#idSuccessErrMsg').fadeOut(2000,function(){
					$('#myModal').modal('hide');
					$('#idBtnAddComaplaint').attr('disabled', false);
				});
			}else{
				$("#idSuccessErrMsg").html("<span class='alert alert-danger'>"+jsonData.message+"</span>").show();	
				$('#idBtnAddComaplaint').attr('disabled', false);
			}
			refreshComplaintData_DT();
		},
		error: function(error){
			alert('Failed to add service');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}


function upsertComplaintPopup(isAdd,data){	
	//alert(data);
	$modalPopup = $("#myModal");
	$modalPopup.modal("toggle");
	if(!isAdd){
		dataArr = data.split(',');
		/*3,1,1970-01-08 00:00:00,W17B191,812474169;channelname=ATL1,9740074855,20,AIRTEL
,2017-03-19 12:05:36,1,dgdf,Admin */
		$modalPopup.find("#idComplaintID").val(dataArr[0]);
		$modalPopup.find("#RequestID").val(dataArr[12]);
		$modalPopup.find("#SearchRequest").val(dataArr[3]);
		$modalPopup.find("#Transaction").val(dataArr[4]);
		$modalPopup.find("#SearchRequest").attr('readonly', true); 
		$modalPopup.find("#Transaction").attr('readonly', false); 
		$modalPopup.find("#Mobile_no").val(dataArr[5]);
		$modalPopup.find("#Amount").val(dataArr[6]);
		$modalPopup.find("#Status").val(dataArr[9]).change();
		$modalPopup.find("#PrevStatus").val(dataArr[9]);
		$modalPopup.find("#Remark").val(dataArr[10]);

	}else{
		
		$('.form-control').val('');
	}
}


function showUsers(){
	loadUsers($("#idSelectUserID"),{
		"Action":"GetUsersByRoles",
		"RoleIDs":"1,3,4,5",
	},false,function(isSuccess){
		$('#idSelectUserID').prepend('<option selected="selected" value=""> Select User </option>');
	});
}

$(function(){
	showUsers();
	refreshComplaintData_DT();
	$("#Search_PayCollection").click(function(e){
		refreshComplaintData_DT();
		return false;
	});
	
	$("#idBtnAddComaplaint").click(function(e){
		var validation_holder = 0;
		var SearchRequest   = $("#SearchRequest").val();
		var RequestID   	= $("#RequestID").val();
		var Transaction 	= $("#Transaction").val();
		var Mobile_no 		= $("#Mobile_no").val(); 
		var Amount 			= $("#Amount").val();
		var Status 			= $("#Status").val();
		var Remark 			= $("#Remark").val();		
		
		if($.trim(SearchRequest) == "") {
			$("span.requestErr").html(" is required.").addClass('validate');
			validation_holder = 1;
		} else { 
			if($.trim(RequestID) == ""){
				$("span.requestErr").html(" Enter valid RequestID.").addClass('validate');
				validation_holder = 1;
			} else { $("span.requestErr").html(""); }
		}
		
		if($.trim(Transaction) == "") {
			$("span.transErr").html(" is required.").addClass('validate');
			validation_holder = 1;
		} else { $("span.transErr").html(""); }
		
		if($.trim(Status) == "") {
			$("span.statusErr").html(" is required.").addClass('validate');
			validation_holder = 1;
		} else { $("span.statusErr").html(""); }
		
		if($.trim(Mobile_no) == "") {
			$("span.mobileErr").html(" is required.").addClass('validate');
			validation_holder = 1;
		} else { $("span.mobileErr").html(""); }
		
		if($.trim(Mobile_no) == "") {
			$("span.amountErr").html(" is required.").addClass('validate');
			validation_holder = 1;
		} else { $("span.amountErr").html(""); }
		
		if($.trim(Remark) == "") {
			$("span.remarkErr").html(" is required.").addClass('validate');
			validation_holder = 1;
		} else { $("span.remarkErr").html(""); }
		
		if(validation_holder == 1) { 
			$("div.validate_msg").slideDown("fast");
			return false;
		}  else { 
			validation_holder = 0; 
			$('#idBtnAddComaplaint').attr('disabled', true);
			$("div.validate_msg").slideUp("fast"); 
			upsertUserComplaint();
			return false;
		}
	});

	
});


/*
//Auto complete function 
 $('#RequestID').autocomplete({
	source: function( request, response ) {
		$.ajax({
		  url : 'UserRequestAction.php',
		  dataType: "json",
		data: {
		 Action: 'GetUserRequest', 
		 SearchRequest: request.term,
		 row_num : 1
		},
		success: function( data ) { //debugger
		response( $.map( data, function( item ) {
			console.log(item);
			return {
				label: item.Name,
				value: item.Name,
				data : item
				}
			}));
			}
		});
		},
		autoFocus: true,          
		minLength: 0,
		select: function( event, ui ) {
		//alert(ui.item.data.Mobile);          
		$('#Mobile_no').val(ui.item.data.Mobile);

    }           
});
*/
            