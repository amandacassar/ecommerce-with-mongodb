<?php
    // including the libraries 
    require __DIR__. '/vendor/autoload.php';

    // create instance of mongodb client
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $collection = $db->Products;

    // getting the filter selected by the user
    $filterSelection = filter_input(INPUT_GET, 'filtering', FILTER_SANITIZE_STRING);

    // creating the php array based on the filter selected by the user
    if ($filterSelection == "tops" OR $filterSelection == "bottoms" OR $filterSelection == "jackets")
    {
        $criteria = [
            "prodType" => $filterSelection,
            "gender" => "men",
            "stockCount" => ['$gt' => 0]
        ];
    }

    else if ($filterSelection == "black" OR $filterSelection == "blue" OR $filterSelection == "green")
    {
        $criteria = [
            "colour" => $filterSelection,
            "gender" => "men",
            "stockCount" => ['$gt' => 0]         
        ];
    }

    else if ($filterSelection == "S" OR $filterSelection == "M" OR $filterSelection == "L")
    {
        $criteria = [
            "size" => $filterSelection,
            "gender" => "men",
            "stockCount" => ['$gt' => 0]         
        ];
    }

    
    // getting all the relevant products from the database
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