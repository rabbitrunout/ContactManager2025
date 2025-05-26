<?php 

    session_start();

    require_once('database.php');

    // Get form data
    $first_name = filter_input(INPUT_POST, 'first_name');
    // alternative
    // $first_name = $_POST['first_name'];

    $last_name = filter_input(INPUT_POST, 'last_name');
    $email_address = filter_input(INPUT_POST, 'email_address');
    $phone_number = filter_input(INPUT_POST, 'phone_number');
    $status = filter_input(INPUT_POST, 'status'); //assigns of the vakue selected radio button
    $dob = filter_input(INPUT_POST, 'dob');


    // Check for duplicate email
    // require_once('database.php');
    $queryContacts = 'SELECT * FROM contacts';
    $statement1 = $db->prepare($queryContacts);
    $statement1->execute();
    $contacts = $statement1->fetchAll();

    $statement1->closeCursor();

    foreach ($contacts as $contact)
    {
        if ($email_address == $contact["emailAddress"])
        {
            $_SESSION["add_error"] = "Invalid data. Duplicate email address. Try again.";

            $url = "error.php";
            header("Location: " . $url);
            die();
        }
    }
// Check for empty fields
    if ($first_name == null || $last_name == null ||
        $email_address == null || $phone_number == null ||
        $dob == null)
    {
         $_SESSION["add_error"] = "Invalid data. Please check all fields and try again.";

            $url = "error.php";
            header("Location: " . $url);
            die();

    }
    else
    {


        // Insert into database
        $query = 'INSERT INTO contacts
            (firstName, lastName, emailAddress, phone, status, dob)
            VALUES
            (:firstName, :lastName, :emailAddress, :phone, :status, :dob)';

        $statement = $db->prepare($query);
        $statement->bindValue(':firstName', $first_name);   
        $statement->bindValue(':lastName', $last_name); 
        $statement->bindValue(':emailAddress', $email_address); 
        $statement->bindValue(':phone', $phone_number); 
        $statement->bindValue(':status', $status); 
        $statement->bindValue(':dob', $dob); 

        $statement->execute();
        $statement->closeCursor();
    
    }
// Save session data and redirect
    $_SESSION["fullName"] = $first_name . " " . $last_name;

    // redirect to confirmation page
    $url = "confirmation.php";
    header("Location: " . $url);
    die(); //releases add_contact.php from memory

?>