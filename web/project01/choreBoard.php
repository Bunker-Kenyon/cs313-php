<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="stickyNotes.css">

  <title>Chore Board</title>
</head>

<body>
  <?php
  session_start();
  include(dirname(__DIR__) . '/rsc/nav.php');
  include(dirname(__DIR__) . '/rsc/dbConnection.php');
  $db = get_db();

  if (!isset($_SESSION['userId'])) {
    header('Location: index.php');
  }
  $householdID = $_SESSION['userHouseholdID'];


  ?>
  <div class="jumbotron" style="background-image: url('Darkwood_Plank.jpg')">
    <h1>Family Chores and To do Board</h1>
  </div>
  
  <?php include("choreBoardNav.php"); ?>
  <h3></h3>

  <!--Unassigned chores-->
  <h1>Unassigned chores</h1>
  <h2>Note: More chores and rewards can be added, updated or deleted from the 'Chore Library'. Link is above.</h2>

  <?php
  $queryUnassignedChores = 'SELECT chores.chore_name, public.users.display_name, public.users.email, chores.description, date_completed, date_due, xp_reward, 
      public.reward_library.reward_name
    FROM chores
    LEFT JOIN public.users
    ON chores.assigned_to_user_id=public.users.id
    LEFT JOIN public.reward_library
    ON chores.rewards_id=public.reward_library.reward_id
    WHERE chores.household_id = :householdID
    ORDER BY chores.chore_name;';

  $statement = $db->prepare($queryUnassignedChores);
  $statement->bindValue(':householdID', $householdID);
  $statement->execute();

  

  echo "<ul>";

  if ($statement->rowCount() <= 0) {
    echo "
    <li>
      <a href=\"#\">
        <h2>No Chores!</h2>
        <p>If no chores are showing here:</p>
        <p>Go to the \"Chore and Reward Library\" and add chores to your library</p>
        <p> --OR--</p>
        <p>Add more to the right. (minimum of one chore needs to be added to the library first)</p>
        
      </a>
    </li>
    ";
  }
  while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    if ($row['display_name'] == NULL && $row['date_completed'] == NULL) {
      echo "
        <li>
        <a href=\"#\">
        <h2>" . $row['chore_name'] . "</h2>
        <p>" . $row['description'] . "</p>";

      //display assigned to only if the chore is assigned
      if ($row['display_name'] == !NULL) {
        echo "<p>Assigned to: <strong>" . $row['display_name'] . "</strong></p>";
      }

      if ($row['date_due'] == !NULL) {
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
  echo "<li>";

  //Assign chores card
  ?>
  <li>
    <a href="#">
      <h2>Add a Chore from the library</h2>
      <form id="assign_chores" method="post" action="">

        <!--Chore Selection-->
        <select name="assign_chores_input" class="form-control" id="inputdefault">
          <?php
          $query = "SELECT chore_library.chore_id, chore_library.chore_name
            FROM chore_library
            WHERE household_id = '$householdID'";
          $choreStatement = $db->prepare($query);
          $choreStatement->execute();

          //Fills the chore selection dropdown
          while ($row = $choreStatement->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value=\"" . $row['chore_id'] . "\">" . $row['chore_name'] . "</option>";
          }
          ?>
        </select>
        
        <!--User Selection-->
        <select class="form-control" id="inputdefault" name="assign_user_input">
          <option value="leave_unassigned">Leave Unnassigned</option>
          <?php
          $query = "SELECT users.id, users.display_name
            FROM users
            WHERE household_id = '$householdID'";
          $rewardStatement = $db->prepare($query);
          $rewardStatement->execute();

          //Fills the user selection drop down
          while ($row = $rewardStatement->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value=\"" . $row['id'] . "\">" . $row['display_name'] . "</option>";
          }
          ?>
        </select>
        <button type="submit" class="btn btn-primary" name="assign_chore_button">Assign Chore</button>
      </form>
    </a>
  </li>

  <?php
  echo "</li>";
  echo "</ul>";

  if (isset($_POST['assign_chore_button'])) {
    //Leave chore unassigned
    if ($_POST['assign_user_input'] == 'leave_unassigned') {
      echo "Chores is unassigned<br>";
      $choreID = $_POST['assign_chores_input'];
      $queryChore = 'INSERT INTO chores (chore_name, description, is_repeatable, xp_reward, rewards_id, household_id)
                      SELECT chore_name, description, is_repeatable, xp_reward, reward_library_id, household_id
                      FROM chore_library
                      WHERE chore_id = :choreID;';
      $statement = $db->prepare($queryChore);
      $statement->bindValue(':choreID', $choreID);
      $statement->execute();
      echo "<meta http-equiv='refresh' content='0'>";
    } else{ //assign chore to user
        echo "Chore is assigned to: " . $_POST['assign_user_input'] . "<br>";
        $choreID = $_POST['assign_chores_input'];
        $queryChore = 'INSERT INTO chores (chore_name, description, is_repeatable, xp_reward, rewards_id, household_id, assigned_to_user_id)
                      SELECT chore_name, description, is_repeatable, xp_reward, reward_library_id, household_id, (SELECT users.id FROM users WHERE id = :userId)
                      FROM chore_library
                      WHERE chore_id = :choreID;';
        $statement = $db->prepare($queryChore);
        $statement->bindValue(':userId', $_POST['assign_user_input']);
        $statement->bindValue(':choreID', $choreID);
        $statement->execute();
        echo "<meta http-equiv='refresh' content='0'>";
    }
  }
  ?>

  <!--User's chores-->
  <h1>My Chores</h1>
  <?php
  $queryUserChores =
    'SELECT chores.chore_name, public.users.id, public.users.display_name, public.users.email, chores.description, date_completed, date_due, xp_reward, public.reward_library.reward_name
      FROM chores
      LEFT JOIN public.users
      ON chores.assigned_to_user_id=public.users.id
      LEFT JOIN public.reward_library
      ON chores.rewards_id=public.reward_library.reward_id
      WHERE public.users.id = :userId
      ORDER BY chores.chore_name;';
  $statement = $db->prepare($queryUserChores);
  $statement->bindValue(':userId', $_SESSION['userId']);
  $statement->execute();

  echo "<ul>";
  while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    //var_dump($row);
    if ($row['id'] == $_SESSION['userId']) {
      echo "
        <li>
        <a href=\"#\">
        <h2>" . $row['chore_name'] . "</h2>
        <p>" . $row['description'] . "</p>";

      //display assigned to only if the chore is assigned
      if ($row['display_name'] == !NULL) {
        echo "<p>Assigned to: <strong>" . $row['display_name'] . "</strong></p>";
      }

      if ($row['date_due'] == !NULL) {
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
  <h1>View other user's chores</h1>
  <?php

    $queryUsers = 'SELECT public.users.display_name, id
      FROM public.users
      WHERE household_id = :householdID;';
      $statementUsers = $db->prepare($queryUsers);
      $statementUsers->bindValue(':householdID', $householdID);
      $statementUsers->execute();
  ?>
  <!--Dropdown for select user-->
  <form action="#bottomOfList" method="post">
    <select name='select_user' onChange="this.form.submit()">
      <option value="s">Select</option>
      <option value="a">All</option>
      <?php
      while ($row = $statementUsers->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='" . $row['id'] . "'>" . $row['display_name'] . "</option>";
      };
      ?>
    </select>
  </form>

  <?php
  $queryChores = 
    'SELECT chores.chore_name, public.users.display_name, public.users.id, public.users.email, chores.description, date_completed, date_due, xp_reward, public.reward_library.reward_name
      FROM chores
      LEFT JOIN public.users
      ON chores.assigned_to_user_id=public.users.id
      LEFT JOIN public.reward_library
      ON chores.rewards_id=public.reward_library.reward_id
      WHERE chores.household_id = :householdID
      ORDER BY chores.chore_name;';

      $statementChores = $db->prepare($queryChores);
      $statementChores->bindValue(':householdID', $householdID);
      $statementChores->execute();

  if (isset($_POST['select_user'])) {
    $select_user = $_POST['select_user'];

    $statementChores->execute();

    //All Users
    if ($select_user == 'a') {
      echo "Selected User: All";
      echo "<ul>";
      while ($row = $statementChores->fetch(PDO::FETCH_ASSOC)) {
        if ($row['date_completed'] == NULL) {
          echo "
              <li>
                <a href=\"#\">
                  <h2>" . $row['chore_name'] . "</h2>
                  <p>" . $row['description'] . "</p>";

          //display assigned to only if the chore is assigned
          if ($row['display_name'] == !NULL) {
            echo "<p>Assigned to: <strong>" . $row['display_name'] . "</strong></p>";
          }

          if ($row['date_due'] == !NULL) {
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
    } else {
      //Selected users
      $queryUsers = 'SELECT public.users.display_name, id
        FROM public.users
        WHERE household_id = :householdID;';
      $statementUsers = $db->prepare($queryUsers);
      $statementUsers->bindValue(':householdID', $householdID);
      $statementUsers->execute();
      while ($row = $statementUsers->fetch(PDO::FETCH_ASSOC)) {
      
          echo "Selected User: " . $row['display_name'];
         
      }

      echo "<ul>";
      $statementChores->execute();
      while ($row = $statementChores->fetch(PDO::FETCH_ASSOC)) {
        if ($row['id'] == $select_user) {
          echo "
                <li>
                <a href=\"#\">
                <h2>" . $row['chore_name'] . "</h2>
                <p>" . $row['description'] . "</p>";

          //display assigned to only if the chore is assigned
          if ($row['display_name'] == !NULL) {
            echo "<p>Assigned to: <strong>" . $row['display_name'] . "</strong></p>";
          }

          if ($row['date_due'] == !NULL) {
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