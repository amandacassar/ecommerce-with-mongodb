<?php
    // including the libraries 
    require __DIR__. '/vendor/autoload.php';

    // create instance of mongodb client
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient -> ecommerce;
    $collection = $db -> Baskets;


    // get username - filtering input to reduce the chance of sql injection etc
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    

    // creating a php array to search for username's basket
    $searchCriteria = [
        "username" => $username
    ];


    // obtain the basket for this username from the database
    $basket = $collection->find($searchCriteria);


    // each customer can have only one basket so one json object will be obtained
    foreach ($basket as $cart)
    {
        $jsonBasket = json_encode($cart);
    }      
    
    // return the result
    echo $jsonBasket;

?>