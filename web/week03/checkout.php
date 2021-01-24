<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <link rel="stylesheet" type="text/css" href="..\styleGuide.css">
  <title>Kenyon Bunker CS 313</title>
</head>
<style>
    label {
        padding-top:    10px;
        padding-right:  10px;
        padding-bottom: 10px;
    }

    .error {color: #FF0000;}
</style>
<body>
  <div class="jumbotron">
    <h1>Kenyon Bunker, CS 313</h1>
  </div>
  <!--Nav-->
  <?php 
    include(dirname(__DIR__).'/rsc/nav.php'); 
    session_start();

    include("validation.php");
    
  ?>

  <div class="container">
    <h2>Checkout</h2>
    <h4>Total: $ <?php echo $_SESSION['total']; ?></h4>

    <form method="post"> 

	<div class="form-group"> <!-- Full Name -->
		<label for="full_name_id" class="control-label">Full Name</label>
        <span class="error">* <?php echo $nameErr;?></span>
		<input type="text" class="form-control" id="full_name_id" name="full_name" placeholder="John Deer">
        
	<!-- Street 1 -->
		<label for="street1_id" class="control-label">Street Address 1</label>
        <span class="error">* <?php echo $street1Err;?></span>
		<input type="text" class="form-control" id="street1_id" name="street1" placeholder="Street address, P.O. box, company name, c/o">
						
	<!-- Street 2 -->
		<label for="street2_id" class="control-label">Street Address 2</label>
		<input type="text" class="form-control" id="street2_id" name="street2" placeholder="Apartment, suite, unit, building, floor, etc.">
	</div>

	<div class="form-inline">
        <!-- City-->
		<label for="city_id" class="control-label" style="padding-right: 10px;">City</label>
        <span class="error">* <?php echo $cityErr;?></span>
		<input type="text" class="form-control" id="city_id" name="city" placeholder="Smallville">
        

        <!-- State -->
        <label for="state_id" class="control-label" style="padding-right: 10px; padding-left: 10px;">State</label>
        <span class="error">* <?php echo $stateErr;?></span>
		<select class="form-control" id="state_id" name="state">
			<option value="AL">Alabama</option>
			<option value="AK">Alaska</option>
			<option value="AZ">Arizona</option>
			<option value="AR">Arkansas</option>
			<option value="CA">California</option>
			<option value="CO">Colorado</option>
			<option value="CT">Connecticut</option>
			<option value="DE">Delaware</option>
			<option value="DC">District Of Columbia</option>
			<option value="FL">Florida</option>
			<option value="GA">Georgia</option>
			<option value="HI">Hawaii</option>
			<option value="ID">Idaho</option>
			<option value="IL">Illinois</option>
			<option value="IN">Indiana</option>
			<option value="IA">Iowa</option>
			<option value="KS">Kansas</option>
			<option value="KY">Kentucky</option>
			<option value="LA">Louisiana</option>
			<option value="ME">Maine</option>
			<option value="MD">Maryland</option>
			<option value="MA">Massachusetts</option>
			<option value="MI">Michigan</option>
			<option value="MN">Minnesota</option>
			<option value="MS">Mississippi</option>
			<option value="MO">Missouri</option>
			<option value="MT">Montana</option>
			<option value="NE">Nebraska</option>
			<option value="NV">Nevada</option>
			<option value="NH">New Hampshire</option>
			<option value="NJ">New Jersey</option>
			<option value="NM">New Mexico</option>
			<option value="NY">New York</option>
			<option value="NC">North Carolina</option>
			<option value="ND">North Dakota</option>
			<option value="OH">Ohio</option>
			<option value="OK">Oklahoma</option>
			<option value="OR">Oregon</option>
			<option value="PA">Pennsylvania</option>
			<option value="RI">Rhode Island</option>
			<option value="SC">South Carolina</option>
			<option value="SD">South Dakota</option>
			<option value="TN">Tennessee</option>
			<option value="TX">Texas</option>
			<option value="UT">Utah</option>
			<option value="VT">Vermont</option>
			<option value="VA">Virginia</option>
			<option value="WA">Washington</option>
			<option value="WV">West Virginia</option>
			<option value="WI">Wisconsin</option>
			<option value="WY">Wyoming</option>
		</select>
        

        <!-- Zip -->
        <label for="zip_id" class="control-label" style="padding-right: 10px; padding-left: 10px;">Zip Code</label>
        <span class="error">* <?php echo $zipErr;?></span>	
		<input type="text" class="form-control" id="zip_id" name="zip" placeholder="#####">
        		
	
	</div>									
									
	
	<div class="form-group"> <!-- Confirmation Button -->
    <input type="submit" name="confirm" value="Confirm Purchase" class="btn btn-primary"/>

	</div>     
	
</form>

  </div>
</body>
</html>
