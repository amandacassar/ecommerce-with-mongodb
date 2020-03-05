
<?php
    //Include the PHP functions to be used on the page 
    include('common.php');
    outputHeader("Home");
?>

<?php
    links("Home");
    outputMainNavigation("Home");
    outputNavigation("Home");
?>

<main>
<div id="cookie" class="cookie"> This website makes use of cookies <button id="accept">Accept</button></div>
  <div class="slideshow-container">
    <div class="mySlides fade">
      <div class="numbertext">1 / 3</div>
      <img src="assets/login-background.jpg" style="width:100%">
      <div class="text"></div>
      </div>
      <div class="mySlides fade">
      <div class="numbertext">2 / 3</div>
      <img src="assets/background2.jpg" style="width:100%">
      <div class="text"></div>
      </div>
      <div class="mySlides fade">
      <div class="numbertext">3 / 3</div>
      <img src="assets/main-background.jpg" style="width:100%">
      <div class="text"></div>
      </div>
      <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
      <a class="next" onclick="plusSlides(1)">&#10095;</a>
  </div>
  <br>
</main>


<!-- display recommendations -->
<div class="clothing-grid-container">
  <p id="recommend"></p>
</div>


<?php
    outputFooter("Home");
?>


<script>
  var slideIndex = 1;
  showSlides(slideIndex);

  function plusSlides(n) {
    showSlides(slideIndex += n);
  }

  function currentSlide(n) {
    showSlides(slideIndex = n);
  }

  function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dot");
    if (n > slides.length) {slideIndex = 1}    
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";  
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex-1].style.display = "block";  
    dots[slideIndex-1].className += " active";
  }
</script>



<script>
  window.onload = loadRecommendations();

  function loadRecommendations()
  {
    // check if this is the first time that the user loaded the index page
    // if yes, display cookies message
    // else, display recommended products
    if (sessionStorage.getItem("firstLoad") === null)
    {
      let displayCookie = document.getElementById("cookie");

      // on clicking the Accept button, remove the cookies message
      document.getElementById("accept").onclick = function()
      {
        displayCookie.style.display = "none";
      };
    }

    else
    {
      document.getElementById("cookie").style.display = "none";

      // create request object
      let request = new XMLHttpRequest();

      // create event handler that specifies what should happen when server responds
      request.onload = function()
      {
          if(request.status === 200)
          {
            // display the products - through another function
            displayProducts(request.responseText);
          }
      }
      let recommendation = sessionStorage.getItem("lastSearch");

      // set up request and send it
      request.open("POST", "get_recommended.php");
      request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

      // send request
      request.send('recommend=' + recommendation);
    }

    // setting the session storage "firstLoad" as not null, so that next time the index page is loaded, the cookie message does not show up
    sessionStorage.setItem("firstLoad", "loaded");
  }
  

  // function to display the products
  function displayProducts(jsonProducts)
  {
    // convert json to array of products object
    let prodArray = JSON.parse(jsonProducts);

    // create html table containing products data
    let htmlStr = "<p>Recommended for you:</p>"
    htmlStr += "<table>";
    htmlStr += "<tr>";

    // display 6 random products obtained, in one row
    for (let i = 0; i < 6; i++)
    {
      let x = Math.floor(Math.random() * prodArray.length);
      htmlStr += "<td><img width=150 height=150 src= '" + prodArray[x].imageUrl + "'></td>";
    }

    // finish off table and add to document
    htmlStr += "</tr>";
    htmlStr += "</table>";

    document.getElementById("recommend").innerHTML = htmlStr;
  }

</script> 