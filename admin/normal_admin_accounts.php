<?php

include 'adminHeader.php';


$admin_id = $_SESSION['admin_id'];


if(!isset($admin_id)){
   header('location:admin_login.php');
}


// if(isset($_GET['delete'])){
//    $delete_id = $_GET['delete'];
//    $delete_admins = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
//    $delete_admins->execute([$delete_id]);
// // echo "<script>window.location.href='../components/admin_logout.php'</script>";
// }


$productsItem = $conn->prepare("SELECT admin_id FROM `products`");
$productsItem->execute();

$adminIds = array();

while ($row = $productsItem->fetch(PDO::FETCH_ASSOC)) {
    $adminIds[] = $row['admin_id'];
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    // Check if the admin_id exists in the $adminIds array
    if (!in_array($delete_id, $adminIds)) {
        $delete_admins = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
        $delete_admins->execute([$delete_id]);        
        if ($delete_admins->rowCount() > 0) {

            echo "<script>alert('Admin deleted successfully!')</script>";
            echo "<script>window.location.href='normal_admin_accounts.php'</script>";
        } else {
            echo "<script>alert('Error deleting admin.')</script>";
            echo "<script>window.location.href='normal_admin_accounts.php'</script>";
        }
    } else {
        echo "<script>alert('You cannot be deleted as it is associated with a product.')</script>";
        echo "<script>window.location.href='normal_admin_accounts.php'</script>";
    }


   
}


?>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Account</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <link rel="stylesheet" href="dist/css/adminlte.min.css?v=3.2.0">

</head>


<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Admin Data</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Admin Data</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Admin Data</h3>
            </div>

            <div class="card-body">


                <?php
$select_accounts = $conn->prepare("SELECT * FROM `admins` WHERE admin_status = 'regular' OR admin_status='deactive' ");
$select_accounts->execute();
 
if ($select_accounts->rowCount() > 0) {
    $table = "";
    $table .= "<table id='example1' class='table table-bordered table-striped' style='text-align:center;align_items:center' >";
    $table .= "<thead>";
    $table .= "<tr>";
    $table .= "<th>ID</th>";
    $table .= "<th>Name</th>";
    $table .= "<th>Logo</th>";
    $table .= "<th>Update</th>";
    $table .= "<th>View</th>";
    $table .= "</tr>";
    $table .= "</thead>";
    $table .= "<tbody>";

    $i = 1;
    while ($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
        $table .= "<tr>";
        $table .= "<td><b>$i</b></td>";
        $i++;
        $profilePicture = (!empty($fetch_accounts['photo']))
   ? "../admin_picture/{$fetch_profile['photo']}"
   : "../admin_picture/default_profile_picture.png";
        $table .= "<td><b>" . htmlspecialchars($fetch_accounts['name']) . "</b></td>";
        $table .= "<td><span style='display: inline-block; width: 40px; height: 40px; border-radius: 50%; overflow: hidden;'>
    <a href='$profilePicture' target='_blank' >
        <img src='$profilePicture' alt='Logo' style='width: 100%; height: 100%; object-fit: cover;'>
    </a>
</span></td>";

        if ($fetch_accounts['id'] == $admin_id && ($fetch_accounts['admin_status'] == 'regular' || $fetch_accounts['admin_status'] == 'deactive')) {
            
                $table .= "<td><a href='update_profile.php' class='btn btn-warning'>Update</a></td>";
                $table .= "<td><a href='admin_profile_view.php?view={$fetch_accounts['id']}' class='btn btn-info'>View</a></td>";
        } else {
            // Display empty cells if none of the conditions is met
            if ($fetch_accounts['admin_status'] == 'deactive') {
                $table .= "<td style='color: red; font-weight: bold;'>Blocked</td>";
                $table .= "<td style='color: red; font-weight: bold;'>Do not engage in suspicious behavior.</td>";                
            } else {
            $table .= "<td></td>";
            $table .= "<td></td>";
            }
        }
        
       
        }

        $table .= "</tr>";
    $table .= "</tbody>";
    $table .= "</table>";
    echo $table;
   
} else {
    echo '<p class="empty">No accounts available!</p>';
}
?>

            </div>

        </div>

    </div>

    </div>

    </div>

    </section>

    </div>



    </div>



    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>