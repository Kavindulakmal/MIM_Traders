<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <section class="flex">

      <a href="home.php" class="logo">
         <img src="images/logo.jpg" alt="MIM | Traders">
      </a>

      <nav class="navbar">
         <a href="home.php">HOME</a>
         <a href="shop.php">SHOP</a>
         <a href="orders.php">ORDERS</a>
         <a href="about.php">ABOUT</a>
         <a href="contact.php">CONTACT</a>
         <div class="categories-dropdown">
            <select id="categories" onchange="redirectToCategory()">
               <option value="" selected disabled>CATEGORIES</option>
               <option value="Paints">Paints</option>
               <option value="Garden">Garden</option>
               <option value="Plumbing">Plumbing</option>
               <option value="Tools">Tools</option>
               <option value="Electric">Electric</option>
               <option value="Generators">Generators</option>
               <option value="Furniture">Furniture</option>
               <option value="Building">Building</option>
            </select>
         </div>
      </nav>

      <div class="icons">
         <?php
         $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
         $count_wishlist_items->execute([$user_id]);
         $total_wishlist_counts = $count_wishlist_items->rowCount();

         $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $count_cart_items->execute([$user_id]);
         $total_cart_counts = $count_cart_items->rowCount();
         ?>
         <div id="menu-btn" class="fas fa-bars"></div>
         <a href="search_page.php"><i class="fas fa-search"></i></a>
         <a href="wishlist.php"><i class="fas fa-heart"></i><span>(<?= $total_wishlist_counts; ?>)</span></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_counts; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
         $select_profile->execute([$user_id]);
         if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
            <p><?= $fetch_profile["name"]; ?></p>
            <a href="update_user.php" class="btn">update profile</a>
            <div class="flex-btn">
               <a href="user_register.php" class="option-btn">register</a>
               <a href="user_login.php" class="option-btn">login</a>
            </div>
            <a href="components/user_logout.php" class="delete-btn" onclick="return confirm('logout from the website?');">logout</a>
         <?php
         } else {
         ?>
            <p>please login or register first!</p>
            <div class="flex-btn">
               <a href="user_register.php" class="option-btn">register</a>
               <a href="user_login.php" class="option-btn">login</a>
            </div>
         <?php
         }
         ?>
      </div>

   </section>

</header>

<script>
   
   function redirectToCategory() {
      const select = document.getElementById('categories');
      const selectedCategory = select.value;
      if (selectedCategory) {
         window.location.href = `category.php?category=${selectedCategory}`;
      }
   }

   
   document.querySelector('#menu-btn').onclick = () => {
      document.querySelector('.navbar').classList.toggle('active');
      document.querySelector('.profile').classList.remove('active');
   }

   document.querySelector('#user-btn').onclick = () => {
      document.querySelector('.profile').classList.toggle('active');
      document.querySelector('.navbar').classList.remove('active');
   }

   
   window.onscroll = () => {
      document.querySelector('.navbar').classList.remove('active');
      document.querySelector('.profile').classList.remove('active');
   }
</script>

<style>
   /* Additional CSS for the categories dropdown box */
   .categories-dropdown {
      display: inline-block;
      position: relative;
   }

   .categories-dropdown select {
      background-color: transparent;
      color: var(--black);
      padding: 10px;
      font-size: 20px;
      border: none;
      cursor: pointer;
      appearance: none; 
      -webkit-appearance: none; 
      -moz-appearance: none; 
   }

   .categories-dropdown::after {
      content: '\f0d7'; 
      font-family: 'Font Awesome 5 Free';
      font-weight: 900;
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      pointer-events: none; 
      color: var(--black);
   }

   .categories-dropdown:hover select {
      background-color: var(--white);
   }

   
   .profile {
      position: absolute;
      top: 120%;
      right: 2rem;
      background-color: var(--white);
      border-radius: .5rem;
      box-shadow: var(--box-shadow);
      border: var(--border);
      padding: 2rem;
      width: 30rem;
      display: none;
      z-index: 1000;
   }

   .profile.active {
      display: block;
   }
</style>