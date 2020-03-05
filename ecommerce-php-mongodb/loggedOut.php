
<?php
    //Include the PHP functions to be used on the page 
    include('common.php');
    outputHeader("Home");
    links("Home");
    outputMainNavigation("Home");
    outputNavigation("Home");
?>


<!-- closing the session management -->
<?php
    // starting the session management
    session_start();

    // remove all variables related to this session
    session_unset();

    // destroy the session
    session_destroy();
?>


<main>
<div class="main-grid">
  <div class="grid-container">
    <div class="tb">
        <h2>Successfully logged out</h2>
        <a href="index.php">Return to Home Page</a>
    </div>
  </div>
</div>
</main>


<?php
    outputFooter("Home");
?>


