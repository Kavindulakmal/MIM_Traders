<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:user_login.php');
}

if (isset($_POST['delete'])) {
   $cart_id = $_POST['cart_id'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
}

if (isset($_GET['delete_all'])) {
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   header('location:cart.php');
}

if (isset($_POST['update_qty'])) {
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);
   $message[] = 'Cart quantity updated';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping Cart</title>
   
   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/discount.css">

   <style>
      
      mark.discount {
         background-color: red;
         color: black;
         padding: 2px 5px;
         border-radius: 3px;
         font-size: 14px;
         margin-left: 10px; 
      }

      
      .price {
         display: flex;
         align-items: center;
         gap: 10px; 
      }

      .price s {
         color: #888; 
      }

      .price .new-price {
         color: #333; 
         font-weight: bold;
      }

      
      .congrats-message {
         color: green;
         font-weight: bold;
         margin-top: 10px;
      }
   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products shopping-cart">

   <h3 class="heading">Shopping Cart</h3>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart->execute([$user_id]);
      if ($select_cart->rowCount() > 0) {
         while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
            
            $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
            $select_product->execute([$fetch_cart['pid']]);
            $fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);

            $original_price = $fetch_product['price'];
            $discount = $fetch_product['discount'];
            $new_price = $original_price;

            
            if (isset($discount) && $discount > 0) {
               $new_price = $original_price - ($original_price * ($discount / 100));
            }

            
            $sub_total = $new_price * $fetch_cart['quantity'];
            $grand_total += $sub_total;
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
      <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
      <div class="name">
         <?= $fetch_cart['name']; ?>
         
         <?php if (isset($discount) && $discount > 0): ?>
            <mark class="discount"><?= $discount; ?>% OFF</mark>
         <?php endif; ?>
      </div>
      <div class="flex">
         <div class="price">
            
            <s><span>LKR </span><?= $original_price; ?><span>.00</span></s>
            
            <?php if (isset($discount) && $discount > 0): ?>
               <span class="new-price"><span>LKR </span><?= number_format($new_price, 2); ?></span>
            <?php endif; ?>
         </div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="<?= $fetch_cart['quantity']; ?>">
         <button type="submit" class="fas fa-edit" name="update_qty"></button>
      </div>
      <div class="sub-total">Sub Total : <span>LKR <?= number_format($sub_total, 2); ?></span></div>
      <input type="submit" value="Delete Item" onclick="return confirm('Delete this from cart?');" class="delete-btn" name="delete">
   </form>
   <?php
         }
      } else {
         echo '<p class="empty">Your cart is empty</p>';
      }
   ?>
   </div>

   <?php
      
      $discount_applied = false;
      $discounted_grand_total = $grand_total;
      if ($grand_total > 2000000) {
         $discounted_grand_total = $grand_total - ($grand_total * 0.10);
         $discount_applied = true;
      }
   ?>

   <div class="cart-total">
      <p>Total : <span>LKR <?= number_format($discounted_grand_total, 2); ?></span></p>
      <?php if ($discount_applied): ?>
         <p class="congrats-message">Congratulations! You have received a 10% discount on your order.</p>
      <?php endif; ?>
      <a href="shop.php" class="option-btn">Continue Shopping</a>
      <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('Delete all from cart?');">Delete All Items</a>
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">Proceed to Checkout</a>
   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>