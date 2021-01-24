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
<body>
  <div class="jumbotron">
    <h1>Kenyon Bunker, CS 313</h1>
  </div>
  <!--Nav-->
  <?php include(dirname(__DIR__).'/rsc/nav.php'); ?>
  
  <form method="post">
    
    <div class="container">
    <h2>Shopping Cart</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Item</th>
                <th scope="col">Price</th>
                <th scope="col">Item#</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
    <?php
        //Inlcudes class for Products
        include 'Product.php';
        session_start();

        if (isset($i)) {
          //do nothing
        }
        else {
          $i = 0;
        }
        
        $total = 0;

        //Clears the cart
        if (isset($_POST["clearCart"])) {
          unset($_SESSION['cart']);
        }

        //Populates the cart
        if (isset($_SESSION['cart'])) {
          foreach ($_SESSION['cart'] as $item) {
              $total = $total + $item->getPrice();
              echo "
              <tr>
                  <th scope=\"row\">" . $item->getName() . "</th>
                  <td>" . $item->getPrice() . "</td>
                  <td>" . $i . "</td>
                  <td> <input type=\"submit\" name=\"removeItem" . $i . "\"value=\"Remove Item\" class=\"btn btn-primary\"/> </td>
              </tr>
              ";

              //Deletes items fromt eh cart
              if (isset($_POST["removeItem" . $i])) {
                unset($_SESSION['cart'][$i]);
                $_SESSION['cart'] = array_values($_SESSION['cart']);

                //forces page update
                header("Refresh:0");
              }
              $i++;
          }
        }
        
    ?>
  </tbody>
</table>

<!--Total-->
<?php 
  echo "Total: $" . $total . "<br>";
  $_SESSION['total'] = $total;
?>

<!--Back to shopping/browse page-->
<a href="browse.php" class="btn btn-primary">Continue Shopping</a>

<!--Clear Cart Button-->
<input type="submit" name="clearCart" value="Clear Cart" class="btn btn-primary"/>

<!-- Checkout Button -->
<a href="checkout.php" class="btn btn-primary">Checkout</a>
  
    </div>
    </form>
</body>
</html>
