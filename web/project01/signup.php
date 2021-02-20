<?php
session_start();
include('signupValidation.php');
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="stickyNotes.css">

    <title>Chore Board: Signup</title>
</head>
<style>
    .error {color: #FF0000;}
</style>

<body>
<div class="jumbotron" style="background-image: url('Darkwood_Plank.jpg')">
    <h1>Family Chores and To do Board</h1>
</div>
<?php include("choreBoardNav.php");?>
    <div class="d-flex align-items-center justify-content-center">
        <div class="row">
            <div class="col-md-20">
                <h3>Sign Up</h3>
                <?php if (!empty($error_message)) { ?>
                    <div class="alert alert-danger"><?php echo $errEmptyForm . " " . $errGeneral; ?></div>
                <?php } ?>
                <form method="post">
                    <div class="form-group">
                        <label for="inputEmail">Email Address</label><span class="error"><?php echo " *" . $errEmailExists; ?></span>
                        <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Email" required />
                    </div>
                    <div class="form-group">
                        <label for="inputConfirmEmail">Confirm Email Address</label><span class="error"><?php echo " *" . $errEmailsDontMatch; ?></span>
                        <input type="email" class="form-control" id="inputConfirmEmail" name="conf_email" placeholder="Confirm Email" required />
                    </div>
                    <div class="form-group">
                        <label for="inputPassword1">Password</label><span class="error"><?php echo " *" . $errPasswordStrength; ?></span>
                        <input required minlength="7" type="password" class="form-control" id="inputPassword1" name="password" placeholder="Password" required />
                    </div>
                    <div class="form-group">
                        <label for="inputConfirmPassword">Confirm Password</label><span class="error"><?php echo " *" . $errPasswordsDontMatch; ?></span>
                        <input type="password" class="form-control" id="inputConfirmPassword" name="conf_password" placeholder="Confirm Password" required />
                    </div>
                    <div class="form-group">
                        <label for="inputDisplayName">Display Name</label><span class="error"><?php echo " *" . $errDisplayNameExists; ?></span>
                        <input type="text" class="form-control" id="inputDisplayName" name="display_name" placeholder="Ex: Derek OR Dad, etc" required />
                    </div>
                    <div class="form-group">
                        <label for="inputHouseholdName">Household Name</label><span class="error"><?php echo " *" . $errHouseholdNameExists; ?></span>
                        <input type="text" class="form-control" id="inputHouseholdName" name="household_name" placeholder="Ex: Last Name or Kayson and Roommates" required />
                    </div>
                    <div class="form-group">
                        <input type="radio" name="inputUserType" value="parent" required> Parent 
                        <input type="radio" name="inputUserType" value="standard"> Standard<br>
                    </div>
                    
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>