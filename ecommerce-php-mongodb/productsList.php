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
            <p id="searchRequest" name="searchRequest" type="text" value="<?php echo $_GET["search"]; ?>" > Search Results For:  <?php echo $_GET["search"]; ?> </p>
            <p id="productsList"></p>
        </div>
    </div>
</main>


<?php
    outputFooter("Home");
?>

<script>
    window.onload = loadContent();

    // function to obtainp roducts
    function loadContent()
    {
        // create request object
        let request = new XMLHttpRequest();

        // create event handler that specifies what should happen when server responds
        request.onload = function()
        {
            if(request.status === 200)
            {
                displayProducts(request.responseText);
            }
            else
            {
                alert("error communicating with server!  -" + request.status)
            }
        }

        // set up request and send it
        request.open("POST", "search_products.php");
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        // getting user's search keyword/s --> doing this by splitting the URL where there is the '=' 
        // and then getting the second element which represents the search request
        let searchString = window.location.href.split('=');

        // replacing any '+' signs inside the search string (for when user inputs >1 word) with a space
        let searching = searchString[1].replace("+" , " ");

        // clearing any previous keyword searches stored in the sessionStorage, and adding the latest search performed
        sessionStorage.removeItem("lastSearch");
        sessionStorage.setItem("lastSearch", searching);

        // send request
        request.send("search=" + searching);
    }


    // function to display products
    function displayProducts(jsonProducts)
    {
        // convert json to array of products object
        let prodArray = JSON.parse(jsonProducts);

        // clearing the previous localStorage
        localStorage.clear();

        // create html table containing products data
        let htmlStr = "<table>";

        htmlStr += "<tr>";
        htmlStr += "<td> TITLE </td>";
        htmlStr += "<td> SIZE </td>";
        htmlStr += "<td> COLOUR </td>";
        htmlStr += "<td> UNIT PRICE </td>";
        htmlStr += "<td> IMAGE </td>";
        htmlStr += "</tr>";

        // display the products list in the form of a table
        for (let i = 0; i < prodArray.length; i++)
        {
            htmlStr += "<tr>";
            htmlStr += "<td>" + prodArray[i].title + "</td>";
            htmlStr += "<td>" + prodArray[i].size + "</td>";
            htmlStr += "<td>" + prodArray[i].colour + "</td>";
            htmlStr += "<td> &euro;" + prodArray[i].unitPrice + "</td>";
            htmlStr += "<td><img width=150 height=150 src = '" + prodArray[i].imageUrl + "'></td>";
            // adding the "Add to Basket" button, next to each product, and calling the displayButtonId() function, with the current index as parameter
            htmlStr += '<td> <button onclick="addToBasket(' + i + ')" id="p' + i + '" value="' + i + '">Add to Basket</button> </td>';
            htmlStr += "</tr>";

            // adding each product in the local storage - will use this data if the user wants to add an item to the basket
            let productObject = {mongoId : prodArray[i]._id, title : prodArray[i].title, size : prodArray[i].size, colour : prodArray[i].colour, unitPrice : prodArray[i].unitPrice, imageUrl : prodArray[i].imageUrl};

            // storing the product object in the localStorage, and assigning iteration value as key value
            localStorage.setItem(i, JSON.stringify(productObject));
        }

        // finish off table and add to document
        htmlStr += "</table>";

        document.getElementById("productsList").innerHTML = htmlStr;
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

        // gathering the usernamd and product data
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
        request.send('userProduct=' + userProduct);
    }

</script>

