<?php

include 'adminHeader.php';



$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}



if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title> Placed Orders</title>

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
                    <h1> Placed Orders</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Placed_orders</li>
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
                            <h3 class="card-title"> Placed Orders</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <?php
      
// Assuming $conn is your database connection object

$select_orders = $conn->prepare("SELECT
    users.name,
    users.phone,
    users.address,
    users.city,
    users.state,
    orders.order_status AS order_status,
    orders.method AS method,
    orders.id AS id,
    orders.total_products AS total_products,
    orders.total_price AS total_price,
    orders.placed_on AS placed_on,
    orders.invoice_no AS invoice_no
FROM
    orders
JOIN
    users ON orders.user_id = users.id
ORDER BY
    orders.id DESC");
      $select_orders->execute();
      if($select_orders->rowCount() > 0){
        $table="";
        $table.= "<table id='example1' class='table table-bordered table-striped'>";
        $table.= "<thead>";
        $table.= "<tr>";
        $table.= "<th>#</th>";
        $table.= "<th>Placed on</th>";
        $table.= "<th>Name</th>";
        $table.= "<th>Phone</th>";
        $table.= "<th>Address</th>";
        $table.= "<th>Invoice No.</th>";
        $table.= "<th>Total products</th>";
        $table.= "<th>Total price</th>";
        $table.= "<th>Order Status</th>";
        $table.= "<th>Payment method</th>";
        $table.= "<th>Action</th>";
        $table.= "<th>Action</th>";
        $table.= "</tr>";
        $table.= "</thead>";
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
            $table.="<tbody>";
            $table.="<tr>";
            $id=$fetch_orders['id'];
            $table.="<td>".$fetch_orders['id']."</td>";
            $table.="<td>".$fetch_orders['placed_on']."</td>";
            $table.="<td>".$fetch_orders['name']."</td>";
            $table.="<td>".$fetch_orders['phone']."</td>";
            $table.="<td>".$fetch_orders['address'].' '.$fetch_orders['city'].', '.$fetch_orders['state']. "</td>";
            if($fetch_orders['order_status']=="Cancelled"){
            $table.="<td><strong><a onclick=\" return view($id)\" href='javascript:void(0)'
            class='text-secondary'>".$fetch_orders['invoice_no']."</a></strong></td>";
            }elseif($fetch_orders['method']=="cash-on-delivery"){
            $table.="<td><strong><a onclick=\" return view($id)\" href='javascript:void(0)'
            class='text-danger'>".$fetch_orders['invoice_no']."</a></strong></td>";
            }else{
            $table.="<td><strong><a onclick=\" return view($id)\" href='javascript:void(0)'
            class='text-primary'>".$fetch_orders['invoice_no']."</a></strong></td>";
            }
            $table.="<td>".$fetch_orders['total_products']."</td>";
            $table.="<td>".$fetch_orders['total_price']."</td>";
            if($fetch_orders['order_status']=="Pending"){
                $table.="<td><span class='badge badge-danger'>".$fetch_orders['order_status']."</span></td>"; 
            }else if($fetch_orders['order_status']=="Completed"){
                $table.="<td><span class='badge badge-success'>".$fetch_orders['order_status']."</span></td>"; 
            }elseif($fetch_orders['order_status']=="Cancelled"){
                $table.="<td><span class='badge badge-secondary'>".$fetch_orders['order_status']."</span></td>";
            }else{
                $table.="<td><span class='badge badge-warning'>".$fetch_orders['order_status']."</span></td>"; 
            }
            $table.="<td>".$fetch_orders['method']."</td>";
            if($fetch_orders['order_status']=="Completed"){
                $table.="<td><a onclick=\" return alreadycompleted($id)\" class='btn btn-badge badge-warning' href='javascript:void(0)'>Shipped</a></td>";                            
            $table.="<td><a onclick=\" return alreadycompleted($id)\" class='btn btn-badge badge-success' href='javascript:void(0)'>Completed</a></td>"; 
            }
            elseif($fetch_orders['order_status']=="Shipped"){
                $table.="<td><a onclick=\" return alreadyShipped($id)\" class='btn btn-badge badge-warning' href='javascript:void(0)'>Shipped</a></td>";
                $table.="<td><a onclick=\" return completed($id)\" class='btn btn-badge badge-danger' href='javascript:void(0)'>Complete </a> </td>";      
            }elseif($fetch_orders['order_status']=="Pending"){  
                $table.="<td><a onclick=\" return shipped($id)\" class='btn btn-badge badge-danger' href='javascript:void(0)'>&nbsp;&nbsp;&nbsp;Ship&nbsp;&nbsp;&nbsp;&nbsp;</a></td>"; 
               $table.="<td><a onclick=\" return firstShipped($id)\" class='btn btn-badge badge-primary' href='javascript:void(0)'>Complete &nbsp;</a></td>";
            }elseif ($fetch_orders['order_status']=="Cancelled") {
                $table.="<td><a onclick=\" return Cancelled($id)\" class='btn btn-badge badge-secondary' href='javascript:void(0)'>Shipped</a></td>";
                $table.="<td><a onclick=\" return Cancelled($id)\" class='btn btn-badge badge-secondary' href='javascript:void(0)'>Completed</a></td>"; 
            }else{
                $table.="<td><a onclick=\" return shipped($id)\" class='btn btn-badge badge-danger' href='javascript:void(0)'>Shipped</a></td>"; 
            $table.="<td><a onclick=\" return completed($id)\" class='btn btn-badge badge-danger' href='javascript:void(0)'>Complete</a></td>";      
            }
            
            $table.="</tr>";
            $table.="</tbody>";
         }
            echo $table;
            
            $table.="</table>";
         
      }else{
         echo '<p class="empty">No orders placed yet!</p>';
      }
   ?>

                        </div>


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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <script>
    function completed(id) {

        // Your SweetAlert2 code goes here
        Swal.fire({
            title: "Are you sure?",
            text: "Order has been Completed!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Completed !"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'complete_order.php?id=' + id;

                Swal.fire({
                    title: "Completed!",
                    text: "Order has been Completed!",
                    icon: "success"
                });
            } else {
                // swal("Your imaginary file is safe!");
                Swal.fire({

                    text: "Order has not been Completed!",
                    icon: "error"
                });
            }
        });

    }

    function Cancelled(id) {
        Swal.fire({
            text: "This order has been Cancelled!",
            icon: "info"
        }).then(() => {
            // Optionally, you can perform additional actions after the user dismisses the error message
            window.location.href = 'placed_orders.php';
        });


    }

    function alreadycompleted(id) {
        Swal.fire({
            text: "This order has already been Completed!",
            icon: "info"
        }).then(() => {
            // Optionally, you can perform additional actions after the user dismisses the error message
            window.location.href = 'placed_orders.php';
        });


    }

    function alreadyShipped(id) {
        Swal.fire({
            text: "This order has already been Shipped!",
            icon: "info"
        }).then(() => {
            // Optionally, you can perform additional actions after the user dismisses the error message
            window.location.href = 'placed_orders.php';
        });


    }

    function firstShipped(id) {
        Swal.fire({
            text: "Please ship the order first.",
            icon: "info"
        }).then(() => {
            // Optionally, you can perform additional actions after the user dismisses the error message
            window.location.href = 'placed_orders.php';
        });
    }




    function shipped(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "Order has been Shipped!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Shipped !"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'shipped_order.php?id=' + id;
                Swal.fire({
                    title: "Shipped!",
                    text: "Order has been Shipped!",
                    icon: "success"
                });
            } else {
                // swal("Your imaginary file is safe!");
                Swal.fire({

                    text: "Order has not been Shipped!",
                    icon: "error"
                });
            }
        });

    }

    function view(id) {
        window.location.href = 'view_order.php?view=' + id;

        // Swal.fire({
        //     title: "Are you sure ?",
        //     text: " View Order Details",
        //     icon: "info",
        //     showCancelButton: true,
        //     confirmButtonColor: "#3085d6",
        //     cancelButtonColor: "#d33",

        // }).then((result) => {
        //     if (result.isConfirmed) {
        //         window.location.href = 'view_order.php?view=' + id;
        //         Swal.fire({
        //             title: "Are you sure ?",
        //             text: " View Order Details",
        //             icon: "info"
        //         });
        //     } else {
        //         // swal("Your imaginary file is safe!");
        //         Swal.fire({
        //             text: "Something went wrong! ",
        //             icon: "error"
        //         });
        //     }
        // });
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>

    <!-- jQuery -->

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

    <!-- AdminLTE App -->
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="dist/js/demo.js"></script> -->
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
    $(document).ready(function() {
        $('#example').dataTable({
            "sDom": 'T<"clear">lfrtip',
            "oTableTools": {
                "aButtons": [{
                    "sExtends": "print",
                    "bShowAll": true
                }]
            }
        });
        $(a).addClass('d-flex justify-content-center');
    });
    </script>


</body>

</html>