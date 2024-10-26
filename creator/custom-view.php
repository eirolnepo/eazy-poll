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
    }

    $select = " SELECT fname FROM survey_db.users WHERE user_id = '$id' ";
    $result = mysqli_query($conn, $select);
    while($row = mysqli_fetch_array($result)){
        $fname = $row['fname'];
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

    if(isset($_POST['save-btn'])){
        echo '<pre>';
        print_r($_POST);
        echo '</pre>';
        $survey_title = $_POST['survey-title'];
        $survey_desc = $_POST['description'];

        $insert_survey = "INSERT INTO survey_db.surveys (user_id,title,description) VALUES(?,?,?)";
                    
        $stmt = $conn -> prepare ($insert_survey);
        $stmt -> bind_param('iss',$id,$survey_title,$survey_desc);
            
        if($stmt->execute()){
            $survey_id = $stmt->insert_id;
            $questions = $_POST['question-title'];
            $count = count($questions);
            
            for($i=0; $i < $count; $i++){
                $question = $_POST['question-title'][$i];
                $type = $_POST['question-type'][$i];
                    if($questions==""){
                        break;
                    }else{
                        $insert_question = "INSERT INTO survey_db.questions (survey_id,question_text,question_type) VALUES(?,?,?)";
                                
                        $stmt = $conn -> prepare ($insert_question);
                        $stmt -> bind_param('iss',$survey_id,$question,$type);
                        
                            if($stmt->execute()){

                                if (isset($_POST['choice'][$i]) && is_array($_POST['choice'][$i])) {
                                    $count_choices = count($_POST['choice'][$i]);
                                } else {
                                    $count_choices = 0;
                                }

                                if($count_choices == 0){
                                    continue;
                                }else{
                                    if($type == "Multiple Choice" || $type == "Checkboxes"){
                                        if(isset($_POST['choice'][$i]) && is_array($_POST['choice'][$i])) {
                                            $count_choices = count($_POST['choice'][$i]); 
                                            
                                            $select = "SELECT LAST_INSERT_ID() AS question_id"; 
                                            $query = mysqli_query($conn, $select);

                                            if ($query) {
                                                $row = mysqli_fetch_array($query);
                                                $question_id = $row['question_id'];
                                            }
                                            
                                            for($j = 0; $j < $count_choices; $j++) {
                                                $choice = $_POST['choice'][$i][$j];
                    
                                                if(!empty($choice)) {
                                                    $insert_choice = "INSERT INTO survey_db.choices (question_id, choice_text) VALUES (?, ?)";
                                                    $stmt = $conn->prepare($insert_choice);
                                                    $stmt->bind_param('is', $question_id, $choice);
                                                    $stmt->execute();
                                                }
                                            }
                                        }
                                    }
                            }
                    }
                }

                
            }
            header("Location: home.php?id=$id");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EazyPoll</title>
    <link rel="icon" href="../imgs/logo.png">
    <link rel="stylesheet" href="css/custom-view.css">
    <script src="js/custom-view.js" defer></script>
</head>
<body>
    <nav id="nav-bar">
        <div id="nav-left-side">
            <img src="../imgs/logo.png" alt="Eazypoll logo" id="nav-logo">
            <input type="text" id="nav-title" value="<?php echo $survey_title;?>" readonly>
        </div>
        <div id="nav-center">
            <div id="links-container">
                <a href="#" class="nav-links">Questions</a>
                <a href="#" class="nav-links">Responses</a>
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
        <form action="" method="post" class="main">
            <div class="title-desc-container">
                <input type="text" name="survey-title" class="survey-title" id="nav-survey-title" value="<?php echo $survey_title;?>">
                <textarea name="survey-desc" class="survey-desc" placeholder="Survey Description"><?php echo $survey_description;?></textarea>
            </div>

            <div id="survey-container">
                <?php 
                    $SELECT_DATA = " SELECT * FROM survey_db.questions WHERE survey_id = '$survey_id'";
                    $RESULT_DATA = mysqli_query($conn, $SELECT_DATA);
                    while($row = mysqli_fetch_array($RESULT_DATA)){
                        $question_id = $row['question_id'];
                        $question_text = $row['question_text'];
                        $question_type = $row['question_type'];
                        echo    '<div class="question-container">
                                    <div class="question-upper">
                                        <input type="text" name="question-title[0]" class="question-title" placeholder="Untitled Question" value="',$question_text,'">
                                        <select name="question-type[0]" class="question-type">
                                            <option value="Multiple Choice" ' . ($question_type == "Multiple Choice" ? ' selected' : '') . '>Multiple Choice</option>
                                            <option value="Checkboxes" ' . ($question_type == "Checkboxes" ? ' selected' : '') . '>Checkboxes</option>
                                            <option value="Dropdown" ' . ($question_type == "Dropdown" ? ' selected' : '') . '>Dropdown</option>
                                            <option value="Short Answer" ' . ($question_type == "Short Answer" ? ' selected' : '') . '>Short Answer</option>
                                            <option value="Paragraph" ' . ($question_type == "Paragraph" ? ' selected' : '') . '>Paragraph</option>
                                        </select>
                                    </div>';
                                    
                            if($question_type == "Multiple Choice" || $question_type == "Checkboxes"){
                                echo   '<div class="question-choices-container">';

                                    $SELECT_CHOICES = " SELECT * FROM survey_db.choices WHERE question_id = '$question_id'";
                                    $RESULT_CHOICES = mysqli_query($conn, $SELECT_CHOICES);
                                    while($row = mysqli_fetch_array($RESULT_CHOICES)){
                                        $choice_text = $row['choice_text'];
                                        if ($question_type == "Checkboxes"){
                                            $inputType = "checkbox";
                                        }else{
                                            $inputType = "radio";
                                        }

                                        echo    '<div class="choice-container">
                                                    <input type="'.$inputType.'" name="multiple-choice">
                                                    <input type="text" class="choice-input-text" placeholder="Option text" name="choice" value="'.$choice_text.'" required>
                                                </div>';
                                    }
                                echo         '<img src="../imgs/plus_choices.svg" alt="Add choice button" class="add-choice-btn">
                                        </div>
                                            <img src="../imgs/delete.svg" alt="Delete question button" class="delete-question-btn">
                                </div>';
                            }elseif ($question_type == "Short Answer") {
                                echo   '<div class="question-choices-container">
                                            <input type="text" class="short-answer-input" placeholder="Your answer" readonly>
                                        </div>
                                            <img src="../imgs/delete.svg" alt="Delete question button" class="delete-question-btn">
                                </div>';
                            }elseif ($question_type == "Dropdown") {
                                echo   '<div class="question-choices-container">
                                            <select class="dropdown-choices">
                                                <option value="True">True</option>
                                                <option value="False">False</option>
                                            </select>
                                        </div>
                                            <img src="../imgs/delete.svg" alt="Delete question button" class="delete-question-btn">
                                </div>';
                            }elseif ($question_type == "Paragraph") {
                                echo   '<div class="question-choices-container">
                                            <textarea name="" class="paragraph-textarea" placeholder="Your answer" rows="4" style="resize:none;" readonly></textarea>
                                        </div>
                                            <img src="../imgs/delete.svg" alt="Delete question button" class="delete-question-btn">
                                </div>';
                            }

                    }
                ?>
            </div>

            <img src="../imgs/plus.svg" alt="Add options button" id="add-options-btn">

            <div id="add-options-container">
                <img src="../imgs/plus_choices.svg" alt="Add question button" id="add-question-btn" class="add-options-btns">
                <img src="../imgs/text_logo.svg" alt="Add title and description button" id="add-td-btn" class="add-options-btns">
                <img src="../imgs/image_logo.svg" alt="Add image logo" id="add-image-btn" class="add-options-btns">
                <button class="save-btn" name="save-btn">
                    <img src="../imgs/save.svg" alt="Add image logo" id="add-save-btn" class="add-options-btns">
                </button>       
            </div>
        </form>
    </main>
</body>
</html>