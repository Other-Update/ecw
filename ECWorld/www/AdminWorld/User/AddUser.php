<?php
include '../../Header/Header.php';
?>
<style>
.form-group { margin-bottom: 5px; }
</style>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Create User</h1>
    </section>
	<?php include 'UserForm.php';?>
  </div>
<?php
	include '../../Header/Footer.php';
?>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/User/user_ilaiya.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/User/user.js"></script>
