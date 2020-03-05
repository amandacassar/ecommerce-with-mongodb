<?php
    // including the libraries
    require __DIR__. '/vendor/autoload.php';

    // create instance of mongodb client
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $collection = $db->Baskets;

    // get products data - as one string, and fields seperated with "||"
    $userProduct = filter_input(INPUT_POST, 'userProduct', FILTER_SANITIZE_STRING);

    // explode the string received, to get the value of each field
    $strings = explode("||", $userProduct);

    // search for the basket of this useraname
    $userBasket = [
        "username" => $strings[0]
    ];

    $searchBasket = $collection->find($userBasket);


    // delete this item from the user's basket
    $basketItem = [
       '$pull' => [
            "itemsList" => [
                "size" => $strings[1],
                "imageUrl" => $strings[2],
            ]
        ]
    ];

    $removeProduct = $collection->updateOne($userBasket, $basketItem);
    

    // update the product's stock count
    $searchArray = [
        "size" => $strings[1],
        "imageUrl" => $strings[2]
    ];

    $searchResult = $db->Products->find($searchArray);

    
    // if the item was successfully removed from the basket, update the product's stock count
    if ($removeProduct->getModifiedCount() == 1)
    {
        foreach($searchResult as $result)
        {
            $newStockCount = $result['stockCount'] + 1;
        }

        $updateArray = [
            '$set' => ["stockCount" => $newStockCount]
        ];    
        
        $updateProduct = $db->Products->updateOne($searchArray, $updateArray);
    }


    // confirming successful or uncussessful addition of item into the customer's basket
    if ($removeProduct->getModifiedCount() == 1)
    {
        echo 'Successfully removed the product from your basket';
    }
    else
    {
        echo 'Could not remove this product from your basket';
    }
 
?>