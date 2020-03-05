<?php
    // including the libraries
    require __DIR__. '/vendor/autoload.php';

    // create instance of mongodb client
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $collection = $db->Products;

    // creating the php array to search for all men products
    $criteria = [
        "gender" => "men",
        "stockCount" => ['$gt' => 0]
    ];
    
    // getting all the products of gender "men" from the database
    $products = $collection->find($criteria);

    // passing each product as a JSON object
    $jsonProducts = '[';

    foreach ($products as $prod)
    {
        $jsonProducts .= json_encode($prod);
        $jsonProducts .= ',';
    }
    // removing the last comma
    $jsonProducts = substr($jsonProducts, 0, (strlen($jsonProducts)-1));

    $jsonProducts .= ']';

    // return the result
    echo $jsonProducts;

?>