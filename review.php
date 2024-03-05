<?php
include "./components/connect.php";
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   header("location:user_login.php");
};
if(isset($_GET['review'])){
    $reviewid=intval(base64_decode($_GET['review']));
} else {
    header("location: quick_view.php");
    exit(); // Make sure to exit after redirecting
}
if(isset($_POST['submit_rating'])){
$selectadmin= $conn->prepare("SELECT admin_id FROM products WHERE id = ?");
$selectadmin->execute([$reviewid]);
$adminid = $selectadmin->fetch();
$adminid = intval($adminid['admin_id']);   
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : '';
if ($rating === '') {
    echo "<script>alert('Please select a rating!')</script>";
}  
    $comment = isset($_POST['comment']) ? $_POST['comment'] : '';
    if ($comment === '') {
        echo "<script>alert('Please enter a comment!')</script>";
    }
    if(isset($_POST['anonymous'])){
        $anonymous = 1;
    } else {
        $anonymous = 0;
    }
    try {
        $checkItem = $conn->prepare("SELECT * FROM product_ratings WHERE user_id = ? AND pid = ?");
        $checkItem->execute([$user_id, $reviewid]);
        
        if ($checkItem->rowCount() > 0) {
            $update = $conn->prepare("UPDATE product_ratings SET rating = ?, anonymous=?, comments = ? WHERE user_id = ? AND pid = ?");
            $update->execute([$rating,$anonymous, $comment, $user_id, $reviewid]);
            if($update){
                echo "<script>alert('Your review has been updated successfully!')</script>";
                echo "<script>window.location.href='shop.php'</script>";
                exit();
            }
        } elseif ($checkItem->rowCount() == 0) {
            $insert = $conn->prepare("INSERT INTO product_ratings (user_id,admin_id, pid, rating, anonymous, comments) VALUES (?,?,?,?, ?, ?)");
            $insert->execute([$user_id,$adminid, $reviewid, $rating,$anonymous, $comment]);
            if($insert){
                echo "<script>
                alert('Your review has been submitted successfully!');
                window.location.href='shop.php';
            </script>";
            
                exit();
            }
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();

    }
}
include "./components/user_header.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Rating </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<style>
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.container {
    max-width: 800px;
    margin: 50px auto;
}

.user-header {
    background-color: #333;
    color: #fff;
    padding: 10px;
    text-align: center;
}

.rating-section {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
}

.heading_rating {
    color: #333;
    text-align: center;
    margin-bottom: 20px;
    font-size: 20px;
}

.rating-form {
    text-align: center;
}

.rating-stars {
    display: flex;
    justify-content: center;
    flex-direction: row-reverse;
    margin-bottom: 20px;
}

.rating-stars input {
    display: none;
}

.rating-stars label {
    font-size: 30px;
    color: #ddd;
    cursor: pointer;
}

.rating-stars input:checked~label {
    color: #f8d64e;
}


label {
    display: block;
    margin-bottom: 5px;
    color: #555;
}



textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin: 0 -20px 15px -20px;
    resize: none;
    font-size: 20px;
}

.submit-btn {
    background-color: #333;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.submit-btn:hover {
    background-color: #555;
}

.reviews_buttons {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 10px;
}

#anonymousMessage {
    display: none;
}

#anonymousMessage,
.anonymousLabel {
    color: red;
    font-weight: bolder;
    font-size: 14px;
}
</style>

<body>
    <div class="container">
        <section class="rating-section">
            <h1 class="heading_rating">Product Rating</h1>

            <form action="" method="post" class="rating-form">
                <div class="rating-stars">
                    <input type="radio" name="rating" value="5" id="star1" required><label for="star1"><i
                            class="fas fa-star"></i></label>
                    <input type="radio" name="rating" value="4" id="star2"><label for="star2"><i
                            class="fas fa-star"></i></label>
                    <input type="radio" name="rating" value="3" id="star3"><label for="star3"><i
                            class="fas fa-star"></i></label>
                    <input type="radio" name="rating" value="2" id="star4"><label for="star4"><i
                            class="fas fa-star"></i></label>
                    <input type="radio" name="rating" value="1" id="star5"><label for="star5"><i
                            class="fas fa-star"></i></label>
                </div>
                <h2 for="comment">Leave a comment:</h2>
                <textarea name="comment" id="comment" rows="8" cols="100" required></textarea>
                <div class="reviews_buttons">
                    <div>
                        <input type="checkbox" name="anonymous" class="submit-btn" id="anonymous">
                        <label for="checkbox" class="anonymousLabel">Submit anonymously</label>
                        <span id="anonymousMessage">Your name will be anonymous.</span>
                    </div>
                    <input type="submit" value="Submit Rating" name="submit_rating" class="submit-btn">
                </div>
            </form>
        </section>
    </div>
    <?php include "./components/footer.php"; ?>
    <script>
    const checkbox = document.getElementById("anonymous");
    const anonymousMessage = document.getElementById("anonymousMessage");

    checkbox.addEventListener("change", function() {
        if (checkbox.checked) {
            anonymousMessage.style.display = "block";
        } else {
            anonymousMessage.style.display = "none";
        }
    });
    </script>
</body>

</html>