<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/support.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<div class="home-bg">

<section class="home">

   <div class="swiper home-slider">
   
   <div class="swiper-wrapper">

      <div class="swiper-slide slide">
         <div class="image">
            <img src="catimg/generator.png" alt="">
         </div>
         <div class="content">
            <span>upto 15% off</span>
            <h3>ASHOK LEYLAND 10 kVA 75L Diesel</h3>
            <a href="shop.php" class="btn">shop now</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="catimg/ajamtha.png" alt="">
         </div>
         <div class="content">
            <span>upto 20% off</span>
            <h3>High Quality Ajamtha Brick Blocks</h3>
            <a href="shop.php" class="btn">shop now</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="catimg/grill.png" alt="">
         </div>
         <div class="content">
            <span>upto 50% off</span>
            <h3>Bosch Impact Drill GSB 16RE</h3>
            <a href="shop.php" class="btn">shop now</a>
         </div>
      </div>

   </div>

      <div class="swiper-pagination"></div>

   </div>

</section>

</div>

<section class="category">

   <h1 class="heading">shop by category</h1>

   <div class="swiper category-slider">

   <div class="swiper-wrapper">

   <a href="category.php?category=Paints" class="swiper-slide slide">
      <img src="catimg/icon-1.png" alt="">
      <h3>Paints</h3>
   </a>

   <a href="category.php?category=Garden" class="swiper-slide slide">
      <img src="catimg/icon-2.png" alt="">
      <h3>Garden</h3>
   </a>

   <a href="category.php?category=Plumbing" class="swiper-slide slide">
      <img src="catimg/icon-3.png" alt="">
      <h3>Plumbing</h3>
   </a>

   <a href="category.php?category=building" class="swiper-slide slide">
      <img src="catimg/icon-4.png" alt="">
      <h3>Buildng Material</h3>
   </a>

   <a href="category.php?category=Tools" class="swiper-slide slide">
      <img src="catimg/icon-5.png" alt="">
      <h3>Tools</h3>
   </a>

   <a href="category.php?category=electric" class="swiper-slide slide">
      <img src="catimg/icon-6.png" alt="">
      <h3>Electrical Supplies</h3>
   </a>

   <a href="category.php?category=generators" class="swiper-slide slide">
      <img src="catimg/icon-7.png" alt="">
      <h3>Generators</h3>
   </a>

   <a href="category.php?category=furniture" class="swiper-slide slide">
      <img src="catimg/icon-8.png" alt="">
      <h3>Furniture</h3>
   </a>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<section class="home-products">

   <h1 class="heading">latest products</h1>

   <div class="swiper products-slider">

   <div class="swiper-wrapper">

   <?php
     $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 2"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>

   <form action="" method="post" class="swiper-slide slide">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_product['name']; ?></div>
      <div class="flex">
         <div class="price"><span>LKR </span><?= $fetch_product['price']; ?><span>.00</span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<section>
   <!-- Support Button -->
   <button class="support-btn" onclick="toggleSupportBox()">
            <img src="https://img.icons8.com/ios-filled/50/ffffff/help.png" alt="Help Icon">
            Support
   </button>
   <!-- Support Box -->
   <div class="support-box" id="supportBox">
        <h3>How can we help you?</h3>
        <div class="faq-item" onclick="toggleAnswer(this)">
            What are your store hours?
            <div class="faq-answer">Our online store is open 24/7, so you can place orders anytime!
            For deliveries, we operate from Monday to Friday, and orders are typically delivered within 3 
            to 5 business days (excluding weekends and public holidays).
            If you have any urgent inquiries, feel free to contact our support team! 😊</div>
        </div>
        <div class="faq-item" onclick="toggleAnswer(this)">
            Do you offer home delivery?
            <div class="faq-answer">Yes! We provide home delivery island-wide. Delivery charges may apply based on the location and order size.</div>
        </div>
        <div class="faq-item" onclick="toggleAnswer(this)">
            What payment methods do you accept?
            <div class="faq-answer">We accept cash on delivery, credit/debit cards and paypal.</div>
        </div>
        <div class="faq-item" onclick="toggleAnswer(this)">
            Do you provide bulk order discounts?
            <div class="faq-answer">Yes! We offer special discounts on bulk purchases. Please contact us at tel: 0112345678 for more details.</div>
        </div>
    </div>
</section>









<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".home-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
    },
});

 var swiper = new Swiper(".category-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
         slidesPerView: 2,
       },
      650: {
        slidesPerView: 3,
      },
      768: {
        slidesPerView: 4,
      },
      1024: {
        slidesPerView: 5,
      },
   },
});

var swiper = new Swiper(".products-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      550: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      },
   },
});


const supportBox = document.getElementById('supportBox');

        // Function to toggle the support box visibility
        function toggleSupportBox() {
            if (supportBox.style.display === 'none' || supportBox.style.display === '') {
                supportBox.style.display = 'flex';
            } else {
                supportBox.style.display = 'none';
            }
        }

        // Function to toggle FAQ answers
        function toggleAnswer(faqItem) {
            const answer = faqItem.querySelector('.faq-answer');
            if (answer.style.display === 'none' || answer.style.display === '') {
                answer.style.display = 'block';
            } else {
                answer.style.display = 'none';
            }
        }

      var homeSwiper = new Swiper(".home-slider", {
      loop: true, 
      spaceBetween: 20, 
      pagination: {
         el: ".swiper-pagination", 
         clickable: true, 
      },
      autoplay: {
         delay: 3000, 
         disableOnInteraction: false, 
      },

   });

</script>

</body>
</html>