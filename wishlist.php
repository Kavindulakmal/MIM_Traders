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

include 'components/wishlist_cart.php';

// Delete a single wishlist item
if (isset($_POST['delete'])) {
   $wishlist_id = filter_input(INPUT_POST, 'wishlist_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
   $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE id = ?");
   $delete_wishlist_item->execute([$wishlist_id]);
}

// Delete all wishlist items
if (isset($_GET['delete_all'])) {
   $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
   $delete_wishlist_item->execute([$user_id]);
   header('location:wishlist.php');
   exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Wishlist</title>
   
   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="css/style.css">

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
   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products">

   <h3 class="heading">Your Wishlist</h3>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
      $select_wishlist->execute([$user_id]);
      if ($select_wishlist->rowCount() > 0) {
         while ($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)) {
            
            $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
            $select_product->execute([$fetch_wishlist['pid']]);
            $fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);

            $original_price = $fetch_product['price'];
            $discount = $fetch_product['discount'];
            $new_price = $original_price;

            // Calculate discounted price
            if (isset($discount) && $discount > 0) {
               $new_price = $original_price - ($original_price * ($discount / 100));
            }

            // Add to grand total
            $grand_total += $new_price;
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_wishlist['pid'], ENT_QUOTES, 'UTF-8'); ?>">
      <input type="hidden" name="wishlist_id" value="<?= htmlspecialchars($fetch_wishlist['id'], ENT_QUOTES, 'UTF-8'); ?>">
      <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_wishlist['name'], ENT_QUOTES, 'UTF-8'); ?>">
      <input type="hidden" name="price" value="<?= htmlspecialchars($new_price, ENT_QUOTES, 'UTF-8'); ?>">
      <input type="hidden" name="image" value="<?= htmlspecialchars($fetch_wishlist['image'], ENT_QUOTES, 'UTF-8'); ?>">
      <a href="quick_view.php?pid=<?= htmlspecialchars($fetch_wishlist['pid'], ENT_QUOTES, 'UTF-8'); ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= htmlspecialchars($fetch_wishlist['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="">
      <div class="name">
         <?= htmlspecialchars($fetch_wishlist['name'], ENT_QUOTES, 'UTF-8'); ?>

         <?php if (isset($discount) && $discount > 0): ?>
            <mark class="discount"><?= htmlspecialchars($discount, ENT_QUOTES, 'UTF-8'); ?>% OFF</mark>
         <?php endif; ?>
      </div>
      <div class="flex">
         <div class="price">
            <!-- Original Price -->
            <s><span>LKR </span><?= htmlspecialchars($original_price, ENT_QUOTES, 'UTF-8'); ?><span>.00</span></s>
            <!-- Price with Discount -->
            <?php if (isset($discount) && $discount > 0): ?>
               <span class="new-price"><span>LKR </span><?= number_format($new_price, 2); ?></span>
            <?php endif; ?>
         </div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="Add to Cart" class="btn" name="add_to_cart">
      <input type="submit" value="Delete Item" onclick="return confirm('Delete this from wishlist?');" class="delete-btn" name="delete">
   </form>
   <?php
         }
      } else {
         echo '<p class="empty">Your wishlist is empty</p>';
      }
   ?>
   </div>

   <div class="wishlist-total">
      <p>Grand Total : <span>LKR <?= number_format($grand_total, 2); ?></span></p>
      <a href="shop.php" class="option-btn">Continue Shopping</a>
      <a href="wishlist.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('Delete all from wishlist?');">Delete All Items</a>
   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>