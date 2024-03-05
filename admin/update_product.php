<?php

include 'adminHeader.php';



$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['update'])){

   $pid = $_POST['pid'];
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);
    $oldprice = $_POST['oldprice'];
    $oldprice = filter_var($oldprice, FILTER_SANITIZE_STRING);
    $p_qty = $_POST['p_qty'];
    $old_quantity = $conn->prepare("SELECT p_quantity FROM `products` WHERE id = ?");
    $old_quantity->execute([$pid]);
    $old_quantity = $old_quantity->fetch(PDO::FETCH_ASSOC);
    $old_quantity = $old_quantity['p_quantity']; 
    $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);
    $new_value = $old_quantity + $p_qty;

    
   $update_product = $conn->prepare("UPDATE `products` SET old_price =?, p_quantity=?, p_name = ?, price = ?, details = ? WHERE id = ?");
   $update_product->execute([ $oldprice, $new_value, $name, $price, $details, $pid]);

   $message[] = 'Product updated successfully!';

   $old_image_01 = $_POST['old_image_01'];
   $image_01 = $_FILES['image_01']['name'];
   $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = '../uploaded_img/'.$image_01;

   if(!empty($image_01)){
      if($image_size_01 > 2000000){
         $message[] = 'Image size is too large!';
      }else{
         $update_image_01 = $conn->prepare("UPDATE `products` SET image_01 = ? WHERE id = ?");
         $update_image_01->execute([$image_01, $pid]);
         move_uploaded_file($image_tmp_name_01, $image_folder_01);
         unlink('../uploaded_img/'.$old_image_01);
         $message[] = 'Image 01 updated successfully!';
      }
   }

   $old_image_02 = $_POST['old_image_02'];
   $image_02 = $_FILES['image_02']['name'];
   $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
   $image_size_02 = $_FILES['image_02']['size'];
   $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
   $image_folder_02 = '../uploaded_img/'.$image_02;

   if(!empty($image_02)){
      if($image_size_02 > 2000000){
         $message[] = 'Image size is too large!';
      }else{
         $update_image_02 = $conn->prepare("UPDATE `products` SET image_02 = ? WHERE id = ?");
         $update_image_02->execute([$image_02, $pid]);
         move_uploaded_file($image_tmp_name_02, $image_folder_02);
         unlink('../uploaded_img/'.$old_image_02);
         $message[] = 'Image 02 updated successfully!';
      }
   }

   $old_image_03 = $_POST['old_image_03'];
   $image_03 = $_FILES['image_03']['name'];
   $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
   $image_size_03 = $_FILES['image_03']['size'];
   $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
   $image_folder_03 = '../uploaded_img/'.$image_03;

   if(!empty($image_03)){
      if($image_size_03 > 2000000){
         $message[] = 'Image size is too large!';
      }else{
         $update_image_03 = $conn->prepare("UPDATE `products` SET image_03 = ? WHERE id = ?");
         $update_image_03->execute([$image_03, $pid]);
         move_uploaded_file($image_tmp_name_03, $image_folder_03);
         unlink('../uploaded_img/'.$old_image_03);
         $message[] = 'Image 03 updated successfully!';
      }
   }
   echo'  <script>
   window.location.replace("product_lists.php");
   </script>';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update product</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <link rel="stylesheet" href="admin.css?v=<?php echo Time();?>">


</head>

<body>

    <?php
      $update_id = $_GET['update'];
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $select_products->execute([$update_id]);
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>


    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Product Update</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Products</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
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
   }?>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-6  mx-auto">
                    <!-- general form elements -->
                    <div class="card card-primary ">
                        <div class="card-header">
                            <h3 class="card-title">Product Update</h3>
                        </div>
                        <div class="image-container">
                            <div class="main-image">
                                <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
                            </div>
                            <div class="sub-image">
                                <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
                                <img src="../uploaded_img/<?= $fetch_products['image_02']; ?>" alt="">
                                <img src="../uploaded_img/<?= $fetch_products['image_03']; ?>" alt="">
                            </div>
                        </div>

                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                            <input type="hidden" name="old_image_01" value="<?= $fetch_products['image_01']; ?>">
                            <input type="hidden" name="old_image_02" value="<?= $fetch_products['image_02']; ?>">
                            <input type="hidden" name="old_image_03" value="<?= $fetch_products['image_03']; ?>">

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="Product_name">Product Name (required) </label>
                                    <input type="text" name="name" required class="form-control" maxlength="100"
                                        placeholder="Enter product name" value="<?= $fetch_products['p_name']; ?>">

                                </div>
                                <div class="form-group">
                                    <label for="Product_Price">Product Price (required) </label>
                                    <input type="number" name="price" required class="form-control" min="0"
                                        max="9999999999" placeholder="Enter product price"
                                        onkeypress="if(this.value.length == 10) return false;"
                                        value="<?= $fetch_products['price']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="oldprice">Old Price (required) </label>
                                    <input type="number" name="oldprice" required class="form-control" min="0"
                                        max="9999999999" placeholder="Enter old product price"
                                        onkeypress="if(this.value.length == 10) return false;"
                                        value="<?= $fetch_products['old_price']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="Product_qty">Product Quantity (required) </label>
                                    <input type="number" name="p_qty" required class="form-control" min="0"
                                        max="9999999999" placeholder="Enter product Quantities"
                                        onkeypress="if(this.value.length == 10) return false;"
                                        value="<?= $fetch_products['P_quantity']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="Product_details">Product details (required) </label>
                                    <textarea style=" resize: none" name="details" class="form-control" required
                                        maxlength="50" cols="1" rows="5"><?= $fetch_products['details']; ?></textarea>

                                </div>


                                <div class="form-group">
                                    <label for="Image_01">Image 01 (required)</label>
                                    <div class="custom-file">
                                        <input type="file" name="image_01"
                                            accept="image/jpg, image/jpeg, image/png, image/webp"
                                            class="custom-file-input" required>
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="Image_02">Image 02 (required)</label>
                                    <div class="custom-file">
                                        <input type="file" name="image_02"
                                            accept="image/jpg, image/jpeg, image/png, image/webp"
                                            class="custom-file-input" required>
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="Image_03">Image 03 (required)</label>
                                    <div class="custom-file">
                                        <input type="file" name="image_03"
                                            accept="image/jpg, image/jpeg, image/png, image/webp"
                                            class="custom-file-input" required>

                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>


                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer col-md-0 d-flex justify-content-end">
                                <input type="submit" name="update" class="btn btn-primary" value="Update">
                                <a href="product_lists.php" class="btn btn-danger ml-2">Go back</a>
                            </div>

                        </form>
                    </div>



                    <?php

         }
      }else{
         echo '<p class="empty">No product found!</p>';
      }
   ?>


                    <script src="plugins/jquery/jquery.min.js"></script>
                    <!-- Bootstrap 4 -->
                    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
                    <!-- bs-custom-file-input -->
                    <script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
                    <!-- AdminLTE App -->
                    <script src="dist/js/adminlte.min.js"></script>
                    <!-- AdminLTE for demo purposes -->
                    <script src="dist/js/demo.js"></script>
                    <!-- Page specific script -->
                    <script>
                    $(function() {
                        bsCustomFileInput.init();
                    });
                    </script>

                    <script>
                    let mainImage = document.querySelector(
                        '.image-container .main-image img');
                    let subImages = document.querySelectorAll(
                        '.image-container .sub-image img');

                    subImages.forEach(images => {
                        images.onclick = () => {
                            src = images.getAttribute('src');
                            mainImage.src = src;
                        }
                    });
                    </script>



</body>

</html>