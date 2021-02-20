<?php
    include(dirname(__DIR__).'/rsc/dbConnection.php');
    $db = get_db();

    $errChoreName = $errChoreDescription = $errChoreXPReward = "";
    $errRewardName = $errRewardDescription = "";
    $errAddingChore = "";

    //Validation for add new chores
    if (isset($_POST['addNewChore'])) {
        //No rewards in library
        $query = "SELECT reward_library.reward_id, reward_library.reward_name
            FROM reward_library
            WHERE household_id = '$householdID'";
        $rewardStatement = $db->prepare($query);
        $rewardStatement->execute();

        if($rewardStatement->rowCount() <= 0 ) {
            $errAddingChore = "You need to add a reward to the library first";
        }
    }

    //Validation for add new rewards
    if (isset($_POST['addNewReward'])) {

    }
?>