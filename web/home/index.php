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

  

  <div class="row" id="row1">
    <div class="col-sm-4">
    <p>
          I’m Kenyon. I am 3rd generation native Arizonan. During my childhood years, I grew up in a little community in Mesa called Lehi. 
          In my teenage years, I grew up in Queen Creek. Now I live in San Tan Valley with my wife and 3 kids. 9, 6 and 2.
      </p>
      <p>I have been a member of the church my whole life. The gospel really came alive for me after my family moved to Queen Creek. 
          I supposed that’s when I became “converted”. This area and its people have been a huge blessing to me and my family.
      </p>
      <p>
          I served my mission at the Mesa Family History Center. There I learned to help others find their ancestors, work on and with computers. 
          My mission changed the entire course of my life. Prior to my mission, I wanted to be a K-9 cop. But thanks to the skills I learned on 
          my mission, IT was the field I got into. My Major is Software Development. I enjoy the integration of the gospel into secular learning here at BYU-I.
      </p>
      <p>
          I have many hobbies and interests. They currently are Legos, programming, woodworking, gaming and A Cappella (listening and singing). 
          I enjoy all things Star Trek, Star Wars and Stargate. My favorite food is pizza. I am an introvert and I like being one (most of the time).
      </p>
    </div>
    <div class="col-sm-8" >
    
    <div class="carousel slide" data-ride="carousel">
      <ul class="carousel-indicators">
        <li data-target="#demo" data-slide-to="0" class="active"></li>
        <li data-target="#demo" data-slide-to="1"></li>
        <li data-target="#demo" data-slide-to="2"></li>
        <li data-target="#demo" data-slide-to="3"></li>
      </ul>

      <!-- The slideshow -->
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="..\media\family.jpg" alt="family">
        </div>
        <div class="carousel-item">
          <img src="..\media\kanda.jpg" alt="Kenyon and Alea">
        </div>
        <div class="carousel-item">
          <img src="..\media\keepinItReal.jpg" alt="Keeping It Reel">
        </div>
        <div class="carousel-item">
          <img src="..\media\Kenyon2.jpg" alt="Kenyon">
        </div>
      </div>
      </div>
      
    </div>
</div>

</body>
</html>
