<?php

if (isset($_POST['add_to_wishlist'])) {

   if ($user_id == '') {
      header('location:user_login.php');
      exit(); 
   } else {

      
      $pid = filter_input(INPUT_POST, 'pid', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      $image = filter_input(INPUT_POST, 'image', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

      // Check if the product is already in the wishlist
      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$name, $user_id]);

      // Check if the product is already in the cart
      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check_cart_numbers->execute([$name, $user_id]);

      if ($check_wishlist_numbers->rowCount() > 0) {
         $message[] = 'Already added to wishlist!';
      } elseif ($check_cart_numbers->rowCount() > 0) {
         $message[] = 'Already added to cart!';
      } else {
         // Insert the product into the wishlist
         $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
         $insert_wishlist->execute([$user_id, $pid, $name, $price, $image]);
         $message[] = 'Added to wishlist!';
      }
   }
}

if (isset($_POST['add_to_cart'])) {

   if ($user_id == '') {
      header('location:user_login.php');
      exit(); 
   } else {

      // Sanitize inputs
      $pid = filter_input(INPUT_POST, 'pid', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      $image = filter_input(INPUT_POST, 'image', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $qty = filter_input(INPUT_POST, 'qty', FILTER_SANITIZE_NUMBER_INT);

      // Check if the product is already in the cart
      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check_cart_numbers->execute([$name, $user_id]);

      if ($check_cart_numbers->rowCount() > 0) {
         $message[] = 'Already added to cart!';
      } else {

         // Check if the product is in the wishlist and remove it
         $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
         $check_wishlist_numbers->execute([$name, $user_id]);

         if ($check_wishlist_numbers->rowCount() > 0) {
            $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
            $delete_wishlist->execute([$name, $user_id]);
         }

         // Insert the product into the cart
         $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
         $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $image]);
         $message[] = 'Added to cart!';
      }
   }
}

?>