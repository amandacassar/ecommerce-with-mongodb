<?php
    //Include the PHP functions to be used on the page 
    include('common.php');
    outputHeader("Home");
?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<?php
    links("Home");
    outputMainNavigation("Home");
    outputNavigation("Home");
?>


<main>
    <div class="main-grid">
        <div class="clothing-grid-container">
            <p id="orders_table"></p>
        </div>
    </div>
</main>

<?php
    outputFooter("Home");
?>


<script>
  window.onload = showOrders();

  // function to obtain the basket details for the currently logged-in user
  function showOrders()
  {
    // create request object
    let request = new XMLHttpRequest();

    // create event handler that specifies what should happen when server responds
    request.onload = function()
    {
      if(request.status === 200)
      {
          displayOrders(request.responseText);            
      }
      else
      {
          alert("error communicating with server!  -" + request.status)
      }
    }

    // set up request and send it
    request.open("POST", "get_orders.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // obtaining current customer's email
    let customer = sessionStorage.getItem("email");

    // send request
    request.send("username=" + customer);
  }


  // function to display the products
  function displayOrders(jsonProducts)
  {
    // convert json to array of products object
    let allOrders = JSON.parse(jsonProducts);   

    // create html table containing products data
    let htmlStr = "<table>";


    // obtaining the number of orders, to know how many iterations to perform in the next chunk of code
    let ordersCount = allOrders.length;

    // for each product inside the user's basket, display the product info
    for (let i = 0; i < ordersCount; i++)
    {
      // display titles
      htmlStr += "<tr>";
      htmlStr += "<td> IMAGE </td>";
      htmlStr += "<td> PRODUCT </td>";
      htmlStr += "<td> SIZE </td>";
      htmlStr += "<td> QUANTITY </td>";
      htmlStr += "<td> UNIT </td>";
      htmlStr += "</tr>";

      // obtaining the number of items for this order, to know how many iterations to perform in the next chunk of code
      let itemsCount = allOrders[i].itemsList.length;

      for (let j = 0; j < itemsCount; j++)
      {
        // display items
        htmlStr += "<tr>";
        htmlStr += "<td><img width=30 height=30 src = '" + allOrders[i].itemsList[j].imageUrl + "'></td>";
        htmlStr += "<td>" + allOrders[i].itemsList[j].title + "</td>";
        htmlStr += "<td>" + allOrders[i].itemsList[j].size + "</td>";
        htmlStr += "<td>" + allOrders[i].itemsList[j].quantity + "</td>";
        htmlStr += "<td> &euro;" + allOrders[i].itemsList[j].unitPrice + "</td>";
        htmlStr += "</tr>";
      }

      htmlStr += "<td> <p> Shipping: &euro;" + allOrders[i].shipping + "</p></td>";
      htmlStr += "<td> <p> Total Cost: &euro;" + allOrders[i].totalCost + "</p></td>";
      htmlStr += "<td> <p> Date Ordered: " + allOrders[i].date + "</p></td>";

    }

    // finish off table and add to document
    htmlStr += "</table>";
    
    // display the table
    document.getElementById("orders_table").innerHTML = htmlStr;
  }

</script>