<?php
session_start();
include '../components/connect.php';
$current_page = basename($_SERVER['PHP_SELF']);
$admin_id = $_SESSION['admin_id'];
include 'blocked_admin.php';

if(!isset($admin_id)){
   header('location:admin_login.php');
}

   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }

   $select_admins = $conn->prepare("SELECT * FROM `admins` WHERE id = ?"); 
$select_admins->execute([$admin_id]);
$fetch_admins = $select_admins->fetch(PDO::FETCH_ASSOC);
$admin = $fetch_admins['admin_status'];
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin BN Electronics Nepal</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css" />
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css" />
    <!-- JQVMap -->
    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css" />
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css" />
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css" />
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css" />
    <!-- summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link rel="stylesheet" href="admin.css?v=<?php echo Time();?>">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper overflow-hidden ">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../images/bn-electronics-logo.png" alt="AdminLogo" height="auto"
                width="300" />

        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <!-- <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Home</a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
          </li> -->
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <a class="nav-link" data-widget="navbar-search" data-target="#navbar-search3" href="#" role="button">
                    <i class="fas fa-search"></i>
                </a>
                <div class="navbar-search-block" id="navbar-search3">
                    <form class="form-inline">
                        <div class="input-group input-group-sm">
                            <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                aria-label="Search">
                            <div class="input-group-append">
                                <button class="btn btn-navbar" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                </li>
                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a href="messages.php" class="nav-link">
                        <i class="far fa-comments"></i>
                        <?php
                        if($admin === 'super'){
                            $select_messages = $conn->prepare("SELECT * FROM `messages`");
                            $select_messages->execute();
                        }else{
                            $select_messages = $conn->prepare("SELECT * FROM `messages` WHERE admin_id = ?");
                            $select_messages->execute([$admin_id]);
                        }
                            $number_of_messages = $select_messages->rowCount()
                        ?>
                        <span class="badge badge-danger navbar-badge"><?= $number_of_messages; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fa-solid fa-user"></i>
                        <span class="badge badge-warning navbar-badge"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <!-- <span class="dropdown-item dropdown-header">15 Notifications</span> -->
                        <?php
                         if($admin =='super'){
                            ?>
                        <div class="dropdown-divider"></div>
                        <a href="categoryAdd.php" class="dropdown-item">
                            <i class="fa-solid fa-plus"></i> Category Add
                            <span class="float-right text-muted text-sm"></span>
                        </a>
                        <?php
                         }else{
                             echo '';
                         }
                        ?>
                        <div class="dropdown-divider"></div>
                        <a href="update_profile.php" class="dropdown-item">
                            <i class="fa-solid fa-pen-to-square"></i>Update profile
                            <span class="float-right text-muted text-sm"></span>
                        </a>
                        <?php
                        if($admin =='super'){
                            echo ' <div class="dropdown-divider"></div>';
                            echo ' <a href="register_admin.php" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> Register
                            <span class="float-right text-muted text-sm"></span>
                        </a>
                        ';
                        }else{
                            echo '';
                        }
                        ?>
                        <div class="dropdown-divider"></div>
                        <a href="../components/admin_logout.php" class="dropdown-item">
                            <i class="fa-solid fa-right-from-bracket"></i> Log out
                            <span class="float-right text-muted text-sm"></span>
                        </a>
                        <!-- <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a> -->
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index.php" class="brand-link">
                <img src="../images/bn-electronics-logo.png" alt="BN Logo" class="brand-image img-circle elevation-3"
                    style="opacity: 1" />
                <span class="brand-text font-weight-light">BN Electronics</span>
            </a>
            <div class="sidebar">
                <?php
                $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
                $select_profile->execute([$admin_id]);
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                $profilePicture = (!empty($fetch_profile['photo']))
                ? "../admin_picture/{$fetch_profile['photo']}"
                : "../admin_picture/default_profile_picture.png"; 
                ?>
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="<?= $profilePicture;?> " class=" img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="admin_profile.php" class="d-block text-capitalize"><?= $fetch_profile['name']; ?></a>
                    </div>
                </div>
                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                            data-accordion="false">
                            <li class="nav-item menu-open">
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="index.php"
                                            class="nav-link <?php echo ($current_page === 'index.php') ? 'active' : ''; ?>">
                                            <i class="fas fa-tachometer-alt"></i>
                                            <p>Dashboard</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="./products.php"
                                            class="nav-link <?php echo ($current_page === 'products.php') ? 'active' : ''; ?>">
                                            <i class="fas fa-plus-circle"></i>
                                            <p>Product Add</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="./product_lists.php"
                                            class="nav-link <?php echo ($current_page === 'product_lists.php') ? 'active' : ''; ?>">
                                            <i class="fas fa-list"></i>
                                            <p>Product Lists</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="placed_orders.php"
                                            class="nav-link <?php echo ($current_page === 'placed_orders.php') ? 'active' : ''; ?>">
                                            <i class="fas fa-shopping-bag"></i>
                                            <p>Orders</p>
                                        </a>
                                    </li>
                                    <?php      
                                    if($admin == 'super'){
                                            echo "<li class='nav-item'>";
                                            echo "<a href='super_admin_accounts.php' class='nav-link " . ($current_page === 'super_admin_accounts.php' ? 'active' : '') . "'>";
                                            echo "<i class='fas fa-user-cog'></i>";
                                            echo "<p>Super Admin Users</p>";
                                            echo "</a>";
                                            echo "</li>";
                                            echo "<li class='nav-item'>";
                                            echo "<a href='normal_admin_allData.php' class='nav-link" . ($current_page === 'normal_admin_allData.php' ? ' active' : '') . "'>";
                                            echo "<i class='fas fa-user-cog'></i>";
                                            echo "<p>Admin Profile</p>";
                                            echo "</a>";
                                            echo "</li>";
                                        }else{
                                            echo "<li class='nav-item'>";
                                            echo "<a href='normal_admin_accounts.php' class='nav-link" . ($current_page === 'normal_admin_accounts.php' ? ' active' : '') . "'>";
                                            echo "<i class='fas fa-user-cog'></i>";
                                            echo "<p>Admin Profile</p>";
                                            echo "</a>";
                                            echo "</li>";
                                        }
                                    ?>
                            </li>
                            <li class="nav-item">
                                <a href="users_accounts.php"
                                    class="nav-link <?php echo ($current_page === 'users_accounts.php') ? 'active' : ''; ?>">
                                    <i class="fas fa-users"></i>
                                    <p> Users</p>
                                </a>
                            </li>
                        </ul>
                        </li>

                        </ul>

                    </nav>


                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
        </aside>
        </nav>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->


            <script src="plugins/jquery/jquery.min.js"></script>
            <!-- Bootstrap 4 -->
            <!-- <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script> -->
            <!-- AdminLTE App -->
            <script src="dist/js/adminlte.min.js"></script>