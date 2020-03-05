<?php
    // including the libraries
    require __DIR__. '/vendor/autoload.php';

    // create instance of mongodb client, select database and Customers collection
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $collection = $db->Customers;

    // get user's inputted data - filtering input to reduce the chance of sql injection etc
    $userData = filter_input(INPUT_POST, 'userData', FILTER_SANITIZE_STRING);

    // explode the string received, to get the value of each field
    $strings = explode("||", $userData);

    $name = $strings[0];
    $surname = $strings[1];
    $contactNo = $strings[2];
    $email = $strings[3];
    $password = $strings[4];
    $address = $strings[5];
    $city = $strings[6];
    $country = $strings[7];
    

    // creating a php array with the user's email to check if this email is already in use
    $checkUsername = [
        'email' => $email
    ];

    // getting the email of each documents inside the collection Customer
    $check = $collection->find($checkUsername);

    $clash = FALSE;

    // checking if email address is already in use for an existing customer
    foreach ($check as $user)
    {
        if ($email === $user['email'])
        {        
            $clash = TRUE;
        }
    }


    // if there is no other customer with this email, add the customer
    if ($clash != TRUE)
    {
        // convert to php array
        $userArray = [
        "name" => $name,
        "surname" => $surname,
        "contactNo" => $contactNo,
        "email" => $email,
        "password" => $password,
        "address" => $address,
        "city" => $city,
        "country" => $country
        ];

        // add the new customer to the database
        $insertResult = $collection->insertOne($userArray);

        // start session for this user
        $_SESSION['loggedUsername'] = $email;
    
        // confirming successful or uncussessful registration
        if ($insertResult->getInsertedCount() == 1)
        {
            echo 'Registration successful ' . $email;
        }
    }

    else
    {
        echo 'Registration NOT successful - email already in use.';
    }

?>