<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="stickyNotes.css">
    
<title>ChoreBoard</title>
</head>
<body>
  <?php 
    session_start();
    /* if (!isset($_SESSION['id'])) {
      header('Location: index.php');
  } */
    include(dirname(__DIR__).'/rsc/nav.php');
    include(dirname(__DIR__).'/rsc/dbConnection.php');
    $db = get_db();
  
  ?>
  <h1>Family Chores and To do Board</h1>
  <h3><a href="logout.php">Logout</a></h3>

  <!--Unassigned chores-->
  <h2>Unassigned chores</h2>

  <?php
    $statement = $db->prepare(
    'SELECT chores.chore_name, public.user.display_name, public.user.email, chores.description, date_completed, date_due, xp_reward, public.rewards.reward_name
      FROM chores
      LEFT JOIN public.user
      ON chores.assigned_to_user=public.user.id
      LEFT JOIN public.rewards
      ON chores.rewards_id=public.rewards.id
      ORDER BY chores.chore_name;');
  $statement->execute();

  echo "<ul>";
  while ($row = $statement->fetch(PDO::FETCH_ASSOC))
  {
    if ($row['display_name'] == NULL && $row['date_completed'] == NULL) {
      echo "
        <li>
        <a href=\"#\">
        <h2>" . $row['chore_name'] . "</h2>
        <p>" . $row['description'] . "</p>";

        //display assigned to only if the chore is assigned
        if ($row['display_name'] == !NULL){
          echo "<p>Assigned to: <strong>" . $row['display_name'] . "</strong></p>";
        }

        if ($row['date_due'] == !NULL){
          echo "<p>Due: " . $row['date_due'] . "</p>";
        }

      echo "
        <p>XP Reward: " . $row['xp_reward'] . "</p>
        <p> Reward: " . $row['reward_name'] . "</p>
      </a>
        </li>
      ";
    }
  }
  echo "</ul>";  
    ?>

<!--User's chores-->
<h2>My Chores</h2>
<?php
    $statement = $db->prepare(
    'SELECT chores.chore_name, public.user.display_name, public.user.email, chores.description, date_completed, date_due, xp_reward, public.rewards.reward_name
      FROM chores
      LEFT JOIN public.user
      ON chores.assigned_to_user=public.user.id
      LEFT JOIN public.rewards
      ON chores.rewards_id=public.rewards.id
      ORDER BY chores.chore_name;');
  $statement->execute();

  echo "<ul>";
  while ($row = $statement->fetch(PDO::FETCH_ASSOC))
  {
    if ($row['email'] == $_SESSION['user_choreBoard']) {
      echo "
        <li>
        <a href=\"#\">
        <h2>" . $row['chore_name'] . "</h2>
        <p>" . $row['description'] . "</p>";

        //display assigned to only if the chore is assigned
        if ($row['display_name'] == !NULL){
          echo "<p>Assigned to: <strong>" . $row['display_name'] . "</strong></p>";
        }

        if ($row['date_due'] == !NULL){
          echo "<p>Due: " . $row['date_due'] . "</p>";
        }

      echo "
        <p>XP Reward: " . $row['xp_reward'] . "</p>
        <p> Reward: " . $row['reward_name'] . "</p>
      </a>
        </li>
      ";
    }
  }
  echo "</ul>";  
    ?>
    
<!--Select User Section-->
<h2>View other user's chores</h2>
<?php
  
  $statement = $db->prepare(
    'SELECT public.user.display_name, id
      FROM public.user;');
  $statement->execute();
?>
  <!--Dropdown for select user-->
  <form action="#bottomOfList" method="post">
    <select name='select_user' onChange="this.form.submit()">
    <option value="s">Select</option>
      <option value="a">All</option>
        <?php
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . $row['id'] . "'>" . $row['display_name'] . "</option>";
          };
        ?>
    </select>
  </form>

<?php
  $statement = $db->prepare(
    'SELECT chores.chore_name, public.user.display_name, public.user.id, public.user.email, chores.description, date_completed, date_due, xp_reward, public.rewards.reward_name
      FROM chores
      LEFT JOIN public.user
      ON chores.assigned_to_user=public.user.id
      LEFT JOIN public.rewards
      ON chores.rewards_id=public.rewards.id
      ORDER BY chores.chore_name;');

    if(isset($_POST['select_user']) ) {
      $select_user = $_POST['select_user'];
      
        $statement->execute();

      //All Users
      if ( $select_user == 'a') {
        echo "Selected User: All";
        echo "<ul>";
          while ($row = $statement->fetch(PDO::FETCH_ASSOC))
          {
            if ($row['date_completed'] == NULL) {  
            echo "
              <li>
                <a href=\"#\">
                  <h2>" . $row['chore_name'] . "</h2>
                  <p>" . $row['description'] . "</p>";

                  //display assigned to only if the chore is assigned
                  if ($row['display_name'] == !NULL){
                    echo "<p>Assigned to: <strong>" . $row['display_name'] . "</strong></p>";
                  }

                  if ($row['date_due'] == !NULL){
                    echo "<p>Due: " . $row['date_due'] . "</p>";
                  }

                  echo "
                    <p>XP Reward: " . $row['xp_reward'] . "</p>
                    <p> Reward: " . $row['reward_name'] . "</p>
                </a>
                </li>
              ";
            }
          }
          echo "</ul>";
          
      }

      else {
          

          //Selected users
          $selectUserStatement = $db->prepare(
            'SELECT public.user.display_name, id
              FROM public.user
              WHERE id = ' . $select_user . ';');
          $selectUserStatement->execute();
          while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            if ($row['id'] == $select_user) {
              echo "Selected User: " . $row['display_name'];
              break;
            }
          }
          
          echo "<ul>";
          $statement->execute();
          while ($row = $statement->fetch(PDO::FETCH_ASSOC))
          {
            if ($row['id'] == $select_user) {
              echo "
                <li>
                <a href=\"#\">
                <h2>" . $row['chore_name'] . "</h2>
                <p>" . $row['description'] . "</p>";

                //display assigned to only if the chore is assigned
                if ($row['display_name'] == !NULL){
                  echo "<p>Assigned to: <strong>" . $row['display_name'] . "</strong></p>";
                }

                if ($row['date_due'] == !NULL){
                  echo "<p>Due: " . $row['date_due'] . "</p>";
                }

              echo "
                <p>XP Reward: " . $row['xp_reward'] . "</p>
                <p> Reward: " . $row['reward_name'] . "</p>
              </a>
                </li>
              ";
            }
          }
          echo "</ul>";
        
      
      }
    }
?>
<p id="bottomOfList"></p>

</body>
</html>