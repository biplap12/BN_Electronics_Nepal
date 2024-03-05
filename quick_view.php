<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

$selectdata=$conn->prepare("SELECT * FROM users WHERE id=?");
$selectdata->execute([$user_id]);
$fetch_user=$selectdata->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick view</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link rel="stylesheet" href="css/style.css?v=<?php echo Time()?>">
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />


</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="quick-view">

        <h1 class="heading">Quick view</h1>

        <?php
     $pid = $_GET['pid'];
    //  $select_products = $conn->prepare("SELECT admins.name,products.* FROM products JOIN admins ON products.admin_id = admins.id WHERE products.id = ?");
    $select_products = $conn->prepare("SELECT admins.name,COUNT(product_ratings.pid) as pid,products.* FROM products JOIN admins ON products.admin_id = admins.id LEFT JOIN product_ratings ON products.id = product_ratings.pid WHERE products.id = ?");
    $select_products->execute([$pid]);
     $encryptid=base64_encode($pid);
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
        $encryptAid=base64_encode($fetch_product['admin_id']);
        $ratingcount = $fetch_product['pid'];        
        $selectdata_rating = $conn->prepare("SELECT * FROM product_ratings WHERE pid=?");
        $selectdata_rating->execute([$pid]);
        $totalRating = 0;        
        while ($fetch_rating = $selectdata_rating->fetch(PDO::FETCH_ASSOC)) {
            $totalRating += $fetch_rating['rating'];
        }
        if ($ratingcount == 0) {
            $avgRating = 0;
        } else{
            $avgRating = $totalRating / $ratingcount;
        }
   ?>
        <form action="" method="post" class="box">
            <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
            <input type="hidden" name="name" value="<?= $fetch_product['p_name']; ?>">
            <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
            <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">

            <div class="row">
                <div class="image-container">
                    <div class="main-image">
                        <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
                    </div>
                    <div class="sub-image">
                        <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
                        <img src="uploaded_img/<?= $fetch_product['image_02']; ?>" alt="">
                        <img src="uploaded_img/<?= $fetch_product['image_03']; ?>" alt="">
                    </div>
                </div>
                <div class="content">
                    <div class="name"><?= $fetch_product['p_name']; ?></div>
                    <div class="flex">
                        <div class="price"><span><?= $fetch_product['price']; ?><span> /-</span></div>
                        <input type="number" name="qty" class="qty" min="1" max="<?=$fetch_product['P_quantity']?>"
                            onkeypress="if(this.value.length == 2) return false;" value="1">
                    </div>
                    <?php
                    if ($fetch_product['P_quantity'] >= 10) {
                        echo '<div class="details">' . $fetch_product['details'] . '</div>';
                        echo '<span class="greenText textsize">In Stock</span>';
                        echo '<div class="flex-btn">';
                        echo '<input type="submit" value="Add to cart" class="btn option-btn" name="add_to_cart">';
                    }
                      elseif($fetch_product['P_quantity']<10 && $fetch_product['P_quantity']>=1){
                          echo '<div class="details">' . $fetch_product['details'] . '</div>';
                          echo '<span class="warningText textsize">' . $fetch_product['P_quantity'] . ' items left in stock</span>';
                        echo '<div class="flex-btn">';
                        echo '<input type="submit" value="Add to cart" class="btn warning-btn option-btn" name="add_to_cart">';
                    }else{
                        echo '<div class="details">' . $fetch_product['details'] . '</div>';
                        echo '<span class="redText textsize">' . $fetch_product['P_quantity'] . ' items left in
                                    stock</span>';
                         echo '<div class="flex-btn">';
                         echo '<div class="btn btnRed">Out of Stock</div>';
                            }
                            ?>
                    <!-- <input type="submit" value="Add to cart" class="btn" name="add_to_cart"> -->
                    <input class="option-btn" type="submit" name="add_to_wishlist" value="Add to wishlist">
                </div>
            </div>
            <div class="vendername">
                <div class="product-info">
                    <div class="product-title"><?= $fetch_product['p_name']; ?></div>
                    <div class="product-price">Rs. <?= $fetch_product['price']; ?></div>


                    <!-- Address Section -->
                    <div class="address-section">
                        <div class="address-title"><i class="fas fa-map-marker-alt"></i> Delivery Address</div>
                        <div class="address-details">
                            <?php
                            if($user_id){  
                            echo'<p>'.$fetch_user['address']." ".$fetch_user['city'].', '.$fetch_user['state'].'</p>';  
                            echo '<div class="change_title"><a href="update_user.php">Change</a></div>';
                            }else{
                                echo'<p>Bagmati, KTM Metro 22 - Newroad</p>';
                                echo '<div class="change_title"><a href="user_login.php">Change</a></div>';
                            }
                            ?>


                            <?php
                            $currentDate = new DateTime();
                            // Calculate 2 - 3 days from today
                            $deliveryStartDate = $currentDate->add(new DateInterval('P2D'));
                            $deliveryEndDate = $currentDate->add(new DateInterval('P1D'));
                            
                            // Format the dates as strings
                            $deliveryStartDateStr = $deliveryStartDate->format('j M Y');
                            $deliveryEndDateStr = $deliveryEndDate->format('j M Y');
                          
                            echo "<p>Free Delivery between $deliveryStartDateStr and $deliveryEndDateStr.</p>";
                            ?>
                            <p>Enjoy free shipping promotion with a minimum spend of Rs. 1000
                                from <?= $fetch_product['name']; ?>.</p>
                            <p><strong>Cash on Delivery Available</strong></p>
                        </div>
                    </div>
                    <!-- Service Section -->
                    <div class="service-section">
                        <div class="section-title"><i class="fas fa-hand-holding-usd"></i> Service</div>
                        <ul>
                            <li>100% Authentic from Trusted Brand or Get 2x Your Money Back</li>
                            <li>14 days free & easy return</li>
                            <li>Change of mind is not applicable</li>
                            <li>Warranty not available</li>
                        </ul>
                    </div>

                    <!-- Sold By Section -->
                    <div class="sold-by-section">
                        <div class="section-title"><i class="fas fa-store"></i> Sold by</div>
                        <h3><strong><?= $fetch_product['name']; ?></strong></h3>
                        <p><i class="fa-solid fa-circle-check" style="color: #005eff;"></i> Certified Store</p>
                    </div>

                    <!-- Chat Section -->
                    <div class="chat-section">
                        <div class="section-title"><i class="fas fa-comments"></i> CHAT</div>
                        <ul>
                            <li>Positive Seller Ship on Time</li>

                            <li>
                                <h3>Ratings(<?=$ratingcount?>)</h3>
                                <a href="review.php?review=<?php echo $encryptid?>">
                                    <span onclick="gfg(1)" class="star">★
                                    </span>
                                    <span onclick="gfg(2)" class="star">★
                                    </span>
                                    <span onclick="gfg(3)" class="star">★
                                    </span>
                                    <span onclick="gfg(4)" class="star">★
                                    </span>
                                    <span onclick="gfg(5)" class="star">★
                                    </span>
                                </a>
                            </li>
                            <a href="contact.php?chat=<?=$encryptAid;?>" class="btn-chat"><i
                                    class="fas fa-comments"></i> Chat with Seller</a>
                        </ul>
                    </div>
                </div>
            </div>
            </div>
        </form>
        <?php
      }
   }else{
      echo '<p class="empty">No products added yet!</p>';
   }
   ?>

    </section>



    <?php
    include 'usersReviews.php';
     include 'components/footer.php'; ?>

</body>

</html>