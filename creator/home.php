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
    <nav id="nav-bar">
        <div id="nav-left-side">
            <img src="../imgs/logo.png" alt="" id="nav-logo">
            <h1 id="nav-title">EazyPoll</h1>
        </div>
        <div id="nav-center">
            <div id="search-container">
                <input type="text" placeholder="Search" id="nav-search-bar">
            </div>
            <img src="../imgs/dark-mode-green.png" alt="" id="nav-dark-mode">
        </div>
        <div id="nav-right-side">
            <div id="profile-container">
                <img src="../imgs/default_profile_image.svg" alt="" id="nav-profile-img">
                <div id="profile-options" class="hidden">
                    <img src="../imgs/default_profile_image.svg" alt="" id="profile-options-image">
                    <p>Hi, <?php echo $fname; ?>!</p>
                    <div id="profile-options-btns-container">
                        <button class="profile-options-btns"><img src="../imgs/manage_accounts.svg" alt="" class="profile-options-btns-imgs">Manage Accounts</button>
                        <form action="" method="post">
                            <button class="profile-options-btns" name="sign-out"><img src="../imgs/signout.svg" alt=""  class="profile-options-btns-imgs">Sign Out</button>
                        </form>
                    </div>
                </div>
            </div>
            <img src="../imgs/info_button.svg" alt="" id="nav-info">
        </div>
    </nav>

    <main>
        <div id="templates-container">
            <h3 class="containers-title">Templates</h3>
            <div class="divs-container">
                <div class="templates-divs-container">
                    <div class="templates-divs"></div>
                    <p class="templates-texts">Multiple-Choice Survey</p>
                </div>
                <div class="templates-divs-container">
                    <div class="templates-divs"></div>
                    <p class="templates-texts">True or False Survey</p>
                </div>
                <div class="templates-divs-container">
                    <div class="templates-divs"></div>
                    <p class="templates-texts">Satisfaction Survey</p>
                </div>
                <div class="templates-divs-container">
                    <div class="templates-divs"></div>
                    <p class="templates-texts">Rating Scale Survey</p>
                </div>
                <div class="templates-divs-container">
                    <div class="templates-divs"></div>
                    <p class="templates-texts">Pulse Survey</p>
                </div>
            </div>
        </div>

        <div id="custom-container">
            <h3 class="containers-title">Custom Surveys</h3>
            <div class="divs-container">
                <div id="add-custom-div">
                    <img src="../imgs/plus.svg" alt="" id="add-custom-btn">
                </div>
                <div class="custom-divs-container">
                    <div class="custom-divs"></div>
                    <p class="custom-texts">Sample Title</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>