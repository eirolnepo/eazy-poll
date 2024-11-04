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

    if(isset($_POST['custom-btn'])){
        header("location: custom-survey.php?id=$id");
        exit;
    }

    if(isset($_POST['multiple-choice-btn'])){
        header("location: multiple-choice-template.php?id=$id");
        exit;
    }

    if(isset($_POST['checkboxes-btn'])){
        header("location: checkboxes-template.php?id=$id");
        exit;
    }

    if(isset($_POST['true-false-btn'])){
        header("location: true-false-template.php?id=$id");
        exit;
    }

    if(isset($_POST['short-answer-btn'])){
        header("location: short-answer-template.php?id=$id");
        exit;
    }

    if(isset($_POST['paragraph-btn'])){
        header("location: paragraph-template.php?id=$id");
        exit;
    }

    if(isset($_POST['custom-div-btn'])){
        $survey_id = $_POST['custom-div-btn'];
        header("location: custom-view.php?id=$id&survey_id=$survey_id");
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
    <link rel="stylesheet" href="css/home.css">
    <script src="js/home.js" defer></script>
</head>
<body>
    <script src="../js/check-dark.js"></script>
    <nav id="nav-bar">
        <div id="nav-left-side">
            <img src="../imgs/logo.png" alt="Eazypoll logo" id="nav-logo">
            <h1 id="nav-title">EazyPoll</h1>
        </div>
        <div id="nav-center">
            <div id="search-container">
                <input type="text" placeholder="Search" id="nav-search-bar">
            </div>
            <img src="../imgs/dark-mode-green.png" alt="Dark mode button" id="nav-dark-mode">
        </div>
        <div id="nav-right-side">
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
            <img src="../imgs/info_button_light.svg" alt="Play tutorial button" id="nav-info">
        </div>
    </nav>

    <main>
        <div id="templates-container">
            <form method="post">
                <h3 class="containers-title">Templates</h3>
                <div class="divs-container">
                    <div class="templates-divs-container" id="multiple-choice-btn">
                        <button class = "multiple-choice-btn" name = "multiple-choice-btn">
                            <div class="templates-divs"></div>
                        </button>
                        <p class="templates-texts">Multiple-Choice Survey</p>
                    </div>
                    <div class="templates-divs-container" id="checkboxes-btn">
                        <button class = "checkboxes-btn" name = "checkboxes-btn">
                            <div class="templates-divs"></div>
                        </button>
                        <p class="templates-texts">Checkboxes Survey</p>
                    </div>
                    <div class="templates-divs-container" id="true-false-btn">
                        <button class = "true-false-btn" name = "true-false-btn">
                            <div class="templates-divs"></div>
                        </button>
                        <p class="templates-texts">True or False Survey</p>
                    </div>
                    <div class="templates-divs-container" id="short-answer-btn">
                        <button class = "short-answer-btn" name = "short-answer-btn">
                            <div class="templates-divs"></div>
                        </button>
                        <p class="templates-texts">Short Answer Survey</p>
                    </div>
                    <div class="templates-divs-container" id="paragraph-btn">
                        <button class = "paragraph-btn" name = "paragraph-btn">
                            <div class="templates-divs"></div>
                        </button>
                        <p class="templates-texts">Paragraph Survey</p>
                    </div>
                </div>
            </form>
        </div>

        <div id="custom-container">
            <h3 class="containers-title">Custom Surveys</h3>
            <div class="divs-container">
                <div id="add-custom-div">
                    <form method="post">
                        <button id="custom-btn" name="custom-btn">
                            <img src="../imgs/plus.svg" alt="Make a custom survey button" id="add-custom-btn">
                        </button>
                    </form>
                </div>
                <div class="custom-divs-container">
                    <form method="post" class="custom-divs-container">
                        <?php
                            $select_survey = " SELECT * FROM survey_db.surveys WHERE user_id = '$id' ";
                            $result_survey = mysqli_query($conn, $select_survey);
                            if(mysqli_num_rows($result_survey) > 0){
                                while($row = mysqli_fetch_array($result_survey)){
                                    $survey_id = $row['survey_id'];
                                    $survey_title = $row['title'];

                                    echo   '<div class="custom-section">
                                                <button class = "custom-div-btn" name = "custom-div-btn" value="'.$survey_id.'">
                                                    <div class="custom-divs"></div>
                                                </button>
                                                <p class="custom-texts">'.$survey_title.'</p>
                                        </div>';
                                }
                            }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>