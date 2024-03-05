<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css?v=<?php echo Time()?>">

</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="about">

        <div class="row">

            <div class="image">
                <img src="images/about-img.svg" alt="">
            </div>

            <div class="content">
                <h3>Why Choose Us?</h3>
                <p>Welcome to Electronics Nepal, your one-stop destination for all your electronic needs! We are a
                    leading e-commerce platform dedicated to providing a wide range of high-quality electronic products
                    at competitive prices. Whether you're looking for smartphones, laptops, televisions, home
                    appliances, or accessories, we've got you covered. With our user-friendly interface and secure
                    payment options, shopping with us is convenient and hassle-free. Our extensive collection features
                    the latest innovations from top brands, ensuring you get the best technology in the market. We also
                    offer fast and reliable shipping across Nepal, so you can enjoy your new gadgets in no time.
                    Experience the joy of shopping for electronics online with Electronics Nepal and take your digital
                    lifestyle to the next level!</p>
                <a href="contact.php" class="btn">Contact Us</a>
            </div>

        </div>

    </section>

    <?php include 'components/footer.php'; ?>

</body>

</html>