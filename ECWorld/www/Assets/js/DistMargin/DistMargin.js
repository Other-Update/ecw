var isDtInitialized=false;
var isAddEditMarginChanged=false;
var dt;
function initDistMarginUsers_DT(){//alert(1);
	dt=ecwDTAdv.init(
		$("#idTblDistributorMarginUsers"),
		{			
			"url" : "../../Action/DistMargin/DistributorMarginAction.php",
			"type" : "POST",
			"data" : {
				"Action" : "GetDistMarginUsers_DT",
				"ParentID" : "1",
				"ExcludeRoleIDs":"2"
			}
		},
		{
			"PageLength":500,
			"Columns":[
				{"mData" : null,'bVisible':false,'sTitle':'UserUID','mRender':function(data){
					return data[0];
				}},
				{"mData" : null,'sTitle':'UserID','mRender':function(data){
					return data[1];
				}},
				{"mData" : null,'sTitle':'User Name','mRender':function(data){
					return data[2];
				}},
				{"mData" : null,'sTitle':'Mobile No','mRender':function(data){
					return data[3];
				}},
				{"mData" : null,'sTitle':'Previous Margin(%)','mRender':function(data){
					if(!data[4])	return "0.00";
					return data[4];
				}},
				{"mData" : null,'sTitle':'Opening Balance(&#8377;)','mRender':function(data){
					if(!data[5])	return "0.00";
					return data[5];
				}},
				{"mData" : null,'sTitle':'Purchase(&#8377;)','mRender':function(data){
					if(!data[6])	return "0.00";
					return data[6];
				}},
				{"mData" : null,'sTitle':'Current Margin(%)','mRender':function(data){
					if(!data[7])	return "0.00";
					return data[7];
				}},
				{"mData" : null,'sTitle':'Current Balance(&#8377;)','mRender':function(data){
					if(!data[8])	return "0.00";
					return data[8];
				}},
				{"mData" : null,'sTitle':'Elibile for Regular Margin(Y/N)','mRender':function(data){
					return data[9];
				}}
			]
		},
		{
			"Edit":{
				"IsEnabled":true
			},
			"Delete":{
				"IsEnabled":false
			},
			"Checkbox":{
				"IsEnabled":true
			},
			"SerialNo":{
				"IsEnabled":true
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
	isDtInitialized=true;
}
function editSelected(){
	if(confirm("(Same will be in popup) Send SMS to : "+ecwDTAdv.getSelectedIds($("#idTblDistributorMarginUsers"),9)))
		alert('Sending');
}
function getMarginByUser(userID,callbackFn){
	//alert(userID);
	ajaxRequest({
		type: 'post',
		url: '../../Action/DistMargin/DistributorMarginAction.php',
		data: "Action=GetMarginByUser&UserID="+userID,
		success: function(data){
			//alert('deleteDistMargin='+data);
			var jsonData = JSON.parse(data);	
			if(jsonData.isSuccess){
				callbackFn(jsonData);
			}else{
				alert('Loadin user margin failed');
			}
			//alert(jsonData.message);
		},
		error: function(error){
			alert('Failed to load margin');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}

function getRow(from,to,normal,regular){
	return "<tr>"
			  +"<td>"
				+"<input type='number' class='form-control' name='FromAmount[]' placeholder='From Amount' value='"+from+"' /></td>"
			  +"<td>"
				+"<input type='number' class='form-control'  name='ToAmount[]' placeholder='To Amount' value='"+to+"' /></td></td>"
			  +"<td>"
				+"<input type='number' class='form-control' name='NormalBilling[]' placeholder='Normal Billing' value='"+normal+"' /></td></td>"
			  +"<td>"
				+"<input type='number' class='form-control' name='RegularBilling[]' placeholder='Regular Billing' value='"+regular+"' /></td></td>"
			  +"<td>"
				+"<a href='#' class='clsBtnDeleteMargin' >"
					+"<i class='fa fa-trash-o'></i>"
				+"</a>"
				+"<span class='error'></span>"
			  +"</td>"
			+"</tr>";
}
function openEditDistMargin(data){
	isAddEditMarginEdited=false;
	$("#idSpnSuccessErr").hide();
	var dataArr = data.split(",");
	//alert(dataArr);
	$modalPopup = $("#idEdiMarginModal");
	$modalPopup.modal("toggle");
	$("#idPopUserID").val(dataArr[0]);
	$("#idPopUserDisplayID").val(dataArr[1]);
	$("#idPopUserName").val(dataArr[2]);
	$("#idPopUserMobile").val(dataArr[3]);
	$("#idPopOpeningBalance").val(dataArr[5]);
	var tblbody=$("#idTblDistMarginBody");
	tblbody.html("");
	getMarginByUser(dataArr[0],function(marginDataRes){
		var marginData = JSON.parse(marginDataRes.data);
		$(marginData.MarginData).each(function(index,margin){	
			tblbody.append(getRow(margin.FromAmount,margin.ToAmount,margin.NormalBilling,margin.RegularBilling));
		});
		$('#idPopOpeningBalance').val(marginData.OpenBalance);
		//console.log("OPen:"+marginData.OpenBalance);
	});
}
function removeMarginUI(elem){
	isAddEditMarginChanged=true;
	$(elem).parent().parent().remove();
}

function isAddFormInValid(){
	var inValid=false;
	$("#idFormAddMargin").find("input[type=number]").each(function(index,data){
		//console.log('form ip val='+$(this).val());
		if($(this).val()==""){
			inValid=true;
			$(this).parent().find('span').html('Required').css({'color':'red'}).show();
		}else{
			$(this).parent().find('span').hide();
		}
	});
	return inValid;
}
function addMargin(from,to,noraml,regular){
	if(isAddFormInValid()){
		return false;
	}
	$("#idTblDistMarginBody").append(getRow(from,to,noraml,regular));
	return true;
}
function isUpdateFormInValid(){
	var inValid=false;
	$("#idTblDistMarginBody").find("input[type=number]").each(function(index,data){
		//console.log('form ip val='+$(this).val());
		if($(this).val()=="") inValid=true;
	});
	return inValid;
}
function updateMargin(){
	isAddEditMarginChanged=false;
	if(isUpdateFormInValid()){
		$("#idSpnSuccessErr").html("Fill all the fields").css({'color':'red'}).show();
		return false;
	}else{
		$("#idSpnSuccessErr").hide();
	}
	ajaxRequest({
		type: 'post',
		url: '../../Action/DistMargin/DistributorMarginAction.php',
		data: $('form#idFormUpdateMargin').serialize(),
		success: function(data){
			//alert('upsertDistMargin='+data);
			var jsonData = JSON.parse(data);
			
			//if add then reset the form	
			if(jsonData.isSuccess){
				$("#idSpnSuccessErr").html(jsonData.message).css({'color':'green'}).show();
			}else{
				$("#idSpnSuccessErr").html(jsonData.message).css({'color':'red'}).show();
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
$(function(){
	initDistMarginUsers_DT();
	$("#idBtnSelectUsers").click(function(){
		editSelected();
	});
	$("#idPopBtnUpdate").click(function(e){
		updateMargin();
		return false;
	});
	$("#idPopBtnAddMargin").click(function(e){
		//alert("Add margin is being implemented");
		var isOK = addMargin($("#idPopTxtNewFrom").val(),$("#idPopTxtNewTo").val(),$("#idPopTxtNewNormal").val(),$("#idPopTxtNewRegular").val());
		//alert(isOK);
		if(isOK){
			$("#idPopTxtNewFrom").val('0');
			$("#idPopTxtNewTo").val('0');
			$("#idPopTxtNewNormal").val('0');
			$("#idPopTxtNewRegular").val('0');
		}
		return false;
	});
	$("#idEdiMarginModal").on("click",".clsBtnDeleteMargin",function(e){
		removeMarginUI(this);
		return false;
	});
});