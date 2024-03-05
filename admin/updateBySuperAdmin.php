<?php include 'adminHeader.php';
$admin_id=$_GET['update'];


if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   $update_profile_name = $conn->prepare("UPDATE `admins` SET name = ? WHERE id = ?");
   $update_profile_name->execute([$name, $admin_id]);

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $prev_pass = $_POST['prev_pass'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = sha1($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   if($old_pass == $empty_pass){
      $message[] = 'Please enter old password!';
       }elseif($old_pass != $prev_pass){
      $message[] = 'Old password not matched!';
   }elseif($new_pass != $confirm_pass){
      $message[] = 'Confirm password not matched!';
   }else{
      if($new_pass != $empty_pass){
         $update_admin_pass = $conn->prepare("UPDATE `admins` SET password = ? WHERE id = ?");
         $update_admin_pass->execute([$confirm_pass, $admin_id]);
         $message[] = 'Password updated successfully!';
      }else{
         $message[] = 'Please enter a new password!';
      }
   

   
       $profile_picture = $_FILES['profile_picture']['name'];
       $profile_picture = filter_var($profile_picture, FILTER_SANITIZE_STRING);
       $image_folder_01 = '../admin_picture/'.$profile_picture;
       
       // Prepare and execute the SQL query
       $update_profile_picture = $conn->prepare("UPDATE `admins` SET photo = ? WHERE id = ?");
       $update_profile_picture->execute([$profile_picture, $admin_id]);
   
       // Move the uploaded file to the destination folder
       move_uploaded_file($_FILES['profile_picture']['tmp_name'], $image_folder_01);
   
       $message[] = 'Image updated successfully!';
   } 
}
   


            $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>

<!-- left column -->

<!-- jquery validation -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Update Profile By Super Admin</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Update Profile</li>
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
                    <h3 class="card-title">Update Profile</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="post" action="" id="quickForm" enctype="multipart/form-data">
                    <div class="card-body">
                        <input type="hidden" name="prev_pass" value="<?= $fetch_profile['password']; ?>">
                        <div class="form-group">
                            <label for="username">UserName</label>
                            <input type="text" name="name" value="<?= $fetch_profile['name']; ?>" class="form-control"
                                id="username" placeholder="Enter Username" required>
                        </div>
                        <div class="form-group">
                            <label for="profile_pic">Profile Picture</label><br>
                            <input type="file" name="profile_picture" placeholder="Profile Picture"
                                accept="image/png, image/jpeg, image/jpg" id="profile_picture" required>
                        </div>

                        <div class="form-group">
                            <label for="old_pass">Old Password</label>
                            <input type="password" name="old_pass" placeholder="Old password" maxlength="20"
                                class="form-control" id="oldPassword" required>
                        </div>
                        <div class="form-group">
                            <label for="NewPassword">New Password</label>
                            <input type="password" name="new_pass" placeholder="New password" maxlength="20"
                                class="form-control" id="new_password" required>
                        </div>
                        <div class="form-group">
                            <label for="Confirm_pss">Confirm new Password</label>
                            <input type="password" name="confirm_pass" placeholder="Confirm new password" maxlength="20"
                                class="form-control" id="confirm_pass" required>
                        </div>
                        <div class="col-md-0 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" name="submit">Update Profile</button>
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