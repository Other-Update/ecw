//Ajax - Ilaiya
var datatable;
var _rechargeTypes;
var dt;
function loadAllServices(){//alert(1);
	dt=ecwDTAdv.init(
		$("#idTblServiceList"),
		{			
			"url" : _actionUrl+'/Services/ServiceAction.php',
			"type" : "POST",
			"data" : {
				"Action" : "GetServices_DT"
			}
		},
		{
			"PageLength":500,
			"Columns":[
				{"mData" : null,'bVisible':false,'sTitle':'ServiceID','mRender':function(data){
					return data[0];
				}},
				{"mData" : null,'sTitle':'Name','mRender':function(data){
					return data[1];
				}},
				{"mData" : null,'sTitle':'RechargeCode','mRender':function(data){
					return data[2];
				}},
				{"mData" : null,'sTitle':'TopupCode','mRender':function(data){
					return data[3];
				}},
				{"mData" : null,'sTitle':'Default Type','mRender':function(data){
					return data[5];
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
				"IsEnabled":false
			},
			"SerialNo":{
				"IsEnabled":true
			},
			"UniqueIdColumnIndex":0,
			"NameColumnIndex":1,
			"ExcludeColumnIndex":0,
			"OrderByColumnIndex":1
		},function(e,id,name,all){
			upsertServicePopup(false,all);
			e.preventDefault();
		},function(e,id,name,all){
			if(confirm("Are you sure want to delete "+name+"?")) deleteService(id);
			e.preventDefault();
		});
	isDtInitialized=true;
}
function reloadServices(){
	//("#idServiceID").val(0);
	$('#idTblServiceList').DataTable().ajax.reload();
}
function upsertService(){
	ajaxRequest({
		type: 'post',
		url: _actionUrl+'/Services/ServiceAction.php',
		data: $('form#idFrmAddService').serialize(),
		success: function(data){
			var jsonData = JSON.parse(data);
			var errorData = JSON.parse(jsonData.data);
			$('.NameExistErr').hide(); 
			$('.RCodeExistErr').hide();  
			$('.TCodeExistErr').hide();
			//if add then reset the form	
			if(jsonData.isSuccess && $("#idServiceID").val()==0){
				//$('form#idFrmAddService')[0].reset(); 
				$('.form-control').val('');
				$("#DefaultType").val('').change();
				$("#NetworkProvider").val('').change();
				$("#NetworkMode").val('').change();
				reloadServices(); 
				//$("#idSpnSuccessErr").html(jsonData.message).css({'color':'green'});
				$("#idSpnSuccessErr").html("<div class='alert alert-success alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
				$('#idSpnSuccessErr').fadeOut(2000,function(){
					$('#myModal').modal('hide');
					$('#idBtnAddService').attr('disabled', false);
				});
			} else if(!jsonData.isSuccess) {
				$('#idBtnAddService').attr('disabled', false);
				if(errorData.Name  != 0) { $('.NameExistErr').show(); } else { $('.NameExistErr').hide();  }
				if(errorData.RCode != 0) { $('.RCodeExistErr').show(); } else { $('.RCodeExistErr').hide();  }
				if(errorData.TCode != 0) { $('.TCodeExistErr').show(); } else { $('.TCodeExistErr').hide();  }
			}else{
				//Update success
				//$("#idSpnSuccessErr").html(jsonData.message).css({'color':'green'});	
				$("#idSpnSuccessErr").html("<div class='alert alert-success alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
				reloadServices(); 
				$('#idSpnSuccessErr').fadeOut(2000,function(){
					$('#myModal').modal('hide');
					$('#idBtnAddService').attr('disabled', false);
				});
			}
		
		},
		error: function(error){
			alert('Failed to add service');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}

function deleteService(serviceID){
	ajaxRequest({
		type: 'post',
		url: _actionUrl+'/Services/ServiceAction.php',
		data: "Action=Delete&ServiceID="+serviceID,
		success: function(data){
			//var jsonData = JSON.parse(data);
			if(data==1) {alert('Deleted');
			reloadServices();}
			else alert('Problem indelete')
		},
		error: function(error){
			alert('Failed to add service');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}
function upsertServicePopup(isAdd,data){
	
	$modalPopup = $("#myModal");
	$modalPopup.modal("toggle");
	if(!isAdd){
		dataArr = data.split(',');
		$modalPopup.find(".modal-title").html("Edit Service");
		$modalPopup.find("#idServiceID").val(dataArr[0]);
		$modalPopup.find("#Name").val(dataArr[1]);
		$modalPopup.find("#rechargeCode").val(dataArr[2]);
		$modalPopup.find("#topupCode").val(dataArr[3]);
		$modalPopup.find("#DefaultType").val(dataArr[4]).change();
		$modalPopup.find("#NetworkProvider").val(dataArr[6]).change();
		$modalPopup.find("#NetworkMode").val(dataArr[7]).change();
	}else{
		//This functio is not being called for opening add popup
		//$('form#idFrmAddService').reset();
		$('.form-control').val('');
	}
}
function getRechargeType(){
	ajaxRequest({
		type: 'post',
		url: _actionUrl+'/Services/ServiceAction.php',
		data: "Action=GetRechargeTypes",
		success: function(data){
		//alert(data);
			var jsondata = JSON.parse(data);
			$userContainer = $("#DefaultType");
			$userContainer.html('');
			$userContainer.append("<option value='0'>Select</option>");
			$(jsondata).each(function(index,value){
				$userContainer.append("<option value='"+value.RechargeTypeID+"'>"+value.Name+"</option>");
			});
		},
		error: function(error){
			alert('Failed to add service');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}

function getNetworkProvider(){
	ajaxRequest({
		type: 'post',
		url: _actionUrl+'/Services/ServiceAction.php',
		data: "Action=GetNetworkProvider",
		success: function(data){
		//alert(data);
			var jsondata = JSON.parse(data);
			$userContainer = $("#NetworkProvider");
			$userContainer.html('');
			$userContainer.append("<option value='0'> --Select Network-- </option>");
			$(jsondata).each(function(index,value){
				$userContainer.append("<option value='"+value.NetworkProviderID+"'>"+value.Name+"</option>");
			});
		},
		error: function(error){
			alert('Failed to add service');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}

//Get Network mode
function getNetworkMode(){
	ajaxRequest({
		type: 'post',
		url: _actionUrl+'/Services/ServiceAction.php',
		data: "Action=GetNetworkMode",
		success: function(data){
		//alert(data);
			var jsondata = JSON.parse(data);
			$userContainer = $("#NetworkMode");
			$userContainer.html('');
			$userContainer.append("<option value=''> --Select Mode-- </option>");
			$(jsondata).each(function(index,value){
				$userContainer.append("<option value='"+value.NetworkModeId+"'>"+value.Name+"</option>");
			});
		},
		error: function(error){
			alert('Failed to add mode');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}

$(function(){
	loadAllServices();
	getRechargeType();
	getNetworkProvider();
	getNetworkMode();
	$("#idBtnAddServicePopup").click(function(e){
		$('#idServiceID').val(0);
		$('.form-control').val('');
		$("#DefaultType").val(0).change();
		$("#NetworkProvider").val(0).change();
		$("#NetworkMode").val('').change();
	});
		
});