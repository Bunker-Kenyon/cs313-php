<?php
    session_start();
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

  <link rel="stylesheet" type="text/css" href="..\styleGuide.css">
  <title>Kenyon Bunker CS 313</title>
</head>
<body>
    <div class="jumbotron">
        <h1>Kenyon Bunker, CS 313</h1>
    </div>
    <!--Nav Bar-->
    <?php include(dirname(__DIR__).'/rsc/nav.php'); ?>

    <?php
        include 'Product.php';

        //Create products

        //Stargate with DHD
        $stargate_W_DHD = new Product();
        $stargate_W_DHD->setName('Stargate with DHD');
        $stargate_W_DHD->setPrice(49.99);
        $stargate_W_DHD->setDescription("High quality 3D plastic model of the stargate and DHD. Diameter is 12 inches.");

        //SG1 Hoodie
        $SG1_Hoodie = new Product();
        $SG1_Hoodie->setName('SG1 Hoodie');
        $SG1_Hoodie->setPrice(48.99);
        $SG1_Hoodie->setDescription("High-quality polyester but feels as soft as cotton - Guaranteed. 
                                    The Hoodie made of lightweight and breathable insulating material against cold and 
                                    wind, with synthetic fibers that have membranes adhering to body heat, helping to avoid cold.");

        //SG Acction Figure Set
        $SG_ActionFigureSet = new Product();
        $SG_ActionFigureSet->setName("Stargate Action Figure Set");
        $SG_ActionFigureSet->setPrice(103.99);
        $SG_ActionFigureSet->setDescription("Set includes Stargate, DHD, SG1, and Gou'ald");

        //Sets the cart array if it is not set yet.
        if (isset($_SESSION['cart'])) {
            //Do nothing
        }
        else {
            $_SESSION['cart']=array();
        }
        
        //Product cards

        if ( isset($_POST["add1"]) ) {
            echo "add1 was clicked";
            array_push($_SESSION['cart'], $stargate_W_DHD);
        }
        if ( isset($_POST["add2"]) ) {
            echo "add2 was clicked";
            array_push($_SESSION['cart'], $SG1_Hoodie);
        }
        if ( isset($_POST["add3"]) ) {
            echo "add3 was clicked";
            array_push($_SESSION['cart'], $SG_ActionFigureSet);
        }

        //Clear Cart
        if (isset($_POST["clearCart"])) {
            unset($_SESSION['cart']);
        }
    ?>

    <!--Browse Section-->
    <!-- Product Cards -->
    <form method="post">
    <div class="container">
    <h2>Product Catalog</h2>
        <div class="row">
            <div class="col-sm">
                <div class="card" style="width: 18rem;">
                    <img class="card-img-top" src="stargate_W_DHD.jpg" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $stargate_W_DHD->getName() ?></h5>
                    <p class="card-text">Price: $<?php echo $stargate_W_DHD->getPrice()?></p>
                    <p class="card-text"><?php echo $stargate_W_DHD->getDescription()?></p>
                    <!-- <a href="?add1=" class="btn btn-primary">Add to cart</a> -->
                    <input type="submit" name="add1" value="Add to cart" class="btn btn-primary"/>
                </div>
            </div>
        </div>
            <div class="col-sm">
                <div class="card" style="width: 18rem;">
                    <img class="card-img-top" src="SG1_Hoodie.jpg" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $SG1_Hoodie->getName() ?></h5>
                        <p class="card-text"><?php echo $SG1_Hoodie->getPrice() ?></p>
                        <p class="card-text"><?php echo $SG1_Hoodie->getDescription() ?></p>
                        <!-- <a href="?add2=" class="btn btn-primary">Add to cart</a> -->
                        <input type="submit" name="add2" value="Add to cart" class="btn btn-primary"/>
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="card" style="width: 18rem;">
                    <img class="card-img-top" src="SG_ActionFigureSet.gif" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $SG_ActionFigureSet->getName() ?></h5>
                        <p class="card-text"><?php echo $SG_ActionFigureSet->getPrice() ?></p>
                        <p class="card-text"><?php echo $SG_ActionFigureSet->getDescription() ?></p>
                        <!-- <a href="?add3=" class="btn btn-primary">Add to cart</a> -->
                        <input type="submit" name="add3" value="Add to cart" class="btn btn-primary"/>
                    </div>
                </div>
            </div>
            
        </div>
        <!-- Go to Cart -->
        <a href="viewCart.php" class="btn btn-primary">Go to cart</a>
        <!-- Clear Cart -->
        <input type="submit" name="clearCart" value="Clear Cart" class="btn btn-primary"/>
    </div>
    </form>
</body>
</html>