<?php
    //Include the PHP functions to be used on the page 
    include('common.php');
    outputHeader("Home");

    // including the list of countries used for sign up ---AC
    include("countries_list.php");
    links("Home");
    outputMainNavigation("Home");
    outputNavigation("Home");
?>


<main>
  <div class="main-grid">
    <div class="login-signup-image-grid-container">

      <div class="login"><h2>Login</h2>
        <div>
          <form action="login_customer.php" method="POST"> 
            <label for="logEmail"></label>
            <input type="text" id="logEmail" name="logEmail" placeholder="Your email..">
            <label for="logPassword"></label>
            <input type="text" id="logPass" name="logPass" placeholder="Your password..">
            <label for="login"></label> <br>
            <input type="button" value="Login" onclick="logInCustomer()">
            <p id="logMessage"></p>
          </form>  
      
        </div>
      </div>

      <div class="signup"><h2>Sign Up</h2>
        <div>
          <form action="add_customer.php" method="POST">  
            <label for="forename"></label>
            <input type="text" id="forename" name="forename" placeholder="Your forename.." required>
            <label for="surname"></label>
            <input type="text" id="surname" name="surname" placeholder="Your surname.." required>     
            <label for="mobno"></label>
            <input type="text" id="mobno" name="mobno" placeholder="Your mobile number.." required>
            <label for="email"></label>
            <input type="text" id="email" name="email" placeholder="Your email.." required>
            <label for="password"></label>
            <input type="text" id="password" name="password" placeholder="Your password.." required>
            <label for="address"></label>
            <input type="text" id="address" name="address" placeholder="Your address.." required>
            <input type="text" id="city" name="city" placeholder="Your city.." required>


            <!-- new drop down list with all countries -->
            <!-- Select Country drop-down menu -->
            <label for="country"></label>
            <select id="country" name="country" class="btn-group">
                <!-- using php to get the values & list items for the dropdown menu - these have been stored in an associative array in the file countries.php -->
                <!-- using the tag "button" from bootstrap for mouse cursor display -->
                <div class="dropdown-menu back-color">
                    <?php foreach($countriesList as $key => $countries)
                    { ?>
                        <button class="dropdown-item"> 
                            <option value="<?php echo $key ?>">
                                <?php echo $countries ?>
                            </option>
                        </button>
                    <?php 
                    } ?>
                </div>
            </select><br>


            <input type="button" value="Sign Up" onclick="addCustomer()">
            <p id="message"></p>
          </form>   
        </div>
      </div>
      
    </div>
  </div> 
</main>


<?php
    outputFooter("Home");
?>


<script>
  // function to add a new customer to the database
  function addCustomer()
  {
    // extract registration data
    let uName = document.getElementById("forename").value;
    let uSurname = document.getElementById("surname").value;
    let uMob = document.getElementById("mobno").value;
    let uEmail = document.getElementById("email").value;
    let uPass = document.getElementById("password").value;
    let uAddress = document.getElementById("address").value;
    let uCity = document.getElementById("city").value;
    let uCountry = document.getElementById("country").value;

    if(uName=="" || uSurname=="" || uMob=="" || uEmail=="" || uPass=="" || uAddress=="" || uCity=="")
    {
      document.getElementById("message").innerHTML = "Please enter all the data"; 
      return;
    }

    // create request object
    let request = new XMLHttpRequest();

    // create event handler that specifies what should happen when server responds
    request.onload = function()
    {
      if(request.status === 200)
      {
        // get data from server
        let responseData = request.responseText;

        // add data to page
        document.getElementById("message").innerHTML = responseData;    

        // clearing any previous customers stored in the sessionStorage, and adding the logged-in customer
        sessionStorage.removeItem("email");
        sessionStorage.setItem("email", uEmail);   
      }
      else
      {
          alert("error communicating with server!  -" + request.status);
      }
    }
    
    // gathering the user's credentials - email and password
    // note - sending them as one string, since AJAX would still gather variables as one string in this second request
    // adding a "||" in between variables, to seperate the email from the password
    let userData = uName + "||";
    userData += uSurname + "||";
    userData += uMob + "||";
    userData += uEmail + "||";
    userData += uPass + "||";
    userData += uAddress + "||";
    userData += uCity + "||";
    userData += uCountry;

    // set up request and send it
    request.open("POST", "add_customer.php?");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    request.send('userData=' + userData);  
  }


  // function to log-in an existing user
  function logInCustomer()
  {
    // create request object
    let request = new XMLHttpRequest();

    // create event handler that specifies what should happen when server responds
    request.onload = function()
    {
      if(request.status === 200)
      {
        // get data from server
        let responseData = request.responseText;

        // add data to page
        document.getElementById("logMessage").innerHTML = responseData;

        // clearing any previous customers stored in the sessionStorage, and adding the logged-in customer
        sessionStorage.removeItem("email");
        sessionStorage.setItem("email", uEmail);
      }
      else
      {
          alert("error communicating with server!  -" + request.status);
      }
    }

    // gathering the user's credentials - email and password
    // note - sending them as one string, since AJAX would still gather variables as one string in this second request
    // adding a "||" in between variables, to seperate the email from the password
    let uEmail = document.getElementById("logEmail").value;
    let userCredentials = uEmail + "||";
    userCredentials += document.getElementById("logPass").value;

    // set up request and send it
    request.open("POST", "login_customer.php?");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    request.send('credentials=' + userCredentials);  
  }
  
</script>

