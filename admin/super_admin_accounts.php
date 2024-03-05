<?php

include 'adminHeader.php';


$admin_id = $_SESSION['admin_id'];


if(!isset($admin_id)){
   header('location:admin_login.php');
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
$select_accounts = $conn->prepare("SELECT * FROM `admins` where admin_status = 'super'");
$select_accounts->execute();

if ($select_accounts->rowCount() > 0) {
    $table = "";
    $table .= "<table id='example1' class='table table-bordered table-striped'>";
    $table .= "<thead>";
    $table .= "<tr>";
    $table .= "<th>ID</th>";
    $table .= "<th>Name</th>";
    $table .= "<th>Update</th>";
    $table .= "<th>View</th>";
    $table .= "</tr>";
    $table .= "</thead>";
    $table .= "<tbody>";

    $i = 1;
    while ($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
        $table .= "<tr>";
        $table .= "<td><b>$i</b></td>";
        
        $table .= "<td><b>" . htmlspecialchars($fetch_accounts['name']) . "</b></td>";
        if($fetch_accounts['id'] == $admin_id){
            $table .= "<td><a href='update_profile.php?update={$fetch_accounts['id']}' class='btn btn-success'>Update</a></td>";
            $table .= "<td><a href='admin_profile_view.php?view={$fetch_accounts['id']}' class='btn btn-info'>View</a></td>";
        }
        $table .= "</tr>";
        $i++;
    }
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