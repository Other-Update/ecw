function editUser(userID){	
	//window.location.href="/ECWorldGit/ECWorld/www/AdminWorld/User/EditUser.php?userid="+userID;
	//window.location.href="http://ecworld.co.in/AdminWorld/User/EditUser.php?userid="+userID";
	//window.location.href="http://worldec.in/New/1912/ECWorld/www/AdminWorld/User/EditUser.php?userid="+userID;
	window.location.href="EditUser.php?userid="+userID;
}
function deleteUser(userID,callbackfn){
	ajaxRequest({
		type: 'post',
		//url: 'UserAction.php',
		url: "../../Action/User/UserAction.php",
		data: "Action=DeleteUser&UserID="+userID,
		success: function(data){		
			var jsonData = JSON.parse(data);
			if(jsonData.isSuccess==true){
				alert(jsonData.message);
				callbackfn();
			}else
				alert(jsonData.message);
			console.log(data);
			//getParents();
		},
		error: function(error){
			alert('Error:Unable to delete user');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}
function updateUserStatus(userID,newStatus,callbackfn){
	ajaxRequest({
		type: 'post',
		//url: 'UserAction.php',
		url: "../../Action/User/UserAction.php",
		data: "Action=UpdateUserStatus&UserID="+userID+"&NewStatus="+newStatus,
		success: function(data){
			if(data="true") alert("Success");
			else alert("Failed to update");
			callbackfn();
		},
		error: function(error){
			alert('Error:Unable to delete user');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}

function loadUsers_DT(){
	dt=ecwDTAdv.init(
		$("#idTblUsers"),
		{
			//"url" : "UserAction.php",
			"url" : "../../Action/User/UserAction.php",
			"type" : "POST",
			"data" : {
				"Action" : "GetUsersByParent_DT",
				"Filter" : "Parents",//ByRoleIDs
				"ParentID" : function(d){ return $("#idSelectUser").val();},
				"IncludeParent" : "1",
				"RoleID" : function(d){ return $("#idSelectRole").val();}
			}
		},
		{
			"PageLength":500,
			"Columns":[
				{"mData" : null,'sTitle':'UserID','mRender':function(data){
					return data[0];
				}},
				{"mData" : null,'sTitle':'Name','mRender':function(data){
					return data[1];
				}},
				{"mData" : null,'sTitle':'RoleName','mRender':function(data){
					return data[2];
				}},
				{"mData" : null,'sTitle':'Mobile','mRender':function(data){
					return data[3];
				}},
				{"mData" : null,'sTitle':'Wallet','mRender':function(data){
					return data[8];
				}},
				{"mData" : null,'sTitle':'ParentID','mRender':function(data){
					return data[5];
				}},
				{"mData" : null,'sTitle':'ClientLimit','mRender':function(data){
					//Display -1 for Admin. Otherwise actual value
					return data[0]==1?-1:data[6];
				}},
				{"mData" : null,'sTitle':'Status','mRender':function(data){
					var status="Active";
					if(data[9]==0) status = "InActive";
					var btnChangeStatus ="<a href='#' class='clsChangeUserStatus' data-userdisplayid='"+data[0]+"' data-username='"+data[1]+"' id data-userid='"+data[10]+"'>"+status+"</a>"	;
					/* btnChangeStatus.click(function(e){
						alert();
					}); */
					if(data[0]==1) return "Active";
					else return btnChangeStatus;
				}},
				{"mData" : null,'sTitle':'UniqueUserID','bVisible':false,'mRender':function(data){
					return data[10];
				}}
			]
		},
		{
			"Edit":{
				"IsEnabled":false
			},
			"Delete":{
				"IsEnabled":false
			},
			"Checkbox":{
				"IsEnabled":false
			},
			"SerialNo":{
				"IsEnabled":true
			},
			"UniqueIdColumnIndex":10,
			"NameColumnIndex":1,
			"ExcludeColumnIndex":1,
			"OrderByColumnIndex":0
		},function(e,id,name,all){
			editUser(id);
			//editDistMargin(all);
		},function(e,id,name,all){
			if(confirm("Are you sure want to delete "+id+" - "+name+"?"))
				deleteUser(id,function(){
					ecwDTAdv.refresh();
				});	
		},function(e,id,all){
			alert(id);
		});
	$(dt).on("click",".clsChangeUserStatus",function(e){
		/* alert($(this).data('userid'));
		alert($(this).data('userdisplayid'));
		alert($(this).data('username')); */
		var status = $(this).html();
		var userid=$(this).data('userid');
		var name = $(this).data('username');
		var oppositeStatus = status=="Active"? "InActivate" : "Activate";
		var oppositeStatusCode = status=="Active"? 0 : 1;
		if(confirm("Are you sure want to "+oppositeStatus+" "+name+"?"))
			updateUserStatus(userid,oppositeStatusCode,function(){
				ecwDTAdv.refresh();
			});	
	});
	//isDtInitialized=true;
}
function reloadUsers_DT(){
var dt = $('#idTblUsers').DataTable();
	dt.clear();//.draw();
	//dt.DataTable().ajax.reload();
	dt.ajax.reload();
}
function searchUsers(){
	//alert(2);
	//alert($("#idSelectUser").val()+", "+$("#idSelectRole").val());
	reloadUsers_DT();
}
function loadUsersAutoSelect(){
	
	loadUsers($("#idSelectUser"),{
		"Action":"GetAllUsers",
		"ParentID":0,//0 Means loggedinuserID
		"IncludeParent":1,
		"IncludeAllSubUsers":1,
		"ExcludeRoleIDs":"0"
	},true,function(isSuccess){
		loadUsers_DT();
	});
}
function loadRolesAutoSelect(){
	ajaxRequest({
		type: 'post',
		//url: 'UserAction.php',
		url: '../../Action/User/UserAction.php',
		data: "Action=GetRoles&RoleID="+0,// means logged in users role id
		success: function(data){
			var jsondata = JSON.parse(data);
			$roleContainer = $("#idSelectRole");
			$roleContainer.html('');
			$roleContainer.append("<option value='-1'>Select</option>");
			$roleContainer.append("<option value='0'>All</option>");
			$(jsondata).each(function(index,value){
					$roleContainer.append("<option value='"+value.RoleID+"'>"+value.Name+"</option>");
			});
		},
		error: function(error){
			alert(0);
		}
	},{
		isLoader:0,
		loaderElem:$('body')
	});
}

$(function(){
	//Loading users might be slower than loading roles.
	//So load data table user inside success of load dropdown users.
	//So that roles and users dropdown will have data to filter data table users.
	loadRolesAutoSelect();
	loadUsersAutoSelect();
	
	$("#idSelectUser, #idSelectRole").change(function(){
		searchUsers();
	});
});
