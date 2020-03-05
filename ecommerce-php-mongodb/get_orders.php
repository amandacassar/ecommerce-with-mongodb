<?php
    // including the libraries
    require __DIR__. '/vendor/autoload.php';

    // create instance of mongodb client
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $collection = $db->Orders;

    // get username - filtering input to reduce the chance of sql injection etc
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);

    // creating a php array to search for username's basket
    $searchCriteria = [
        "username" => $username
    ];

    // obtain the orders for this username from the database
    $ordersList = $collection->find($searchCriteria);

    // passing each order as a JSON object
    $jsonOrders = '[';

    foreach ($ordersList as $orders)
    {
        $jsonOrders .= json_encode($orders);
        $jsonOrders .= ',';
    }
    // removing the last comma
    $jsonOrders = substr($jsonOrders, 0, (strlen($jsonOrders)-1));

    $jsonOrders .= ']';


    // return the result
    echo $jsonOrders;

?>