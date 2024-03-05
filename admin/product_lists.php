<?php           

include 'adminHeader.php'; 

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
header('location:admin_login.php');
};

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Product Lists</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />
    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css" />
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css" />
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css" />
</head>

<body>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Products_lists</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Products_lists</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- /.card -->

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Products_lists</h3>
                        </div>
                        <div class="card-body">

                            <?php
  
                                    $select_products = $conn->prepare("SELECT products.id, products.p_name, products.price, products.details,products.P_quantity, products.image_01 FROM products ORDER BY products.id ASC ");
                                    $select_products->execute();
                                    if($select_products->rowCount() > 0){
                                    
                                        $table="";
                                        $table .= "<div class='table-responsive'>";
                                        $table.= "<table id='example1' class='table table-bordered table-striped'>";
                                        $table.= "<thead>";
                                        $table.= "<tr>";
                                        $table.= "<th>#</th>";
                                        $table.= "<th>Name</th>";   
                                        $table.= "<th>Price</th>";
                                        $table.= "<th>Photo</th>";
                                        $table.= "<th>Details</th>";
                                        $table.= "<th>Quantities</th>";
                                        $table.= "<th>Update</th>";
                                        $table.= "<th>Delete</th>";
                                        $table.= "</tr>";
                                        $table.= "</thead>";
                                    while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
                                    $id=$fetch_products['id'];
                                        $table.="<tbody>";
                                       $table.="<tr>";
                                        $table.="<td>".$fetch_products['id']."</td>";
                                        $table.="<td>".$fetch_products['p_name']."</td>";
                                       $table.="<td>".$fetch_products['price']."</td>";
                                       $table .= "<td><a href='../uploaded_img/{$fetch_products['image_01']}' target='_blank' ><img src='../uploaded_img/{$fetch_products['image_01']}' alt='' class='img-fluid' width='150px'></a></td>";
                                       $table .= "<td>".$fetch_products['details']."</td>";
                                        $table .= "<td> <a onclick=\"return updateQty($id)\" href='javascript:void(0)'>".$fetch_products['P_quantity']."</a> </td>";
                                        $table .= "<td><a class='btn btn-success text-white' href='update_product.php?update={$fetch_products['id']}' onclick=\"return confirm('Update this product?');\">Update</a></td>";
                                      $table .= "<td ><a class='btn btn-danger ml-2 text-white' href='products.php?delete={$fetch_products['id']}' onclick=\"return confirm('Delete this product?');\" >Delete</a></td>";                                      
                                       $table.="</tr>";
                                       $table.="</tbody>";
                                      
                                    }
                                    echo $table;
                                    $table.= "<tfoot>";
                                    $table.= "<tr>";
                                    $table.= "<th>#</th>";
                                    $table.= "<th>Name</th>";   
                                    $table.= "<th>Price</th>";
                                    $table.= "<th>Photo</th>";
                                    $table.= "<th>Details</th>";
                                     $table.= "<th>Update</th>";
                                     $table.= "<th>Delete</th>";
                                     $table.= "</tr>";
                                     $table.= "</tfoot>";
                                       $table.="</table>";
                                       $table .= "</div>";
                                      
                                    }else{
                             echo '<p class="empty">No products added yet!</p>';
                                        }
                                        ?>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->

    <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <!-- DataTables  & Plugins -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="plugins/jszip/jszip.min.js"></script>
    <script src="plugins/pdfmake/pdfmake.min.js"></script>
    <script src="plugins/pdfmake/vfs_fonts.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- Page specific script -->
    <script>
    $(function() {
        $("#example1")
            .DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
            })
            .buttons()
            .container()
            .appendTo("#example1_wrapper .col-md-6:eq(0)");
        $("#example2").DataTable({
            paging: true,
            lengthChange: false,
            searching: false,
            ordering: true,
            info: true,
            autoWidth: false,
            responsive: true,
        });
    });
    </script>
    <!-- Include SweetAlert 2 CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script type="text/javascript">
    function updateQty(id) {
        Swal.fire({
            title: 'Confirm Quantity Update',
            text: 'Are you sure you want to update this product\'s quantity?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, update it!',
            cancelButtonText: 'No, cancel!',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
        }).then((result) => {
            if (result.isConfirmed) {
                // User confirmed, show the input prompt for quantity update
                Swal.fire({
                    title: 'Enter Quantity',
                    text: 'Enter new quantity for this product',
                    input: 'number',
                    inputAttributes: {
                        autocapitalize: 'off',
                        min: 0,
                        autocomplete: 'off',
                        placeholder: 'Enter new quantity',
                        keyboardType: 'number-pad',
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Update',
                    showLoaderOnConfirm: true,
                    preConfirm: (qty) => {
                        // Validate the quantity
                        if (qty < 0 || qty === '' || qty == null || qty == 0) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Invalid quantity. Please enter a valid value greater than 0.',
                            });
                            return false;
                        } else {
                            // Proceed with the quantity update
                            return $.ajax({
                                url: 'update_qty.php',
                                type: 'POST',
                                data: {
                                    update_qty_id: id,
                                    update_qty: qty,
                                },
                                dataType: 'json', // Expect JSON response
                            });
                        }
                    },
                }).then((result) => {
                    // Handle the result after the quantity update
                    if (result.isConfirmed && result.value && result.value.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Quantity updated successfully!',
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Update failed: ' + (result.value ? result.value.message :
                                'Unknown error'),
                        });
                    }
                });
            }
        });
    }


    // function updateDiscount(id) {
    //     Swal.fire({
    //         title: 'Confirm Discount Update',
    //         text: 'Are you sure you want to update this product\'s discount?',
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonText: 'Yes, update it!',
    //         cancelButtonText: 'No, cancel!',
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             // User confirmed, show the input prompt for discount update
    //             Swal.fire({
    //                 title: 'Enter Discount',
    //                 text: 'Enter new discount for this product',
    //                 input: 'number',
    //                 inputAttributes: {
    //                     autocapitalize: 'off',
    //                     min: 0,
    //                     max: 100,
    //                     autocomplete: 'off',
    //                     placeholder: 'Enter new discount',
    //                     keyboardType: 'number-pad',
    //                 },
    //                 showCancelButton: true,
    //                 confirmButtonText: 'Update',
    //                 showLoaderOnConfirm: true,
    //                 preConfirm: (discount) => {
    //                     // Validate the discount
    //                     if (discount < 0 || discount === '' || discount == null || discount == 0) {
    //                         Swal.fire({
    //                             icon: 'error',
    //                             title: 'Oops...',
    //                             text: 'Invalid discount. Please enter a valid value greater than 0.',
    //                         });
    //                         return false;
    //                     } else if (discount > 99) {
    //                         Swal.fire({
    //                             icon: 'error',
    //                             title: 'Oops...',
    //                             text: 'Invalid discount. Please enter a valid value less than 99.',
    //                         });
    //                         return false;
    //                     } else {
    //                         // Proceed with the discount update
    //                         return $.ajax({
    //                             url: 'update_qty.php',
    //                             type: 'POST',
    //                             data: {
    //                                 update_discount_id: id,
    //                                 update_discount: discount,
    //                             },
    //                             dataType: 'json', // Expect JSON response
    //                         });
    //                     }
    //                 },
    //             }).then((result) => {
    //                 // Handle the result after the discount update
    //                 if (result.isConfirmed && result.value && result.value.status === 'success') {
    //                     Swal.fire({
    //                         icon: 'success',
    //                         title: 'Success!',
    //                         text: 'Discount updated successfully!',
    //                     }).then(() => {
    //                         location.reload();
    //                     });
    //                 } else {
    //                     Swal.fire({
    //                         icon: 'error',
    //                         title: 'Oops...',
    //                         text: 'Update failed: ' + (result.value ? result.value.message :
    //                             'Unknown error'),
    //                     });
    //                 }
    //             });
    //         }
    //     });
    // }
    </script>




</body>

</html>