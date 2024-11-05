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
    <link rel="stylesheet" href="css/thankyou-page.css">
    <script src="js/thankyou-page.js" defer></script>
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
                <p class="survey-title" id="nav-survey-title">SURVEY DONE</p>
                <p class="survey-desc">Thanks for answering!</p>
                <p class="date-created-text">Your response was submitted</p>
                <a href="response-page.php?id=<?php echo $id;?>&&survey_id=<?php echo $survey_id;?>">Submit another response</a>
                <a href="../index.php">Create My Own Form</a>          
                <input type="hidden" id="user_id" value="<?php echo $id?>">
                <input type="hidden" id="survey_id" value="<?php echo $survey_id?>">
            </div>
        </form>
    </main>

</body>
</html>