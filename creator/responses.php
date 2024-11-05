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
        $status1 = $row['status'];

        if($status1 == "ACCEPTING"){
            $status2 = "checked";
        }elseif($status1 == "NOT ACCEPTING"){
            $status2 = "unchecked";
        }
    }

    $select = " SELECT * FROM survey_db.respondents WHERE survey_id = '$survey_id' ";
    $result = mysqli_query($conn, $select);
    $count_respondents = mysqli_num_rows($result);

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

    if(isset($_POST['custom-btn'])){
        header("location: custom-survey.php?id=$id");
        exit;
    }

    if(isset($_POST['individual-btn'])){
        header("location: individual-responses-page.php?id=$i&&survey_id=$survey_id");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $status = $_POST['status']; 
    
        // Update the surveys table
        $query = "UPDATE survey_db.surveys SET status='$status' WHERE survey_id='$survey_id'";
        
        if (mysqli_query($conn, $query)) {
            header("location: responses.php?id=$id&&survey_id=$survey_id");
            exit;
        } else {
            echo "Error updating status: " . mysqli_error($conn);
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
    <link rel="stylesheet" href="css/responses.css">
    <script src="js/responses.js" defer></script>
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
                <a href="custom-view.php?id=<?php echo $id;?>&&survey_id=<?php echo $survey_id;?>" class="nav-links">Questions</a>
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
                    <div id="profile-options-btns-container">
                        <form action="" method="post">
                            <button class="profile-options-btns" id="manage-acc-btn" name="manage-acc"><img src="../imgs/manage_accounts.svg" alt="" class="profile-options-btns-imgs">Manage Account</button>
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
        <div id="responses-container">
            <p class="survey-title" id="nav-survey-title"><?php echo $survey_title;?></p>
            <p id="responses-text"><?php echo $count_respondents;?> responses</p>
            <div id="accept-response-container">
            <form id="surveyForm" action="" method="post">
                <label class="switch">
                    <input type="checkbox" id="toggleSwitch" <?php echo $status2?>>
                    <span class="slider"></span>
                </label>
                <input type="submit" style="display:none;">
            </form>
                <p id="accepting-responses-text">Accepting responses</p>
            </div>
            <div class = "responses-btn-section">
                <form class = "responses-btn-section" action="" method="post">
                    <button id="summary">Summary</button>
                    <button name="individual-btn">Individual</button>
                </form>
            </div>
        </div>

        <div id="survey-header-container">
            <p>Statistics of All Multiple Choice and Checkboxes Questions</p>
        </div>

        <?php
            $SELECT = "SELECT * FROM survey_db.questions WHERE survey_id = '$survey_id'";
            $RESULT = mysqli_query($conn, $SELECT);
            while($row = mysqli_fetch_array($RESULT)){
                $question_type = $row['question_type'];

                if($question_type == "Multiple Choice" || $question_type == "Checkboxes"){
                    $question_text = $row['question_text'];
                    echo    '<div id="survey-question-container">
                                 <p id="question-title" class="question-title">'.$question_text.'</p>
                            </div>';
                }
            }     
        ?>
        
    </main>
</body>
</html>