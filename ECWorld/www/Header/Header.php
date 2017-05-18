<?php  
if(isset($report)){
	include '../../../WebsiteUrl/WebsiteUrl.php';
	include_once '../../../../BaseUrl.php';
	include_once APPROOT_URL.'/www/Session/AutoLogout.php';
}  else {
	include '../../WebsiteUrl/WebsiteUrl.php';
	include_once '../../../BaseUrl.php';
	include_once APPROOT_URL.'/www/Session/AutoLogout.php';
}

$loggedInUserDetails = json_decode(json_decode($_SESSION['me']));
if(strpos($_SERVER['REQUEST_URI'], 'AdminWorld') >0 && $loggedInUserDetails->user->RoleID!='1'){
	
	session_redirect($WebsiteUrl."/Distributor/Dashboard");
}
//Session_Signout();
//$userDetails = Session_ReturnLoggedInUser();

//echo json_encode($loggedInUserDetails);
$user = $loggedInUserDetails->user;
$fat = $loggedInUserDetails->fat;
$role = $loggedInUserDetails->role;
//echo json_encode($role);
//echo $role->RoleID;
function hasReadAccess($fat,$featureName){
	if(property_exists($fat,$featureName)){
		$values = explode(",",$fat->$featureName);
		if(count($values)>0)
			return $values[0]==1;
		return 0;
	}else return 0;
}
function hasAddUpdateAccess($fat,$featureName){
	if(property_exists($fat,$featureName)){
		$values = explode(",",$fat->$featureName);
		if(count($values)>1)
			return $values[1]==1;
		return 0;
	}else return 0;
}
function isAdmin($role){
	return $role->RoleID==1;
}
function isRetailer($role){
	return $role->RoleID==6;
}
function getPageName(){//AdminWorld or Distributor
	if(strpos($_SERVER['REQUEST_URI'], 'AdminWorld') >0)
		return "/AdminWorld";
	else
		return "/Distributor";
}
//echo hasAddUpdateAccess($fat,"ServiceList");
//echo isAdmin($role);
//if(hasReadAccess($fat,"UserAccess")) echo "test";
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>EC World</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
 <link rel="stylesheet" href="<?php echo $WebsiteUrl; ?>/Assets/bootstrap/css/bootstrap.min.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="<?php echo $WebsiteUrl; ?>/Assets/plugins/datepicker/datepicker3.css">
  <link rel="stylesheet" href="<?php echo $WebsiteUrl; ?>/Assets/plugins/iCheck/all.css">
  <link rel="stylesheet" href="<?php echo $WebsiteUrl; ?>/Assets/plugins/select2/select2.min.css">
  <link rel="stylesheet" href="<?php echo $WebsiteUrl; ?>/Assets/plugins/datatables/dataTables.bootstrap.css">
  <link rel="stylesheet" href="<?php echo $WebsiteUrl; ?>/Assets/dist/css/AdminLTE.css">
  <link rel="stylesheet" href="<?php echo $WebsiteUrl; ?>/Assets/dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="<?php echo $WebsiteUrl; ?>/Assets/css/style.css">
 
  
  
