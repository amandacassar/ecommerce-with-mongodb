
<?php
    //Include the PHP functions to be used on the page 
    include('common.php');
    outputHeader("Home");
    links("Home");
    outputMainNavigation("Home");
    outputNavigation("Home");
?>


<main>
<div class="main-grid">
  <div class="grid-container">
    
    <div class="tb">
      <h2>Shopping Cart</h2>
    </div>
    <div class="tp">
      <h2>Payment Details</h2>
    </div>    


    <p id="basket_table"></p>

    <div class="payment">
      <div>
        <form action="order_confirm.php" method="POST">
          <label for="Card Number"></label>
          <input type="text" id="Card Number" name="Card Number" placeholder="Your Card Number ...">
          <label for="Expiration Date"></label>
          <input type="text" id="Expiration Date" name="Expiration Date" placeholder="Expiration Date ...">
          <label for="CVC"></label>
          <input type="text" id="CVC" name="CVC" placeholder="Your CVC..">
          </select><br>
          <input type="button" value="Pay Now" onclick="addOrder()">
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
  window.onload = loadBasket();

  // function to obtain the basket details for the currently logged-in user
  function loadBasket()
  {
    // create request object
    let request = new XMLHttpRequest();

    // create event handler that specifies what should happen when server responds
    request.onload = function()
    {
      if(request.status === 200)
      {
          let result = request.responseText;

          // if the result is empty, display the below message
          if (typeof result == 'undefined')
          {
            document.getElementById("basket_table").innerHTML = "You have no items in your basket"
          }
          // if the result is not empty, display the products - through another function
          else
          {
            displayProducts(request.responseText);
          }
        
      }
      else
      {
          alert("error communicating with server!  -" + request.status)
      }
    }

    // set up request and send it
    request.open("POST", "get_basket.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // obtaining current customer's email
    let customer = sessionStorage.getItem("email");

    // send request
    request.send("username=" + customer);
  }


  // function to display the products
  function displayProducts(jsonProducts)
  {
    // convert json to array of products object
    let basket = JSON.parse(jsonProducts);

    // clearing the previous localStorage
    localStorage.clear();

    // create html table containing products data
    let htmlStr = "<table>";

    htmlStr += "<tr>";
    htmlStr += "<td> IMAGE </td>";
    htmlStr += "<td> PRODUCT </td>";
    htmlStr += "<td> SIZE </td>";
    htmlStr += "<td> QUANTITY </td>";
    htmlStr += "<td> PRICE </td>";
    htmlStr += "</tr>";

    // variable to store the total cost for this basket
    let basketTotal = 0;

    // obtaining the number of items in the basket, to know how many iterations to perform in the next chunk of code
    let count = basket.itemsList.length;

    // for each product inside the user's basket, display the product info
    for (let i = 0; i < count; i++)
    {
      htmlStr += "<tr>";
      htmlStr += "<td><img width=30 height=30 src = '" + basket.itemsList[i].imageUrl + "'></td>";
      htmlStr += "<td>" + basket.itemsList[i].title + "</td>";
      htmlStr += "<td>" + basket.itemsList[i].size + "</td>";
      htmlStr += "<td>" + basket.itemsList[i].quantity + "</td>";
      htmlStr += "<td> &euro;" + basket.itemsList[i].unitPrice + "</td>";
      // adding the "Add to Basket" button, next to each product, and calling the displayButtonId() function, with the current index as parameter
      htmlStr += '<td> <button onclick="removeItem(' + i + ')" id="p' + i + '" value="' + i + '">Remove</button> </td>';
      htmlStr += "</tr>";

      // adding each product in the local storage - will use this data if the user wants to remove an item to the basket
      let productObject = {title : basket.itemsList[i].title, size : basket.itemsList[i].size, colour : basket.itemsList[i].colour, unitPrice : basket.itemsList[i].unitPrice, imageUrl : basket.itemsList[i].imageUrl};

      // storing the product object in the localStorage, and assigning iteration value as key value
      localStorage.setItem(i, JSON.stringify(productObject));

      // adding this product's cost to the total cost for the whole basket of this customer
      basketTotal = basketTotal + parseInt(basket.itemsList[i].unitPrice);
    }

    // finish off table and add to document
    htmlStr += "</table>";

    // display shipping cost - fixed at €20
    htmlStr += "<p> Shipping: &euro; 20 </p>";

    basketTotal += 20;

    // display total cost
    htmlStr +="<p> Total for this Basket: &euro;" + basketTotal + "</p>";

    // storing the basket's total cost in the local storage, with "basketTotal" as key
    localStorage.setItem("basketTotal", basketTotal);

    // display the table
    document.getElementById("basket_table").innerHTML = htmlStr;
  }


  // function to identify which "Add to Basket" button was clicked
  // in return, the product selected, will be added to the customer's basket
  // and the product's stockCount will be deducted too
  function removeItem(num)
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

        // if successfully removed, refresh the basket
        // else alert the error message
        if(responseData == "Successfully removed the product from your basket")
        {
          loadBasket();
        }
        else
        {
          alert(responseData);
        }
        
      }
      else
      {
        alert("error communicating with server!  -" + request.status);
      }
    }

    // getting the object of the selected product
    let selectedProduct = JSON.parse(localStorage.getItem(num));

    // gathering the username and product data
    // note - sending them as one string, since AJAX would still gather variables as one string in this second request
    // adding a "||" in between variables, to seperate each element
    let userProduct = sessionStorage.getItem("email") + "||";
    userProduct += selectedProduct.size + "||";
    userProduct += selectedProduct.imageUrl + "||";

    // set up request and send it
    request.open("POST", "remove_from_basket.php?");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // send request
    // note - this request will add product to basket PLUS deduct the product's stockCount by 1
    request.send('userProduct=' + userProduct);
  }



  // add a new order
  function addOrder()
  {    
    // create request object
    let request = new XMLHttpRequest();

    // create event handler that specifies what should happen when server responds
    request.onload = function()
    {
      if(request.status === 200)
      {
        // display the page that confirms order
        window.location="http://localhost/ecommerce_php/order_confirm.php"
      }
      else
      {
        alert("error communicating with server!  -" + request.status)
      }    
    }

    // set up request and send it
    request.open("POST", "add_order.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // obtaining current user and basket information
    let orderData = sessionStorage.getItem("email");
    orderData += "||";
    orderData += localStorage.getItem("basketTotal")

    // send request
    request.send("order=" + orderData);
  }

</script>
