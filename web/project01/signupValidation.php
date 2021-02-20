<?php
    include(dirname(__DIR__).'/rsc/dbConnection.php');
    $db = get_db();
    $errEmailsDontMatch = $errEmailExists = "";
    $errPasswordsDontMatch = $errPasswordStrength = "";
    $errDisplayNameExists = "";
    $errHouseholdNameExists = "";
    $errEmptyForm = "";
    $errGeneral = "";

if (isset($_POST['submit'])) {
    extract($_POST);

    $email = "";
    $confEmail = "";
    $password = "";
    $confPassword = "";
    $displayName = "";
    $householdName = "";
    $userType = "";
    $userXP = 0;

    $validInputs = false;

    //User variables
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = htmlspecialchars($_POST['email']);
        $confEmail = htmlspecialchars($_POST['conf_email']);
        $password = htmlspecialchars($_POST['password']);
        $confPassword = htmlspecialchars($_POST['conf_password']);
        $displayName = htmlspecialchars($_POST['display_name']);
        $householdName = htmlspecialchars($_POST['household_name']);
        $userType = $_POST['inputUserType'];
        $userXP = 0;
    }

    //*** Empty form check ***
    if (!empty($email) && !empty($confEmail) && !empty($password) && $confPassword == $password && !empty($displayName) && !empty($householdName) && !empty($userType)) {
        $errEmptyForm = "";
    } else {
        $errEmptyForm = "All fields must be filled out.";
    }

    //***Email checks***
    //Emails match
    if ($email == $confEmail) {
        $errEmailsDontMatch = "";
    } else {
        $errEmailsDontMatch = "Emails do not match";
    }

    //Existing email query
    $queryEmail = 'SELECT email FROM public.users WHERE email = :email';
    $statementEmail = $db->prepare($queryEmail);
    $statementEmail->bindValue(':email', $email);
    $statementEmail->execute();

    if ($statementEmail->rowCount() > 0) {
        $errEmailExists = "Account already exists.";
    } else {
        //$validInputs = true;
        $errEmailExists = "";
    }

    //***Password checks***
    //Password Strength
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
        $errPasswordStrength =  'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
    } else {
        $errPasswordStrength = "";
    }

    //Passwords match
    if ($password != $confPassword) {
        $errPasswordsDontMatch = "Passwords do not match";
    } else {
        $errPasswordsDontMatch = "";
    }

    //***Existing household**
    $queryHouseHold = 'SELECT name_of_household FROM public.household WHERE name_of_household = :name_of_household';
    $statementHosuehold = $db->prepare($queryHouseHold);
    $statementHosuehold->bindValue(':name_of_household', $householdName);
    $statementHosuehold->execute();

    if ($statementHosuehold->rowCount() > 0) {
        $errHouseholdNameExists = "Household name already exists. Choose another household name.";
    } else {
        $errHouseholdNameExists = "";
    }

    //Final Checks and data insert
    if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
        $errPasswordStrength = 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
    }
    else{
            if (!empty($email) && !empty($confEmail) && !empty($password) && $confPassword == $password && !empty($displayName) && !empty($householdName)) {

                if ($email == $confEmail && $password == $confPassword) {
                    //Hash pw
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    //Existing email query
                    $queryEmail = 'SELECT email FROM public.users WHERE email = :email';
                    $statementEmail = $db->prepare($queryEmail);
                    $statementEmail->bindValue(':email', $email);
                    $statementEmail->execute();

                    //Check for existing user
                    if ($statementEmail->rowCount() > 0) {

                        //Check for existing household
                    } elseif ($statementHosuehold->rowCount() > 0) {

                    } else {
                        //AddHosuehold
                        $query = 'INSERT INTO household(name_of_household)
                                    VALUES(:name_of_household);';
                        $statement = $db->prepare($query);
                        $statement->bindValue(':name_of_household', $householdName);
                        $statement->execute();

                        //Add user
                        $query = 'INSERT INTO users(email, password, display_name, household_ID, user_type, xp_user)
                                    VALUES(:email, :password, :display_name, (SELECT id FROM household WHERE name_of_household = :name_of_household), :user_type, :xp_user)';
                        $statement = $db->prepare($query);
                        $statement->bindValue(':email', $email);
                        $statement->bindValue(':password', $hashedPassword);
                        $statement->bindValue(':display_name', $displayName);
                        $statement->bindValue(':name_of_household', $householdName);
                        $statement->bindValue(':user_type', $userType);
                        $statement->bindValue(':xp_user', $userXP);
                        $statement->execute();

                        $errGeneral = "";

                        header('Location: index.php');
                        exit();
                    }
                }
            } else {
                $errGeneral = "Check entries and try again.";
            }
    }
}
    //Implement at a later date
    /* function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    } */

?>