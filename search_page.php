<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search page</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css?v=<?php echo Time()?>">

</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="search-form">
        <form action="" method="post">
            <input type="text" name="search_box" placeholder="Search here..." maxlength="100" class="box" required>
            <button type="submit" class="fas fa-search" name="search_btn"></button>
        </form>
    </section>

    <section class="products" style="padding-top: 0; min-height:100vh;">

        <div class="box-container">

            <?php
     if(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
     $search_box = $_POST['search_box'];
     $select_products = $conn->prepare("SELECT * FROM `products` WHERE p_name LIKE '%{$search_box}%'"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
            <form action="" method="post" class="box">
                <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
                <input type="hidden" name="name" value="<?= $fetch_product['p_name']; ?>">
                <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
                <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
                <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
                <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
                <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
                <div class="name"><?= $fetch_product['p_name']; ?></div>
                <div class="flex">
                    <div class="price"><span>Rs</span><?= $fetch_product['price']; ?><span>/-</span></div>
                    <input type="number" name="qty" class="qty" min="1" max="<?=$fetch_product['P_quantity']?>"
                        onkeypress="if(this.value.length == 2) return false;" value="1">
                </div>
                <?php
                if($fetch_product['P_quantity']>=10)
                {
                    echo '<span class="greenText">Stock</span>';
                    echo '<input type="submit" value="Add to cart" class="btn" name="add_to_cart">';
                    }
                    elseif($fetch_product['P_quantity']<10 && $fetch_product['P_quantity']>=1){
                        echo '<span class="warningText" ">' . $fetch_product['P_quantity'] . ' items left in stock</span>';
                        echo '<input type="submit" value="Add to cart" class="btn warning-btn"  name="add_to_cart">';
                    }
                    else
                    {
                        echo '<span class="redText" ">' . $fetch_product['P_quantity'] . ' items left in stock</span>';
                        echo '<div class="btn btnRed">Out of Stock</div>';
                    }
                    ?>
                <!-- <input type="submit" value="Add to cart" class="btn" name="add_to_cart"> -->
            </form>
            <?php
         }
      }else{
         echo '<p class="Empty">No products found!</p>';
      }
   }
   ?>

        </div>

    </section>












    <?php include 'components/footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>