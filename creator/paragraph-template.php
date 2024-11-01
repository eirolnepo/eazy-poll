<?php

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
    <link rel="stylesheet" href="css/paragraph-template.css">
    <script src="js/paragraph-template.js" defer></script>
</head>
<body>
    <script src="../js/check-dark.js"></script>
    <nav id="nav-bar">
        <div id="nav-left-side">
            <img src="../imgs/logo.png" alt="Eazypoll logo" id="nav-logo">
            <input type="text" id="nav-title" value="Untitled Survey" readonly>
        </div>
        <div id="nav-center">
            <div id="links-container">
                <a href="#" class="nav-links">Questions</a>
                <a href="responses.html" class="nav-links">Responses</a>
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
        <div class="title-desc-container">
            <input type="text" name="survey-title" class="survey-title" id="nav-survey-title" value="Untitled Survey">
            <textarea name="survey-desc" class="survey-desc" placeholder="Survey Description"></textarea>
        </div>

        <div id="survey-container">
            <div class="question-container">
                <div class="question-upper">
                    <input type="text" name="question-title[0]" class="question-title" placeholder="Untitled Question" required>
                </div>
                <div class="question-choices-container"></div>
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
    </main>
</body>
</html>