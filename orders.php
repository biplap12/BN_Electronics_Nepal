<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
   include 'admin/blocked_user.php'; 
}else{
   $user_id = '';
    header('location:user_login.php');
};

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css?v=<?php echo Time()?>">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>
<style>
.larger-swal {
    width: 500px;
    font-size: 18px;
    /* Adjust the width to your preference */
}

.larger-swal-title {
    font-size: 24px;
    /* Adjust the title font size to your preference */
}

.larger-swal-content {
    font-size: 18px;
    /* Adjust the content font size to your preference */
}

.larger-swal-confirm-button {
    font-size: 16px;
    /* Adjust the confirm button font size to your preference */
}

.larger-swal-icon {
    font-size: 36px;
    /* Adjust the icon size to your preference */
}

.larger-swal-loader {
    font-size: 36px;
    /* Adjust the icon size to your preference */
}
</style>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="orders">

        <h1 class="heading">Placed orders</h1>

        <div class="box-container">


            <?php
if ($user_id == '') {
    echo '<p class="empty">Please login to see your orders</p>';
} else {
    // $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?  ");
    $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? ORDER BY id DESC");
    $select_orders->execute([$user_id]);
    $table = '';
    $i = 1;
    
    if ($select_orders->rowCount() > 0) {
        $table .= '<table class="box" style="border: 1px solid black;">';
        $table .= '<tr>';
        $table .= '<thead>';
        $table .= '<th>#</th>';
        $table .= '<th>Invoice No.</th>';
        $table .= '<th>Placed on</th>';
        $table .= '<th>Orders</th>';
        $table .= '<th>Payment Status</th>';
        $table .= '<th>Payment method</th>';
        $table .= '<th>Total price</th>';
        $table .= '<th>Order status</th>';
        $table .= '</thead>';
        $table .= '</tr>';

        while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
            $id = $fetch_orders['id'];
            $paymentStatusColor = (
                $fetch_orders['payment_Status'] == 'Success' ? 'green' : 'red'
            );
            // $fetch_orders
            $orderStatusColor = ''; 

            if ($fetch_orders['order_status'] == 'Completed') {
                $orderStatusColor = '#00ff40';
            } elseif ($fetch_orders['order_status'] == 'Pending') {
                $orderStatusColor = '#ff0000';
            } elseif ($fetch_orders['order_status'] == 'Shipped') {
                $orderStatusColor = '#FFA500';
            
            } else{
                $orderStatusColor = '#ff0000';
            }

            $table .= '<tr>';
            $table .= '<tbody>';
            $table .= '<td>'. $i++ . '.' .'</td>';
            if($fetch_orders['order_status'] == 'Cancelled' && $fetch_orders['payment_Status'] == 'Cancelled'){
                $table .= '<td ><a style="color:red" onclick="return errorBill()" href="javascript:void(0)">' . $fetch_orders['invoice_no'] . '</a></td>';
            }
            elseif($fetch_orders['method'] == "") {
                $table .= '<td ><a style="color:red" onclick="return errorBill()" href="javascript:void(0)">' . $fetch_orders['invoice_no'] . '</a></td>';

            }elseif($fetch_orders['order_status'] !=="" && $fetch_orders['payment_Status'] !==""){
                $table .= '<td><a onclick="return viewbill(' . $id . ')" href="javascript:void(0)">' . $fetch_orders['invoice_no'] . '</a></td>';
            }
            else{
                $table .= '<td ><a style="color:red" onclick="return errorBill()" href="javascript:void(0)">' . $fetch_orders['invoice_no'] . '</a></td>';
            }
            $table .= '<td>' . $fetch_orders['placed_on'] . '</td>';
            $table .= '<td>' . $fetch_orders['total_products'] . '</td>';
            $table .= '<td style="text-align: center;">' .
            '<span style="color: ' . $paymentStatusColor . '; display: inline-block; width: 100px; height: 25px; margin: 0 auto; border: 2px solid ' . ($fetch_orders['payment_Status'] == 'Success' ? '#00ff40' : '#ff0000') . '; padding: 1px;">' .
            ($fetch_orders['payment_Status'] == 'Success' ? '<i class="fa-solid fa-circle-check" style="color: #00ff40;"></i>' : '<i class="fa-solid fa-circle-xmark" style="color: #ff0000;"></i>') .
            ' ' . $fetch_orders['payment_Status'] .
            '</span>' .
        '</td>';
       
        if($fetch_orders['method']){
            $table .= '<td style="text-align: center">' . $fetch_orders['method'] . '</td>';
        }
        elseif($fetch_orders['method'] == '' && $fetch_orders['payment_Status'] == 'Cancelled' && $fetch_orders['order_status'] == 'Cancelled'){
            $table .= "<td style='text-align: center;'><a style=' background-color:red;' class='btnPay' onclick=\"return deleteOrder($id)\" href='javascript:void(0)'>Delete</a></td>";
        }
        else{

            $table .= '<td><a href="payment.php?order_id=' . $id . '" class="btnPay">Pay Now</a></td>';
        }
            $table .= '<td>Rs' . $fetch_orders['total_price'] . '/-</td>';

            if ($fetch_orders['order_status'] == 'Cancelled' || $fetch_orders['order_status'] == 'Completed' || $fetch_orders['order_status'] == 'Shipped' || $fetch_orders['payment_Status'] == 'Success') {
                $table .= '<td style="text-align: center;">' .
                    '<a onclick="return disableOrder(' . $id . ')" href="javascript:void(0)">' .
                    '<span style="color: ' . $orderStatusColor . '; display: inline-block; width: 100px; height: 25px; margin: 0 auto; border: 2px solid ' . $orderStatusColor . '; padding: 1px;">' .
                    '<i class="fa-solid fa-circle-check" style="color: ' . $orderStatusColor . ';"></i> ' . $fetch_orders['order_status'] .
                    '</span>' .
                    '</a>' .
                '</td>';
                // Assuming $orderStatus contains the status of the order
            } else {
                $table .= "<td style='text-align: center;'><a onclick=\"return cancelOrder($id)\" href='javascript:void(0)'><span style='color: $orderStatusColor; display: inline-block; width: 100px; height: 25px; margin: 0 auto; border: 2px solid $orderStatusColor; padding: 1px;'>" .
                " <i class='fa-solid fa-circle-xmark' style='color: $orderStatusColor;'></i> Cancel</span></a></td>";

            }
            
            $table .= '</tr>';
            $table .= '</tbody>';
        }

        $table .= '</table>';
        echo $table;
    } else {
        echo '<p class="empty">No orders placed yet!</p>';
    }
}
?>


        </div>

    </section>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
    function disableOrder(id) {
        Swal.fire({
            title: "You cannot Modify this order.",
            icon: "error",
            confirmButtonColor: "#d33",
            confirmButtonText: "Cancel",
            customClass: {
                popup: 'larger-swal',
                title: 'larger-swal-title',
                content: 'larger-swal-content',
                confirmButton: 'larger-swal-confirm-button',

            }
        });
    }


    function cancelOrder(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You want to Cancel this order.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Cancel !",
            customClass: {
                popup: 'larger-swal',
                title: 'larger-swal-title',
                content: 'larger-swal-content',
                confirmButton: 'larger-swal-confirm-button',

            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'cancel_order.php?cancel_order=' + id;

                Swal.fire({
                    title: "Completed!",
                    text: "Order has been Cancel!",
                    icon: "success",
                    customClass: {
                        popup: 'larger-swal',
                        title: 'larger-swal-title',
                        content: 'larger-swal-content',
                        confirmButton: 'larger-swal-confirm-button',

                    }
                });
            } else {
                Swal.fire({
                    text: "Order has not been Cancel!",
                    icon: "error",
                    customClass: {
                        popup: 'larger-swal',
                        title: 'larger-swal-title',
                        content: 'larger-swal-content',
                        confirmButton: 'larger-swal-confirm-button',

                    }
                });
            }
        });
    }

    function deleteOrder(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You want to Delete this order.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Delete !",
            customClass: {
                popup: 'larger-swal',
                title: 'larger-swal-title',
                content: 'larger-swal-content',
                confirmButton: 'larger-swal-confirm-button',

            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'cancel_order.php?delete_order=' + id;

                Swal.fire({
                    title: "Completed!",
                    text: "Order has been Delete!",
                    icon: "success",
                    customClass: {
                        popup: 'larger-swal',
                        title: 'larger-swal-title',
                        content: 'larger-swal-content',
                        confirmButton: 'larger-swal-confirm-button',

                    }
                });
            } else {
                Swal.fire({
                    text: "Order has not been Delete!",
                    icon: "error",
                    customClass: {
                        popup: 'larger-swal',
                        title: 'larger-swal-title',
                        content: 'larger-swal-content',
                        confirmButton: 'larger-swal-confirm-button',

                    }
                });
            }
        });
    }

    function viewbill(id) {
        Swal.fire({
            icon: 'info',
            title: 'Please Wait...',
            text: 'Generating Bill...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            showConfirmButton: false,
            timer: 3000,
            customClass: {
                popup: 'larger-swal',
                title: 'larger-swal-title',
                content: 'larger-swal-content',
                confirmButton: 'larger-swal-confirm-button',
                loader: 'larger-swal-loader', // Add a custom class for the loader


            },
            onBeforeOpen: () => {
                Swal.showLoading();
                $(".orders").css({
                    opacity: "0.2",
                    pointerEvents: "none"
                });
            }
        }).then((result) => {
            window.location.href = 'bill.php?view_bill=' + id;

        });
    }

    function errorBill() {
        Swal.fire({
            title: "You cannot view this bill.",
            text: "Please pay first and then try again.",
            icon: "error",
            confirmButtonColor: "#d33",
            confirmButtonText: "Cancel",
            customClass: {
                popup: 'larger-swal',
                title: 'larger-swal-title',
                content: 'larger-swal-content',
                confirmButton: 'larger-swal-confirm-button',
            },
            onBeforeOpen: () => {
                $(".orders").css({
                    opacity: "0.2",
                    pointerEvents: "none"
                });
            },
            onClose: () => {
                $(".orders").css({
                    opacity: "1",
                    pointerEvents: "auto"
                });
            }
        });
    }
    </script>

    <?php include 'components/footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>