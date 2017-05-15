var ecwDatatable = {};

ecwDatatable.refresh = function(){
	this.dt.DataTable().ajax.reload();
}
ecwDatatable.init = function(elem,ajax,pageLength,isEdit,isDelete,isBasic,uniqueIdColumnIndex,nameColumnIndex,exclude,columns,editCallbackFn,deletCallbackFn){
	var editDelete = {"mData" : null,'sTitle':'Action', "bSortable":false,"mRender" : function(data){
				var uniqueID=data[uniqueIdColumnIndex];
				if(exclude!=uniqueID){
					var name=data[nameColumnIndex];
					var btnEdit = '<a href="#" data-uniqueid="'+uniqueID+'" data-name="'+name+'" data-all="'+data+'" class="clsBtnDtEdit" ><i class="fa fa-pencil"></i></a>';
					var btnDelete = '<a href="#" data-uniqueid="'+uniqueID+'" data-name="'+name+'" data-all="'+data+'" class="clsBtnDtDelete deleteButton" ><i class="fa fa-trash-o"></i></a>';
					if(isDelete) return btnEdit+' || '+btnDelete;
					else if(isEdit) return btnEdit;
				}
			}};
	//console.log("Before Extend columns="+JSON.stringify(columns));
	//console.log("editDelete="+JSON.stringify(editDelete));
	//columns = $.extend({},columns,editDelete);
	if(isEdit==true || isDelete==true)
		columns.push(editDelete);
	//console.log("After Extend columns="+JSON.stringify(columns));
	
	//To disable DT warning message alert
	$.fn.dataTableExt.sErrMode = 'none';
	this.dt = elem.dataTable({
		"aProcessing": true,
		"aServerSide": true,
		"aoColumns":columns,
		"ajax": ajax,
		"sEcho":10,
		"pageLength":pageLength,
		"order": [[1, "desc"]]
	});
	//alert(elem.attr("id"));
	$(elem).on("click",".clsBtnDtEdit",function(e){
		editCallbackFn(e,$(this).data('uniqueid'),$(this).data('name'),$(this).data('all'));
	});
	$(elem).on("click",".clsBtnDtDelete",function(e){
		deletCallbackFn(e,$(this).data('uniqueid'),$(this).data('name'),$(this).data('all'));
	});
}

var ecwDTAdv = {};
ecwDTAdv.refresh = function(){
	this.dt.DataTable().ajax.reload();
}
ecwDTAdv.select = function(){
	this.dt.columns( '.sum' ).every( function () {
		var sum = this
			.data()
			.reduce( function (a,b) {
				return a + b;
			} );
	 
		$(el).html( 'Sum: '+sum );
	} );
}

//Advanced
ecwDTAdv.init = function(elem,ajax,dtConfig,ecwConfig,editCallbackFn,deletCallbackFn,selectCallbackFn){
	if(ecwConfig.Checkbox.IsEnabled==true){
		var selectHeader='<input type="checkbox" id="idDTSelectAll" style="width: 50px;height: 15px;"></input>';
		var selectColumn = {"mData" : null,'sTitle':selectHeader, "bSortable":false,"mRender" : function(data){
				var uniqueID=data[ecwConfig.UniqueIdColumnIndex];
				var checkbox = '<input type="checkbox" id="idDTChkSelect" name="DTChkSelect" class="clsBtnCheck" style="width: 50px;height: 15px;" data-uniqueid="'+uniqueID+'" data-all="'+data+'"></input>';
				return checkbox;
		}};
		dtConfig.Columns.push(selectColumn);
	}
	if(ecwConfig.Edit.IsEnabled==true || ecwConfig.Delete.IsEnabled==true){
		var actionColumn = {"mData" : null,'sTitle':'Action', "bSortable":false,"mRender" : function(data){
				var uniqueID=data[ecwConfig.UniqueIdColumnIndex];
				if(ecwConfig.ExcludeColumnIndex!=uniqueID){
					var name=data[ecwConfig.NameColumnIndex];
					var btnEdit = '<a href="#" data-uniqueid="'+uniqueID+'" data-name="'+name+'" data-all="'+data+'" class="clsBtnDtEdit" ><i class="fa fa-pencil"></i></a>';
					var btnDelete = '<a href="#" data-uniqueid="'+uniqueID+'" data-name="'+name+'" data-all="'+data+'" class="clsBtnDtDelete deleteButton" ><i class="fa fa-trash-o"></i></a>';
					if(ecwConfig.Delete.IsEnabled) return btnEdit+' || '+btnDelete;
					else if(ecwConfig.Edit.IsEnabled) return btnEdit;
				}
			}};
		dtConfig.Columns.push(actionColumn);
	}
	if(ecwConfig.SerialNo)
		if(ecwConfig.SerialNo.IsEnabled==true){
			var snoColumn = {"mData" : null,'sTitle':'SNo', "bSortable":false,"mRender" : function (data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			}};
			//unshift is to bring this column in the beginnig of the data table
			dtConfig.Columns.unshift(snoColumn);
		}
	//To disable DT warning message alert in case of err
	$.fn.dataTableExt.sErrMode = 'none';
	var dtObj = {};
	if(ecwConfig.OrderByColumnIndex>=0){
		dtObj = {
			"aProcessing": true,
			"aServerSide": true,
			"bAutoWidth": false,
			"aoColumns":dtConfig.Columns,
			"ajax": ajax,
			"sEcho":10,
			"pageLength":dtConfig.PageLength,
			"order": [[ecwConfig.OrderByColumnIndex, "asc"]],
			"lengthChange": false//To hide show entries dropdown
		}
	}else{
		dtObj = {
			"aProcessing": true,
			"aServerSide": true,
			"bAutoWidth": false,
			"aoColumns":dtConfig.Columns,
			"ajax": ajax,
			"sEcho":10,
			"pageLength":dtConfig.PageLength,
			"bSort": false,
			"lengthChange": false//To hide show entries dropdown
		}

	}
	this.dt = elem.dataTable({
		"aProcessing": true,
		"aServerSide": true,
		"bAutoWidth": false,
		"aoColumns":dtConfig.Columns,
		"ajax": ajax,
		"sEcho":10,
		"pageLength":dtConfig.PageLength,
		"bSort": false,
		//"order": [[ecwConfig.OrderByColumnIndex, "asc"]],
		"lengthChange": false//To hide show entries dropdown
	});
	//alert(elem.attr("id"));
	$(elem).on("click",".clsBtnDtEdit",function(e){
		editCallbackFn(e,$(this).data('uniqueid'),$(this).data('name'),$(this).data('all'));
	});
	$(elem).on("click",".clsBtnDtDelete",function(e){
		deletCallbackFn(e,$(this).data('uniqueid'),$(this).data('name'),$(this).data('all'));
	});
	$(elem).on("click",".clsBtnCheck",function(e){
		$(elem).find('#idDTSelectAll').prop('checked', false);
	});
	$(elem).on("click","#idDTSelectAll",function(e){
		$(elem).find('.clsBtnCheck').prop('checked', this.checked);
	});
	return this.dt;
}
ecwDTAdv.getSelectedIds = function(elem,columnId){
	var rows = $(elem).dataTable().fnGetNodes();
	var selectedIds=[];
	for(var i=0;i<rows.length;i++)
	{
		chkElem=$(rows[i]).find("td:eq("+columnId+")").find('input'); 
		//console.log(chkElem.html());
		if(chkElem.is(":checked"))
			selectedIds.push(chkElem.data("uniqueid"));
	}
	return selectedIds;
}