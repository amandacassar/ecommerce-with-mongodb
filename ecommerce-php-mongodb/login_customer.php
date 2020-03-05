<?php
    // including the libraries
    require __DIR__. '/vendor/autoload.php';

    // create instance of mongodb client
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $collection = $db->Customers;

    // get user's inputted data - filtering input to reduce the chance of sql injection etc
    $credentials = filter_input(INPUT_POST, 'credentials', FILTER_SANITIZE_STRING);

    // explode the string received, to get the value of each field
    $strings = explode("||", $credentials);

    $email = $strings[0];
    $password = $strings[1];


    // creating a php array with the user's email to check if this email is already in use
    $checkUser = [
        'email' => $email
    ];


    // getting the record with the email entered from the database
    $check = $collection->find($checkUser);

    $match = FALSE;

    // checking that email and password entered correspond with a document in the database
    foreach ($check as $user)
    {
        if ($email == $user['email'] && $password == $user['password'])
        {
            $match = TRUE;
        }
    }


    // if the email and password entered are both correct - return success message;   else return error message
    if($match == TRUE)
    {
        // start session for this user
        $_SESSION['loggedUsername'] = $email;

        // start session management
        session_start();

        echo 'Successfully Logged In ' . $email;
    }
    else
    {
        echo 'Invalid email or password.  Try again.';
    }

?>