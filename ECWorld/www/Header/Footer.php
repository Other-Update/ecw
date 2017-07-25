<footer class="main-footer">
	<div class="pull-right hidden-xs">
	  <b></b>
	</div>
	<strong>Copyright &copy; 2015 - <?php echo date('Y');?> <a href="#">EC World</a>.</strong> All rights reserved.
</footer>

<div class="control-sidebar-bg"></div>
</div><!-- Wrapper End -->

<script src="<?php echo $WebsiteUrl; ?>/Assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/dist/js/app.min.js"></script >
<script src="<?php echo $WebsiteUrl; ?>/Assets/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/plugins/select2/select2.full.min.js"></script>
<!--script src="<?php echo $WebsiteUrl; ?>/Assets/plugins/input-mask/jquery.inputmask.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/bootstrap/js/jquery-ui.min.js"></script-->

<script src="<?php echo $WebsiteUrl; ?>/Assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/plugins/iCheck/icheck.min.js"></script>

<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Common/ajax.js?v=1.1"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Common/Ecwdatatable.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Common/Common.js"></script>

 <script>
	$(function(){
		$("#idSignout").click(function(){
			var url = '../../Action/User/UserAction.php';
			var redirectUrl = '../Login';
			var currentUrl = window.location.href;
			if(currentUrl.includes('Reports')){
				url = '../../../Action/User/UserAction.php';
				redirectUrl = '../../Login';
			}
			ajaxRequest({
				type: 'post',
				url: url,
				data: "Action=Signout",
				success: function(data){	
					if(data==true){
						localStorage.Token = "";
						window.location.href=redirectUrl;
					}
				},
				error: function(data){
				}
			},{
				isLoader:0,
				loaderElem:$('body')
			});
		});
		
		sessionStorage.lastClickedMainMenu = sessionStorage.MainMenuSelecetd?sessionStorage.MainMenuSelecetd:"Dashboard";
		$(".clsMainMenuName").click(function(){
			if($(this).hasClass('treeview'))
				sessionStorage.lastClickedMainMenu = $(this).attr("id");
			else
				sessionStorage.MainMenuSelecetd = $(this).attr("id");
		});
		$(".clsSubMenuName").click(function(e){
			sessionStorage.MainMenuSelecetd=sessionStorage.lastClickedMainMenu;
			sessionStorage.SubMenuSelecetd = $(this).attr("id");
		});
		if(sessionStorage.MainMenuSelecetd)
		{
			$(".clsMainMenuName").removeClass("active");
			$("#"+sessionStorage.MainMenuSelecetd).addClass("active");
			if(sessionStorage.SubMenuSelecetd)
			{
				$("#"+sessionStorage.MainMenuSelecetd).find(".clsSubMenuName").removeClass("active");
				$("#"+sessionStorage.SubMenuSelecetd).addClass("active");
			}
		}
	});
	
	
	$.fn.modal.Constructor.prototype.enforceFocus = function () {
        var that = this;
        $(document).on('focusin.modal', function (e) {
            if ($(e.target).hasClass('select2-search__field')) {
                return true;
            }
			//$.magnificPopup.proto._onFocusIn.call(this,e);
			
            if (that.$element[0] !== e.target && !that.$element.has(e.target).length) {
                that.$element.focus();
            }
        });
    }
	
 </script>
</body>
</html>