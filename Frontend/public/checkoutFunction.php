<?php
//checking if all inputs are filled
if (isset($_POST["firstName"]) && isset($_POST["lastName"]) && isset($_POST["username"]) && isset($_POST["email"])&& isset($_POST["address"])&& isset($_POST["country"]) && isset($_POST["state"])&& isset($_POST["zip"])){
    
    
    // shipping info
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $country = $_POST['country'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];

    //card info ????
    
    $ccname = $_POST['ccname'];
    $ccnumber = $_POST['ccnumber'];
    $ccexpiration = $_POST['ccexpiration'];
    $cvv = $_POST['cvv'];

    //Server Validation for Shipping information
    $hasError = false;
    switch (true){
        case empty($firstName):
            $hasError = true;
            // console.log("First name cannot be empty.");
            break;
        // add case for checking valid username
        case empty($lastName):
            $hasError = true;
           // console.log("Last name cannot be empty.");
            break;
        //add case for checking valid email
         case empty($username):
        $hasError = true;
       // console.log("Username cannot be empty.");
            break;
            // add case for checking valid password
        case empty($email):
            $hasError = true;
         //   console.log("Email cannot be empty.");
            break;
        //add case for checking valid email
        case empty($address):
            $hasError = true;
        //    console.log("Address cannot be empty.");
            break;
        case empty($country):
            $hasError = true;
        //    console.log("Country cannot be empty.");
            break;
        // add case for checking valid password
        case empty($state):
            $hasError = true;
         //   console.log("State cannot be empty.");
            break;
        // add case for checking valid password
        case empty($zip):
            $hasError = true;
         //   console.log("Zip cannot be empty.");
            break;   
    }

    //If there are no validation errors
    if(!$hasError){
        //opening a rabbitMQclient connection
        global $rbMQc;
        $msg = "Sending shipping request";

        //creating a checkout array to store shipping values
        $checkout = array();
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $country = $_POST['country'];
        $state = $_POST['state'];
        $zip = $_POST['zip'];
    
        //sending received form responses to rabbitMQ
        $response = json_decode($rbMQc->send_request($register_req), true);

        //checking whether or not resgister was processed successfully/unsuccessfully
        switch($response['code']){
            case 200:
                redirect(get_url("orderSummary.php"));
                break;
            case 409:
                echo '<script language="javascript">';
                echo 'alert("' . $response['message'] . '")';
                echo '</script>';
                break;
            default:
                echo($response['message']);
    
          }
        } else {
            echo '<script language="javascript">';
            echo 'alert("' . $errorMsg . '")';
            echo '</script>';
        }

}
?>