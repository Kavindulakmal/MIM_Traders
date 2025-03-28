<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
   exit(); 
}

if (isset($_POST['add_product'])) {
   
   $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
   $details = filter_input(INPUT_POST, 'details', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $discount = filter_input(INPUT_POST, 'discount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

   
   $image_01 = htmlspecialchars($_FILES['image_01']['name'], ENT_QUOTES, 'UTF-8');
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = '../uploaded_img/' . $image_01;

   $image_02 = htmlspecialchars($_FILES['image_02']['name'], ENT_QUOTES, 'UTF-8');
   $image_size_02 = $_FILES['image_02']['size'];
   $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
   $image_folder_02 = '../uploaded_img/' . $image_02;

   $image_03 = htmlspecialchars($_FILES['image_03']['name'], ENT_QUOTES, 'UTF-8');
   $image_size_03 = $_FILES['image_03']['size'];
   $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
   $image_folder_03 = '../uploaded_img/' . $image_03;

   
   $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];

   function validate_image($file, $allowed_extensions) {
      $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
      if (!in_array($file_extension, $allowed_extensions)) {
         return false; 
      }
      if ($file['size'] > 2000000) { 
         return false; 
      }
      if ($file['error'] !== UPLOAD_ERR_OK) {
         return false; 
      }
      return true;
   }

   if (!validate_image($_FILES['image_01'], $allowed_extensions) || 
       !validate_image($_FILES['image_02'], $allowed_extensions) || 
       !validate_image($_FILES['image_03'], $allowed_extensions)) {
      $message[] = 'Invalid image file(s)!';
   } else {
      
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
      $select_products->execute([$name]);

      if ($select_products->rowCount() > 0) {
         $message[] = 'Product name already exists!';
      } else {
         
         $insert_products = $conn->prepare("INSERT INTO `products`(name, details, price, image_01, image_02, image_03, category, discount) VALUES(?,?,?,?,?,?,?,?)");
         $insert_products->execute([$name, $details, $price, $image_01, $image_02, $image_03, $category, $discount]);

         if ($insert_products) {
            
            if (move_uploaded_file($image_tmp_name_01, $image_folder_01) && 
                move_uploaded_file($image_tmp_name_02, $image_folder_02) && 
                move_uploaded_file($image_tmp_name_03, $image_folder_03)) {
               $message[] = 'New product added!';
            } else {
               $message[] = 'Failed to upload image(s)!';
            }
         }
      }
   }
}

if (isset($_GET['delete'])) {
   
   $delete_id = filter_input(INPUT_GET, 'delete', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);

   
   unlink('../uploaded_img/' . $fetch_delete_image['image_01']);
   unlink('../uploaded_img/' . $fetch_delete_image['image_02']);
   unlink('../uploaded_img/' . $fetch_delete_image['image_03']);

   
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);

   
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);

   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);

   header('location:products.php');
   exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="add-products">
   <h1 class="heading">Add Product</h1>
   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>Product Name (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="Enter product name" name="name">
         </div>
         <div class="inputBox">
            <span>Product Price (required)</span>
            <input type="number" min="0" class="box" required max="9999999999" placeholder="Enter product price" onkeypress="if(this.value.length == 10) return false;" name="price">
         </div>
         <div class="inputBox">
            <span>Product Discount</span>
            <input type="number" min="0" max="100" class="box" placeholder="Enter product discount" name="discount" required>
         </div>
         <div class="inputBox">
            <span>Image 01 (required)</span>
            <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
         </div>
         <div class="inputBox">
            <span>Image 02 (required)</span>
            <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
         </div>
         <div class="inputBox">
            <span>Image 03 (required)</span>
            <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
         </div>
         <div class="inputBox">
            <span>Product Details (required)</span>
            <textarea name="details" placeholder="Enter product details" class="box" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
         <div class="inputBox">
            <span>Category</span>
            <select name="category" class="box" required>
               <option value="">Select a category</option>
               <option value="paints">Paints</option>
               <option value="garden">Garden</option>
               <option value="plumbing">Plumbing</option>
               <option value="tools">Tools</option>
               <option value="electric">Electric</option>
               <option value="generators">Generators</option>
               <option value="furniture">Furniture</option>
               <option value="building">Building</option>
            </select>
         </div>
      </div>
      <input type="submit" value="Add Product" class="btn" name="add_product">
   </form>
</section>

<section class="show-products">
   <h1 class="heading">Products Added</h1>
   <div class="box-container">
      <?php
      $select_products = $conn->prepare("SELECT * FROM `products`");
      $select_products->execute();
      if ($select_products->rowCount() > 0) {
         while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
      ?>
            <div class="box">
               <img src="../uploaded_img/<?= htmlspecialchars($fetch_products['image_01'], ENT_QUOTES, 'UTF-8'); ?>" alt="">
               <div class="name"><?= htmlspecialchars($fetch_products['name'], ENT_QUOTES, 'UTF-8'); ?></div>
               <div class="price">LKR <span><?= htmlspecialchars($fetch_products['price'], ENT_QUOTES, 'UTF-8'); ?></span>/-</div>
               <div class="details"><span><?= htmlspecialchars($fetch_products['details'], ENT_QUOTES, 'UTF-8'); ?></span></div>
               <div class="category"><span><?= htmlspecialchars($fetch_products['category'], ENT_QUOTES, 'UTF-8'); ?></span></div>
               <div class="flex-btn">
                  <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Update</a>
                  <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?');">Delete</a>
               </div>
            </div>
      <?php
         }
      } else {
         echo '<p class="empty">No products added yet!</p>';
      }
      ?>
   </div>
</section>

<script src="../js/admin_script.js"></script>
</body>
</html>