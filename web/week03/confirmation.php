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
</style>
<body>
  <div class="jumbotron">
    <h1>Kenyon Bunker, CS 313</h1>
  </div>
  <!--Nav-->
  <?php 
    include(dirname(__DIR__).'/rsc/nav.php');

    include("validation.php");
    

  ?>

    <div class="container"> <!-- Confirmation Info -->
        <h2>Confirmation</h2>
        <h4>Items Purchased</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    //Inlcudes class for Products
                    include 'Product.php';
                    
                    session_start();

                    //Populates Purchased Items
                    if (isset($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $item) {
                            echo "
                            <tr>
                                <th scope=\"row\">" . $item->getName() . "</th>
                                <td>" . $item->getPrice() . "</td>
                            </tr>
                            ";
                        }
                    }
                ?>
            </tbody>
        </table>

        <!-- Total -->
        <?php 
        echo "Total: $" . $_SESSION['total'] . "<br>";
        ?>
        <h4>Shipping Information:</h4>
        <?php 
            foreach ($_SESSION['userinfo'] as $useritem)
            {
                echo $useritem . "<br>";
            }
            unset($_SESSION['userinfo']);
        ?>
        <!--Back to shopping/browse page-->
        <a href="browse.php" class="btn btn-primary">Continue Shopping</a>
    <div>
</body>
</html>
