<?php
ob_start();

include '../components/connect.php';
include 'adminHeader.php';

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};
if (isset($_POST['categoryAdd'])) {
    $category = $_POST['category'];
    $category = filter_var($category, FILTER_SANITIZE_STRING);

    // Check if the category already exists
    $category_check = $conn->prepare("SELECT COUNT(*) FROM product_category WHERE category = :category");
    $category_check->bindValue(':category', $category, PDO::PARAM_STR);
    $category_check->execute();
    $category_count = $category_check->fetchColumn();
    $category_check->closeCursor();

    if ($category_count > 0) {
        echo "<script>alert('Category Already Exist');</script>";
        echo "<script>window.location.href='categoryAdd.php';</script>";
        die();
    } else {
        // Insert the new category
        $category_add = $conn->prepare("INSERT INTO product_category (category) VALUES (:category)");
        $category_add->bindValue(':category', $category, PDO::PARAM_STR);
        if ($category_add->execute()) {
            $category_add->closeCursor();
            echo "<script>alert('Category Added Successfully');</script>";
            echo "<script>window.location.href='categoryAdd.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error adding category');</script>";
            // Log the error or handle it as needed
        }
    }
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Add</title>


</head>

<body>


    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Category Add</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">category_Add</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-4  mx-auto">
                    <!-- general form elements -->
                    <div class="card card-primary ">
                        <div class="card-header">
                            <h3 class="card-title">Category Add</h3>
                        </div>
                        <!-- /.card-header -->

                        <!-- form start -->
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="Product_name">Category Name</label>
                                    <input type="text" class="form-control" required maxlength="100"
                                        placeholder="Enter Category Name" name="category">
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer col-md-0 d-flex justify-content-end">
                                <button type="submit" name="categoryAdd" class="btn btn-primary">Submit</button>
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