<?php
    $nameErr = $street1Err = $cityErr = $stateErr = $zipErr = "";
    $name = $street1 = $street2 = $city = $state = $zip = "";

    $validInputs = false;
    
    $_SESSION['userinfo']=array();

    

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = test_input($_POST["full_name"]);
        $street1 = test_input($_POST["street1"]);
        $street2 = test_input($_POST["street2"]);
        $city = test_input($_POST["city"]);
        $state = test_input($_POST["state"]);
        $zip = test_input($_POST["zip"]);
    }

    /* name */
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["full_name"])) {
            $nameErr = "Name is required";
            $validInputs = false;
            //echo "false";
          } else {
            $name = test_input($_POST["full_name"]);
            array_push($_SESSION['userinfo'], $name);
            $validInputs = true;
          }  
    }

    /* Street 1 */
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["street1"])) {
            $street1Err = "Street Address is required";
            $validInputs = false;
          } else {
            $street1 = test_input($_POST["street1"]);
            array_push($_SESSION['userinfo'], $street1);
            $validInputs = true;
          }
    }

    /* Street 2 */
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["street2"])) {
            //Do nothing
          } else {
            $street2 = test_input($_POST["street2"]);
            array_push($_SESSION['userinfo'], $street2);
            $validInputs = true;
          }
    }

    /* City */
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["city"])) {
            $cityErr = "City is required";
            $validInputs = false;
          } else {
            $city = test_input($_POST["city"]);
            array_push($_SESSION['userinfo'], $city);
            $validInputs = true;
          }
    }

    /* State */
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["state"])) {
            //$stateErr = "State is required";
          } else {
            $state = test_input($_POST["state"]);
            array_push($_SESSION['userinfo'], $state);
            $validInputs = true;
          }
    }

    /* Zip */
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["zip"])) {
            $zipErr = "Zipcode is required";
            $validInputs = false;
          } else {
            $zip = test_input($_POST["zip"]);
            array_push($_SESSION['userinfo'], $zip);
            $validInputs = true;
          }
    }

    if (empty($_POST["zip"]) or empty($_POST["zip"]) or empty($_POST["zip"]) or empty($_POST["zip"]))
    {
        $validInputs = false;
    }

    if ($validInputs === true) {
        //echo 'redirect';
        header("Location: confirmation.php");
        exit();
    }


    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>