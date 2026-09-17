<!DOCTYPE html>
<html>
  <head>
    <title><?php echo env('APP_NAME', 'My Application'); ?></title>
	 <link rel="icon" href="<?php echo base_url(); ?><?php echo env('APP_FAVICON', 'assets/tambahan/gambar/Coates_Indonesia.jpg'); ?>">
    <!-- meta -->
    
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- css --> 
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/sweetalert/sweetalert.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/tambahan.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/eksternal/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/eksternal/ionicons.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/bootstrap-summernote/summernote.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/skins/<?php echo env('APP_SKIN', 'skin-red'); ?>.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/select2.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/iCheck/all.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.bootstrap.css">

    <!-- jQuery 2.2.3 -->
    <script src="<?php echo base_url(); ?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
	
	<style type="text/css">
	
	.merah {
	    color:red;
	}
	
	
	.thumb1 { 
  background: url(blah.jpg) 50% 50% no-repeat; /* 50% 50% centers image in div */
  width: 150px;
  height: 150px;
}

	
	/* Important part */
/*.modal-dialog{*/
/*    overflow-y: initial !important*/
/*}*/
/*.modal-body{*/
/*    height: 485px;*/
/*    overflow-y: auto;*/
/*}*/

.jarak-pop{
   margin-left: -10px;
}

  .pdf {
    color: red;
}

	
	@media only screen and (min-width: 800px) {
	.table-responsive {
    overflow: hidden;
	}
	}


.control-sidebar {
       .tab-content {
               height: calc(100vh - 135px);
               overflow-y: scroll;
               overflow-x: hidden;
        }
}

@media (min-width: 768px) {
        .control-sidebar {
               .tab-content {
                       height: calc(100vh - 85px);
                }
        }
}

	</style>
	
    </head>
  
	<?php
	$_body_class  = 'hold-transition ' . env('APP_SKIN', 'skin-red') . ' sidebar-mini';
	if (env('APP_FIXED_LAYOUT', '0') == '1')     $_body_class .= ' fixed';
	if (env('APP_SIDEBAR_COLLAPSE', '0') == '1') $_body_class .= ' sidebar-collapse';
	if (env('APP_BOXED_LAYOUT', '0') == '1')     $_body_class .= ' layout-boxed';
	?>
	<body class="<?php echo $_body_class; ?>">
    <div class="wrapper">
      <!-- header -->
     
	<header class="main-header">
    <!-- Logo -->
    <a href="<?php echo base_url(); ?>" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b><?php echo substr(env('APP_NAME','App'),0,2); ?></b></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b><?php echo env('APP_NAME', 'My Application'); ?></b></span>
    </a>

  <!-- nav -->
  <nav class="navbar navbar-static-top" role="navigation">
  <!-- Sidebar toggle button-->
  <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
  
  <!-- Navbar Right Menu -->
  <div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
      <!-- User Account Menu -->
      <li class="dropdown user user-menu">
        <!-- Menu Toggle Button -->
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <!-- The user image in the navbar-->
          <?php if (!empty($userdata->foto) && file_exists(FCPATH.'upload/user/'.$userdata->foto)): ?>
          <img src="<?php echo base_url(); ?>upload/user/<?php echo $userdata->foto; ?>" class="user-image" alt="User Image">
          <?php else: ?>
          <img src="<?php echo base_url(); ?>assets/dist/img/user.png" class="user-image" alt="User Image">
          <?php endif; ?>
          <!-- hidden-xs hides the username on small devices so only the image appears. -->
          <span class="hidden-xs"><?php echo $userdata->nama; ?></span>
        </a>
        <ul class="dropdown-menu">
          <!-- The user image in the menu -->
          <li class="user-header">
            <?php if (!empty($userdata->foto) && file_exists(FCPATH.'upload/user/'.$userdata->foto)): ?>
            <img src="<?php echo base_url(); ?>upload/user/<?php echo $userdata->foto; ?>" class="img-circle" alt="User Image">
            <?php else: ?>
            <img src="<?php echo base_url(); ?>assets/dist/img/user.png" class="img-circle" alt="User Image">
            <?php endif; ?>
            <p>
              <?php echo $userdata->nama; ?>
            </p>
          </li>
          <!-- Menu Footer-->
          <li class="user-footer">
            <div class="pull-left">
              <a class="ajaxify klik " href="<?php echo site_url('profile'); ?>" class="btn btn-default btn-flat">Profile</a>
            </div>
            <div class="pull-right">
              <a class="ajaxify klik" href="<?php echo site_url('Default/Auth/logout'); ?>" class="btn btn-default btn-flat">Sign out</a>
            </div>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
