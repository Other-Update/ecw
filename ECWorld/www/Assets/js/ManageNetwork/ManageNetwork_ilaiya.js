//Ajax - Ilaiya
function loadAllService(){
	ajaxRequest({
		type: 'post',
		url: '../../Action/NetworkManagement/ManageNetworkAction.php',
		data: "Action=LoadPage",//$('form#idManageNetworkForm').serialize(),
		success: function(data){
			var jsonRes = JSON.parse(data);
			if(!jsonRes.isSuccess) return false;
			
			var jsonData=JSON.parse(jsonRes.data);
			var serviceList=jsonData.Services;
			
			$("#idServiceProblemMsgCur").val(jsonData.ServiceProblemMsgCur);
			$("#idServiceProblemMsgPrev").val(jsonData.ServiceProblemMsgPrev);
			
			function getDisplayServiceHtml(service){
				var checked=(service.IsProblem==true)?"checked":"";
				return '<div class="col-md-2">'
							+'<div class="input-group">'
								+'<input type="hidden" name="ServiceID[]" value="'+service.ServiceID+'">'
								+'<span class="input-group-addon"><input type="checkbox" class="clsChkEnable" name="ServiceCheck[]" '+checked+' ></span>'
								+'<input type="text" class="form-control" value="'+service.Name+'" readonly>'
							+'</div>'
						+'</div>';
			}
			var htmlContent='';
			var sequence='';
			$(serviceList).each(function(index,service){
				//console.log(service.Name);
				htmlContent+=getDisplayServiceHtml(service);
				sequence+=service.IsProblem;
			});
			$("#idCheckSequence").val(sequence);
			$("#idServiceListContent").html('').html(htmlContent);
		},
		error: function(error){
			alert('Failed to load services');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}

function updateManageNetwork(){
	ajaxRequest({
		type: 'post',
		url: '../../Action/NetworkManagement/ManageNetworkAction.php',
		data: $('form#idManageNetworkForm').serialize(),
		success: function(data){
			//var jsonData = JSON.parse(data);
			if(data=='true')
				alert('Updated');
			else
				alert('Update failed');
			loadAllService();
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
	loadAllService();
	$("#idBtnManageNetwork").click(function(e){
		updateManageNetwork();
		return false;
	});
	
	//Keep track of '.clsChkEnable' checks in a textbox(#idCheckSequence)
	//From backend access this textbox instead of checkboxes. 
	//Bcoz, checkboxes won't send anything if it is unchecked
	//which is difficult to calculate which check check box is for which service
	$("#idServiceListContent").on('click','.clsChkEnable',function(){
		var sequence='';
		$("#idServiceListContent").find('.clsChkEnable').each(function(index,elem){
			sequence+=$(elem).prop('checked')?1:0;
		});
		$("#idCheckSequence").val(sequence);
	});
});