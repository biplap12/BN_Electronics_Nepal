<?php include 'adminHeader.php'; 
$admin_id=$_GET['user_id'];

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
                             $user_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                                $user_profile->execute([$admin_id]);
                                $fetch_profile = $user_profile->fetch(PDO::FETCH_ASSOC);

                                $profilePicture = (!empty($fetch_profile['user_picture']))
                                ? "../user_picture/{$fetch_profile['user_picture']}"
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


                        <!-- The Modal -->




                        <h3 class="profile-username text-center text-capitalize"><?= $fetch_profile['name']; ?></h3>

                        <p class="text-muted text-center">Id: <?= $fetch_profile['id']; ?></p>

                        <?php
$total_user_order = 0;  
$total_user_purchase = 0;

$user_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");  
$user_orders->execute([$admin_id]);

if ($user_orders->rowCount() > 0) {
    while ($fetch_total_order = $user_orders->fetch(PDO::FETCH_ASSOC)) {
        // Assuming you want to accumulate some information from each order, adjust this part accordingly
        $total_user_order += $fetch_total_order['user_id']; 
        $total_user_purchase += $fetch_total_order['total_price'];
        // $fetch__product = $fetch_total_order['total_products'];
}
}

$user_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");  
$user_orders->execute([$admin_id]);

// Fetch the data
$data = $user_orders->fetchAll(PDO::FETCH_ASSOC);

// Count occurrences of each product
$productCounts = [];
$totalProductsCount = 0;


// Separate and count total_products
$electronicItems = [];
foreach ($data as $order) {
    $userId = $order['user_id'];
    $totalProducts = $order['total_products'];

    // Check if user_id is 1
    if ($userId == $fetch_profile['id']) {
        // Extract individual products from the total_products string
        preg_match_all('/([a-zA-Z\s]+) \((\d+) x (\d+)\)/', $totalProducts, $matches, PREG_SET_ORDER);

        // Process each product in the order
        foreach ($matches as $match) {
            $productName = $match[1];
            $productQuantity = $match[3];
            $productPrice= $match[2];

            // Initialize count for the product if not already set
            if (!isset($productCounts[$productName])) {
                $productCounts[$productName] = 0;
            }

            // Increment the count for the product
            $productCounts[$productName] += $productQuantity;
            $totalProductsCount += $productQuantity;


            //  $productCounts[$productName] += $productPrice;
            // $totalProductsCount += $productPrice;

            // echo $productPrice;

        }
    }
}

?>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Total Orders</b> <a class="float-right"><?=$total_user_order ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Total Purchase</b> <a class="float-right">RS <?=$total_user_purchase?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Total Product</b> <a class="float-right"><?= $totalProductsCount ?></a>
                            </li>


                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td><b>SN</b></td>
                                        <td><b>Total Product</b></td>
                                        <td class="text-right"><b>Qty</b></td>
                                    </tr>
                                    <hr>

                                    <?php
                      
                        $i = 1;
                        
                        foreach ($productCounts as $productName => $productCount) {
                            // Assuming $productPrices is an array containing prices for each product
                        
                            echo "<tr>
                                    <td>$i</td>
                                    <td><b>$productName</b></td>
                                    <td class='text-right'>$productCount</td>
                                  </tr>";
                            $i++;
                        }
                        
                        
                        ?>
                                </tbody>
                            </table>


                        </ul>
                        <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->
                    </div>
                    <!-- /.card-body -->
                </div>







                <!-- ************** -->
                <?php 

// $user_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");  
// $user_orders->execute([$admin_id]);

// // Fetch the data
// $data = $user_orders->fetchAll(PDO::FETCH_ASSOC);

// // Count occurrences of each product
// $productCounts = [];

// // Separate and count total_products
// $electronicItems = [];
// foreach ($data as $order) {
//     $userId = $order['user_id'];
//     $totalProducts = $order['total_products'];

//     // Check if user_id is 1
//     if ($userId == $fetch_profile['id']) {
//         // Extract individual products from the total_products string
//         preg_match_all('/([a-zA-Z\s]+) \((\d+) x (\d+)\)/', $totalProducts, $matches, PREG_SET_ORDER);

//         // Process each product in the order
//         foreach ($matches as $match) {
//             $productName = $match[1];

//             // Initialize count for the product if not already set
//             if (!isset($productCounts[$productName])) {
//                 $productCounts[$productName] = 0;
//             }

//             // Increment the count for the product
//             $productCounts[$productName]++;
//         }

//         // Create a list of products for each order
//         $productList = [];
//         foreach ($matches as $match) {
//             $product = [
//                 'name' => $match[1],
//                 'price' => $match[2],
//                 'quantity' => $match[3],
//                 'count' => $productCounts[$match[1]] // Add count information to each product
//             ];
//             $productList[] = $product;
//         }

//         // Add the product list to the order
//         $order['products'] = $productList;

//         // Add the order to the electronic items list
//         $electronicItems[] = $order;
//     }
// }

// // Count of electronic items
// $count = count($electronicItems);

// // Add an h1 tag to display the count
// echo "<h1>Count of electronic items for user_id 1: $count</h1>";

// // Print the list of electronic items with product counts
// echo "<ul>";
// foreach ($electronicItems as $item) {
//     echo "<li>";
//     print_r($item);
//     echo "</li>";
// }
// echo "</ul>";








?>









                <!-- ************ -->

                <!-- /.card -->

                <!-- About Me Box -->
                <!-- <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">About Me</h3>
                    </div>
                    /.card-header -->
                <!-- <div class="card-body">
                        <strong><i class="fas fa-book mr-1"></i> Education</strong>

                        <p class="text-muted">
                            B.S. in Computer Science from the University of Tennessee at Knoxville
                        </p>

                        <hr>

                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                        <p class="text-muted">Malibu, California</p>

                        <hr>

                        <strong><i class="fas fa-pencil-alt mr-1"></i> Skills</strong>

                        <p class="text-muted">
                            <span class="tag tag-danger">UI Design</span>
                            <span class="tag tag-success">Coding</span>
                            <span class="tag tag-info">Javascript</span>
                            <span class="tag tag-warning">PHP</span>
                            <span class="tag tag-primary">Node.js</span>
                        </p>

                        <hr>

                        <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>

                        <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam
                            fermentum enim neque.</p>
                    </div> -->
                <!-- /.card-body -->
                <!-- </div>  -->
                <!-- /.card -->
            </div>

            <!-- /.row -->
        </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<!-- /.content-wrapper -->


<!-- Control Sidebar -->

<!-- ./wrapper -->

<!-- jQuery -->

<script src="../js/profile_picture.js"></script>