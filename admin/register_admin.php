<?php
include 'adminHeader.php';

// Assuming $admin_id is defined somewhere
$select_admins = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
$select_admins->execute([$admin_id]);
$fetch_admins = $select_admins->fetch(PDO::FETCH_ASSOC);
$admin = $fetch_admins['admin_status'];

if ($admin == 'super') {
    $message = [];

    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $pass = $_POST['pass'];
        $cpass = $_POST['cpass'];

        // Basic form validation
        if (empty($name) || empty($pass) || empty($cpass)) {
            $message[] = 'All fields are required!';
        } else {
            $name = filter_var($name, FILTER_SANITIZE_STRING);

            if (strlen($name) < 5 || strlen($name) > 20) {
                $message[] = 'Username must be between 6 and 20 characters!';
            }

            if (strlen($pass) < 8 || strlen($pass) > 50) {
                $message[] = 'Password must be between 8 and 50 characters!';
            }

            if ($pass != $cpass) {
                $message[] = 'Confirm password does not match!';
            }

            // Check if the username already exists
            $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ?");
            $select_admin->execute([$name]);

            if ($select_admin->rowCount() > 0) {
                $message[] = 'Username already exists!';
            } elseif(
                $pass != $cpass
            ){
                $message[] = 'Confirm password does not match!';
            }
            elseif(preg_match('/[@#$%]/', $pass) && preg_match('/[A-Z]/', $pass)){
                $message[] = 'Password must be Upper case and contain at least one of the special characters (@, #, $, %)!';
            }
            else {
                // Insert new admin
                $insert_admin = $conn->prepare("INSERT INTO `admins` (name, password) VALUES (?, ?)");
                $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
                $insert_admin->execute([$name, $hashed_password]);
                $message[] = 'New admin registered successfully!';
            }
        }
    }
?>


<!-- ************************************************** -->

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Register Admin</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Register_Admin</li>
                </ol>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<?php
                        if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4  mx-auto">
            <div class="card card-green ">
                <div class=" card-header">
                    <h3 class="card-title">Register Admin</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="post" action="" id="quickForm">
                    <div class="card-body">
                        <input type="hidden" name="prev_pass" value="<?= $fetch_profile['password']; ?>">
                        <div class="form-group">
                            <label for="username">UserName</label>
                            <input type="text" name="name" required placeholder="Username" minlength="5" maxlength="20"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="pass">Password</label>
                            <input type="password" name="pass" required placeholder="Password" minlength="8"
                                maxlength="50" class="form-control">

                        </div>
                        <div class="form-group">
                            <label for="ConfirmPassword">Confirm Password</label>
                            <input type="password" name="cpass" required minlength="8" placeholder="Confirm Password"
                                maxlength="50" class="form-control">
                        </div>

                        <div class="col-md-0 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" name="submit">Register</button>
                        </div>

                    </div>

            </div>
            <!-- /.card-body -->

            </form>
        </div>
    </div>
</div>
</div>
<!-- /.card -->
</div>
<!--/.col (left) -->
<!-- right column -->

<!--/.col (right) -->
</div>
<!-- /.row -->
<?php
} else {
echo '<script>window.location.href = "index.php";</script>' ;
}
?>


<script src="../js/admin_script.js"></script>