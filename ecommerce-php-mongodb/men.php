
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
          <div class="refine">            
          <div class="w3-container">
          <div class="w3-dropdown-click">
            <button onclick="myFunction()" class="w3-button w3-black">Refine Search</button>
              <div id="Demo" class="w3-dropdown-content w3-bar-block w3-border">
                <h3>TYPE</h3>
                <a href="#" class="w3-bar-item w3-button"><button onclick="return filterItems('tops')">Tops</button></a>
                <a href="#" class="w3-bar-item w3-button"><button onclick="return filterItems('bottoms')">Bottoms</button></a>
                <a href="#" class="w3-bar-item w3-button"><button onclick="return filterItems('jackets')">Jackets</button></a>
                <h3>COLOUR</h3>
                <a href="#" class="w3-bar-item w3-button"><button onclick="return filterItems('black')">Black</button></a>
                <a href="#" class="w3-bar-item w3-button"><button onclick="return filterItems('blue')">Blue</button></a>
                <a href="#" class="w3-bar-item w3-button"><button onclick="return filterItems('green')">Green</button></a>
                <h3>SIZE</h3>
                <a href="#" class="w3-bar-item w3-button"><button onclick="return filterItems('small')">Small</button></a>
                <a href="#" class="w3-bar-item w3-button"><button onclick="return filterItems('medium')">Medium</button></a>
                <a href="#" class="w3-bar-item w3-button"><button onclick="return filterItems('large')">Large</button></a>
                <h3>SORT</h3>
                <a href="#" class="w3-bar-item w3-button"><button onclick="return sortItems('price_Asc')">Price - Low to High</button></a>
                <a href="#" class="w3-bar-item w3-button"><button onclick="return sortItems('price_Desc')">Price - High to Low</button></a>
                <a href="#" class="w3-bar-item w3-button"><button onclick="return sortItems('title_Asc')">Title - A to Z</button></a>
                <a href="#" class="w3-bar-item w3-button"><button onclick="return sortItems('title_Desc')">Title - Z to A</button></a>
            </div>
          </div>
        </div>
           
          </div>
          <div class="display">
              <p id="menProducts"></p>
          </div>
      </div> 
      </div>
    </main>


<script>
  function myFunction() {
    var x = document.getElementById("Demo");
    if (x.className.indexOf("w3-show") == -1) { 
      x.className += " w3-show";
    } else {
      x.className = x.className.replace(" w3-show", "");
    }
}
</script>


<?php
    outputFooter("Home");
?>



