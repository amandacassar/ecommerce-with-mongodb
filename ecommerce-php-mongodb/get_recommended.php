<?php
    // including the libraries
    require __DIR__. '/vendor/autoload.php';

    // create instance of mongodb client
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient -> ecommerce;
    $collection = $db -> Products;

    // get user's inputted data - filtering input to reduce the chance of sql injection etc
    $lastSearch = filter_input(INPUT_POST, 'recommend', FILTER_SANITIZE_STRING);
    

    // creating a php array with the user's search criteria, which will be searching in the keywords field
    $searchCriteria = [
        '$text' => ['$search' => $lastSearch],
        "stockCount" => ['$gt' => 0]
    ];


    // getting all the products that include these search words (or part of them) as keywords
    $matches = $collection->find($searchCriteria);


    // passing each product as a JSON object
    $jsonProducts = '[';

    foreach ($matches as $products)
    {
        $jsonProducts .= json_encode($products);
        $jsonProducts .= ',';
    }
    // removing the last comma
    $jsonProducts = substr($jsonProducts, 0, (strlen($jsonProducts)-1));

    $jsonProducts .= ']';

    // return the result
    echo $jsonProducts;
      
?>