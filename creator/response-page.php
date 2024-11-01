<?php
    session_start();
    include '../php/db_connect.php';

    if (!isset($_SESSION['loggedin'])) {
        header("Location: ../index.php");
        exit;
    }

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    $id = $_GET['id'];
    $survey_id = $_GET['survey_id'];

    $select = " SELECT * FROM survey_db.surveys WHERE survey_id = '$survey_id' ";
    $result = mysqli_query($conn, $select);
    while($row = mysqli_fetch_array($result)){
        $survey_title = $row['title'];
        $survey_description = $row['description'];
        $datetime = $row['created_at'];
        $date = new DateTime($datetime);
        $formattedDateTime = $date->format('F j, Y | H:i:s');
    }

    $select = " SELECT fname FROM survey_db.users WHERE user_id = '$id' ";
    $result = mysqli_query($conn, $select);
    while($row = mysqli_fetch_array($result)){
        $fname = $row['fname'];
    }

    
    

    if (isset($_SESSION['Deleted_Options']) || isset($_SESSION['Deleted_Questions'])) {
        $OptionsToDelete = json_decode($_SESSION['Deleted_Options'], true);
        $QuestionsToDelete = json_decode($_SESSION['Deleted_Questions'], true);

        if ((is_array($OptionsToDelete) && !empty($OptionsToDelete)) || (is_array($QuestionsToDelete) && !empty($QuestionsToDelete))) {
            
            $OptionsString = !empty($OptionsToDelete) ? implode(',', array_map('intval', $OptionsToDelete)) : '';
            $QuestionsString = !empty($QuestionsToDelete) ? implode(',', array_map('intval', $QuestionsToDelete)) : '';

            if (!empty($OptionsString)) {
                $delete_option = "DELETE FROM survey_db.choices WHERE choice_id IN ($OptionsString)";
                $delete_choices = mysqli_query($conn, $delete_option);
                
                if (!$delete_choices) {
                    echo "Error deleting choices: " . mysqli_error($conn);
                }
            }

            if (!empty($QuestionsString)) {
                $delete_question = "DELETE FROM survey_db.questions WHERE question_id IN ($QuestionsString)";
                $delete_questions = mysqli_query($conn, $delete_question);
                
                if (!$delete_questions) {
                    echo "Error deleting questions: " . mysqli_error($conn);
                }
            }

            unset($_SESSION['Deleted_Options'], $_SESSION['Deleted_Questions']);
            header('Location: home.php?id=' . $id);
            exit();
        }
    }

    if(isset($_POST['sign-out'])){
        session_destroy();
        header("location: ../index.php");
        exit;
    }

    if(isset($_POST['manage-acc'])){
        header("location: manage-acc.php?id=$id");
        exit;
    }

    if(isset($_POST['home-btn'])){
        header("location: home.php?id=$id");
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EazyPoll</title>
    <link rel="icon" href="../imgs/logo.png">
    <link rel="stylesheet" href="css/response-page.css">
    <script src="js/response-page.js" defer></script>
</head>
<body>
    <script src="../js/check-dark.js"></script>
    <nav id="nav-bar">
        <div id="nav-left-side">
            <img src="../imgs/logo.png" alt="Eazypoll logo" id="nav-logo">
            <input type="text" id="nav-title" value="<?php echo $survey_title;?>" readonly>
        </div>
        <div id="nav-center">
            <div id="links-container">
                <a href="#" class="nav-links">Questions</a>
                <a href="responses.php?id=<?php echo $id; ?>&&survey_id=<?php echo $survey_id; ?>" class="nav-links">Responses</a>
            </div>
        </div>
        <div id="nav-right-side">
            <form action="" method="post">
                <button class="home-btn" name="home-btn">
                    <img src="../imgs/home.svg" alt="Back to home button" id="nav-home-btn">
                </button>   
            </form>
            <button class="dark-mode-btn">
                <img src="../imgs/dark-mode-green.png" alt="Dark mode button" id="nav-dark-mode">
            </button>
            <div id="profile-container">
                <div id="nav-profile-img-container">
                    <img src="../imgs/default_profile_image_light.svg" alt="User's profile picture" id="nav-profile-img">
                </div>
                <div id="profile-options" class="hidden">
                    <div id="profile-options-img-container">
                        <img src="../imgs/default_profile_image_light.svg" alt="User's profile picture" id="profile-options-img">
                    </div>
                    <p>Hi, <?php echo $fname; ?>!</p>
                    <form action="" method="post">
                        <button class="profile-options-btns" id="manage-acc-btn" name="manage-acc"><img src="../imgs/manage_accounts.svg" alt="" class="profile-options-btns-imgs">Manage Account</button>
                    </form>
                    <div id="profile-options-btns-container">
                        <form action="" method="post">
                            <button class="profile-options-btns" id="add-acc-btn" name="add-acc"><img src="../imgs/plus_white.svg" alt="" class="profile-options-btns-imgs">Add Account</button>
                        </form>
                        <form action="" method="post"> 
                            <button class="profile-options-btns" name="sign-out"><img src="../imgs/signout.svg" alt=""  class="profile-options-btns-imgs">Sign Out</button>
                        </form>
                    </div>
                </div>
            </div>
            <button class="info-btn">
                <img src="../imgs/info_button_light.svg" alt="Play tutorial button" id="nav-info">
            </button>
        </div>
    </nav>

    <main>
        <form id="hiddenDataForm" method="post" class="main" action="custom-view.php?id=<?php echo $id;?>&survey_id=<?php echo $survey_id;?>">
            <div class="title-desc-container">
                <p class="survey-title" id="nav-survey-title"><?php echo $survey_title;?></p>
                <p class="survey-desc"><?php echo $survey_description;?></p>
                <p class="date-created-text">Date created: <?php echo $formattedDateTime;?></p>
            </div>

            <div id="survey-container">
                <?php 
                    $SELECT_DATA = " SELECT * FROM survey_db.questions WHERE survey_id = '$survey_id'";
                    $RESULT_DATA = mysqli_query($conn, $SELECT_DATA);
                    $question_count = mysqli_num_rows($RESULT_DATA);
                    $questionCounter = 0;
                    echo '<input type="hidden" id="question_count" value="'.$question_count.'">';
                    while($row = mysqli_fetch_array($RESULT_DATA)){
                        $question_id = $row['question_id'];
                        $question_text = $row['question_text'];
                        $question_type = $row['question_type'];
                        echo    '<div class="question-container" id="question'.$question_id.'">
                                    <div class="question-upper">
                                        <input type="text" name="question-title['.$questionCounter.']" class="question-title" placeholder="Untitled Question" value="'.$question_text.'">
                                        <input type="hidden" name="question-id['.$questionCounter.']" value="'.$question_id.'">
                                    </div>';
                        
                                    
                            if($question_type == "Multiple Choice" || $question_type == "Checkboxes"){
                                echo   '<div class="question-choices-container">';

                                    $SELECT_CHOICES = " SELECT * FROM survey_db.choices WHERE question_id = '$question_id'";
                                    $RESULT_CHOICES = mysqli_query($conn, $SELECT_CHOICES);
                                    $choiceCounter=0;
                                    while($row = mysqli_fetch_array($RESULT_CHOICES)){
                                        $choice_text = $row['choice_text'];
                                        $choice_id = $row['choice_id'];

                                        if ($question_type == "Checkboxes"){
                                            $inputType = "checkbox";
                                        }else{
                                            $inputType = "radio";
                                        }

                                        echo    '<div class="choice-container" id="option'.$choice_id.'">
                                                    <input type="'.$inputType.'" name="multiple-choice">
                                                    <input type="text" name="choice['.$questionCounter.']['.$choiceCounter.']" class="choice-input-text" placeholder="Option text" value="'.$choice_text.'" required>
                                                    <input type="hidden" name="choice-id['.$questionCounter.']['.$choiceCounter.']" value="'.$choice_id.'">
                                                </div>';
                                        $choiceCounter++;
                                    }
                                echo         '
                                        </div>
                                </div>';
                            }elseif ($question_type == "Short Answer") {
                                echo   '<div class="question-choices-container">
                                            <input type="text" class="short-answer-input" placeholder="Your answer" readonly>
                                        </div>
                                </div>';
                            }elseif ($question_type == "Dropdown") {
                                echo   '<div class="question-choices-container">
                                            <select class="dropdown-choices">
                                                <option value="True">True</option>
                                                <option value="False">False</option>
                                            </select>
                                        </div>
                                </div>';
                            }elseif ($question_type == "Paragraph") {
                                echo   '<div class="question-choices-container">
                                            <textarea name="" class="paragraph-textarea" placeholder="Your answer" rows="4" style="resize:none;" readonly></textarea>
                                        </div>
                                </div>';
                            }
                            $questionCounter++;
                    }
                ?>
            </div>

        </form>
    </main>

</body>
</html>