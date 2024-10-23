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

    if (isset($_POST['save-btn'])) {

        $survey_id = 1; // Adjust as necessary
        $questions = $_POST['question-title'] ?? [];
        $question_types = $_POST['question-type'] ?? [];
    
        // Ensure $questions is an array
        if (!is_array($questions)) {
            $questions = [$questions]; // Wrap in an array if it's a string
        }
    
        foreach ($questions as $index => $question_text) {
            if (!empty($question_text) && isset($question_types[$index])) {
                // Insert question into the database
                $stmt = $conn->prepare("INSERT INTO survey_db.questions (survey_id, question_text, question_type) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $survey_id, $question_text, $question_types[$index]);
    
                if ($stmt->execute()) {
                    $lastQuestionId = $conn->insert_id;
    
                    // Access choices safely, ensure to use $index
                    $options = $_POST['choice-input'][$index] ?? [];
    
                    // Check if options are indeed an array
                    if (!is_array($options)) {
                        $options = [$options]; // Wrap the string in an array
                    }
    
                    foreach ($options as $optionText) {
                        if (!empty($optionText)) {
                            // Insert each choice into the database
                            $stmt = $conn->prepare("INSERT INTO survey_db.choices (question_id, choice_text) VALUES (?, ?)");
                            $stmt->bind_param("is", $lastQuestionId, $optionText);
                            if (!$stmt->execute()) {
                                echo "Choice Error: " . $stmt->error; // Print error for choice insertion
                            }
                        }
                    }
                } else {
                    echo "Question Error: " . $stmt->error; // Print error for question insertion
                }
            }
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
    <link rel="stylesheet" href="css/custom-survey.css">
    <script src="js/custom-survey.js" defer></script>
</head>
<body>
    <nav id="nav-bar">
        <div id="nav-left-side">
            <img src="../imgs/logo.png" alt="Eazypoll logo" id="nav-logo">
            <h1 id="nav-title">Untitled Survey</h1>
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
            <img src="../imgs/dark-mode-green.png" alt="Dark mode button" id="nav-dark-mode">
            <div id="profile-container">
                <img src="../imgs/default_profile_image_light.svg" alt="User's profile picture" id="nav-profile-img">
                <div id="profile-options" class="hidden">
                    <img src="../imgs/default_profile_image_light.svg" alt="User's profile picture" id="profile-options-img">
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
            <img src="../imgs/info_button_light.svg" alt="Play tutorial button" id="nav-info">
        </div>
    </nav>

    <main>
        <form action="" method="post" class="main">
            <div id="title-desc-container">
                <input type="text" name="survey-title" id="survey-title" value="Untitled Survey">
                <textarea name="survey-desc" id="survey-desc" placeholder="Survey Description"></textarea>
            </div>

            <div id="survey-container">
                <div class="question-container">
                    <div class="question-upper">
                        <input type="text" name="question-title" class="question-title" placeholder="Untitled Question">
                        <select name="question-type" class="question-type">
                            <option value="Multiple Choice">Multiple Choice</option>
                            <option value="Checkboxes">Checkboxes</option>
                        </select>
                    </div>
                    <div class="question-choices-container">
                        <img src="../imgs/plus_choices.svg" alt="Add choice button" class="add-choice-btn">
                    </div>
                    <img src="../imgs/delete.svg" alt="Delete question button" class="delete-question-btn">
                </div>
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