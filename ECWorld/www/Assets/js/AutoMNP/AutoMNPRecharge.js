
function loadAllNetwork(id,type,edit,del,editFn){
	var dt=ecwDatatable.init(
		$(id),
		{			
			"url" : "../../Action/AutoMNP/AutoMnpAction.php",
			"type" : "POST",
			"data" : {
				"Action" : "GetAuto_DT",
				"idRCType" : type
			}
		},
		500,
		edit,del,false,0,1,0,
		[
			{"mData" : null,'bVisible':false,'sTitle':'NeworkID','mRender':function(data){
				return data[0];
			}},
			{"mData" : null,'sTitle':'MobileNo','mRender':function(data){
				return data[1];
			}},
			{"mData" : null,'sTitle':'Name','mRender':function(data){
				return data[2];
			}}
		],editFn,function(e,id,name,all){
			if(confirm("Are you sure? Do you want to delete Mobile No:"+name+"?")) deleteAtuoMnp(id);
			e.preventDefault();
		});
}

function refreshAllNetwork(){	
	$("#idTblAutoRecharge").DataTable().ajax.reload();
	$("#idTblMNPRecharge").DataTable().ajax.reload();
}
function deleteAtuoMnp(id){
	ajaxRequest({
		type: 'post',
		//url: 'AutoMnpAction.php',
		url : '../../Action/AutoMNP/AutoMnpAction.php',
		dataType: 'json',
		data: "Action=Delete&NeworkID="+id,
		success: function(data){
			var jsonData = JSON.parse(data);
			if(jsonData.isSuccess) refreshAllNetwork();
			alert(jsonData.message)
		},
		error: function(error){
			alert('Error');
		}
	},{
		isLoader:0,
		loaderElem:$('body')
	});
}

$(document).on("keyup",".SearchNetwork",function(){
	var mobile = $(this).val();
	var RCType= $(this).data('type');
	if(RCType == 1){ var mobLength = 4; } else { var mobLength = 10; }
		
	if(mobile.length >= mobLength){
	ajaxRequest({
		type: 'post',
		url : '../../Action/AutoMNP/AutoMnpAction.php',
		dataType: 'json',
		data: "Action=GetNetwork&Mobile="+mobile+"&RCType="+RCType,
		success: function(data){
			var jsonData = JSON.parse(data);
			var  NetworkName= jsonData[0].NetworkProviderID;
			if(RCType == 1)
				$("#AutoNetwork").val(NetworkName);
			else 
				$("#MNPNetwork").val(NetworkName);
		},
		error: function(error){
			alert('Error');
		}
	},{
		isLoader:0,
		loaderElem:$('body')
	}); } else {
		if(RCType == 1)
			$("#AutoNetwork").val('');
		else 
			$("#MNPNetwork").val('');
	}
});


function getNetworkList(){
	ajaxRequest({
		type: 'post',
		url : '../../Action/AutoMNP/AutoMnpAction.php',
		data: "Action=GetNetworkList",
		success: function(data){
			parentsList = data;
			var jsondata = JSON.parse(data);
			$(".SelectedNetwork").html('');
			$(".SelectedNetwork").append("<option value='' > --Select Network-- </option>");
			$(jsondata).each(function(index,value){
				$(".SelectedNetwork").append("<option value='"+value.NetworkProviderID+"' >"+value.Name+"</option>");
			});
			$(".SelectedNetwork").append("<option value='0' >Other</option>");
			
		},
		error: function(error){
			alert(0);
		}
	},{
		isLoader:0,
		loaderElem:$('body')
	});
}

function upsertAutoRecharge(){
	ajaxRequest({
		type: 'post',
		url : '../../Action/AutoMNP/AutoMnpAction.php',
		data: $('form#idFrmAddAuto').serialize(),
		success: function(data){
			var jsonData = JSON.parse(data);
			$('#errorMsgAuto').fadeIn(100,function(){});
			$('#errorMsgAuto').fadeOut(5000,function(){});
			if(jsonData.isSuccess){
				$("#errorMsgAuto").html("<span class='alert alert-success'>"+jsonData.message+"</span>").show();
				$('.form-control').val('');
			} else{
				$("#errorMsgAuto").html("<span class='alert alert-danger'>"+jsonData.message+"</span>").show();	
			}
			refreshAllNetwork();
		},
		error: function(error){
			alert('Failed to add service');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}


function upsertMnpRecharge(){
	ajaxRequest({
		type: 'post',
		url : '../../Action/AutoMNP/AutoMnpAction.php',
		data: $('form#idFrmAddMNP').serialize(),
		success: function(data){
			var jsonData = JSON.parse(data);
			$('#errorMsgMnp').fadeIn(100,function(){});
			$('#errorMsgMnp').fadeOut(5000,function(){});
			if(jsonData.isSuccess){
				$("#errorMsgMnp").html("<span class='alert alert-success'>"+jsonData.message+"</span>").show();
				$('.form-control').val('');
			} else{
				$("#errorMsgMnp").html("<span class='alert alert-danger'><p>"+jsonData.message+"</span>").show();	
			}
			refreshAllNetwork();
		},
		error: function(error){
			alert('Failed to add service');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}


$(function(){
	loadAllNetwork("#idTblAutoRecharge",1,false,true,function(e,id,name,all){
			e.preventDefault();
			var dataArr = all.split(',');
			$('#AutoNeworkID').val(dataArr[0]);
			$('#AutoMobileNo').val(dataArr[1]);
			$("#AutoNetwork").val(dataArr[3]);
	});
	loadAllNetwork("#idTblMNPRecharge",0,true,true,function(e,id,name,all){
			e.preventDefault();
			var dataArr = all.split(',');
			$('#MnpNeworkID').val(dataArr[0]);
			$('#MNPMobileNo').val(dataArr[1]);
			$("#MNPNetwork").val(dataArr[3]);
			
	});
	getNetworkList();
	$("#idBtnAddAuto").click(function(e){
		if($('#AutoMobileNo').val() !='' && $('#AutoNetwork').val() != ''){
			upsertAutoRecharge();
		}
		return false;
	});
	
	$("#idBtnAddMNP").click(function(e){
		if($('#MNPMobileNo').val() !='' && $('#MNPNetwork').val() != ''){
			upsertMnpRecharge();
		}
		return false;
	});
	
});

$('#AutoNetwork').change(function(){
	if($('#AutoNetwork').val() == 0){
		$('#othresAuto').val('');
		$('#othresAutoField').show(); 
	} else {
		$('#othresAuto').val('');
		$('#othresAutoField').hide();
	}
});

$('#MNPNetwork').change(function(){
	if($('#MNPNetwork').val() == 0){
		$('#othresMNP').val('');
		$('#othresMNPField').show(); 
	} else {
		$('#othresMNP').val('');
		$('#othresMNPField').hide();
	}
});