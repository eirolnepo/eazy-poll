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

    $select = " SELECT * FROM survey_db.questions WHERE survey_id = '$survey_id' ";
    $result = mysqli_query($conn, $select);
    $number_of_questions = mysqli_num_rows($result);

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

    if(isset($_POST['submit-btn'])){
        $insert = "INSERT INTO survey_db.respondents(survey_id) VALUES(?)";
                            
        $stmt = $conn -> prepare ($insert);
        $stmt -> bind_param('i',$survey_id);

        if($stmt->execute()){
            $respondent_id = $conn->insert_id;
            $checkboxResponses = [];

            for($i = 0; $i < $number_of_questions; $i++){
                $question_id = $_POST['question-id'][$i];

                $SELECT_DATA = " SELECT question_type FROM survey_db.questions WHERE question_id = '$question_id'";
                $RESULT_DATA = mysqli_query($conn, $SELECT_DATA);
                while($row = mysqli_fetch_array($RESULT_DATA)){
                    $question_type = $row['question_type'];
                }

                if($question_type == "Multiple Choice"){
                        $response_text = $_POST['multiple-choice'][$i];
                        $SELECT_DATA = " SELECT choice_id FROM survey_db.choices WHERE choice_text = '$response_text'";
                        $RESULT_DATA = mysqli_query($conn, $SELECT_DATA);
                        while($row = mysqli_fetch_array($RESULT_DATA)){
                            $choice_id = $row['choice_id'];
                        }
                            
                            if($response_text != ""){
                                $insert = "INSERT INTO survey_db.responses(respondent_id,question_id,choice_id,response_text) VALUES(?,?,?,?)";
                                
                                $stmt = $conn -> prepare ($insert);
                                $stmt -> bind_param('iiis',$respondent_id,$question_id,$choice_id,$response_text);
                                
                                $stmt->execute();
                            }
                        continue;
                   
                }elseif($question_type == "Checkboxes"){
                    $numcheckbox = $_POST['count-choices'];
                    

                    for($j = 0; $j <= $numcheckbox; $j++){
                        $choice_id =  $_POST['choice-id'][$i][$j];
                        $response_text = $_POST['checkbox'][$i][$j];
                        if($response_text != ""){
                            $insert = "INSERT INTO survey_db.responses(respondent_id,question_id,choice_id,response_text) VALUES(?,?,?,?)";
                            
                            $stmt = $conn -> prepare ($insert);
                            $stmt -> bind_param('iiis',$respondent_id,$question_id,$choice_id,$response_text);
                            
                            $stmt->execute();
                        }
                    }
                    continue;
                }elseif($question_type == "Short Answer"){
                    $response_text = $_POST['short_answer'];
                }elseif($question_type == "Dropdown"){
                    $response_text = $_POST['dropdown'];
                }elseif($question_type == "Paragraph"){
                    $response_text = $_POST['paragraph'];
                }

                $insert = "INSERT INTO survey_db.responses(respondent_id,question_id,response_text) VALUES(?,?,?)";
                        
                $stmt = $conn -> prepare ($insert);
                $stmt -> bind_param('iis',$respondent_id,$question_id,$response_text);
                            
                $stmt->execute();
                    
            }
        }
            header("Location: response-page.php?id=$id&&survey_id=$survey_id");
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
            <h1 id="nav-title">EazyPoll</h1>
        </div>
        <div id="nav-right-side">
            <button class="dark-mode-btn">
                <img src="../imgs/dark-mode-green.png" alt="Dark mode button" id="nav-dark-mode">
            </button>
        </div>
    </nav>

    <main>
        <form id="survey-form" method="post" class="main"">
            <div class="title-desc-container">
                <p class="survey-title" id="nav-survey-title"><?php echo $survey_title;?></p>
                <p class="survey-desc"><?php echo $survey_description;?></p>
                <p class="date-created-text">Date created: <?php echo $formattedDateTime;?></p>
                <input type="hidden" id="user_id" value="<?php echo $id?>">
                <input type="hidden" id="survey_id" value="<?php echo $survey_id?>">
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
                                        <p id="question-title['.$questionCounter.']" class="question-title">'.$question_text.'</p>
                                        <input type="hidden" name="question-id['.$questionCounter.']" value="'.$question_id.'">
                                        <select name="question-type['.$questionCounter.']" class="question-type" style="display:none">
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
                                    $choiceCounter=0;
                                    while($row = mysqli_fetch_array($RESULT_CHOICES)){
                                        $choice_text = $row['choice_text'];
                                        $choice_id = $row['choice_id'];

                                        if ($question_type == "Checkboxes"){
                                            $inputType = "checkbox";

                                            echo    '<div class="choice-container" id="option'.$choice_id.'">
                                                        <input type="'.$inputType.'" name="checkbox['.$questionCounter.']['.$choiceCounter.']" value="'.$choice_text.'">
                                                        <p id="choice['.$questionCounter.']['.$choiceCounter.']" class="choice-input-text">'.$choice_text.'</p>
                                                        <input type="hidden" name="choice-id['.$questionCounter.']['.$choiceCounter.']" value="'.$choice_id.'">
                                                    </div>';
                                        }else{
                                            $inputType = "radio";

                                            echo    '<div class="choice-container" id="option'.$choice_id.'">
                                                        <input type="'.$inputType.'" name="multiple-choice['.$questionCounter.']" value="'.$choice_text.'" required>
                                                        <p id="choice['.$questionCounter.']['.$choiceCounter.']" class="choice-input-text">'.$choice_text.'</p>
                                                        <input type="hidden" name="choice-id['.$questionCounter.']['.$choiceCounter.']" value="'.$choice_id.'">
                                                    </div>';
                                        }

                                        
                                        $choiceCounter++;
                                    }

                                echo         '<input type="hidden" class="add-choice-btn">
                                              <input type="hidden" name="count-choices" value="'.$choiceCounter.'">
                                        </div>
                                            <input type="hidden" class="delete-question-btn">
                                </div>';
                            }elseif ($question_type == "Short Answer") {
                                echo   '<div class="question-choices-container">
                                            <input type="text" name="short_answer" class="short-answer-input" placeholder="Your answer" required>
                                        </div>
                                                <input type="hidden" class="delete-question-btn">
                                </div>';
                            }elseif ($question_type == "Dropdown") {
                                echo   '<div class="question-choices-container">
                                            <select class="dropdown-choices" name="dropdown">
                                                <option value="True">True</option>
                                                <option value="False">False</option>
                                            </select>
                                        </div>
                                                <input type="hidden" class="delete-question-btn">
                                </div>';
                            }elseif ($question_type == "Paragraph") {
                                echo   '<div class="question-choices-container">
                                            <textarea name="paragraph" class="paragraph-textarea" placeholder="Your answer" rows="4" style="resize:none;" required></textarea>
                                        </div>
                                                <input type="hidden" class="delete-question-btn">
                                </div>';
                            }
                            $questionCounter++;
                    }
                ?>
            </div>

            <input type="hidden" id="add-options-btn">

            <div id="add-options-container">
                <img src="../imgs/plus_choices.svg" alt="Add question button" id="add-question-btn" class="add-options-btns">
                <img src="../imgs/text_logo.svg" alt="Add title and description button" id="add-td-btn" class="add-options-btns">
                <img src="../imgs/image_logo.svg" alt="Add image logo" id="add-image-btn" class="add-options-btns">
                <button class="save-btn" name="save-btn" onclick="submitHiddenQuestions()">
                    <img src="../imgs/save.svg" alt="Add image logo" id="add-save-btn" class="add-options-btns">
                </button>       
            </div>
            
            <div class="survey-btn">
                <button name="submit-btn" class="submit-btn">Submit</button>
                <button name="clear-btn" class="clear-btn" id="clear-btn">Clear Form</button>          
            </div>
        </form>
    </main>

</body>
</html>