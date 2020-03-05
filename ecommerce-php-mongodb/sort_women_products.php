<?php
    // including the libraries 
    require __DIR__. '/vendor/autoload.php';

    // create instance of mongodb client
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $collection = $db->Products;

    $criteria = filter_input(INPUT_GET, 'criteria', FILTER_SANITIZE_STRING);

    // explode the string received, to get the value of each field
    $strings = explode("||", $criteria);

    $filterSelection = $strings[0];
    $sortSelection = $strings[1];


    // defining the Filter criteria
    if ($filterSelection == "tops" OR $filterSelection == "bottoms" OR $filterSelection == "jackets")
    {
        $filterCriteria = [
            "prodType" => $filterSelection,
            "gender" => "women",
            "stockCount" => ['$gt' => 0]         
        ];
    }

    else if ($filterSelection == "black" OR $filterSelection == "blue" OR $filterSelection == "green")
    {
        $filterCriteria = [
            "colour" => $filterSelection,
            "gender" => "women",
            "stockCount" => ['$gt' => 0]         
        ];
    }

    else if ($filterSelection == "S" OR $filterSelection == "M" OR $filterSelection == "L")
    {
        $filterCriteria = [
            "size" => $filterSelection,
            "gender" => "women",
            "stockCount" => ['$gt' => 0]         
        ];
    }
    // if no filter was selected
    else 
    {
        $filterCriteria = [
            "gender" => "women",
            "stockCount" => ['$gt' => 0]         
        ];
    }



    // defining the Sort criteria
    if ($sortSelection == "priceAsc")
    {
        $sortCriteria = [
            'sort' =>
            ["unitPrice" => 1]
        ];
    }

    else if ($sortSelection == "priceDesc")
    {
        $sortCriteria = [
            'sort' =>
            ["unitPrice" => -1]
        ];
    }

    else if ($sortSelection == "titleAsc")
    {
        $sortCriteria = [
            'sort' =>
            ["title" => 1]
        ];
    }

    else if ($sortSelection == "titleDesc")
    {
        $sortCriteria = [
            'sort' =>
            ["title" => -1]
        ];
    }

    
    // performing the Filter and Sort query
    $products = $collection->find($filterCriteria, $sortCriteria);


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