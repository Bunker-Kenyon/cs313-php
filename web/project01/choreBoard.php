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
  include(dirname(__DIR__) . '/rsc/nav.php');
  include(dirname(__DIR__) . '/rsc/dbConnection.php');
  $db = get_db();

  ?>
  <h1>Family Chores and To do Board</h1>
  <?php include("choreBoardNav.php"); ?>
  <h3></h3>

  <!--Unassigned chores-->
  <h1>Unassigned chores</h1>
  <h2>Note: More chores and rewards can be added, updated or deleted from the 'Chore Library'. Link is above.</h2>

  <?php
  $statement = $db->prepare(
    'SELECT chores.chore_name, public.users.display_name, public.users.email, chores.description, date_completed, date_due, xp_reward, public.rewards.reward_name
      FROM chores
      LEFT JOIN public.users
      ON chores.assigned_to_user_id=public.users.id
      LEFT JOIN public.rewards
      ON chores.rewards_id=public.rewards.id
      ORDER BY chores.chore_name;'
  );
  $statement->execute();

  echo "<ul>";
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
        <select name="assign_chores_input" class="form-control" id="inputdefault" onChange="this.form.submit()">
          <?php
          $choreStatement = $db->prepare(
            'SELECT chore_library.chore_id, chore_library.chore_name
              FROM chore_library
              WHERE household_id = 1;' //TODO: add user/household functionality
          );
          $choreStatement->execute();
          while ($row = $choreStatement->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value=\"" . $row['chore_id'] . "\">" . $row['chore_name'] . "</option>";
            //$_POST['assign_chore_id'] = $row['chore_id'];
          }
          ?>
        </select>

        <!-- TODO: Implement at a later date -->
        <!-- <select class="form-control" id="inputdefault" name="assign_user_input">
          <option value="leave_unassigned">Leave Unnassigned</option>
          <?php
         /*  $rewardStatement = $db->prepare(
            'SELECT users.id, users.display_name
            FROM users
            WHERE household_id = 1;' //TODO: add user/household functionality
          );
          $rewardStatement->execute();
          while ($row = $rewardStatement->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value=\"" . $row['id'] . "\">" . $row['display_name'] . "</option>";
          } */
          ?>
        </select> -->
      </form>
        </a>
  </li>

  <?php
  echo "</li>";
  echo "</ul>";
  
  if (isset($_POST['assign_chores_input'])) {
    echo "Selected Chore ID: " . $_POST['assign_chores_input'] . "<br>";
    $choreID =$_POST['assign_chores_input'];
    $queryChore = 'INSERT INTO chores (chore_name, description, is_repeatable, xp_reward, rewards_id, household_id)
      SELECT chore_name, description, is_repeatable, xp_reward, reward_library_id, household_id
      FROM chore_library
      WHERE chore_id = :choreID;';
    $statement = $db->prepare($queryChore);
    $statement->bindValue(':choreID', $choreID);
    $statement->execute();
    echo "<meta http-equiv='refresh' content='0'>";
  }
    
    
  ?>

  

  <!--User's chores-->
  <h1>My Chores</h1>
  <?php
  $statement = $db->prepare(
    'SELECT chores.chore_name, public.users.display_name, public.users.email, chores.description, date_completed, date_due, xp_reward, public.rewards.reward_name
      FROM chores
      LEFT JOIN public.users
      ON chores.assigned_to_user_id=public.users.id
      LEFT JOIN public.rewards
      ON chores.rewards_id=public.rewards.id
      ORDER BY chores.chore_name;'
  );
  $statement->execute();

  echo "<ul>";
  while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    if ($row['email'] == $_SESSION['user_choreBoard']) {
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

  $statement = $db->prepare(
    'SELECT public.users.display_name, id
      FROM public.users;'
  );
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
    'SELECT chores.chore_name, public.users.display_name, public.users.id, public.users.email, chores.description, date_completed, date_due, xp_reward, public.rewards.reward_name
      FROM chores
      LEFT JOIN public.users
      ON chores.assigned_to_user_id=public.users.id
      LEFT JOIN public.rewards
      ON chores.rewards_id=public.rewards.id
      ORDER BY chores.chore_name;'
  );

  if (isset($_POST['select_user'])) {
    $select_user = $_POST['select_user'];

    $statement->execute();

    //All Users
    if ($select_user == 'a') {
      echo "Selected User: All";
      echo "<ul>";
      while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
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
      $selectUserStatement = $db->prepare(
        'SELECT public.users.display_name, id
              FROM public.users
              WHERE id = ' . $select_user . ';'
      );
      $selectUserStatement->execute();
      while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        if ($row['id'] == $select_user) {
          echo "Selected User: " . $row['display_name'];
          break;
        }
      }

      echo "<ul>";
      $statement->execute();
      while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
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