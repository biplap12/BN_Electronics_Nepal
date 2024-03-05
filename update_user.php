<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
   include 'admin/blocked_user.php'; 
}else{
   $user_id = '';
   header('location: user_login.php');
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
    $phone = $_POST['phone'];   
    $phone = filter_var($phone, FILTER_SANITIZE_STRING);
    $state = $_POST['state'];
    $state = filter_var($state, FILTER_SANITIZE_STRING);
    $city = $_POST['city'];
    $city = filter_var($city, FILTER_SANITIZE_STRING);
    $address = $_POST['address'];
    $address = filter_var($address, FILTER_SANITIZE_STRING);
    $profile_picture = $_FILES['user_profile_picture']['name'];
    $profile_picture = filter_var($profile_picture, FILTER_SANITIZE_STRING);
    $image_folder = './user_picture/'.$profile_picture;

if(empty($name) || empty($email) || empty($phone) || empty($state) || empty($city) || empty($address) || empty($profile_picture)){
      $message[] = 'Please fill all the fields!';
    }else{
   $update_profile = $conn->prepare("UPDATE `users` SET name = ?, email = ?, phone=?, state = ?,city= ?, address=?, user_picture=? WHERE id = ?");
   $update_profile->execute([$name, $email,$phone,$state,$city,$address, $profile_picture, $user_id]);

    move_uploaded_file($_FILES['user_profile_picture']['tmp_name'], $image_folder);
    }
   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $prev_pass = $_POST['prev_pass'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   if($old_pass == $empty_pass){
      $message[] = 'Please enter Old password!';
   }elseif($old_pass != $prev_pass){
      $message[] = 'Old password not matched!';
   }elseif($new_pass != $cpass){
      $message[] = 'Confirm password not matched!';
   }else{
      if($new_pass != $empty_pass){
         $update_admin_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
         $update_admin_pass->execute([$cpass, $user_id]);
         $message[] = 'Password updated successfully!';
      }else{
         $message[] = 'Please enter a new password!';
      }
   }
   
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css?v=<?php echo Time()?>">

</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="form-container">

        <form action="" method="post" enctype="multipart/form-data">
            <h3>Update now</h3>
            <input type="hidden" name="prev_pass" value="<?= $fetch_profile["password"]; ?>">
            <input type="text" name="name" required placeholder="Username" maxlength="20" class="box"
                value="<?= $fetch_profile["name"]; ?>">
            <input type="email" name="email" required placeholder="Email" maxlength="50" class="box"
                oninput="this.value = this.value.replace(/\s/g, '')" value="<?= $fetch_profile["email"]; ?>">
            <input type="tel" name="phone" required placeholder="phone" maxlength="15" class="box"
                oninput="this.value = this.value.replace(/\s/g, '')" value="<?= $fetch_profile["phone"]; ?>">


            <select name="state" class="box">
                <option value="">Select State</option>
                <option value="Koshi State" <?= ($fetch_profile["state"] == 'Koshi State') ? 'selected' : ''; ?>>Koshi
                    State</option>
                <option value="Madesh State" <?= ($fetch_profile["state"] == 'Madesh State') ? 'selected' : ''; ?>>
                    Madesh State</option>
                <option value="Bagmati State" <?= ($fetch_profile["state"] == 'Bagmati State') ? 'selected' : ''; ?>>
                    Bagmati State
                </option>
                <option value="Gandaki State" <?= ($fetch_profile["state"] == 'Gandaki State') ? 'selected' : ''; ?>>
                    Gandaki State
                </option>
                <option value="Lumbini State" <?= ($fetch_profile["state"] == 'Lumbini State') ? 'selected' : ''; ?>>
                    Lumbini State
                </option>
                <option value="Karnali State" <?= ($fetch_profile["state"] == 'Karnali State') ? 'selected' : ''; ?>>
                    Karnali State
                </option>
                <option value="Sudurpaschim State"
                    <?= ($fetch_profile["state"] == 'Sudurpaschim State') ? 'selected' : ''; ?>>
                    Sudurpaschim State</option>
            </select>


            <input type=" text" name="city" required placeholder="City" maxlength="50" class="box"
                oninput="this.value = this.value.replace(/\s/g, ' ')" value="<?= $fetch_profile["city"]; ?>">
            <input type="text" name="address" required placeholder="Address" maxlength="100" class="box"
                value="<?= $fetch_profile["address"]; ?>">
            <input type="file" name="user_profile_picture" class="box" placeholder="Profile Picture"
                accept="image/png, image/jpeg, image/jpg" id="profile_picture">
            <input type="password" name="old_pass" placeholder="Old Password" min="8" maxlength="20" class="box"
                oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="new_pass" placeholder="New Password" min="8" maxlength="20" class="box"
                oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="cpass" placeholder="Confirm New Password" min="8" maxlength="20" class="box"
                oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="submit" value="Update now" class="btn" name="submit">
        </form>

    </section>













    <?php include 'components/footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>