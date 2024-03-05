<?php
include '../components/connect.php';

session_start();

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ? AND password = ?");
   $select_admin->execute([$name, $pass]);
   $row = $select_admin->fetch(PDO::FETCH_ASSOC);

   if($select_admin->rowCount() > 0){
      $_SESSION['admin_id'] = $row['id'];
      header('location:index.php');
   }else{
      $message[] = 'Incorrect username or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BN Electronics Nepal</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="admin.css?v=<?php echo Time();?>">
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('adminPass');
        const eyeIcon = document.getElementById('eyeIcon');

        eyeIcon.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Change eye icon based on password visibility
            eyeIcon.classList.toggle('fa-eye-slash');
        });
    });
    </script>
</head>

<body>



    <?php
         if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message col-md-4 mx-auto mt-5">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mx-auto mt-5">
                <div class=" card card-green ">
                    <div class=" card-header">
                        <h3 class="card-title">Admin Login</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="post" action="" id="quickForm">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="username">UserName</label>
                                <input type="text" name="name" required placeholder="Username" maxlength="20"
                                    class="form-control">
                            </div>

                            <div class="form-group position-relative">
                                <label for="Password">Password</label>
                                <div class="input-group">
                                    <input type="password" id="adminPass" name="pass" required placeholder="Password"
                                        maxlength="20" class="form-control">
                                    <div class="input-group-append">
                                        <span id="eyeIcon" class="input-group-text fas fa-eye"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <div class="custom-control custom-checkbox">

                                    <input type="checkbox" name="terms" class="custom-control-input" id="exampleCheck1"
                                        required>
                                    <label class="custom-control-label" for="exampleCheck1">I agree to the <a
                                            href="terms_of_service.php">terms of service</a>.</label>
                                </div>
                                <div class="col-md-0 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary" name="submit">Login</button>
                                </div>

                            </div>

                        </div>
                        <!-- /.card-body -->

                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- jquery-validation -->
    <script src="../../plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="../../plugins/jquery-validation/additional-methods.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../../dist/js/demo.js"></script>
    <!-- Page specific script -->
    <script>
    $(function() {
        $.validator.setDefaults({
            submitHandler: function() {
                alert("Form successful submitted!");
            }
        });
        $('#quickForm').validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                },
                password: {
                    required: true,
                    minlength: 5
                },
                terms: {
                    required: true
                },
            },
            messages: {
                email: {
                    required: "Please enter a email address",
                    email: "Please enter a valid email address"
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long"
                },
                terms: "Please accept our terms"
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        let eyeIcon = document.getElementById('eyeIcon');
        let passInput = document.getElementsByTagName('input[type="password"]')[0];

        eyeIcon.addEventListener('click', function() {
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.className = 'fas fa-eye-slash'; // Eye icon with a slash
            } else {
                passInput.type = 'password';
                eyeIcon.className = 'fas fa-eye'; // Open eye icon
            }
        });
    });
    </script>
</body>

</html>