</header>
	 
	 
      
  <!-- sidebar -->
 <aside class="main-sidebar">
 <!-- sidebar: style can be found in sidebar.less -->
 <div id ="loading2"></div>
  <section class="sidebar">
    <!-- Sidebar user panel (optional) -->
     <div class="user-panel">
      <div class="pull-left image">
        <?php $foto_sesi = $this->session->userdata('foto'); ?>
        <?php if (!empty($foto_sesi) && file_exists(FCPATH.'upload/user/'.$foto_sesi)): ?>
        <img src="<?php echo base_url(); ?>upload/user/<?php echo $foto_sesi; ?>" class="img-circle" alt="User Image">
        <?php else: ?>
        <img src="<?php echo base_url(); ?>assets/dist/img/user.png" class="img-circle" alt="User Image">
        <?php endif; ?>
      </div>
      <div class="pull-left info">
        <p><?php echo $this->session->userdata('nama') ?></p>
        <!-- Status -->
        <a href="<?php echo base_url(); ?>assets/#"> Web Admin</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
	<div id="menu">
    <ul class="sidebar-menu">
    <li class="header">LIST MENU</li>
    <!-- menu sidebar list-->
    
	<?php
	     $this->load->model('M_sidebar');
			 $menu = $this->M_sidebar->left_menu();
				if($menu->num_rows() > 0){
				foreach($menu->result() as $row){
					$menu_child = $this->M_sidebar->left_menu_child($row->id_menu);
					$active = ($this->uri->segment(1)==$row->link)?'active':'';
					$open = ($this->uri->segment(1)==$row->link)?'open':'';
					$access = $this->M_sidebar->access('view', $row->kode_menu);
							
				  // jika view 1 (tampilkan menu)
					
					if($access->menuview==1){
						if($menu_child->num_rows() > 0){
						echo "<li class='treeview'>
							  <a href='javascript:;'><i class='".$row->icon."'></i>
							  <span class='title'>".$row->nama_menu."</span>
							  <span class='pull-right-container ".$open."'><i class='fa fa-angle-left pull-right'></i></span>
							  </a>";
                          
						  
					// untuk sub menu dropdownya
               echo "<ul class='treeview-menu ajaxify'>";
						   if($menu_child->num_rows() > 0){
						   foreach($menu_child->result() as $obj){
						   $access_child = $this->M_sidebar->access('view', $obj->kode_menu);
						   if($access_child->menuview==1){
						   echo "<li><a class='ajaxify' href='".site_url($obj->link)."'>
								<i class='".$obj->icon."'></i>
								<span class='title'>".$obj->nama_menu."</span>
								</a></li>";
						    }													
					      }
						}
					    echo"</ul></li>";
						}
						else
						{						
						echo "
						<li class = '".$active."'>
						<a class='ajaxify' href='".site_url($row->link)."'>
						<i class='".$row->icon."'></i>
						<span class='title'>".$row->nama_menu."</span>
						</a></li>";
						}
					}
				}
			 }
	    ?>
    </ul>
	</div>
    <!-- /.sidebar-menu -->
  </section>
  <!-- /.sidebar -->
</aside>


 <script>
$('.sidebar-menu a').click(function() {
  $(".sidebar-menu").find(".active").removeClass("active");
  $(this).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');
});
</script> 


<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

