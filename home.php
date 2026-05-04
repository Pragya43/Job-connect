<?php
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Website</title>
      
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
       
          
       <link rel="stylesheet" href="style.css">
      </head>
    <body>
      

        <header>
          <img src="Logo.jpg" alt="Logo" class="logo">

           <div id="menu-bar" class="fas fa-bars"></div>
          
           <nav class="navbar">
            <a href="#home">home</a>
            <a href="#about us">about us</a>
            <a href="#service">service</a>
            <a href="#contact">contact</a>
           <a href="login.php"> <button class="btn" id="loginBtn">Log In</button></a>
            <a href="register.php" ><button class="btn" id="signupBtn">Sign Up</button></a>
           </nav>
           

        </header>
      
        <script src="script.js"></script>
        
    </body>
</html>




//home section

<section class="home" id="home">
    <div class="content">
       <h3>AI Powered  <br> Job <br>Platform</br>
    </h3>
   
   <p> Our platform connects job seekers with top employers across various industries, offering opportunities that match your skills, goals, and passion
    <br>Start your journey today- because your dream job is just a click away.</br>
  </p>
       <a herf="#" class="btn"> discover more</a>

        
    </div>
    
    <div class="image">
       <img src="jobfix.jpg"  alt="">
    </div>
    </div>

    
   
       
   </section>

 <section class="about us" id="about us">
    <div class="about-container">
    <div class="about-small-image">
    <img src="your-image.png" class="small-image" alt="Team working"/>

    </div>
    <div class="about-text">
 <h2>About Us</h2>
 <p> At Shakriya, we're reimagining how people connect, collaborate, and grow in the modern work era. Whether you're freelancer with big amitions or a business searching for top-tier talent, Shakriya is your bridge to reliable, flexible and high-quality work.</p>


    </div>

    </div>
 </section>


   <section class="service" id="service">
     
    <h1 class="heading"> our services </h1>
<div class="box-container">
<div class="box">
    <img src="web.jpg" class="small-image" alt="Photo">
    <h3>web optimization</h3>
    <p>We ensure our platform runs fast, smoothly, and efficiently across all devices,we optimize every aspect of the site to provide job seekers and employers with a seamless experience.</p>
    <a href="#" class="btn">read more</a>

</div>
<div class="box">
    <img src="content.jpg" class="small-image" alt="Photo">
    <h3>content marketing</h3>
    <p>We create valuable and engaging content tailored to job seekers and employers, helping you attract the right audience.</p>
    <a href="#" class="btn">read more</a>


</div>
<div class="box">
    <img src="data analytics.jpg" class="small-image" alt="Photo">
    <h3>data analytics</h3>
    <p>We leverage data analytics to match job seekers with the right opportunities by analyzing trends, skills, and hiring patterns. </p>
    <a href="#" class="btn">read more</a>
    </div>

<div class="box">
    <img src="digital.jpg" class="small-image" alt="Photo">
    <h3>digital marketing</h3>
    <p>We help you grow your business online through targeted digital strategies, including SEO, social media marketing, email campaigns, content creation, and paid advertising. </p>
    <a href="#" class="btn">read more</a>
    </div>

    <div class="box">
    <img src="media.jpg" class="small-image" alt="Photo">
    <h3>media marketing</h3>
    <p>We help job seekers and employers grow their online presence through strategic media marketing. </p>
    <a href="#" class="btn">read more</a>
    </div>

       <div class="box">
    <img src="SEO.jpg" class="small-image" alt="Photo">
    <h3>SEO optimization</h3>
    <p>We help your job listings and company profiles rank higher on search engines with expert SEO strategies.  </p>
    <a href="#" class="btn">read more</a>
    </div>
  
   </section>


<section class="contact" id="contact">


    <h1 class="heading"> connect with us</h1>,
    <div class="row">

        <form action="process_contact.php" method="POST">
          <div class="inputBox">
            <input type="text" id="name" name="name"  required>
            <label>name</label>
          </div>

          <div class="inputBox">
            <input type="email" id="email" name="email" required>
            <label>email</label>
          </div>

        <div class="inputBox">
            <input type="number" name="phone" required>
            <label>number</label>
          </div>

    <div class="inputBox">
            <textarea required name="message" id="message" cols="30" rows="10"></textarea>
    <label>message </label>      
 </div>
 
 <input type="submit" class="btn" value="send message">


        </form>
     


    </div>
</section>


<div class="footer";>

<div class="box-container">
    <div class="box">
        <h3>about us</h3>
        <p>At Shakriya, we're reimagining how people connect, collaborate, and grow in the modern work era. Whether you're freelancer with big amitions or a business searching for top-tier talent, Shakriya is your bridge to reliable, flexible and high-quality work.</p>

    </div>
    <div class="box">
     <h3>quick links</h3>
     <a href="#">home</a>
     <a href="#">about us</a>
    <a href="#">service</a>
    <a href="#">contact</a>
    </div>
  <div class="box">
    <h3>category</h3>
    <a href="#">web optimization</a>
    <a href="#">content marketing</a>
    <a href="#">data analytics</a>
    <a href="#">digital marketing</a>
    <a href="#">media marketing</a>
  </div>

</div>
<h1 class="credit"> &copy; copyright by <a href="#"> shakriya</a>@2025</h1>
</div>
