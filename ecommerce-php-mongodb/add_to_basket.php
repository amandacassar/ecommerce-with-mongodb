<?php
    // including the libraries
    require __DIR__. '/vendor/autoload.php';

    // create instance of mongodb client
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $collection = $db->Baskets;

    // get username and product data - as one string, and the fields are seperated with "||"
    $userProduct = filter_input(INPUT_POST, 'userProduct', FILTER_SANITIZE_STRING);

    // explode the string received, to get the value of each field
    $strings = explode("||", $userProduct);


    // 1. SEARCH FOR BASKET OF THIS USERNAME
    $userBasket = [
        "username" => $strings[0]
    ];

    $searchBasket = $collection->find($userBasket);

    $basketExist = "";

    foreach($searchBasket as $basket)
    {
        $basketExist = $basket['username'];
    }


    // 2. ADD THE PRODUCT TO THE USER'S BASKET
    $itemAdded = 0;

    // if the current user has no basket yet
    if ($basketExist == "")
    {
        // convert to php array
        $basketArray = [
        "username" => $strings[0],
        "itemsList" => [[
            "productId" => $strings[1],
            "title" => $strings[2],
            "size" => $strings[3],
            "colour" => $strings[4],
            "unitPrice" => $strings[5],
            "imageUrl" => $strings[6],
            "quantity" => "1"
            ]]
        ];

        // inserting a new basket in the Baskets document
        $insertResult = $collection->insertOne($basketArray);
        
        if ($insertResult->getInsertedCount() == 1)
        {
            $itemAdded = 1;
        }   
    }

    // if current user has a basket already
    else
    {
        $itemArray = [
            '$push' => [
                "itemsList" => [
                    "productId" => $strings[1],
                    "title" => $strings[2],
                    "size" => $strings[3],
                    "colour" => $strings[4],
                    "unitPrice" => $strings[5],
                    "imageUrl" => $strings[6],
                    "quantity" => "1"
                ]
            ]
        ];

        // adding this product to the current user's basket which already exists
        $updateBasket = $collection->updateOne($userBasket, $itemArray);

        if ($updateBasket->getModifiedCount() == 1)
        {
            $itemAdded = 1;
        }        
    }    

    
    // 3. DEDUCT THE PRODUCT'S STOCK COUNT BY 1
    $searchArray = [
        "size" => $strings[3],
        "imageUrl" => $strings[6]
    ];

    $searchResult = $db->Products->find($searchArray);

    // if the item was successfully added to the basket
    if ($itemAdded == 1)
    {
        foreach($searchResult as $result)
        {
            $newStockCount = $result['stockCount'] - 1;
        }

        $updateArray = [
            '$set' => ["stockCount" => $newStockCount]
        ];    
        
        $updateProduct = $db->Products->updateOne($searchArray, $updateArray);
    }    


    // confirming successful or uncussessful addition of item into the customer's basket
    if ($itemAdded == 1)
    {
        echo 'Successfully added to basket ' . $itemAdded . ' product.';
    }
    else
    {
        echo 'Could not add this to basket';
    }
    
?>