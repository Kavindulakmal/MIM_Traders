<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:user_login.php');
   exit();  
}

if (isset($_POST['order'])) {
   // Sanitize inputs
   $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $number = filter_input(INPUT_POST, 'number', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL); 
   $method = filter_input(INPUT_POST, 'method', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $address = ' ' . filter_input(INPUT_POST, 'flat', FILTER_SANITIZE_FULL_SPECIAL_CHARS) . ', ' .
              filter_input(INPUT_POST, 'street', FILTER_SANITIZE_FULL_SPECIAL_CHARS) . ', ' .
              filter_input(INPUT_POST, 'city', FILTER_SANITIZE_FULL_SPECIAL_CHARS) . ', ' .
              filter_input(INPUT_POST, 'state', FILTER_SANITIZE_FULL_SPECIAL_CHARS) . ', ' .
              filter_input(INPUT_POST, 'country', FILTER_SANITIZE_FULL_SPECIAL_CHARS) . ' - ' .
              filter_input(INPUT_POST, 'pin_code', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $total_products = filter_input(INPUT_POST, 'total_products', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $total_price = filter_input(INPUT_POST, 'total_price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

   
   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if ($check_cart->rowCount() > 0) {
      
      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

      
      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      $message[] = 'Order placed successfully!';
   } else {
      $message[] = 'Your cart is empty!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>
   
   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      .congrats-message {
         color: green;
         font-weight: bold;
         margin-top: 10px;
      }
   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="checkout-orders">
   <form action="" method="POST">
      <h3>Your Orders</h3>

      <div class="display-orders">
         <?php
         $grand_total = 0;
         $cart_items = [];
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if ($select_cart->rowCount() > 0) {
            while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
               $cart_items[] = $fetch_cart['name'] . ' (' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ') - ';
               $total_products = implode($cart_items);
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
         ?>
               <p><?= htmlspecialchars($fetch_cart['name'], ENT_QUOTES, 'UTF-8'); ?> <span>(<?= 'LKR ' . htmlspecialchars($fetch_cart['price'], ENT_QUOTES, 'UTF-8') . '.00 x ' . htmlspecialchars($fetch_cart['quantity'], ENT_QUOTES, 'UTF-8'); ?>)</span></p>
         <?php
            }
         } else {
            echo '<p class="empty">Your cart is empty!</p>';
         }

         // Apply discount if Grand Total > 2,000,000
         $discount_applied = false;
         $discounted_grand_total = $grand_total;
         if ($grand_total > 2000000) {
            $discounted_grand_total = $grand_total - ($grand_total * 0.10);
            $discount_applied = true;
         }
         ?>
         <input type="hidden" name="total_products" value="<?= htmlspecialchars($total_products, ENT_QUOTES, 'UTF-8'); ?>">
         <input type="hidden" name="total_price" value="<?= htmlspecialchars($discounted_grand_total, ENT_QUOTES, 'UTF-8'); ?>">
         <div class="grand-total">Grand Total : <span>LKR <?= number_format($discounted_grand_total, 2); ?></span></div>
         <?php if ($discount_applied): ?>
            <p class="congrats-message">Congratulations! You have received a 10% discount on your order.</p>
         <?php endif; ?>
      </div>

      <h3>Place Your Orders</h3>

      <div class="flex">
         <div class="inputBox">
            <span>Name</span>
            <input type="text" name="name" placeholder="Enter your name" class="box" maxlength="200" required>
         </div>
         <div class="inputBox">
            <span>Contact Number</span>
            <input type="number" name="number" placeholder="Enter your number" class="box" min="0" max="9999999999" onkeypress="if(this.value.length == 10) return false;" required>
         </div>
         <div class="inputBox">
            <span>Email</span>
            <input type="email" name="email" placeholder="Enter your email" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Payment Method:</span>
            <select name="method" class="box" required>
               <option value="cash on delivery">Cash on Delivery</option>
               <option value="credit card">Credit Card</option>
               <option value="paypal">PayPal</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Address Line 01:</span>
            <input type="text" name="flat" placeholder="Your Street" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Address Line 02:</span>
            <input type="text" name="street" placeholder="Your Street 02" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>City:</span>
            <input type="text" name="city" placeholder="Your City" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>State:</span>
            <input type="text" name="state" placeholder="Your Province" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Country:</span>
            <input type="text" name="country" placeholder="Country" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Postal Code</span>
            <input type="number" min="0" name="pin_code" placeholder="Postal Code" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;" class="box" required>
         </div>
      </div>

      <input type="submit" name="order" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>" value="Place Order">
   </form>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
</body>
</html>