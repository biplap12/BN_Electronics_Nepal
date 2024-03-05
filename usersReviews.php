<?php
include 'components/connect.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">

</head>

<body>


    <section class="reviews">

        <h1 class="heading">Client's Reviews</h1>

        <div class="swiper reviews-slider">

            <div class="swiper-wrapper">
                <?php
$select_review = $conn->prepare("SELECT COUNT(users.id) as user_count, users.name, users.user_picture, product_ratings.comments, SUM(product_ratings.rating) as total_rating, AVG(product_ratings.rating) as avg_rating, product_ratings.anonymous FROM product_ratings INNER JOIN users ON product_ratings.user_id=users.id WHERE product_ratings.pid=? GROUP BY users.name, users.user_picture, product_ratings.comments");
$select_review->execute([$pid]);
while ($review = $select_review->fetch()) {
    $rating = $review['avg_rating'];
     $users = $review['user_count'];
    $comment = $review['comments'];

    if ($review['anonymous'] === 1) {
        $name = "Anonymous";
        $image = "./user_picture/anonymous.png";
    } else {
        $name = $review['name'];
        $image = (!empty($review['user_picture'])) ? "./user_picture/" . $review['user_picture'] : "./admin_picture/default_profile_picture.png";
    }
?>
                <div class="swiper-slide slide">
                    <img src="<?=$image?>">
                    <p><?= $comment ?></p>
                    <div class="stars">
                        <li>
                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                            <?php if ($i <= $rating) { ?>
                            <i class="fas fa-star"></i>
                            <?php } elseif ($i - 0.5 <= $rating) { ?>
                            <i class="fas fa-star-half-alt"></i>
                            <?php } else { ?>
                            <i class="far fa-star"></i>
                            <?php } ?>
                            <?php } ?>
                        </li>
                    </div>
                    <h3><?= $name ?></h3>
                </div>
                <?php } ?>
            </div>
            <div class="swiper-pagination"></div>

        </div>

    </section>



    <script>
    var totalRating = <?php echo json_encode($avgRating);?>;
    var intRating = parseInt(totalRating);
    let stars = document.getElementsByClassName("star");

    // Function to update rating
    function gfg(n) {
        remove();
        for (let i = 0; i < n; i++) {
            if (n == 1) cls = "one";
            else if (n == 2) cls = "two";
            else if (n == 3) cls = "three";
            else if (n == 4) cls = "four";
            else if (n == 5) cls = "five";
            stars[i].className = "star " + cls;
        }
    }

    // To remove the pre-applied styling
    function remove() {
        let i = 0;
        while (i < 5) {
            stars[i].className = "star";
            i++;
        }
    }

    // Set the default rating to 1 star when the page loads (change if needed)
    window.onload = function() {
        gfg(intRating);
    };
    </script>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>

    <script>
    var swiper = new Swiper(".reviews-slider", {
        loop: true,
        spaceBetween: 20,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            768: {
                slidesPerView: 2,
            },
            991: {
                slidesPerView: 3,
            },
        },
    });
    </script>

    <?php include 'components/footer.php'; ?>

</body>

</html>