</head>
<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
<div class="wrapper">
  <header class="main-header">
    <a href="#" class="logo">
      <span class="logo-mini"><b>E</b>C</span>
      <span class="logo-lg"><b>EC World</b></span>
    </a>
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-user fa-1x"></i>
              <span id="idLblUserName1" class="hidden-xs"><?php echo json_decode(json_decode($_SESSION['me']))->user->Name; ?></span> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
				 <i class="fa fa-user fa-3x img-circle"></i>
                <p><?php echo json_decode(json_decode($_SESSION['me']))->role->Name; ?></p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                  <a id="idSignout" href="#" class="btn btn-default btn-flat">Sign out</a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
   <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
		<li class="clsMainMenuName active" id="idMainMenuDashboard">
          <a href="<?php echo $WebsiteUrl.getPageName(); ?>/Dashboard/">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
		<?php if(isAdmin($role) || hasReadAccess($fat,"PaymentReport") || hasReadAccess($fat,"PaymentCollectionReport") || hasReadAccess($fat,"RechargeReport") || hasReadAccess($fat,"TransactionReport")){?>
		<li class="treeview clsMainMenuName" id="idMainMenuReport" >
		  <a href="#">
            <i class="fa fa-edit"></i><span>Report</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-down"></i>
            </span>
          </a>
		  <ul class="treeview-menu">
			<?php if(isAdmin($role) || hasReadAccess($fat,"PaymentReport")){?>
			<li id="idMainMenuPaymentReport" class="clsSubMenuName">
				<a href="<?php echo $WebsiteUrl.getPageName(); ?>/Reports/Payment/index">
				<i class="fa fa-circle-o"></i>Payment Report</a>
			</li>
			<?php } ?>
			<?php if(isAdmin($role) || hasReadAccess($fat,"PaymentCollectionReport")){?>
			<li id="idMainMenuPaymentCollection" class="clsSubMenuName">
				<a href="<?php echo $WebsiteUrl.getPageName(); ?>/Reports/PaymentCollection/index">
				<i class="fa fa-circle-o"></i>Payment Collection</a>
			</li>
			<?php } ?>
			<?php if(isAdmin($role) || hasReadAccess($fat,"RechargeReport")){?>
			<li id="idMainMenuRechargeReport" class="clsSubMenuName">
				<a href="<?php echo $WebsiteUrl.getPageName(); ?>/Reports/Recharge/index">
				<i class="fa fa-circle-o"></i>Recharge Report</a>
			</li>
			<?php } ?>
			<?php if(isAdmin($role) || hasReadAccess($fat,"TransactionReport")){?>
			<li id="idMainMenuTransactionReport" class="clsSubMenuName">
				<a href="<?php echo $WebsiteUrl.getPageName(); ?>/Reports/Transaction/">
				<i class="fa fa-circle-o"></i>Transaction Report</a>
			</li>
			<?php } ?>
          </ul>
        </li>
		<?php } ?>
		
        <?php if(isAdmin($role) || hasReadAccess($fat,"UserAccess")){?>
        <li id="idMainMenuBusinessUser" class="clsMainMenuName treeview">
			<a href="#">
            <i class="fa fa-users"></i>
            <span>Business User</span>
			 <span class="pull-right-container">
              <i class="fa fa-angle-left pull-down"></i>
            </span>
			</a>
			<ul class="treeview-menu">
				<li id="idSubMenuAddUser" class="clsSubMenuName">
					<a href="<?php echo $WebsiteUrl.getPageName(); ?>/User/AddUser"><i class="fa fa-circle-o"></i> Add User</a>
				</li>
				<li id="idSubMenuViewUser" class="clsSubMenuName">
					<a href="<?php echo $WebsiteUrl.getPageName(); ?>/User/"><i class="fa fa-circle-o"></i>View User</a>
				</li>
				<?php if(isAdmin($role)){?>
				<li id="idSubMenuFAT" class="clsSubMenuName">
					<a href="<?php echo $WebsiteUrl.getPageName(); ?>/User/FeatureAccess"><i class="fa fa-circle-o"></i>Feature Access</a>
				</li>
				<?php } ?>
			</ul>
        </li>
		<?php } ?>
        <?php if(isAdmin($role) || hasReadAccess($fat,"ComplaintRequest")){?>
		<li id="idMainMenuUserRequest" class="clsMainMenuName" >
			<a href="<?php echo $WebsiteUrl.getPageName(); ?>/UserRequest/"><i class="fa fa-pencil"></i><span>User Request</span></a>
		</li>
		<?php } ?>
        <?php if(isAdmin($role) || hasReadAccess($fat,"ServiceList") ){?>
		<li id="idMainMenuManageService" class="clsMainMenuName" >
			<a href="<?php echo $WebsiteUrl.getPageName(); ?>/Services/ManageService"><i class="fa fa-book"></i> <span>Services</span></a>
		</li>
		<?php } ?>
		<?php if(isAdmin($role) || hasReadAccess($fat,"NetworkManagement")){?>
		<li id="idMainMenuManageNetwork" class="clsMainMenuName" >
			<a href="<?php echo $WebsiteUrl.getPageName(); ?>/NetworkManagement/ManageNetwork"><i class="fa fa-book"></i> <span>Manage Network</span></a>
		</li>
		<?php } ?>
		<?php if(isAdmin($role) || hasReadAccess($fat,"DistributorMargin")){?>
		<li id="idMainMenuDistributorMargin" class="clsMainMenuName" >
			<a href="<?php echo $WebsiteUrl.getPageName(); ?>/DistMargin/DistributorMargin"><i class="fa fa-user"></i> <span>Distributor Margin</span></a>
		</li>
		<?php } ?>
		<?php if(isAdmin($role) || hasReadAccess($fat,"RechargePermission") || hasReadAccess($fat,"RechargeAmountSettings") || hasReadAccess($fat,"GeneralSettings") || hasReadAccess($fat,"SMSGateway") || hasReadAccess($fat,"Rechargegateway") || hasReadAccess($fat,"RechargePermission")){?>
		<li class="treeview clsMainMenuName" id="idMainMenuSettings" >
          <a href="#">
            <i class="fa fa-cog"></i> <span>Settings</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-down"></i>
            </span>
          </a>
		  <ul class="treeview-menu">
			<?php if(isAdmin($role) || hasReadAccess($fat,"RechargePermission")){?>
			<li id="idMainMenuRechargePermission" class="clsSubMenuName">
				<a href="<?php echo $WebsiteUrl.getPageName(); ?>/RCPermission/RechargePermission">
				<i class="fa fa-circle-o"></i>Recharge Permission</a>
			</li>
			<?php } ?>
			<?php if(isAdmin($role) || hasReadAccess($fat,"Rechargegateway") && false){?>
			<li id="idMainMenuRechargeGateway" class="clsSubMenuName">
				<a href="<?php echo $WebsiteUrl.getPageName(); ?>/RechargeGateway/RechargeGateway">
				<i class="fa fa-circle-o"></i>Recharge Gateway</a>
			</li>
			<?php } ?>
			<?php if(isAdmin($role) || hasReadAccess($fat,"SMSGateway")){?>
			<li id="idMainMenuSMSGateway" class="clsSubMenuName">
				<a href="<?php echo $WebsiteUrl.getPageName(); ?>/SMSGateway/">
				<i class="fa fa-circle-o"></i>SMS Gateway</a>
			</li>
			<?php } ?>
			<?php if(isAdmin($role) || hasReadAccess($fat,"GeneralSettings")){?>
			<li id="idMainMenuGeneralSettings" class="clsSubMenuName">
				<a href="<?php echo $WebsiteUrl.getPageName(); ?>/GeneralSettings/GeneralSettings">
				<i class="fa fa-circle-o"></i>General Settings</a>
			</li>
			<?php } ?>
			<?php if(isAdmin($role) || hasReadAccess($fat,"RechargeAmountSettings")){?>
			<li id="idMainMenuRechargeAmountSetting" class="clsSubMenuName">
				<a href="<?php echo $WebsiteUrl.getPageName(); ?>/RechargeAmountSetting/RechargeAmountSetting">
				<i class="fa fa-circle-o"></i>Recharge Amount Setting</a>
			</li>
			<?php } ?>
          </ul>
        </li>
		<?php } ?>
		<?php if(isAdmin($role) || hasReadAccess($fat,"PaymentTransfer")|| hasReadAccess($fat,"PaymentCollection")){?>
		<li class="treeview clsMainMenuName" id="idMainMenuPayment" >
          <a href="#">
            <i class="fa fa-money"></i> <span>Payment</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-down"></i>
            </span>
          </a>
		  <ul class="treeview-menu">
			<?php if(isAdmin($role) || hasReadAccess($fat,"PaymentTransfer")){?>
			<li id="idMainMenuPaymentTransfer" class="clsSubMenuName">
				<a href="<?php echo $WebsiteUrl.getPageName(); ?>/Payment/PaymentTransfer">
				<i class="fa fa-circle-o"></i>Payment Transfer</a>
			</li>
			<?php } ?>
			<?php if(isAdmin($role) || hasReadAccess($fat,"PaymentCollection")){?>
			<li id="idMainMenuPaymentCollection" class="clsSubMenuName">
				<a href="<?php echo $WebsiteUrl.getPageName(); ?>/Payment/PaymentCollection">
				<i class="fa fa-circle-o"></i>Payment Collection</a>
			</li>
			<?php } ?>
          </ul>
		  <?php } ?>
		  <?php if(isAdmin($role) || hasReadAccess($fat,"MNPSettings")){?>
		  <li id="idMainMenuAutoRecharge" class="clsMainMenuName" >
			<a href="<?php echo $WebsiteUrl.getPageName(); ?>/AutoMNP/AutoMnpRecharge"><i class="fa fa-book"></i> <span>Auto & MNP Recharge</span></a>
		  </li>
		  <?php } ?>
		  <?php if(isAdmin($role) || hasReadAccess($fat,"Incoming")){?>
		  <li id="idMainMenuIncoming" class="clsMainMenuName" >
			<a href="<?php echo $WebsiteUrl.getPageName(); ?>/Incoming/"><i class="fa fa-bolt"></i> <span>Incoming </span></a>
		  </li>
		  <?php } ?>
		  <?php if(isAdmin($role) || hasReadAccess($fat,"Outgoing")){?>
		  <li id="idMainMenuOutgoing" class="clsMainMenuName" >
			<a href="<?php echo $WebsiteUrl.getPageName(); ?>/Outgoing/"><i class="fa fa-bolt"></i> <span>Outgoing </span></a>
		  </li>
        </li>
		 <?php } ?>
      </ul>
    </section>
  </aside>