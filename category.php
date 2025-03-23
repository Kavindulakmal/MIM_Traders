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
   <title>Category</title>
   
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

   <h1 class="heading"></h1>

   <div class="box-container">

   <?php
     $category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
     $select_products = $conn->prepare("SELECT * FROM `products` WHERE category LIKE '%{$category}%'"); 
     $select_products->execute();
     if ($select_products->rowCount() > 0) {
        while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
           $original_price = $fetch_product['price'];
           $discount = $fetch_product['discount'];
           $new_price = $original_price;

           // Calculate discounted price
           if (isset($discount) && $discount > 0) {
              $new_price = $original_price - ($original_price * ($discount / 100));
           }
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_product['id'], ENT_QUOTES, 'UTF-8'); ?>">
      <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_product['name'], ENT_QUOTES, 'UTF-8'); ?>">
      <input type="hidden" name="price" value="<?= htmlspecialchars($new_price, ENT_QUOTES, 'UTF-8'); ?>">
      <input type="hidden" name="image" value="<?= htmlspecialchars($fetch_product['image_01'], ENT_QUOTES, 'UTF-8'); ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick_view.php?pid=<?= htmlspecialchars($fetch_product['id'], ENT_QUOTES, 'UTF-8'); ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= htmlspecialchars($fetch_product['image_01'], ENT_QUOTES, 'UTF-8'); ?>" alt="">
      <div class="name">
         <?= htmlspecialchars($fetch_product['name'], ENT_QUOTES, 'UTF-8'); ?>
         
         <?php if (isset($discount) && $discount > 0): ?>
            <mark class="discount"><?= htmlspecialchars($discount, ENT_QUOTES, 'UTF-8'); ?>% OFF</mark>
         <?php endif; ?>
      </div>
      <div class="flex">
         <div class="price">
            <!-- Original Price -->
            <s><span>LKR </span><?= htmlspecialchars($original_price, ENT_QUOTES, 'UTF-8'); ?><span>.00</span></s>
            <!-- New Price with Discount -->
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