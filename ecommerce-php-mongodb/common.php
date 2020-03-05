
<?php
    function outputHeader($outputHeader){
        echo '<!DOCTYPE html>';
        echo '<html>';
        echo '<head>';
        echo '<title>Time Jump</title>';
        echo '</head>';
        echo '</html>';
    }
?>

<?php
    function links($outputHeader){
        echo '<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">';
        echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">';
        echo '<link rel="stylesheet" href="style.css" type="text/css">';
        echo '<script src="https://kit.fontawesome.com/a076d05399.js"></script>';
        echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">';
    }
?>


<!-- PHP for navigation -->
<?php

    function outputMainNavigation($pageName){
        echo '<header>';
        echo '<div id="header" class="flex-container-head">';
        echo '<div class="head-grid-container">';
        echo '<div>';
        echo '<div class="dropdown" style="float:left;">';
        echo '<a href="account.php" >';
        echo '<i class="fas fa-user-circle"style="font-size:36px"></i>';
        echo '</a>';
        echo '<div class="dropdown-content" style="left:0;">';
        echo '<a href="account.php">Login</a>';
        echo '<a href="account.php">Sign Up</a>';
        echo '<a href="loggedOut.php">Log Out</a>';
        
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '<div><a href="index.php">HOME</a></div>';
        echo '</div>';
        echo '<div class="dropdown" style="float:left;">';
        echo '<a href="basket.php" ><i class="fa fa-shopping-cart" style="font-size:24px"></i></a>';
        echo '<div class="dropdown-content" style="left:0;">';
        echo '<a href="basket.php">My Basket</a>';
        echo '<a href="orders.php">My Orders</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</header>';
    }
?>

<?php
    function outputNavigation($pageName){
        echo'<nav>';
        echo'<div class="nav-grid-container">';
        echo'<div class="nav_items">';
        echo'<div id="navigation" class="flex-container-nav">';
        echo'<div id="nav">';
        echo'<a href="women.php" >Womens </a>';
        echo'</div>';
        echo'<div id="nav"><a href="men.php">Mens</a>'; 
        echo'</div>';
        echo'</div> ';
        echo'</div>';
        echo'<div class="search">';
        echo'<form action="productsList.php" method="GET">';
        echo'<input class="search" type="text" name="search" placeholder="Search..."> ';
        echo'</form>';
        echo'</div>';
        echo'</div>';
        echo'</nav>';
    }
?>


<?php
    function outputFooter($pageName){
    echo'<footer class="footer-distributed">';
    echo'  <div class="footer-right">';
    echo'    <a href="#"><i class="fa fa-facebook"></i></a>';
    echo'    <a href="#"><i class="fa fa-twitter"></i></a>';
    echo'    <a href="#"><i class="fa fa-linkedin"></i></a>';
    echo'  </div>  ';
    echo'  <div class="footer-left">';
    echo'    <p class="footer-links">';
    echo'      <a class="link-1" href="index.php">Home</a>';
    echo'      <a class="link-1" href="account.php">Account</a>';
    echo'      <a class="link-1" href="men.php">Men</a>';
    echo'      <a class="link-1" href="women.php">Women</a>';
    echo'    </p>';
    echo'    <p>Company Name &copy; 2015</p>';
    echo'  </div>';
    echo'</footer>';
    }
?>