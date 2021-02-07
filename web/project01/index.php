<?php
session_start();
include(dirname(__DIR__).'/rsc/nav.php');  
include(dirname(__DIR__).'/rsc/dbConnection.php');
$db = get_db();
  
//if user is logged in redirect to choreboard page
if (isset($_SESSION['id'])) {
    header('Location: choreBoard.php');
}
  
$error_message = '';
if (isset($_POST['submit'])) {
 
    extract($_POST);
 
    if (!empty($email) && !empty($password)) {
        $statement = "SELECT id  FROM public.user WHERE email = ".  $db->quote($email) . " AND password = ". $db->quote($password)."";
        $result = $db->query($statement);
  
        if ($result->rowCount() > 0) {
            $_SESSION["user_choreBoard"] = $email;
            header('Location: choreBoard.php');

        } else {
            $error_message = 'Incorrect email or password.';
        }
    } else {
        $error_message = 'Please enter email and password.';
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login Form</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="stickyNotes.css">
    </head>
    <body>
    <div class="d-flex align-items-center justify-content-center" style="height: 350px">
            <div class="row">
                <div class="col-md-20">
                    <h3>Login to Chore Board</h3>
                    <?php if(!empty($error_message)) { ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php } ?>
                    <form method="post">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" name="email" placeholder="Email" required />
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password" required />
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>