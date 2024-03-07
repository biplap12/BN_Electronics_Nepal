<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $_SESSION['user_id'] = $row['id'];
      header('location:home.php');
      $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $select_user->execute([$email,]);
        $row = $select_user->fetch(PDO::FETCH_ASSOC);
      date_default_timezone_set("Asia/Kathmandu"); // Set the default timezone
      $loginTime = date("Y-m-d H:i:s"); // Get the current date and time
    $to = $email;
    $subject = "Logged in successfully! 👋😊";
    $body = "Dear {$row['name']} Nameste 🙏,<br><br>
        You have successfully logged in to your account at {$loginTime}. We sincerely appreciate your trust in our services.<br>
        Thank you for choosing BN Electronics Nepal. Your satisfaction is our priority.<br>
        If you have any questions or need assistance, feel free to contact our support team.<br><br>

        Thank you for using our service❤️.<br><br>

        Regards,<br>
        BN Electronics Nepal";
$headers = "From: BN Electronics Nepal\r\n";
$headers .= "Reply-To: blood.bank.nepal11@gmail.com\r\n";
$headers .= "Content-Type: text/html\r\n";
$mailSent = mail($to, $subject, $body, $headers);
}else{
$message[] = 'Incorrect username or password!';
}

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css?v=<?php echo Time()?>">

</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="form-container">
        <div id="loader" class="loader-overlay">
            <div class="loader"> </div>
            <div class="loading"> Loading...</div>
        </div>
        <form action="" method="post" id="loginForm">
            <h3>Login Now</h3>
            <input type="email" name="email" required placeholder="Email" maxlength="50" class="box"
                oninput="this.value = this.value.replace(/\s/g, '')">
            <div class="passAndShowPass">
                <input type="password" name="pass" required placeholder="Password" maxlength="20" class="box"
                    oninput="this.value = this.value.replace(/\s/g, '')">
                <span class="showPass" id="showPass"> <i id="eyeIcon" class="fas fa-eye"></i>
                </span>
            </div>
            <div class="dis_RemP_For">
                <div>
                    <!-- <input type="checkbox" name="rememberPassword" id="rememberPassword">
                    <label for="rememberPassword">Remember Password</label> -->
                </div>
                <a class="forgotPassword" href="forgotPassword/forgot-password.php">forgot password ?</a>
            </div>
            <input type="submit" value="Login now" class="btn" name="submit" onclick="showLoader()">
            <p>Don't have an Account?</p>
            <a href="user_register.php" class="option-btn">Register Now</a>
        </form>

    </section>


    <script src="js/script.js"></script>
    <script src="js/loginForm.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        let showPass = document.getElementById('showPass');
        let pass = document.querySelector('.passAndShowPass input[type="password"]');
        let eyeIcon = document.getElementById('eyeIcon');

        showPass.addEventListener('click', function() {
            if (pass.type === 'password') {
                pass.type = 'text';
                eyeIcon.className = 'fas fa-eye-slash'; // Eye icon with a slash
            } else {
                pass.type = 'password';
                eyeIcon.className = 'fas fa-eye'; // Open eye icon
            }
        });
    });
    </script>



</body>

</html>