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
   <title>Quick View</title>
   
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

      
      .related-products {
         margin-top: 20px;
         padding: 20px;
         background-color: #f9f9f9;
         border-radius: 10px;
      }

      .related-products h2 {
         font-size: 24px;
         margin-bottom: 15px;
      }

      .related-products .box-container {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
         gap: 15px;
      }

      .related-products .box {
         text-align: center;
         padding: 10px;
         background-color: #fff;
         border: 1px solid #ddd;
         border-radius: 5px;
         transition: transform 0.3s ease;
      }

      .related-products .box:hover {
         transform: scale(1.05);
      }

      .related-products .box img {
         width: 100px;
         height: 150px;
         object-fit: cover;
         border-radius: 5px;
      }

      .related-products .box .name {
         font-size: 14px;
         margin-top: 10px;
      }
   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="quick-view">

   <h1 class="heading"></h1>

   <?php
   $pid = $_GET['pid'];
   $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?"); 
   $select_products->execute([$pid]);
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
      <div class="row">
         <div class="image-container">
            <div class="main-image">
               <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
            </div>
            <div class="sub-image">
               <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
               <img src="uploaded_img/<?= $fetch_product['image_02']; ?>" alt="">
               <img src="uploaded_img/<?= $fetch_product['image_03']; ?>" alt="">
            </div>
         </div>
         <div class="content">
            <div class="name">
               <?= $fetch_product['name']; ?>
               
               <?php if (isset($discount) && $discount > 0): ?>
                  <mark class="discount"><?= $discount; ?>% OFF</mark>
               <?php endif; ?>
            </div>
            <div class="flex">
               <div class="price">
                  <!-- Original Price -->
                  <s><span>LKR </span><?= $original_price; ?><span>.00</span></s> <p>|</p>
                  <!-- New Price with Discount -->
                  <?php if (isset($discount) && $discount > 0): ?>
                     <span class="new-price"><span>LKR </span><?= number_format($new_price, 2); ?></span>
                  <?php endif; ?>
               </div>
               <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
            </div>
            <div class="details"><?= $fetch_product['details']; ?></div>
            <div class="flex-btn">
               <input type="submit" value="Add to Cart" class="btn" name="add_to_cart">
               <input class="option-btn" type="submit" name="add_to_wishlist" value="Add to Wishlist">
            </div>
         </div>
      </div>
   </form>

   <!-- Related Products -->
   <div class="related-products">
      <h2>Related Products</h2>
      <?php
         $category = $fetch_product['category'];
         $select_related_products = $conn->prepare("SELECT * FROM `products` WHERE category = ? AND name != ? LIMIT 4");
         $select_related_products->execute([$category, $pid]);

         if ($select_related_products->rowCount() > 0) {
            echo '<div class="box-container">';
            while ($fetch_related_product = $select_related_products->fetch(PDO::FETCH_ASSOC)) {
      ?>
               <a href="quick_view.php?pid=<?= $fetch_related_product['id']; ?>" class="box">
                  <img src="uploaded_img/<?= $fetch_related_product['image_01']; ?>" alt="">
                  <div class="name"><?= $fetch_related_product['name']; ?></div>
               </a>
      <?php
            }
            echo '</div>';
         } else {
            echo '<p class="empty">There are no related products.</p>';
         }
      ?>
   </div>

   <?php
      }
   } else {
      echo '<p class="empty">No products found!</p>';
   }
   ?>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>