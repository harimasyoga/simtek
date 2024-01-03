<?php
$setting = $this->db->query("SELECT * FROM m_setting")->row();
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?= $judul ?> | <?= $setting->nm_aplikasi ?></title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- favicon -->
	<link rel="icon" type="image/png" href="<?= base_url('assets/gambar/') . $setting->logo ?>">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/fontawesome-free/css/all.min.css">

	<!-- DataTables -->
	<link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
	<!-- SweetAlert2 -->
	<!-- <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css"> -->

	<link href="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.css" rel="stylesheet" type="text/css">


	<!-- <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/sweetalert/sweetalert.css"> -->
	<!-- Toastr -->
	<link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/toastr/toastr.min.css">
	<!-- Select2 -->
	<link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/select2/css/select2.min.css">
	<link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="<?= base_url('assets/') ?>dist/css/adminlte.min.css?v=1.0">

	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="<?= base_url('assets/') ?>dist/css/new.css?v=1.1">
	<!-- Google Font: Source Sans Pro -->
	<link href="<?= base_url('assets/') ?>plugins/fontawesome-free/css/customFont.css" rel="stylesheet">

	<!-- jQuery -->
	<script src="<?= base_url('assets/') ?>plugins/jquery/jquery.min.js"></script>

	<style>
		/* .select2.narrow {
			width: 200px;
		}
		.wrap.select2-selection--single {
			height: 100%;
		}
		.select2-container .wrap.select2-selection--single .select2-selection__rendered {
			word-wrap: break-word;
			text-overflow: inherit;
			white-space: normal;
		} */

		.select2-selection--single {
			height: 100% !important;
		}
		.select2-selection__rendered{
			word-wrap: break-word !important;
			text-overflow: inherit !important;
			white-space: normal !important;
		}
	</style>
</head>

<body class="hold-transition sidebar-mini">
	<!-- Site wrapper -->
	<div class="wrapper">
		<!-- Navbar -->
		<nav class="main-header navbar navbar-expand navbar-white navbar-light">
			<!-- Left navbar links -->
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
				</li>
				<li class="nav-item">
					<li style="color:#000" class="nav-link" style="padding-left:0px;" >
						<b><?= ucwords($this->session->userdata('nm_user')) ?></b>  
						[ <span style="color:blue"><b><?= $this->session->userdata('level') ?></b></span> ]
					</li>
				</li>
			</ul>


			<!-- Right navbar links -->
			<ul class="navbar-nav ml-auto">
				<!-- Messages Dropdown Menu -->
				<!-- Notifications Dropdown Menu -->
				<li class="nav-item dropdown">
					<a class="nav-link" href="<?= base_url('Login/logout') ?>">
						<i class="fas fa-sign-out-alt"></i> <b>Logout</b>
					</a>
				</li>
				<!-- <li class="nav-item">
					<a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
						<i class="fas fa-th-large"></i>
					</a>
				</li> -->
			</ul>
		</nav>
		<!-- /.navbar -->

		<!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-dark-primary elevation-1" >
			<!-- Brand Logo -->
			<!-- <a href="<?= base_url('assets/') ?>index3.html" class="brand-link">
      <img src="<?= base_url('assets/') ?>dist/img/AdminLTELogo.png"
           alt="AdminLTE Logo"
           class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Sistem Cost</span>
    </a> -->

			<!-- Sidebar -->
			<div class="sidebar">
				<!-- Sidebar user (optional) -->
				<div class="user-panel mt-3 pb-3 mb-3 d-flex" >
				<!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex"> -->
					<!-- <div class="image">
						<img src="<?= base_url('assets/') ?>dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
					</div> -->
					<!-- <div class="info"> -->
					<div class="">
						<nav class="mt-2" style="font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">
							<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
								<!-- Add icons to the links using the .nav-icon class
					with font-awesome or any other icon font library -->
								<li class="nav-item has-treeview">
								<a href="<?= base_url('Master') ?>" class="nav-link">
									<h2><i class="fas fa-cogs"></i>
										<?= $setting->singkatan ?></h2>
								</a>
								</li>
							</ul>
						</nav>
					</div>
				</div>
				

				<!-- Sidebar Menu -->
				<nav class="mt-2" style="font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">
					<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
						<!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
						<div id="accordion-header">
						<li class="nav-item has-treeview">
							<a href="<?= base_url('Master') ?>" class="nav-link">
								<i class="nav-icon fas fa-tachometer-alt"></i>
								<p>
									<b>Dashboard</b>
								</p>
							</a>
						</li>
						</div>

						<div id="accordion-menu">
						<?php
						$userlevel = $this->session->userdata('level');

						$list_menu = $this->db->query("SELECT * from m_modul where lev=0 and aktif=1 and kode in 
						(SELECT kode_modul from m_modul_groupd a 
						left join m_modul_group b ON a.id_group=b.id_group 
						where b.val_group='$userlevel'
						group by kode_modul)
						order by kode")->result();
						
						foreach($list_menu as $menu1) { 
							$_menu1 = $menu1->kode;
							$level = 10;?>
							<li class="nav-item has-treeview">

								<a href="#collapse<?=$menu1->nama?>" data-toggle="collapse" class="nav-link">
									<i class="nav-icon fas <?=$menu1->icon?>"></i>
									<p>
										<b><?= $menu1->nama ?></b>
										<i class="fas fa-angle-left right"></i>
									</p>
								</a>
								
								<div id="collapse<?=$menu1->nama?>" class="collapse" data-parent="#accordion-menu">
								<?php
								$list_sub_menu = $this->db->query("SELECT * from m_modul where main_menu='$_menu1' and aktif=1 and kode in 
								(SELECT kode_modul from m_modul_groupd a 
								left join m_modul_group b ON a.id_group=b.id_group 
								where b.val_group='$userlevel'
								group by kode_modul) order by kode")->result();

								foreach($list_sub_menu as $menu2) {
								$_menu2 = $menu2->kode;?>
									<a style="color:#fff;" class="nav-link"  href="<?= base_url("$menu2->url"); ?>" > &nbsp;&nbsp;&nbsp;
										<i class="fa <?=$menu2->icon?> nav-icon"></i>
										<p><b><?= $menu2->nama ?></b></p>
									</a>
										
								<?php }?>
								
								</div>
							</li>
						<?php } ?>
						
						</div>

					</ul>
				</nav>
				<!-- /.sidebar-menu -->
			</div>
			<!-- /menu footer buttons -->
			<div class="sidebar-footer">			
              <a data-toggle="tooltip" data-placement="top" title="Logout">
                <img width="50"  src="<?= base_url('assets/gambar/ppi.png')?>" alt=""></span>
              </a>
            </div>
			<!-- <div style="position:absolute;bottom:0;right:0;left:0;padding:5px 5px 5px 10px; background-image: linear-gradient(180deg,#cc193800 10%,#450410ad 100%);">
				
				<img width="50"  src="<?= base_url('assets/gambar/ppi.png')?>" alt="">
			</div> -->
            <!-- /menu footer buttons -->
			
			<!-- /.sidebar -->

			
		</aside>

		<!-- loading -->
			
		<div class="modal fade" id="loading" data-backdrop="static" data-keyboard="false" data-toggle="modal" role="dialog" style="z-index: 1053;">
		<div class="modal-dialog modal-xl" >
			<div class="text-center" style="margin-top: 300px;">
				<button class="btn btn-dark" type="button" disabled>
					<span class="spinner-border text-light" role="status" aria-hidden="true"></span>
					<span style="font-size:50px; color:#fff;" ><h3>Loading...</h3></span>
				</button>
			</div>
		</div>
		</div>

