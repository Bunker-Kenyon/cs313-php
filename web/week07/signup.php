<?php
session_start();
include(dirname(__DIR__).'/rsc/dbConnection.php');
$db = get_db();
  
//if user is logged in redirect to choreboard page
if (isset($_SESSION['id'])) {
    header('Location: signin.php');
}

$error_message = '';
if (isset($_POST['submit'])) {

    extract($_POST);

    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $confPassword = htmlspecialchars($_POST['conf_password']);

    // Validate password strength
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
        echo 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
    }else{
        echo 'Strong password.';
    }
    
    try {
        if (!empty($email) && !empty($password) && $confPassword == $password) {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $query = 'INSERT INTO w7users(email, password)
                        VALUES(:email, :password)';

            $statement = $db->prepare($query);

            $statement->bindValue(':email', $email);
            $statement->bindValue(':password', $hashedPassword);

            $statement->execute();
            

            //header('Location: signin.php');
        } else {
            $error_message = 'Must have an email address and passwords must match.';
        }
    } catch (Exception $ex) {
        // Please be aware that you don't want to output the Exception message in
        // a production environment
        echo "Error with DB. Details: $ex";
        die();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <title>Kenyon Bunker CS 313</title>
</head>

<body>
    <div class="d-flex align-items-center justify-content-center" style="height: 350px">
        <div class="row">
            <div class="col-md-20">
                <h3>Sign Up</h3>
                <?php if (!empty($error_message)) { ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php } ?>
                <form method="post">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" name="email" placeholder="Email" required />
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input required minlength="7" type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password" required />
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword2">Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword2" name="conf_password" placeholder="Confirm Password" required />
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>