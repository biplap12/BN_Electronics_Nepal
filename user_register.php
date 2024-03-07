<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $phone = $_POST['phone'];
   $phone = filter_var($phone, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);



$select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR phone = ?");
$select_user->execute([$email, $phone]);
$row = $select_user->fetch(PDO::FETCH_ASSOC);

if ($select_user->rowCount() > 0) {
    // Check if the email already exists
    if ($row['email'] == $email) {
        $message[] = 'Email already exists!';
    }
    if(empty($name)||empty($email)||empty($phone)||empty($pass)||empty($cpass)){
        $message[] = 'Please fill all the fields!';
    }
    if($phone < 10){
        $message[] = 'Phone number should be 10 digits!';
    }   
    if ($row['phone'] == $phone) {
        $message[] = 'Phone number already exists!';
    }
} else {
    if ($pass != $cpass) {
        $message[] = 'Confirm password not matched!';
    } else {
        $insert_user = $conn->prepare("INSERT INTO `users`(name, email, phone, password) VALUES(?,?,?,?)");
        $insert_user->execute([$name, $email, $phone, $cpass]);
        $to = $email; // Use $email as the recipient
        $subject = "Your BN Electronics Nepal account has been created! 🎉";
        $currentYear = date('Y');
        $body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; text-align: center;'>
                <p>Hello <strong>{$name}</strong>,</p>
                <p><strong>Welcome to BN Electronics Nepal 🙏.</strong></p>
                <p>Thank you for choosing us ❤️! We are excited to have you as part of our community.</p>
                <p>If you have any questions or need assistance, feel free to reach out.</p>
                <br>
                <p>Best regards,</p>
                <p>BN Electronics Nepal</p>
                <p>BN Electronics Nepal &copy; {$currentYear} </p>
            </div>
        "; 
    $headers = "From: BN Electronics Nepal\r\n";
    $headers .= "Reply-To: blood.bank.nepal11@gmail.com\r\n";
    $headers .= "Content-Type: text/html\r\n";
    $mailSent = mail($to, $subject, $body, $headers);
    $message[] = 'Registered successfully, Login now please!';
        }
    }
}
// header("Location: user_login.php");



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

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
        <form action="" method="post" id="signupForm">
            <h3>Register now</h3>
            <input type="text" name="name" required id="name" placeholder="Name" maxlength="20" min="5" class="box">
            <input type="email" name="email" required id="email" placeholder="Email" maxlength="50" class="box">
            <input type="tel" name="phone" required id="phone" placeholder="Phone" maxlength="15"minlength="10" class="box">
            <input type="password" name="pass" required id="pass" placeholder="Password" minlength="8" maxlength="20" class="box">
            <input type="password" name="cpass" required id="cpass" placeholder="Confirm password" maxlength="20"
                class="box">
            <input type="submit" value="Register now" class="btn" name="submit" onclick="showLoaderSignup()">
            <p>Already have an account?</p>
            <a href="user_login.php" class="option-btn">Login now</a>
        </form>
    </section>


    <?php include 'components/footer.php'; ?>
    <script src="./js/loginForm.js"></script>
    <script src="js/script.js"></script>

    <script>
    function showLoaderSignup() {
        var nameValue = document.getElementById("name").value;
        var emailValue = document.getElementById("email").value;
        var phoneValue = document.getElementById("phone").value;
        var passValue = document.getElementById("pass").value;
        var passValue1 = document.getElementById("cpass").value;

        if (emailValue === '' || passValue === '' || passValue1 === '' || nameValue === '' || phoneValue === '' || phoneValue >=10) {
            alert("Please fill all the fields!");
            return false;
        } else {
            document.getElementById('loader').style.display = 'flex';
            document.getElementById('signupForm').style.background = 'white';
            document.getElementById('signupForm').style.opacity = '0.2';
            return true;
        }
    }
    </script>


</body>

</html>