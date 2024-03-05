<?php

if (isset($_POST['add_to_wishlist'])) {
    if ($user_id == '') {
        header('location:user_login.php');
    } else {
        $pid = $_POST['pid'];
        $pid = filter_var($pid, FILTER_SANITIZE_STRING);

        $check_wishlist_numbers = $conn->prepare("
            SELECT * FROM wishlist
            WHERE pid = ? AND user_id = ?
        ");
        $check_wishlist_numbers->execute([$pid, $user_id]);
        $wishlist_result = $check_wishlist_numbers->fetchAll(PDO::FETCH_ASSOC);

        $check_cart_numbers = $conn->prepare("
            SELECT * FROM cart
            WHERE pid = ? AND user_id = ?
        ");
        $check_cart_numbers->execute([$pid, $user_id]);
        $cart_result = $check_cart_numbers->fetchAll(PDO::FETCH_ASSOC);

        if ($wishlist_result) {
            $message[] = 'Already added to wishlist!';
        } elseif ($cart_result) {
            $message[] = 'Already added to cart!';
        } else {
            $insert_wishlist = $conn->prepare("INSERT INTO `wishlist` (user_id, pid) VALUES (?, ?)");
            $insert_wishlist->execute([$user_id, $pid]);
            $message[] = 'Added to wishlist!';
        }
    }
}

if (isset($_POST['add_to_cart'])) {
    if ($user_id == '') {
        header('location:user_login.php');
    } else {
        $pid = $_POST['pid'];
        $pid = filter_var($pid, FILTER_SANITIZE_STRING);
        $qty = $_POST['qty'];
        $qty = filter_var($qty, FILTER_SANITIZE_STRING);

        $check_cart_numbers = $conn->prepare("
            SELECT * FROM cart
            WHERE pid = ? AND user_id = ?
        ");
        $check_cart_numbers->execute([$pid, $user_id]);

        if ($check_cart_numbers->rowCount() > 0) {
            $message[] = 'Already added to cart!';
        } else {
            $check_wishlist_numbers = $conn->prepare("
                SELECT * FROM wishlist
                WHERE pid = ? AND user_id = ?
            ");
            $check_wishlist_numbers->execute([$pid, $user_id]);

            if ($check_wishlist_numbers->rowCount() > 0) {
                $delete_wishlist = $conn->prepare("
                    DELETE FROM `wishlist`
                    WHERE pid = ? AND user_id = ?
                ");
                $delete_wishlist->execute([$pid, $user_id]);
            }
          
            $stock = $conn->prepare("
                SELECT * FROM products
                WHERE id = ?");
            $stock->execute([$pid]);
            $stock_result = $stock->fetch(PDO::FETCH_ASSOC);
            $stock = $stock_result['P_quantity'];
            if($stock < $qty){
                $message[] = 'Not enough stock!';
                if($stock == 0){
                    $message[] = 'Out of stock!';
                }
                else{
                    $message[] = 'Only '.$stock.' items left in stock!';
                }
            }   
            else{
                
                $insert_cart = $conn->prepare("INSERT INTO `cart` (user_id, pid, quantity) VALUES (?, ?, ?)");
                $insert_cart->execute([$user_id, $pid, $qty]);
                $message[] = 'Added to cart!';
                $decrease_stock = $conn->prepare("UPDATE products SET P_quantity = P_quantity - ? WHERE id = ?");
                $decrease_stock->execute([$qty, $pid]);
            }

            // $insert_cart = $conn->prepare("INSERT INTO `cart` (user_id, pid, quantity) VALUES (?, ?, ?)");
            // $insert_cart->execute([$user_id, $pid, $qty]);
            // $message[] = 'Added to cart!';
        }
    }
}

?>