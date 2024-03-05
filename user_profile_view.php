<?php
    include 'components/connect.php';
    session_start();


    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
     include 'admin/blocked_user.php'; 
    
    } else {
        // User is not logged in
        $user_id = '';
        header('location:user_login.php');
    }
 

$user_id= $_GET['view_id'];
    

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile view</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo Time()?>">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

</head>
<style>
.content {
    margin-top: 50px;
    font-size: large;
}

#myImg {
    cursor: pointer;

}
</style>

<body>



    <?php include 'components/user_header.php'; ?>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-5 mx-auto">
                    <?php
                             $user_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                                $user_profile->execute([$user_id]);
                                $fetch_profile = $user_profile->fetch(PDO::FETCH_ASSOC);

                                $profilePicture = (!empty($fetch_profile['user_picture']))
                                ? "user_picture/{$fetch_profile['user_picture']}"
                                : "admin_picture/default_profile_picture.png"; 
                        
                            ?>


                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <a href="<?=$profilePicture?>" target="_blank"> <img id="myImg"
                                        class="profile-user-img img-fluid img-circle" src="<?= $profilePicture ?>"
                                        alt="<?= $fetch_profile['name']; ?> "></a>
                                <div id="myModal" class="modal">
                                    <span class="close"><i class="fa-solid fa-circle-xmark"
                                            style="color: #ff0000;"></i></span>
                                    <img class="modal-content" id="img01">
                                    <div id="caption"><?= $fetch_profile['name']; ?></div>
                                </div>

                            </div>


                            <!-- The Modal -->




                            <h3 class="profile-username text-center text-capitalize"><?= $fetch_profile['name']; ?></h3>


                            <?php
$total_user_order = 0;  
$total_user_purchase = 0;

$user_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");  
$user_orders->execute([$user_id]);
// Fetch the data
$data = $user_orders->fetchAll(PDO::FETCH_ASSOC);

$numRows = $user_orders->rowCount();



// Count occurrences of each product
$productCounts = [];
$totalProductsCount = 0;


// Separate and count total_products
$electronicItems = [];
foreach ($data as $order) {
    $userId = $order['user_id'];
    $totalProducts = $order['total_products'];
    $total_user_purchase += $order['total_price'];

    // Check if user_id is 1
    if ($userId == $fetch_profile['id']) {
        // Extract individual products from the total_products string
        preg_match_all('/([a-zA-Z\s]+) \((\d+) x (\d+)\)/', $totalProducts, $matches, PREG_SET_ORDER);

        // Process each product in the order
        foreach ($matches as $match) {
            $productName = $match[1];
            $productQuantity = $match[3];

            // Initialize count for the product if not already set
            if (!isset($productCounts[$productName])) {
                $productCounts[$productName] = 0;
            }

            // Increment the count for the product
            $productCounts[$productName] += $productQuantity;
            $totalProductsCount += $productQuantity;
        }
    }
}


?>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Total Orders</b> <a class="float-right"><?=$numRows ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Total Purchase</b> <a class="float-right">RS <?=$total_user_purchase;?></a>
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
                            <a href="update_user.php" class="btn btn-primary p-4 text-capitalize text-white-5"
                                style="font-size: 15px;"><b>Update</b></a>
                        </div>
                        <!-- /.card-body -->
                    </div>


                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">About Me</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">




                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                            <p class="text-muted">
                                <?= $fetch_profile['address']. ', ' . $fetch_profile['city'] . ' ' . $fetch_profile['state']  ?>
                            </p>

                            <hr>


                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>