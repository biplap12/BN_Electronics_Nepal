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
   }

$select_messages = $conn->prepare("SELECT sent_msg.admin_reply FROM sent_msg WHERE user_id = ?");
$select_messages->execute([$user_id]); // Assuming $user_id is the user ID you want to filter by
$number_of_messages = $select_messages->rowCount();
?>
<header class="header">
    <section class="flex">
        <a href="home.php" class="logo">BN Electronic Nepal</a>
        <nav class="navbar">
            <a href="home.php">Home</a>
            <a href="about.php">About</a>
            <a href="orders.php">Orders</a>
            <a href="shop.php">Shop</a>
            <a href="contact.php">Contact</a>
            <?php 
if ($user_id) {
    echo '<a href="inbox.php">Messages<span' . (($number_of_messages >= 1) ? ' id="message_counter"' : ' id="message_counter_remove"') . '>'
        . (($number_of_messages >= 1) ? $number_of_messages : '') . '</span></a>';
} else {
    echo "";
}
?>

        </nav>
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php"><i class="fas fa-search"></i>Search</a>
            <?php
            $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
            $count_wishlist_items->execute([$user_id]);
            $total_wishlist_counts = $count_wishlist_items->rowCount();

            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_counts = $count_cart_items->rowCount();
            if($user_id===""){
                echo'<a href="user_login.php"><i class="fas fa-heart"></i>Wishlist<span></a>';
                echo'<a href="user_login.php"><i class="fas fa-shopping-cart"></i>Cart<span></a>';
                echo'<div id="user-btn" class="fas fa-user">';
                echo '<span>Login</span>';
                echo '</div>';
            }else{
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if ($select_profile->rowCount() > 0){
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                ?>
            <a href="wishlist.php">
                <i class="fas fa-heart"></i> Wishlist<span
                    <?= ($total_wishlist_counts >= 1) ? 'id="message_counter"' : 'id="message_counter_remove"'; ?>>
                    <?= ($total_wishlist_counts >= 1) ? $total_wishlist_counts: ''; ?>
                </span>
            </a>
            &nbsp;&nbsp;
            <a href="cart.php"><i class="fas fa-shopping-cart"></i>Cart<span
                    <?= ($total_cart_counts >= 1) ? 'id="message_counter"' : 'id="message_counter_remove"'; ?>>
                    <?= ($total_cart_counts >= 1) ? $total_cart_counts: ''; ?>
                </span></a>
            &nbsp;&nbsp;
            <div id="user-btn" class="fas fa-user">
                <span> <?= $fetch_profile["name"]; ?></span>
            </div>
            <?php }} ?>
        </div>

        <div class="profile">
            <?php
       
       $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
       $select_profile->execute([$user_id]);
       if ($select_profile->rowCount() > 0){
           $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <a href="update_user.php" class="btn">Update profile</a>
            <div class="flex-btn">
                <!-- <a href="mail.php" class="option-btn">Message</a> -->
                <a href="user_profile_view.php?view_id=<?=$user_id?>" class="option-btn">View</a>
            </div>
            <a href="components/user_logout.php" class="delete-btn"
                onclick="return confirm('Logout from the website?');">Logout</a>
            <?php
            }else{
         ?>
            <p>Please login or register first!</p>
            <div class="flex-btn">
                <a href="user_login.php" class="option-btn">Login</a>
                <a href="user_register.php" class="option-btn">Register</a>
                <a href="http://localhost/BN_Electronics_Nepal/admin/admin_login.php"
                    target="_blank" rel="noopener noreferrer">.</a>
            </div>
            <?php
            }
         ?>


        </div>

    </section>

</header>
<!-- <script>
// Disable right click
document.addEventListener('contextmenu', (e) => e.preventDefault());

function ctrlShiftKey(e, keyCode) {
    return e.ctrlKey && e.shiftKey && e.keyCode === keyCode.charCodeAt(0);
}

document.onkeydown = (e) => {
    // Disable F12, Ctrl + Shift + I, Ctrl + Shift + J, Ctrl + U
    if (
        event.keyCode === 123 ||
        ctrlShiftKey(e, 'I') ||
        ctrlShiftKey(e, 'J') ||
        ctrlShiftKey(e, 'C') ||
        (e.ctrlKey && e.keyCode === 'U'.charCodeAt(0))
    )
        return false;
};
</script> -->