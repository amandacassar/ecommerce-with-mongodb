<?php
    // including the libraries
    require __DIR__. '/vendor/autoload.php';

    // create instance of mongodb client
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient -> ecommerce;

    // get username - filtering input to reduce the chance of sql injection etc
    $order = filter_input(INPUT_POST, 'order', FILTER_SANITIZE_STRING);

    // explode the string received, to get the value of each field
    $strings = explode("||", $order);

    // creating a php array to search for username's basket
    $searchCriteria = [
        "username" => $strings[0]
    ];


    // obtain the basket for this username from the database
    $basketResult = $db->Baskets->find($searchCriteria);

    foreach($basketResult as $basket)
    {
        $orderItems = $basket;
    }


    // getting today's date
    $today = date("Y-m-d");


    // add a new order with the basket details
    $newOrder = [
        "username" => $strings[0],
        "itemsList" => $orderItems['itemsList'],
        "shipping" => "20",
        "totalCost" => $strings[1],
        "date" => $today
    ];
    
    $addOrder = $db->Orders->insertOne($newOrder);

  
    // delete the user's basket  - using "deleteMany" so as to be sure that this user will have no other basket stored
    $deleteBasket = $db->Baskets->deleteMany($searchCriteria);

    
    // return the result
    echo $addOrder->getInsertedCount();

?>