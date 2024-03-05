<?php include 'adminHeader.php'; 
$admin_id=$_GET['view'];

?>


<!-- Main content -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Profile</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 mx-auto">
                <?php
                             $admin_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
                                $admin_profile->execute([$admin_id]);
                                $fetch_profile = $admin_profile->fetch(PDO::FETCH_ASSOC);

                                $profilePicture = (!empty($fetch_profile['photo']))
                                ? "../admin_picture/{$fetch_profile['photo']}"
                                : "../admin_picture/default_profile_picture.png"; 
                        
                            ?>


                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img id="myImg" class="profile-user-img img-fluid img-circle" src="<?= $profilePicture ?>">
                            <div id="myModal" class="modal">
                                <span class="close"><i class="fa-solid fa-circle-xmark"
                                        style="color: #ff0000;"></i></span>
                                <img class="modal-content" id="img01">
                                <div id="caption"><?= $fetch_profile['name']; ?></div>
                            </div>

                        </div>
                        <h3 class="profile-username text-center text-capitalize"><?= $fetch_profile['name']; ?></h3>

                        <p class="text-muted text-center">Id: <?= $fetch_profile['id']; ?></p>

                        <?php
                            $products = $conn->prepare("
                                SELECT products.p_name, products.price, products.p_quantity,  products.admin_id, products.id
                                FROM products
                                WHERE products.admin_id = ?
                            ");
                            $products->execute([$admin_id]);

                            $data = $products->fetchAll(PDO::FETCH_ASSOC);
                            $productCount = 0;
                            $totalPrice = 0;

                            // Check if there are any products
                            if (!empty($data)) {
                                echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                echo "<tr>";
                                echo "<th>ID</th>";
                                echo "<th>Name</th>";
                                echo "<th>Price</th>";
                                echo "<th>Qty</th>";
                                echo "<th>Total</th>";
                            
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";

                                foreach ($data as $product) {
                                    echo "<tr>";
                                    echo "<td>" . $product['id'] . "</td>";
                                    echo "<td>" . $product['p_name'] . "</td>";
                                    echo "<td>Rs " . $product['price'] . "</td>";
                                    echo "<td>" . $product['p_quantity'] . "</td>";
                                    $totalProductPrice = $product['price'] * $product['p_quantity'];
                                    echo "<td>Rs " . number_format($totalProductPrice) . "</td>";

                                    echo "</tr>";

                                    // Increment product count
                                    $productCount++;

                                    // Add total price for each product to the overall total
                                    $totalPrice += $totalProductPrice;
                                }

                                echo "</tbody>";
                                echo "</table>";

                                // Display the count and overall total price
                                echo "<p>Total Number of Products: $productCount</p>";
                                echo "<p>Overall Total Price of Products: Rs " . number_format($totalPrice) . "</p>";
                            } else {
                                echo '<p class="empty">No products available!</p>';
                            }
                            ?>


                    </div>

                </div>
</section>


<script src="../js/profile_picture.js"></script>