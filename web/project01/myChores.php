<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="stickyNotes.css">
    
<title>Page Title</title>
</head>
<body>
  <?php include(dirname(__DIR__).'/rsc/nav.php');
        include(dirname(__DIR__).'/rsc/dbConnection.php');
        $db = get_db();
  
  ?>
  <h1>My Chores</h2>

 <ul>
  <?php
  $user = 'Derek';
    $statement = $db->prepare(
      'SELECT chores.name, description, public.user.display_name
      FROM chores
      LEFT JOIN public.user
      ON chores.assigned_to_user=public.user.id
	  WHERE public.user.display_name=\'Derek\' --Need to put variable here
      ORDER BY chores.name;');
    $statement->execute();

    while ($row = $statement->fetch(PDO::FETCH_ASSOC))
	{
		
    echo "
        <li>
        <a href=\"#\">
          <h2>" . $row['name'] . "</h2>
          <p>" . $row['description'] . "</p>
          <p>Assigned to: <strong>" . $row['display_name'] . "</strong></p>
        </a>
      </li>
        ";
	}
      
    ?>
    <
  

</body>
</html>