<script>
  window.onload = loadProducts();

  // variable used to identify the last filter that the user applied
  lastFilter = "";


  // function to obtain the products
  function loadProducts()
  {
      // clearing any previous keyword searches stored in the sessionStorage, and adding the latest search performed
      sessionStorage.removeItem("lastSearch");
      sessionStorage.setItem("lastSearch", "men");

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
        else
        {
            alert("error communicating with server!  -" + request.status)
        }
      }

      // set up request and send it
      request.open("GET", "get_men_products.php");

      // send request
      request.send();
  }


  // function to display the products
  function displayProducts(jsonProducts)
  {
      // convert json to array of products object
      let prodArray = JSON.parse(jsonProducts);

      // clearing the previous localStorage
      localStorage.clear();

      // create html table containing products data
      let htmlStr = "<table>";

      htmlStr += "<tr>";
      htmlStr += "<td> <h4>TITLE</h4></td>";
      htmlStr += "<td> <h4>SIZE</h4> </td>";
      htmlStr += "<td> <h4>COLOUR</h4> </td>";
      htmlStr += "<td> <h4>PRICE</h4> </td>";
      htmlStr += "<td> <h4>IMAGE</h4> </td>";
      htmlStr += "</tr>";

      // display the products list in the form of a table
      for (let i = 0; i < prodArray.length; i++)
      {
        htmlStr += "<tr>";
        htmlStr += "<td><h4>" + prodArray[i].title + "</h4></td>";
        htmlStr += "<td><h4>" + prodArray[i].size + "</h4></td>";
        htmlStr += "<td><h4>" + prodArray[i].colour + "</h4></td>";
        htmlStr += "<td><h4> &euro;" + prodArray[i].unitPrice + "</h4></td>";
        htmlStr += "<td><img width=150 height=150 src = '" + prodArray[i].imageUrl + "'></td>";
        // adding the "Add to Basket" button, next to each product, and calling the displayButtonId() function, with the current index as parameter
        htmlStr += '<td> <button onclick="addToBasket(' + i + ')" id="p' + i + '" value="' + i + '">Add to Basket</button> </td>';
        htmlStr += "</tr>";

        // adding each product in the local storage
        let productObject = {mongoId : prodArray[i]._id, title : prodArray[i].title, size : prodArray[i].size, colour : prodArray[i].colour, unitPrice : prodArray[i].unitPrice, imageUrl : prodArray[i].imageUrl};

        // storing the user object in the localStorage, and assigning iteration value as key value
        localStorage.setItem(i, JSON.stringify(productObject));
      }

      // finish off table and add to document
      htmlStr += "</table>";

      document.getElementById("menProducts").innerHTML = htmlStr;
  }


  // function to identify which "Add to Basket" button was clicked
  // in return, the product selected, will be added to the customer's basket
  // and the product's stockCount will be deducted too
  function addToBasket(num)
  {
    // create request object
    let request = new XMLHttpRequest();

    // create event handler that specifies what should happen when server responds
    request.onload = function()
    {
      if(request.status === 200)
      {
        // get data from server and if there was an error, display message
        let responseData = request.responseText;
        if (responseData == "Could not add this to basket")
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
    userProduct += selectedProduct['mongoId'].toString() + "||";
    userProduct += selectedProduct.title + "||";
    userProduct += selectedProduct.size + "||";
    userProduct += selectedProduct.colour + "||";
    userProduct += selectedProduct.unitPrice + "||";
    userProduct += selectedProduct.imageUrl + "||";

    // set up request and send it
    request.open("POST", "add_to_basket.php?");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // send request
    // note - this request will add product to basket PLUS deduct the product's stockCount by 1
    request.send('userProduct=' + userProduct);
  }


  // filter products by selection
  function filterItems(criteria)
  {
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
      else
      {
        alert("error communicating with server!  -" + request.status)
      }
    }

    // set up request based on the selected filter
    if (criteria == "tops")
    {
      request.open("GET", "filter_men_products.php?filtering=tops");

      // setting the last filter variable - to allow sorting for this selection
      lastFilter = "tops";

      // clearing any previous keyword searches stored in the sessionStorage, and adding the latest search performed
      sessionStorage.removeItem("lastSearch");
      sessionStorage.setItem("lastSearch", "men tops");
    }

    else if (criteria == "bottoms")
    {
      request.open("GET", "filter_men_products.php?filtering=bottoms");

      // setting the last filter variable - to allow sorting for this selection
      lastFilter = "bottoms";

      // clearing any previous keyword searches stored in the sessionStorage, and adding the latest search performed
      sessionStorage.removeItem("lastSearch");
      sessionStorage.setItem("lastSearch", "men bottoms");
    }

    else if (criteria == "jackets")
    {
      request.open("GET", "filter_men_products.php?filtering=jackets");

      // setting the last filter variable - to allow sorting for this selection
      lastFilter = "jackets";

      // clearing any previous keyword searches stored in the sessionStorage, and adding the latest search performed
      sessionStorage.removeItem("lastSearch");
      sessionStorage.setItem("lastSearch", "men jackets");
    }

    else if (criteria == "black")
    {
      request.open("GET", "filter_men_products.php?filtering=black");

      // setting the last filter variable - to allow sorting for this selection
      lastFilter = "black";

      // clearing any previous keyword searches stored in the sessionStorage, and adding the latest search performed
      sessionStorage.removeItem("lastSearch");
      sessionStorage.setItem("lastSearch", "men black");
    }

    else if (criteria == "blue")
    {
      request.open("GET", "filter_men_products.php?filtering=blue");

      // setting the last filter variable - to allow sorting for this selection
      lastFilter = "blue";

      // clearing any previous keyword searches stored in the sessionStorage, and adding the latest search performed
      sessionStorage.removeItem("lastSearch");
      sessionStorage.setItem("lastSearch", "men blue");
    }

    else if (criteria == "green")
    {
      request.open("GET", "filter_men_products.php?filtering=green");

      // setting the last filter variable - to allow sorting for this selection
      lastFilter = "green";

      // clearing any previous keyword searches stored in the sessionStorage, and adding the latest search performed
      sessionStorage.removeItem("lastSearch");
      sessionStorage.setItem("lastSearch", "men green");
    }

    else if (criteria == "small")
    {
      request.open("GET", "filter_men_products.php?filtering=S");

      // setting the last filter variable - to allow sorting for this selection
      lastFilter = "S";
    }

    else if (criteria == "medium")
    {
      request.open("GET", "filter_men_products.php?filtering=M");

      // setting the last filter variable - to allow sorting for this selection
      lastFilter = "M";
    }

    else if (criteria == "large")
    {
      request.open("GET", "filter_men_products.php?filtering=L");

      // setting the last filter variable - to allow sorting for this selection
      lastFilter = "L";
    }

    // send request
    request.send();
  }


  // sort products by selection
  function sortItems(sorting)
  {
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
      else
      {
          alert("error communicating with server!  -" + request.status)
      }
    }

    // gathering the filter and sort criteria
    // note - sending them as one string, since AJAX would still gather variables as one string in this second request
    // adding a "||" in between variables, to seperate each element
    let allCriteria = lastFilter;
    allCriteria +=  "||";

    // adding the respective sorting criteria
    if (sorting == "price_Asc")
    {
      allCriteria += "priceAsc";
    }
    else if (sorting == "price_Desc")
    {
      allCriteria += "priceDesc";
    }
    else if (sorting == "title_Asc")
    {
      allCriteria += "titleAsc";
    }
    else if (sorting == "title_Desc")
    {
      allCriteria += "titleDesc";
    }    

    // set up request and send it
    request.open("GET", "sort_men_products.php?criteria=" + allCriteria);

    // send request
    request.send();
  }

</script>