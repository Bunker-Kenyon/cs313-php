<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="stickyNotes.css">

    <title>Page Title</title>
</head>

<body>
    <?php
    include(dirname(__DIR__) . '/rsc/nav.php');
    include(dirname(__DIR__) . '/rsc/dbConnection.php');
    $db = get_db();


    ?>
    <?php include("choreBoardNav.php");?>

    <!-- Start Chores Table-->
    <h1>Chore Library</h1>
    <form id="choresForm" method="post" action="choreLibrary.php">
        <table class="table">
            <tr>
                <th>Chore Name</th>
                <th>Description</th>
                <th>XP Reward</th>
                <th>Reward</th>
                <th>Action</th>
            </tr>

            <?php
            $choreStatement = $db->prepare(
                'SELECT chore_library.chore_id, chore_library.chore_name, chore_library.description, chore_library.xp_reward, 
                        reward_library.reward_id, reward_library.reward_name, household.id, household.name_of_household
                    FROM chore_library
                    LEFT JOIN reward_library
                    ON chore_library.reward_library_id=reward_library.reward_id
                    LEFT JOIN public.household
                    ON chore_library.household_id=household.id
                    Where household.name_of_household = \'Smith\'
                    ORDER BY chore_library.chore_name'
            );
            $choreStatement->execute();

            $choreIDBtns = []; //Array for dynamic delete button names
            $choreUpdateBtns = []; //array for dyanmic update buttons
            $choreIDs = [];    //Array for available reward ids
            $updateChoreNames = [];
            $updateChoreDescriptions = [];
            $updateChoreRewardLibraryIDs = [];

            while ($row = $choreStatement->fetch(PDO::FETCH_ASSOC)) {
                array_push($choreIDBtns, "delete_chore" . $row['chore_id']);
                array_push($choreUpdateBtns, "update_chore" . $row['chore_id']);
                array_push($choreIDs, $row['chore_id']);
                array_push($updateChoreNames, $row['chore_name']);
                array_push($updateChoreDescriptions, $row['description']);
                array_push($updateChoreRewardLibraryIDs, $row['reward_id']);

                //Variables for form input data
                $choreID = "delete_chore" . $row['chore_id'];
                $choreUpdateBtn = "update_chore" . $row['chore_id'];
                $updateChoreName = "chore_name_input" . $row['chore_id'];
                $updateChoreDescription = "chore_description_input" . $row['chore_id'];
                $updateChoreXPReward = "chore_xp_reward_input" . $row['chore_id'];
                $updateChoreRewardInput = "update_rewards_input" . $row['chore_id'];
                $valueName = $row['chore_name'];
                $valueDescription = $row['description'];
                $valueXPReward = $row['xp_reward'];
                
                //chore table rows
                echo "  
                        <tr>
                            <td><input class=\"form-control\" type=\"text\" id=\"inputdefault\" name=$updateChoreName value=\"" . $valueName . "\"></td>
                            <td><input class=\"form-control\" type=\"text\" id=\"inputdefault\" name=$updateChoreDescription value=\"" . $valueDescription . "\"></td>
                            <td><input class=\"form-control\" type=\"text\" id=\"inputdefault\" name=$updateChoreXPReward value=\"" . $valueXPReward . "\"></td>
                            <td><select class=\"form-control\" id=\"inputdefault\" name=$updateChoreRewardInput>";
                        
                                $rewardstatement = $db->prepare(
                                    'SELECT reward_library.reward_id, reward_library.reward_name, reward_library.description, household.id, 
                                        household.name_of_household
                                    FROM reward_library
                                    LEFT JOIN public.household
                                    ON reward_library.household_id=household.id
                                    Where household.name_of_household = \'Smith\'
                                    ORDER BY reward_library.reward_name'
                                );
                                $rewardstatement->execute();
                                echo "<option value=\"" . $row['reward_id'] . "\">" . $row['reward_name'] . "</option>";
                                while ($row = $rewardstatement->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value=\"" . $row['reward_id'] . "\">" . $row['reward_name'] . "</option>";
                                    $_POST['update_reward_id'] = $row['reward_id'];
                                }
                        
                    echo "</select></td>
                            <td><button type=\"submit\" class=\"btn btn-primary\" name=$choreID>Delete</button><button type=\"submit\" class=\"btn btn-primary\" name=$choreUpdateBtn>Update</button></td>
                        </tr>
                    ";
            }

            ?>
            <!-- Start New Chores input row-->
            <tr>
                <td><input class="form-control" type="text" id="inputdefault" name="chore_name_input"></td>
                <td><input class="form-control" type="text" id="inputdefault" name="chore_description_input"></td>
                <td><input class="form-control" type="number" id="inputdefault" name="chore_xp_reward_input"></td>
                <td><select class="form-control" id="inputdefault" name="rewards_input">
                        <?php
                        $rewardstatement = $db->prepare(
                            'SELECT reward_library.reward_id, reward_library.reward_name, reward_library.description, household.id, 
                                household.name_of_household
                            FROM reward_library
                            LEFT JOIN public.household
                            ON reward_library.household_id=household.id
                            Where household.name_of_household = \'Smith\'
                            ORDER BY reward_library.reward_name'
                        );
                        $rewardstatement->execute();
                        while ($row = $rewardstatement->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value=\"" . $row['reward_id'] . "\">" . $row['reward_name'] . "</option>";
                            $_POST['reward_id'] = $row['reward_id'];
                        }
                        ?>
                    </select></td>
                <td><button type="submit" name="addNewChore" class="btn btn-primary">Add New Chore</button></td>
            </tr>
            <!-- End New Chores input row-->
        </table>
    </form>

    <?php
        //Insert Chore Data
    if (isset($_POST['addNewChore'])) {
        $choreName = $_POST['chore_name_input'];
        $choreDescription = $_POST['chore_description_input'];
        $isRepeatable = TRUE; //Implement later
        $xpReward = $_POST['chore_xp_reward_input'];
        //$reward = $_POST['rewards_input'];
        $rewardLibraryID = $_POST['rewards_input'];
        $householdID = 1; //TODO: Update to use current logged in user household_id

        //echo "Chore: " . $choreName . " Desc: " . $choreDescription . " XP: " . $xpReward . " Reward: " . $reward . " Reward ID: " . $rewardLibraryID . "<br>";

        try {
            $queryChore = 'INSERT INTO chore_library(chore_name, description, is_repeatable, xp_reward, reward_library_id, household_id)
                            VALUES(:choreName, :choreDescription, :isRepeatable, :xpReward, :rewardLibraryID, :householdID)';

            $statement = $db->prepare($queryChore);

            $statement->bindValue(':choreName', $choreName);
            $statement->bindValue(':choreDescription', $choreDescription);
            $statement->bindValue(':isRepeatable', $isRepeatable);
            $statement->bindValue(':xpReward', $xpReward);
            $statement->bindValue(':rewardLibraryID', $rewardLibraryID);
            $statement->bindValue(':householdID', $householdID);
            $statement->execute();

        } catch (Exception $ex) {
            // Please be aware that you don't want to output the Exception message in
            // a production environment
            echo "Error with DB. Details: $ex";
            die();
        }
        echo "<meta http-equiv='refresh' content='0'>";
    }

    //Delete Chore Data
    $countDelete = 0;
    foreach ($choreIDBtns as $choreIDbtn) {

        if (isset($_POST[strval($choreIDbtn)])) {
            /*echo "Deleted btn " . $choreIDbtn;
                    echo "<br>";
                    echo "Deleted id " . $choreIDs[$countDelete];
                    echo "<br>";*/
            $queryReward = 'DELETE FROM chore_library WHERE chore_id = :choreID';
            $statement = $db->prepare($queryReward);
            $statement->bindValue(':choreID', $choreIDs[$countDelete]);
            $statement->execute();
            echo "<meta http-equiv='refresh' content='0'>";
        }
        $countDelete++;
    }

    //uppdate chore data
    $countUpdate = 0;

    foreach ($choreUpdateBtns as $choreUpdateBtn) {

        if (isset($_POST[$choreUpdateBtn])) {
            $choreName = $_POST['chore_name_input' . $choreIDs[$countUpdate]];
            $choreDescription = $_POST['chore_description_input' . $choreIDs[$countUpdate]];
            $choreIsRepeatable = TRUE;
            $choreXPReward = $_POST['chore_xp_reward_input' . $choreIDs[$countUpdate]];
            $choreRewardLibraryID = $_POST['update_rewards_input' . $choreIDs[$countUpdate]];
            $householdID = 1; //Need to udpate to use hosuehold_id based on current logged on user
            $choreID = $choreIDs[$countUpdate];

            //echo "Chore: " . $choreName . " Desc: " . $choreDescription . " XP: " . $choreXPReward .  " Reward ID: " . $choreRewardLibraryID . "<br>";
            
            $queryChore = 'UPDATE chore_library 
                    SET chore_name = :choreName, description = :choreDescription, is_repeatable = :choreIsRepeatable, xp_reward = :choreXPReward, reward_library_id = :choreRewardLibraryID
                    WHERE chore_id = :choreID AND household_id = :householdID'; //Need to udpate to use hosuehold_id based on current logged on user
            $statement = $db->prepare($queryChore);
            $statement->bindValue(':choreName', $choreName);
            $statement->bindValue(':choreDescription', $choreDescription);
            $statement->bindValue(':choreIsRepeatable', $choreIsRepeatable);
            $statement->bindValue(':choreXPReward', $choreXPReward);
            $statement->bindValue(':choreRewardLibraryID', $choreRewardLibraryID);
            $statement->bindValue(':householdID', $householdID);
            $statement->bindValue(':choreID', $choreID);
            $statement->execute();
            echo "<meta http-equiv='refresh' content='0'>";
        }
        $countUpdate++;
}
    
    ?>

    <br><br>
    <!-- End Chores Table-->

    <!-- Start Rewards Table-->
    <h1>Reward Library</h1>
    <form id="rewardsForm" method="post" action="choreLibrary.php">
        <table class="table">
            <thead>
                <th scope="col">Reward</th>
                <th scope="col">Description</th>
                <th scope="col">Action</th>
            </thead>

            <?php
            $rewardstatement = $db->prepare(
                'SELECT reward_library.reward_id, reward_library.reward_name, reward_library.description, household.id, 
                        household.name_of_household
                        FROM reward_library
                        LEFT JOIN public.household
                        ON reward_library.household_id=household.id
                        Where household.name_of_household = \'Smith\'
                        ORDER BY reward_library.reward_name'
            );
            $rewardstatement->execute();


            $rewardIDBtns = []; //Array for dynamic delete button names
            $rewardUpdateBtns = []; //array for dyanmic update buttons
            $rewardIDs = [];    //Array for available reward ids
            $updateRewardNames = [];
            $updateRewardDescriptions = [];

            //Creates the table with buttons
            while ($row = $rewardstatement->fetch(PDO::FETCH_ASSOC)) {
                //builds dynamic buttons
                array_push($rewardIDBtns, "delete_reward" . $row['reward_id']);
                array_push($rewardUpdateBtns, "update_reward" . $row['reward_id']);
                array_push($rewardIDs, $row['reward_id']);
                array_push($updateRewardNames, $row['reward_name']);
                array_push($updateRewardDescriptions, $row['description']);

                //Variables for form input data
                $rewardID = "delete_reward" . $row['reward_id'];
                $rewardUpdateBtn = "update_reward" . $row['reward_id'];
                $updateRewardName = "reward_name_input" . $row['reward_id'];
                $updateRewardDescription = "reward_description_input" . $row['reward_id'];
                $valueName = $row['reward_name'];
                $valueDescription = $row['description'];

                echo "
                        <tr>
                            <td><input class=\"form-control\" type=\"text\" id=\"inputdefault\" name=$updateRewardName value=\"" . $valueName . "\"></td>
                            <td><input class=\"form-control\" type=\"text\" id=\"inputdefault\" name=$updateRewardDescription value=\"" . $valueDescription . "\"></td>
                            <td><button type=\"submit\" class=\"btn btn-primary\" name=$rewardID>Delete</button><button type=\"submit\" class=\"btn btn-primary\" name=$rewardUpdateBtn>Update</button></td>
                        </tr>
                    ";
            }
            ?>
            <!-- Start new rewards input -->
            <tr>
                <td><input class="form-control" type="text" id="inputdefault" name="reward_name_input"></td>
                <td><input class="form-control" type="text" id="inputdefault" name="reward_description_input"></td>
                <td><button type="submit" name="addNewReward" class="btn btn-primary">Add New Reward</button></td>
            </tr>

            <!-- End new rewards input-->

        </table>

    </form>

    <?php
    

        //Insert Reward Data
        if (isset($_POST['addNewReward'])) {
            $rewardName = $_POST['reward_name_input'];
            $rewardDescription = $_POST['reward_description_input'];
            $householdID = 1; //TODO: Update to use current logged in user household_id

            try {
                $queryReward = 'INSERT INTO reward_library(reward_name, description, household_id)
                                VALUES(:rewardName, :rewardDescription, :householdID)';

                $statement = $db->prepare($queryReward);

                $statement->bindValue(':rewardName', $rewardName);
                $statement->bindValue(':rewardDescription', $rewardDescription);
                $statement->bindValue(':householdID', $householdID);
                $statement->execute();
            } catch (Exception $ex) {
                // Please be aware that you don't want to output the Exception message in
                // a production environment
                echo "Error with DB. Details: $ex";
                die();
            }
            echo "<meta http-equiv='refresh' content='0'>";
        }

    $countDelete = 0;

    //Checks which delete button was pushed
    foreach ($rewardIDBtns as $rewardIDbtn) {

        if (isset($_POST[strval($rewardIDbtn)])) {
            /* echo "Deleted btn " . $rewardIDbtn;
                    echo "<br>";
                    echo "Deleted id " . $rewardIDs[$countDelete];
                    echo "<br>"; */
            $queryReward = 'DELETE FROM reward_library WHERE reward_id = :rewardID';
            $statement = $db->prepare($queryReward);
            $statement->bindValue(':rewardID', $rewardIDs[$countDelete]);
            $statement->execute();
            echo "<meta http-equiv='refresh' content='0'>";
        }
        $countDelete++;
    }

    //Checks which update button was pushed
    $countUpdate = 0;

    foreach ($rewardUpdateBtns as $rewardUpdateBtn) {

        if (isset($_POST[$rewardUpdateBtn])) {
            $rewardName = $_POST['reward_name_input' . $rewardIDs[$countUpdate]];
            $rewardDescription = $_POST['reward_description_input' . $rewardIDs[$countUpdate]];
            $householdID = 1; //Need to udpate to use hosuehold_id based on current logged on user
            $queryReward = 'UPDATE reward_library 
                    SET reward_name = :rewardName, description = :rewardDescription 
                    WHERE reward_id = :rewardID AND household_id = :householdID'; //Need to udpate to use hosuehold_id based on current logged on user
            $statement = $db->prepare($queryReward);
            $statement->bindValue(':rewardName', $rewardName);
            $statement->bindValue(':rewardDescription', $rewardDescription);
            $statement->bindValue(':rewardID', $rewardIDs[$countUpdate]);
            $statement->bindValue(':householdID', $householdID);
            $statement->execute();
            echo "<meta http-equiv='refresh' content='0'>";
        }
        $countUpdate++;
    }

    ?>
    <!-- End Rewards Table-->
</body>

</html>