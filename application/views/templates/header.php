<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?= $title ?></title>
    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/');?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/');?>css/sb-admin-2.min.css" rel="stylesheet">
  </head>
  <body class="bg-gradient-primary">
    <?php if ($this->session->userdata('email')): ?>
    
    <!-- Page Wrapper -->
    <div id="wrapper">
      <!-- Sidebar -->
      <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
          <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
          </div>
          <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
        </a>
        <!-- QUERY MENU -->
        <?php
        $role_id = $this->session->userdata('role_id');
        $queryMenu = "SELECT user_menu.id, menu
        FROM user_menu JOIN user_access_menu
        ON user_menu.id = user_access_menu.menu_id
        WHERE user_access_menu.role_id = $role_id
        ORDER BY user_access_menu.menu_id ASC";
        $menu = $this->db->query($queryMenu)->result_array();
        
        ?>
        <!-- LOOPING MENU -->
        <?php foreach ($menu as $row): ?>
        <div class="sidebar-heading">
          <?= $row['menu'] ?>
        </div>
        <!-- SIAPKAN SUB MENU -->
        <?php
        $menuId = $row['id'];
        $querySubMenu = "SELECT *
        FROM user_sub_menu JOIN user_menu
        ON user_sub_menu.menu_id = user_menu.id
        WHERE user_sub_menu.menu_id = $menuId
        AND user_sub_menu.is_active = 1";
        $subMenu = $this->db->query($querySubMenu)->result_array();
        ?>
        <!-- LOOPING SUB MENU -->
        <?php foreach ($subMenu as $row): ?>
          <?php if ($title == $row['title']): ?>
            <li class="nav-item active">
            <?php else: ?>
             <li class="nav-item">
          <?php endif ?>
          <a class="nav-link" href="<?= base_url().$row['url'] ?>">
            <i class="<?= $row['icon'] ?>"></i>
            <span><?= $row['title'] ?></span></a>
          </li>
          <?php endforeach ?>
          <hr class="sidebar-divider my-0">
          <?php endforeach ?>
          
          <!-- Divider -->
          
          <!-- Nav Item - Dashboard -->
          <!-- Nav Item - Tables -->
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url() ?>auth/logout
              ">
              <i class="fas fa-fw fa-sign-out-alt"></i>
              <span>Logout</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
              <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
          </ul>
          <!-- End of Sidebar -->
          <!-- Content Wrapper -->
          <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
              <!-- Topbar -->
              <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
                </button>
                
                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">
                  <!-- Nav Item - Messages -->
                  
                  <!-- Nav Item - User Information -->
                  <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $user['name'] ?></span>
                      <img class="img-profile rounded-circle" src="<?= base_url('assets/img/').$user['image']  ?>">
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"0 aria-labelledby="userDropdown">
                      
                      <a class="dropdown-item" href="<?= base_url() ?>auth/logout
                        " data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                      </a>
                    </div>
                  </li>
                </ul>
              </nav>
              <!-- End of Topbar -->
              <?php endif ?>