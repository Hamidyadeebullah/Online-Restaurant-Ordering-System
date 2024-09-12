<?php
session_start();
require('dbconnection.php');


// Fetch food previews from 'popular_dishes'
$sql1 = "SELECT * FROM popular_dishes";
$preview_result = $conn->query($sql1);

$food_previews = [];
if ($preview_result->num_rows > 0) {
    while ($row = $preview_result->fetch_assoc()) {
        $food_previews[] = $row;
    }
} else {
    echo "No food previews found in the database.";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Restaurant Website</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

   <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery-js/1.4.0/css/lightgallery.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="login_container.css">
</head>
<body>
   
<!-- header section starts     -->

<header class="header">

   <section class="flex">

      <a href="index.php" class="logo"> <i class="fas fa-utensils"></i> food. </a>

      <nav class="navbar">
         <a href="#home">home</a>
         <a href="#about">about</a>
         <a href="#food">food</a>
         <a href="#gallery">gallery</a>
         <a href="#menu">menu</a>
         <a href="#order">order</a>
         <a href="#blogs">blogs</a>
      </nav>
   
      <i id="menu-btn" class="fas fa-bars"></i>
      <i id="login-btn" class="fas fa-user"></i>
   </section>

</header>

<!-- header section ends    -->

<!-- Login Container -->
<div class="login-container">
    <div class="login-box">
        <span id="close-log" class="close-btn">&times;</span>
        <form action="login.php" method="post">
            <h2>Login</h2>
            <div class="input-box">
                <span class="icon"><i class="fas fa-user"></i></span>
                <input type="email" name="email" required>
                <label>Email</label>
            </div>
            <div class="input-box">
                <span class="icon"><i class="fas fa-lock"></i></span>
                <input type="password" name="password" required>
                <label>Password</label>
            </div>
            <button type="submit" class="btn">Login</button>
            <p>Don't have an account? <a href="registeration.php">Register now</a></p>
        </form>
    </div>
</div>
<script>
       document.getElementById('login-btn').addEventListener('click', function() {
        document.querySelector('.login-container').style.display = 'flex';
    });
   </script>
<!-- home section starts  -->

<div class="home" id="home">

   <div class="swiper home-slider">

      <div class="swiper-wrapper">

         <div class="swiper-slide slide" style="background: url(images/home-slide-1.jpg) no-repeat;">
            <div class="content">
               <span>outstanding food</span>
               <h3>delicious cooking</h3>
               <a href="#" class="btn">get started</a>
            </div>
         </div>

         <div class="swiper-slide slide" style="background: url(images/home-slide-2.jpg) no-repeat;">
            <div class="content">
               <span>outstanding food</span>
               <h3>morning moment</h3>
               <a href="#" class="btn">get started</a>
            </div>
         </div>

         <div class="swiper-slide slide" style="background: url(images/home-slide-3.jpg) no-repeat;">
            <div class="content">
               <span>outstanding food</span>
               <h3>authentic kitchen</h3>
               <a href="#" class="btn">get started</a>
            </div>
         </div>

      </div>

      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>

   </div>

</div>

<!-- home section ends -->

<!-- about section starts  -->

<section class="about" id="about">

   <div class="image">
      <img src="images/about-img.png" alt="">
   </div>

   <div class="content">
      <h3 class="title">welcome to our restaurant</h3>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi optio at, saepe accusamus dolorum, quos eos nesciunt amet exercitationem illum quis nostrum, repellat quaerat eum debitis fugit alias magnam omnis!</p>
      <a href="#" class="btn">read more</a>
      <div class="icons-container">
         <div class="icons">
            <img src="images/about-icon-1.png" alt="">
            <h3>quality food</h3>
         </div>
         <div class="icons">
            <img src="images/about-icon-2.png" alt="">
            <h3>food & drinks</h3>
         </div>
         <div class="icons">
            <img src="images/about-icon-3.png" alt="">
            <h3>expert chefs</h3>
         </div>
      </div>
   </div>

</section>

<!-- about section ends -->

<!-- Food Section Starts -->
<div class="odd-section">
    <section class="food" id="food">

        <div class="heading">  
            <span>Popular Dishes</span>
            <h3>Our Delicious Food</h3>
        </div>
    
        <div class="swiper food-slider">
    
            <div class="swiper-wrapper">
                <?php foreach ($food_previews as $index => $dish): ?>
                <article class="swiper-slide slide" data-name="food-<?php echo $index + 1; ?>" aria-labelledby="dish-title-<?php echo htmlspecialchars($dish['id']); ?>">
                    <figure>
                        <img src="<?php echo htmlspecialchars($dish['image']); ?>" alt="<?php echo htmlspecialchars($dish['name']); ?>" class="dish-image">
                        <figcaption>
                            <h3 id="dish-title-<?php echo htmlspecialchars($dish['id']); ?>"><?php echo htmlspecialchars($dish['name']); ?></h3>
                            <div class="price">&euro;<?php echo htmlspecialchars($dish['price']); ?></div>
                        </figcaption>
                    </figure>
                    <div class="order-controls">
                        <input type="hidden" name="dish_id" value="<?php echo htmlspecialchars($dish['id']); ?>">
                        
                        <label for="quantity-<?php echo htmlspecialchars($dish['id']); ?>" class="visually-hidden">
                            Quantity for <?php echo htmlspecialchars($dish['name']); ?>
                        </label>
                        <input 
                            type="number" 
                            id="quantity-<?php echo htmlspecialchars($dish['id']); ?>" 
                            name="quantity" 
                            value="1" 
                            min="1" 
                            max="10" 
                            class="quantity-input" 
                            oninput="limitQuantity(this)"
                            aria-label="Quantity for <?php echo htmlspecialchars($dish['name']); ?>"
                        >
                        <button 
                            type="button" 
                            class="btn add-to-order-btn" 
                            data-dish-id="<?php echo htmlspecialchars($dish['id']); ?>" 
                            aria-label="Add <?php echo htmlspecialchars($dish['name']); ?> to Order"
                        >
                            Add to Order
                        </button>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
    
            <div class="swiper-pagination"></div>
    
        </div>
    
    </section>
</div>
<!-- Food Section Ends -->


<!-- gallery section starts  -->

<section class="gallery" id="gallery">

   <div class="heading">
      <span>our gallery</span>
      <h3>our untold stories</h3>
   </div>

   <div class="gallery-container">

      <a href="images/food-galler-img-1.jpg" class="box">
         <img src="images/food-galler-img-1.jpg" alt="">
         <div class="icon"> <i class="fas fa-plus"></i> </div>
      </a>

      <a href="images/food-galler-img-2.jpg" class="box">
         <img src="images/food-galler-img-2.jpg" alt="">
         <div class="icon"> <i class="fas fa-plus"></i> </div>
      </a>

      <a href="images/food-galler-img-3.jpg" class="box">
         <img src="images/food-galler-img-3.jpg" alt="">
         <div class="icon"> <i class="fas fa-plus"></i> </div>
      </a>

      <a href="images/food-galler-img-4.jpg" class="box">
         <img src="images/food-galler-img-4.jpg" alt="">
         <div class="icon"> <i class="fas fa-plus"></i> </div>
      </a>
      
      <a href="images/food-galler-img-5.jpg" class="box">
         <img src="images/food-galler-img-5.jpg" alt="">
         <div class="icon"> <i class="fas fa-plus"></i> </div>
      </a>

      <a href="images/food-galler-img-6.jpg" class="box">
         <img src="images/food-galler-img-6.jpg" alt="">
         <div class="icon"> <i class="fas fa-plus"></i> </div>
      </a>

   </div>

</section>

<!-- gallery section ends -->



<!-- blogs section starts  -->

<div class="odd-section">

   <section class="blogs" id="blogs">

      <div class="heading">
         <span>our blogs</span>
         <h3>our latest posts</h3>
      </div>
   
      <div class="swiper blogs-slider">
   
         <div class="swiper-wrapper">
   
            <div class="swiper-slide slide">
               <div class="image">
                  <img src="images/blog-img-1.jpg" alt="">
                  <span>burger</span>
               </div>
               <div class="content">
                  <div class="icon">
                     <a href="#"> <i class="fas fa-calendar"></i> 21st may, 2024 </a>
                     <a href="#"> <i class="fas fa-user"></i> by admin </a>
                  </div>
                  <a href="#" class="title">blog title goes here</a>
                  <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Animi, reprehenderit!</p>
                  <a href="#" class="btn">read more</a>
               </div>
            </div>
   
            <div class="swiper-slide slide">
               <div class="image">
                  <img src="images/blog-img-2.jpg" alt="">
                  <span>sandwich</span>
               </div>
               <div class="content">
                  <div class="icon">
                     <a href="#"> <i class="fas fa-calendar"></i> 21st may, 2024 </a>
                     <a href="#"> <i class="fas fa-user"></i> by admin </a>
                  </div>
                  <a href="#" class="title">blog title goes here</a>
                  <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Animi, reprehenderit!</p>
                  <a href="#" class="btn">read more</a>
               </div>
            </div>
   
            <div class="swiper-slide slide">
               <div class="image">
                  <img src="images/blog-img-3.jpg" alt="">
                  <span>chicken</span>
               </div>
               <div class="content">
                  <div class="icon">
                     <a href="#"> <i class="fas fa-calendar"></i> 21st may, 2024 </a>
                     <a href="#"> <i class="fas fa-user"></i> by admin </a>
                  </div>
                  <a href="#" class="title">blog title goes here</a>
                  <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Animi, reprehenderit!</p>
                  <a href="#" class="btn">read more</a>
               </div>
            </div>
   
            <div class="swiper-slide slide">
               <div class="image">
                  <img src="images/blog-img-4.jpg" alt="">
                  <span>ice-cream</span>
               </div>
               <div class="content">
                  <div class="icon">
                     <a href="#"> <i class="fas fa-calendar"></i> 21st may, 2024 </a>
                     <a href="#"> <i class="fas fa-user"></i> by admin </a>
                  </div>
                  <a href="#" class="title">blog title goes here</a>
                  <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Animi, reprehenderit!</p>
                  <a href="#" class="btn">read more</a>
               </div>
            </div>
   
            <div class="swiper-slide slide">
               <div class="image">
                  <img src="images/blog-img-5.jpg" alt="">
                  <span>pizza</span>
               </div>
               <div class="content">
                  <div class="icon">
                     <a href="#"> <i class="fas fa-calendar"></i> 21st may, 2024 </a>
                     <a href="#"> <i class="fas fa-user"></i> by admin </a>
                  </div>
                  <a href="#" class="title">blog title goes here</a>
                  <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Animi, reprehenderit!</p>
                  <a href="#" class="btn">read more</a>
               </div>
            </div>
   
            <div class="swiper-slide slide">
               <div class="image">
                  <img src="images/blog-img-6.jpg" alt="">
                  <span>coffee</span>
               </div>
               <div class="content">
                  <div class="icon">
                     <a href="#"> <i class="fas fa-calendar"></i> 21st may, 2024 </a>
                     <a href="#"> <i class="fas fa-user"></i> by admin </a>
                  </div>
                  <a href="#" class="title">blog title goes here</a>
                  <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Animi, reprehenderit!</p>
                  <a href="#" class="btn">read more</a>
               </div>
            </div>
   
         </div>
   
         <div class="swiper-pagination"></div>
   
      </div>
   
   </section>

</div>

<!-- blogs section ends -->

<!-- footer section starts  -->

<section class="footer">

   <div class="icons-container">

      <div class="icons">
         <i class="fas fa-clock"></i>
         <h3>opening hours</h3>
         <p>09:00am to 06:00pm</p>
      </div>

      <div class="icons">
         <i class="fas fa-phone"></i>
         <h3>phone</h3>
         <p>+393291915083</p>
         <p>+393509768658</p>
      </div>

      <div class="icons">
         <i class="fas fa-envelope"></i>
         <h3>email</h3>
         <p>hmddll00l15z200a@studenti.unime.it</p>
         <p>hamidy.adeebullah@gmail.com</p>
      </div>

      <div class="icons">
         <i class="fas fa-map"></i>
         <h3>address</h3>
         <p>messina, italy - 98168</p>
      </div>

   </div>

   <div class="share">
      <a href="#" class="fab fa-facebook-f"></a>
      <a href="#" class="fab fa-twitter"></a>
      <a href="#" class="fab fa-instagram"></a>
      <a href="#" class="fab fa-linkedin"></a>
   </div>

   <div class="credit"> created by <span>Hamidy Adeebullah</span> | all rights reserved! </div>

</section>

<!-- footer section ends  -->










<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery-js/1.4.0/js/lightgallery.min.js"></script>

<!-- custom js file link  -->
<script src="script2.js"></script>

<script>
   lightGallery(document.querySelector('.gallery .gallery-container'));
</script>

</body>
</html>