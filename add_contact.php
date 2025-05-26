<?php 

    session_start();

    // get data from the form
    $first_name = filter_input(INPUT_POST, 'first_name');
    // alternative
    // $first_name = $_POST['first_name'];

    $last_name = filter_input(INPUT_POST, 'last_name');
    $email_address = filter_input(INPUT_POST, 'email_address');
    $phone_number = filter_input(INPUT_POST, 'phone_number');
    $status = filter_input(INPUT_POST, 'status'); //assigns of the vakue selected radio button
    $dob = filter_input(INPUT_POST, 'dob');

    require_once('database.php');

    //Add the contact to the database
    $query = 'INSERT INTO contacts
        (firsrName, lastName, emailAddress, phone, status, dob)
        VALUES
        (:firstName, :lastName, :emailAddress, :phone, :status, :dob)';

    $statemet = $db->prepare($query);
    $statemet->bindValue(':firstName', $first_name);   
    $statemet->bindValue(':lastName', $last_name); 
    $statemet->bindValue(':emailAddress', $email_address); 
    $statemet->bindValue(':phone', $phone_number); 
    $statemet->bindValue(':status', $status); 
    $statemet->bindValue(':dob', $dob); 

    $statemet->execute();
    $statemet->closeCursor();

    $_SESSION["fullName"] = $first_name . " " . $last_name;

    // redirect to confirmation page
    $url = "confirmation.php";
    header("Location: " . $url);
    die(); //releases add_contact.php from memory

    



?>