<?php

include 'adminHeader.php';



$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
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
$select_orders = $conn->prepare("SELECT orders.id,users.name,users.phone,users.address,users.city,users.state,orders.placed_on,
orders.total_products,orders.total_price,orders.invoice_no,
orders.order_status,orders.method FROM orders INNER JOIN users ON
 orders.user_id=users.id WHERE orders.order_status='Shipped'");
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
        $table.= "<th>Payment status</th>";
        $table.= "<th>Payment method</th>";
        $table.= "<th>Action</th>";
        $table.= "</tr>";
        $table.= "</thead>";
        $i=1;
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
            $table.="<tbody>";
            $table.="<tr>";
            $id=$fetch_orders['id'];
            $table.="<td>".$i++."</td>";
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
            }else{
                $table.="<td><span class='badge badge-warning'>".$fetch_orders['order_status']."</span></td>"; 
            }
            $table.="<td>".$fetch_orders['method']."</td>";
            $table.="<td><a onclick=\" return completed($id)\" class='btn btn-badge badge-danger' href='javascript:void(0)'>Completed</a></td>";                                
            $table.="</tr>";
            $table.="</tbody>";
        }
            echo $table;
            $table.= "<tfoot>"; 
            $table.= "<tr>";
            $table.= "<th>#</th>";
            $table.= "<th>Placed on</th>";
            $table.= "<th>Name</th>";
            $table.= "<th>Number</th>";
            $table.= "<th>Address</th>";
            $table.= "<th>Total products</th>";
            $table.= "<th>Total price</th>";
            $table.= "<th>Payment method</th>";
            $table.= "<th>Payment status</th>";
            $table.= "<th>Update</th>";

            $table.= "<th>Delete</th>";
            $table.= "</tr>";
            $table.= "</tfoot>";
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
            icon: "warning",
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

    function view(id) {
        window.location.href = 'view_order.php?view=' + id;
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
    });
    </script>


</body>

</html>