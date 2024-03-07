<?php

include '../components/connect.php';
include 'adminHeader.php';



$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_product'])){
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);
    $p_qty = $_POST['p_qty'];
    $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);
    $old_price = $_POST['old_price'];
    $old_price = filter_var($old_price, FILTER_SANITIZE_STRING);
    $category = $_POST['category'];
    $category = filter_var($category, FILTER_SANITIZE_STRING);
   $image_01 = $_FILES['image_01']['name'];
   $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = '../uploaded_img/'.$image_01;

   $image_02 = $_FILES['image_02']['name'];
   $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
   $image_size_02 = $_FILES['image_02']['size'];
   $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
   $image_folder_02 = '../uploaded_img/'.$image_02;

   $image_03 = $_FILES['image_03']['name'];
   $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
   $image_size_03 = $_FILES['image_03']['size'];
   $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
   $image_folder_03 = '../uploaded_img/'.$image_03;

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE p_name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'Product name already exist!';
   }elseif($image_size_01 > 5000000 OR $image_size_02 > 5000000 OR $image_size_03 > 5000000){
      $message[] = 'Image size is too large!';
   }
   else{

      $insert_products = $conn->prepare("INSERT INTO `products`(admin_id, p_name, old_price, details, price, image_01, image_02, image_03,p_quantity,category_id) VALUES(?,?,?,?,?,?,?,?,?,?)");
      $insert_products->execute([$admin_id, $name, $old_price, $details, $price, $image_01, $image_02, $image_03,$p_qty,$category]);

      if($insert_products){
         if($image_size_01 > 5000000 OR $image_size_02 > 5000000 OR $image_size_03 > 5000000){
            $message[] = 'Image size is too large!';
         }else{
            move_uploaded_file($image_tmp_name_01, $image_folder_01);
            move_uploaded_file($image_tmp_name_02, $image_folder_02);
            move_uploaded_file($image_tmp_name_03, $image_folder_03);
            $message[] = 'New product added!';
         }

      }

   }  

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image_01']);
   unlink('../uploaded_img/'.$fetch_delete_image['image_02']);
   unlink('../uploaded_img/'.$fetch_delete_image['image_03']);
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   echo'  <script>
   window.location.replace("product_lists.php");
   </script>';
 
};




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Add</title>


</head>

<body>


    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Product Add</h1>
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
         <div class="message col-md-4 mx-auto mt-5">
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
                            <h3 class="card-title">Product Add</h3>
                        </div>
                        <!-- /.card-header -->

                        <!-- form start -->
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="Product_name">Product Name (required) </label>
                                    <input type="text" class="form-control" required maxlength="100"
                                        placeholder="Enter product name" name="name">
                                </div>
                                <div class="form-group">
                                    <label for="Product_category">Product category (required) </label>
                                    <select name="category" class="form-control" required>
                                        <option value="">Select category</option>
                                        <?php
                                    $select_category = $conn->prepare("SELECT * FROM `product_category`");
                                    $select_category->execute();
                                    if($select_category->rowCount() > 0){
                                       while($fetch_category = $select_category->fetch(PDO::FETCH_ASSOC)){
                                          echo '<option value="'.$fetch_category['id'].'">'.$fetch_category['category'].'</option>';
                                       }
                                    }else{
                                        echo '<option value="">No category added yet!</option>';
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="Product_Price">Product Price (required) </label>
                                    <input type="number" class="form-control" required max="99999999"
                                        placeholder="Enter product price"
                                        onkeypress="if(this.value.length == 10) return false;" name="price">
                                </div>
                                <div class="form-group">
                                    <label for="Product_qty">Product Quantities (required) </label>
                                    <input type="number" class="form-control" name="p_qty" required max="99999"
                                        placeholder="Enter Product Quantities"
                                        onkeypress="if(this.value.length == 10) return false;">
                                </div>
                                <div class="form-group">
                                    <label for="oldProduct_Price">Old Price (required) </label>
                                    <input type="number" class="form-control" required max="99999999"
                                        placeholder="Enter Old price"
                                        onkeypress="if(this.value.length == 10) return false;" name="old_price">
                                </div>
                                <div class="form-group">
                                    <label for="Image_01">Image 01 (required)</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customFile" name="image_01"
                                            accept="image/jpg, image/jpeg, image/png, image/webp" required>
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="Image_02">Image 02 (required)</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customFile" name="image_02"
                                            accept="image/jpg, image/jpeg, image/png, image/webp" required>
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="Image_03">Image 03 (required)</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customFile" name="image_03"
                                            accept="image/jpg, image/jpeg, image/png, image/webp" required>
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="Product_details">Product details (required) </label>
                                    <textarea name="details" placeholder="Enter product details" class="form-control"
                                        required maxlength="9999999999999" cols="10" style=" resize: none"></textarea>
                                </div>

                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer col-md-0 d-flex justify-content-end">
                                <button type="submit" name="add_product" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>



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
                    <script src="../js/admin_script.js"></script>

</body>

</html>