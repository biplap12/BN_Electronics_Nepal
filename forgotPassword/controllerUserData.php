<?php 
session_start();
include "../components/connect.php";
$email = "";
$name = "";
$errors = array();


    //if user click verification code submit button
    if (isset($_POST['check'])) {
        $_SESSION['info'] = "";
        $otpCode = $_POST['otp'];
    
        // Check if the provided OTP code exists
        $checkCodeQuery = "SELECT * FROM users WHERE code = :otpCode";
        $checkCodeStmt = $conn->prepare($checkCodeQuery);
        $checkCodeStmt->bindParam(':otpCode', $otpCode, PDO::PARAM_INT);
        $checkCodeStmt->execute();
    
        if ($checkCodeStmt->rowCount() > 0) {
            // Valid OTP code, update user status
            $userData = $checkCodeStmt->fetch(PDO::FETCH_ASSOC);
            $email = $userData['email'];
            $newCode = 0;
    
            $updateCodeQuery = "UPDATE users SET code = :newCode WHERE code = :otpCode";
            $updateCodeStmt = $conn->prepare($updateCodeQuery);
            $updateCodeStmt->bindParam(':newCode', $newCode, PDO::PARAM_INT);
            $updateCodeStmt->bindParam(':otpCode', $otpCode, PDO::PARAM_INT);
            $updateRes = $updateCodeStmt->execute();
    
            if ($updateRes) {
                $_SESSION['name'] = $name; // Assuming $name is already set somewhere in your code
                $_SESSION['email'] = $email;
                header('location:../home.php');
                exit();
            } else {
                $errors['otp-error'] = "Failed while updating code!";
            }
        } else {
            $errors['otp-error'] = "You've entered incorrect code!";
        }
    }
    

    //if user click continue button in forgot password form
    if (isset($_POST['check-email'])) {
        $email = $_POST['email'];
    
        // Check if the email exists
        $checkEmailQuery = "SELECT * FROM users WHERE email = :email";
        $checkEmailStmt = $conn->prepare($checkEmailQuery);
        $checkEmailStmt->bindParam(':email', $email, PDO::PARAM_STR);
        $checkEmailStmt->execute();
    
        if ($checkEmailStmt->rowCount() > 0) {
            // Email exists, generate and update reset code
            $code = rand(999999, 111111);
            $updateCodeQuery = "UPDATE users SET code = :code WHERE email = :email";
            $updateCodeStmt = $conn->prepare($updateCodeQuery);
            $updateCodeStmt->bindParam(':code', $code, PDO::PARAM_INT);
            $updateCodeStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $updateCodeStmt->execute();
    
            // Send email with reset code
            $subject = "Password Reset Code";
            $body = "<p><strong>Your password reset code is:</strong></p><h1>$code</h1><br> Don't share this code with others.<br>  <p> Use this code to reset your password. </p> <br> <br><br> Regards, <br> BN Electronics Nepal ";
            $headers = "From: BN Electronics Nepal\r\n";
            $headers .= "Reply-To: blood.bank.nepal11@gmail.com\r\n";
            $headers .= "Content-Type: text/html\r\n";
            if (mail($email, $subject, $body, $headers)) {
                // Email sent successfully, set session variables and redirect
                $_SESSION['info'] = "We've sent a password reset OTP to your email - $email";
                $_SESSION['email'] = $email;
                header('location: reset-code.php');
                exit();
            } else {
                $errors['otp-error'] = "Failed while sending code!";
            }
        } else {
            $errors['email'] = "This email address does not exist!";
        }
    }
    

    if (isset($_POST['check-reset-otp'])) {
        $_SESSION['info'] = "";
        $otpCode = $_POST['otp'];
    
        // Check if the provided OTP code exists
        $checkCodeQuery = "SELECT * FROM users WHERE code = :otpCode";
        $checkCodeStmt = $conn->prepare($checkCodeQuery);
        $checkCodeStmt->bindParam(':otpCode', $otpCode, PDO::PARAM_INT);
        $checkCodeStmt->execute();
    
        if ($checkCodeStmt->rowCount() > 0) {
            // Valid OTP code, retrieve user email
            $userData = $checkCodeStmt->fetch(PDO::FETCH_ASSOC);
            $email = $userData['email'];
            $_SESSION['email'] = $email;
    
            $info = "Please create a new password that you don't use on any other site.";
            $_SESSION['info'] = $info;
    
            header('location: new-password.php');
            exit();
        } else {
            $errors['otp-error'] = "You've entered incorrect code!";
        }
    }
    

    //if user click change password button
    if (isset($_POST['change-password'])) {
        $_SESSION['info'] = "";
        $password = sha1($_POST['password']);
        $password = filter_var($password, FILTER_SANITIZE_STRING);
        $cpassword = sha1($_POST['cpassword']);
        $cpassword = filter_var($cpassword, FILTER_SANITIZE_STRING);
    
        if ($password !== $cpassword) {
            $errors['password'] = "Confirm password not matched!";
        } else {
            $code = 0;
            $email = $_SESSION['email']; //getting this email using session
    
            // Assuming $conn is your database connection
            $updateCodeStmt = $conn->prepare("UPDATE users SET code = ?, password = ? WHERE email = ?");    
            if ($updateCodeStmt->execute([$code, $password, $email])) {
                $info = "Your password changed. Now you can log in with your new password.";
                $_SESSION['info'] = $info;
                header('Location: password-changed.php');
                exit();
            } else {
                $errors['db-error'] = "Failed to change your password!";
            }
        }
    }
    
    
    
?>