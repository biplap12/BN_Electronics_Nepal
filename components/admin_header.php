<?php
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
?>

<header class="header">

    <section class="flex">

        <a href="../admin/dashboard.php" class="logo"><span>Electronic Nepal</span></a>

        <nav class="navbar">
            <a href="../admin/dashboard.php">Home</a>
            <a href="../admin/products.php">Products</a>
            <a href="../admin/placed_orders.php">Orders</a>
            <a href="../admin/admin_accounts.php">Admins</a>
            <a href="../admin/users_accounts.php">Users</a>
            <a href="../admin/messages.php">Messages</a>
        </nav>
        <?php
            $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"><span style="text-transform: capitalize;">
                    <?= $fetch_profile['name']; ?></span>
            </div>
        </div>

        <div class="profile">


            <a href="../admin/update_profile.php" class="btn">Update profile</a>
            <div class="flex-btn">
                <a href="../admin/register_admin.php" class="option-btn">Register</a>
                <!-- <a href="../admin/admin_login.php" class="option-btn">Login</a> -->
            </div>
            <a href="../components/admin_logout.php" class="delete-btn"
                onclick="return confirm('Logout from the website?');">Logout</a>
        </div>

    </section>

</header>