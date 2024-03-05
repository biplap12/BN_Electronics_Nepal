<?php 
 include 'adminHeader.php';
 
 $admin_id = $_SESSION['admin_id'] ;
 if(isset($_SESSION['totalTrans'])){
     $receivedData = $_SESSION['totalTrans'];
 }
 else{
     $receivedData = 0;
 }

 ?>


<!-- Main content -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 ">Dashboard</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <?php
                        
            $select_orders = $conn->prepare("SELECT * FROM `orders`");
            $select_orders->execute();
            $number_of_orders = $select_orders->rowCount()
         ?>
                        <h3><?= $number_of_orders; ?></h3>

                        <p>New Orders</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="placed_orders.php" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->

            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <?php
            $total_completes = 0;
            $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE order_status = ?");
            $select_completes->execute(['completed']);
            if($select_completes->rowCount() > 0){
               while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
                  $total_completes += $fetch_completes['total_price'];
               }
            }
         ?>
                        <h3><span>Rs </span><?= $total_completes; ?></h3>

                        <p>Complete Orders</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="complete_placed_orders.php" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <?php
                                     $total_shipped = 0;
                                     $select_shipped = $conn->prepare("SELECT * FROM `orders` WHERE order_status = ?");
                                        $select_shipped->execute(['Shipped']);
                                        if($select_shipped->rowCount() > 0){
                                          while($fetch_shipped = $select_shipped->fetch(PDO::FETCH_ASSOC)){
                                            $total_shipped += $fetch_shipped['total_price'];
                                         }
                                     }
                                  ?>
                        <h3><span>Rs </span><?= $total_shipped; ?></h3>

                        <p>Shipped Orders</p>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-truck"></i>
                    </div>
                    <a href="shipped_placed_orders.php" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <?php
                                     $total_pendings = 0;
                                     $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE order_status = ?");
                                        $select_pendings->execute(['Pending']);
                                        if($select_pendings->rowCount() > 0){
                                          while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
                                            $total_pendings += $fetch_pendings['total_price'];
                                         }
                                     }
                                  ?>
                        <h3><span>Rs </span><?= $total_pendings; ?></h3>

                        <p>Pendings Orders</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <a href="pending_placed_orders.php" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->

            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <?php
                                   $select_admins = $conn->prepare("SELECT * FROM `admins` WHERE id = $admin_id");
                                   $select_admins->execute();
                                   $select_admins->setFetchMode(PDO::FETCH_ASSOC);
                                    $adminData = $select_admins->fetch();
                                    $adminstatus = $adminData['admin_status'];
                                       $number_of_admins = $select_admins->rowCount();
                                      
                                     ?>
                        <h3><?= $number_of_admins; ?></h3>

                        <p>Admin Registrations</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <a href="normal_admin_accounts.php" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-blue">
                    <div class="inner">
                        <?php
            $select_users = $conn->prepare("SELECT * FROM `users`");
            $select_users->execute();
            $number_of_users = $select_users->rowCount()
         ?>
                        <h3><?= $number_of_users; ?></h3>

                        <p>User Registrations</p>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <a href="users_accounts.php" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <?php
                        $select_products = $conn->prepare("SELECT * FROM `products`");
                        $select_products->execute();
                        $number_of_products = $select_products->rowCount()
                    ?>
                        <h3><?= $number_of_products; ?></h3>

                        <p>Products Added</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-cart-plus"></i>
                    </div>
                    <a href="product_lists.php" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <?php
                if($adminstatus == 'regular'){
                    ?>
                <div class="small-box bg-info">
                    <div class="inner">
                        <?php  
                                    $select_messages = $conn->prepare("SELECT * FROM `messages`");
                                    $select_messages->execute();
                                    $number_of_messages = $select_messages->rowCount();
                                 
                                  ?>
                        <h3><span></span>
                            <?= $number_of_messages; ?>
                        </h3>
                        <p>Messages</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-comment"></i>
                    </div>
                    <a href="messages.php" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
                <?php
                }else{
                    ?>

                <div class="small-box bg-info">
                    <div class="inner">

                        <h3><span><?php echo  $receivedData ;?></span>
                        </h3>
                        <p>Transection List</p>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-money-bill-trend-up"></i>
                    </div>
                    <a href="transaction_list.php" class="small-box-footer" onclick="showLoader()">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
                <?php
                }
                ?>
            </div>
            <!-- ./col -->
        </div>
    </div>
</section>

<!-- pi Chart -->
<?php 
  include 'chart.php';
   ?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
function showLoader() {
    Swal.fire({
        title: 'Please Wait...',
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        showConfirmButton: false,
        onBeforeOpen: () => {
            Swal.showLoading();
            $(".row").css({
                opacity: "0.2",
                pointerEvents: "none"
            });
        }
    });


}
</script>