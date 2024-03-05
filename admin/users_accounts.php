<?php

include 'adminHeader.php';

if (!isset($_SESSION['admin_id'])) {
    header('location: login.php');
    exit();
}
$admin_id = $_SESSION['admin_id'];

if (isset($_GET['block'])) {
    $block_id = $_GET['block'];
    $block_user = $conn->prepare("UPDATE `users` SET user_status = 0, admin_blocker = ? WHERE id = ?");
    $block_user->execute([$admin_id, $block_id]); 
    $send_email = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $send_email->execute([$block_id]);
    $fetch_email = $send_email->fetch(PDO::FETCH_ASSOC);
    $email = $fetch_email['email'];
    $name = $fetch_email['name'];
    $subject = "Account Blocked";
    $message = "Dear $name, <br><br> Your account has been blocked. Because of your inappropriate activities. You can contact us for more information. <br><br> Thank you. <br> Regards, <br> BN Electronics Nepal";
    $headers = "From: BN Electronics Nepal\r\n";
    $headers .= "Reply-To: blood.bank.nepal11@gmail.com\r\n";
    $headers .= "Content-Type: text/html\r\n";
    mail($email, $subject, $message, $headers);
    echo '<script>window.location.href="users_accounts.php"</script>';
    exit;
} elseif (isset($_GET['unblock'])) {
    $unblock_id = $_GET['unblock'];
    $unblock_user = $conn->prepare("UPDATE `users` SET user_status = 1, admin_UNblocker = ? WHERE id = ?");
    $unblock_user->execute([$admin_id, $unblock_id]);
    $send_email = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $send_email->execute([$unblock_id]);
    $fetch_email = $send_email->fetch(PDO::FETCH_ASSOC);
    $email = $fetch_email['email'];
    $name = $fetch_email['name'];
    $subject = "Account Unblocked";
    $message = "Dear $name, <br><br> <h1>Congratulations!!</h1><br><br> Your account has been unblocked. Don't repeat the same mistake again. <br><br> Thank you. <br> Regards, <br> BN Electronics Nepal";
    $headers = "From: BN Electronics Nepal\r\n";
    $headers .= "Reply-To: blood.bank.nepal11@gmail.com\r\n";
    $headers .= "Content-Type: text/html\r\n";
    mail($email, $subject, $message, $headers);
    echo '<script>window.location.href="users_accounts.php"</script>';
    exit;
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Account</title>

    <link rel="stylesheet" href="dist/css/adminlte.min.css?v=3.2.0">

</head>



<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>User Data</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">User Data</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">User Data</h3>
            </div>

            <div class="card-body">


                <?php
 $select_accounts = $conn->prepare("
 SELECT users.id, users.name, users.email, admins.name AS admin_blocker, users.user_status
 FROM users
 LEFT JOIN admins ON users.admin_blocker = admins.id
");
      $select_accounts->execute();
      if($select_accounts->rowCount() > 0){
         $table="";
         $table.="<table id='example1' class='table table-bordered table-striped'>";
         $table.="<thead>";
         $table.="<tr>";
         $table.="<th>ID</th>";
         $table.="<th>Name</th>";
         $table.="<th>Email</th>";
         $table.="<th>Block</th>";
            $table.="<th>View</th>";
         $table.= "</tr>";
         $table.="</thead>";
         while($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)){   
            $table.="<tbody>";
            $table.="<tr>";
            $table.="<td>".$fetch_accounts['id']."</td>";
            $table.="<td>".$fetch_accounts['name']."</td>";
            $table.="<td>".$fetch_accounts['email']."</td>";
       
        $table .= "<td>
    <div class='d-flex'>";

if (($fetch_accounts['user_status']==true) || ($fetch_accounts['admin_blocker']==NULL ) ){
    $table .= "<a href='users_accounts.php?block={$fetch_accounts['id']}' 
                   onclick=\"return confirm('Are you sure you want to block this account? The user-related information will also be blocked!')\" 
                   class='btn btn-danger btn-sm'>
                    Block
                </a>";
} else {
    $table .= "<a href='users_accounts.php?unblock={$fetch_accounts['id']}' 
                   onclick=\"return confirm('Are you sure you want to unblock this account?')\" 
                   class='btn btn-success btn-sm'>
                    Unblock
                </a>
                <div class='ml-2 font-weight-bold'>This user is blocked by {$fetch_accounts['admin_blocker']}.</div>";
}

$table .= "</div>
</td>";            
$table .= "<td><a href='user_profile.php?user_id=" . $fetch_accounts['id'] . "'
            class='btn btn-primary btn-sm'>&nbsp;
            &nbsp;View&nbsp;&nbsp;</a></td>";
            

                $table.="</tr>";
                $table.="</tbody>";


                }
                $table.="</table>";
                echo $table;
                
                }else{
                echo '<p class="empty">No accounts available!</p>';
                }

                ?>



            </div>

        </div>

    </div>




    </div>
    <?php
    include 'footer.php'; 
    ?>