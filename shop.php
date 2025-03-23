<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}

include 'components/wishlist_cart.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shop</title>
   
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
   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products">

   <h1 class="heading">ALL Products</h1>

   <div class="box-container">

   <?php
   $select_products = $conn->prepare("SELECT * FROM `products`"); 
   $select_products->execute();
   if ($select_products->rowCount() > 0) {
      while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
         $original_price = $fetch_product['price'];
         $discount = $fetch_product['discount'];
         $new_price = $original_price;

         
         if (isset($discount) && $discount > 0) {
            $new_price = $original_price - ($original_price * ($discount / 100));
         }
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $new_price; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
      <div class="name">
         <?= $fetch_product['name']; ?>
         
         <?php if (isset($discount) && $discount > 0): ?>
            <mark class="discount"><?= $discount; ?>% OFF</mark>
         <?php endif; ?>
      </div>
      <div class="flex">
         <div class="price">
            <!-- Original Price -->
            <s><span>LKR </span><?= $original_price; ?><span>.00</span></s><br>
            <!-- Price with Discount-->
            <?php if (isset($discount) && $discount > 0): ?>
               <span class="new-price"><span>LKR </span><?= number_format($new_price, 2); ?></span>
            <?php endif; ?>
         </div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="Add to Cart" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   } else {
      echo '<p class="empty">No products found!</p>';
   }
   ?>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>