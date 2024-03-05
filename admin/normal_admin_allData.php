<?php

include 'adminHeader.php';


$admin_id = $_SESSION['admin_id'];


if(!isset($admin_id)){
   header('location:admin_login.php');
}

// $productsItem = $conn->prepare("SELECT admin_id FROM `products`");
// $productsItem->execute();

// $adminIds = array();

// while ($row = $productsItem->fetch(PDO::FETCH_ASSOC)) {
//     $adminIds[] = $row['admin_id'];
// }

// if (isset($_GET['delete'])) {
//     $delete_id = $_GET['delete'];

//     // Check if the admin_id exists in the $adminIds array
//     if (!in_array($delete_id, $adminIds)) {
//         $delete_admins = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
//         $delete_admins->execute([$delete_id]);

//         if ($delete_admins->rowCount() > 0) {
//             echo "<script>alert('Admin deleted successfully!')</script>";
//             echo "<script>window.location.href='normal_admin_allData.php'</script>";
//         } else {
//             echo "<script>alert('Error deleting admin.')</script>";
//             echo "<script>window.location.href='normal_admin_allData.php'</script>";
//         }
//     } else {
//         echo "<script>alert('You cannot delete the admin as it is associated with a product!')</script>";
//         echo "<script>window.location.href='normal_admin_allData.php'</script>";
//     }
// }
$select_adminName = $conn->prepare("SELECT name, blk  FROM `admins` WHERE id=?");
$select_adminName->execute([$admin_id]);

$adminData = $select_adminName->fetch(PDO::FETCH_ASSOC);
   $adminName = $adminData['name'];

if(isset($_GET['deactive'])){
    $deactive_id = $_GET['deactive'];
    $deactive_admins = $conn->prepare("UPDATE `admins` SET admin_status = ?, blk=? WHERE id = ?");
    $deactive_admins->execute(['deactive',$adminName,$deactive_id]);
    if($deactive_admins->rowCount() > 0){
        echo "<script>alert('Admin Deactivated successfully!')</script>";
        echo "<script>window.location.href='normal_admin_allData.php'</script>";
    }else{
        echo "<script>alert('Error Deactivating admin.')</script>";
        echo "<script>window.location.href='normal_admin_allData.php'</script>";
    }
}


if(isset($_GET['active'])){
    $active_id = $_GET['active'];
    $active_admins = $conn->prepare("UPDATE `admins` SET admin_status = ?, unblk=? WHERE id = ?");
    $active_admins->execute(['regular',$adminName ,$active_id]);
    if($active_admins->rowCount() > 0){
        echo "<script>alert('Admin Activated successfully!')</script>";
        echo "<script>window.location.href='normal_admin_allData.php'</script>";
    }else{
        echo "<script>alert('Error Activating admin.')</script>";
        echo "<script>window.location.href='normal_admin_allData.php'</script>";
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
    $table .= "<table id='example1' class='table table-bordered table-striped'>";
    $table .= "<thead>";
    $table .= "<tr>";
    $table .= "<th>ID</th>";
    $table .= "<th>Name</th>";
    $table .= "<th>Action</th>";
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
        $table .= "<td><b>" . htmlspecialchars($fetch_accounts['name']) . "</b></td>";
        if($fetch_accounts['admin_status'] == 'regular'){
            $table .= "<td><a href='normal_admin_allData.php?deactive={$fetch_accounts['id']}' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to Block?\");'>Block</a></td>";
        }else{
            $table .= "<td><a href='normal_admin_allData.php?active={$fetch_accounts['id']}' class='btn btn-success' onclick='return confirm(\"Are you sure you want to UnBlock?\");'>Unblock</a>
            <span>This admin is blocked by <strong> {$fetch_accounts['blk']} </strong>.</span>

            </td>";

        }

            $table .= "<td><a href='updateBySuperAdmin.php?update={$fetch_accounts['id']}' class='btn btn-warning'>Update</a></td>";
            $table .= "<td><a href='admin_profile_view.php?view={$fetch_accounts['id']}' class='btn btn-info'>View</a></td>";
         
        
         
       
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