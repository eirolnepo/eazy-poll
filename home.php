<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EazyPoll</title>
    <link rel="icon" href="imgs/logo.png">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <nav id="nav-bar">
        <img src="imgs/logo.png" alt="" id="nav-logo">
        <h1 id="nav-title">EazyPoll</h1>
        <div id="nav-btns-container">
            <a href="index.php"><button class="nav-btns" id="sign-up-btn">Log Out</button></a>
        </div>
        <img src="imgs/dark-mode-green.png" alt="" id="nav-dark-mode">
    </nav>

    <main>
        <div id="home-left-container">
            <h2 id="home-title">Welcome</h2><br>
            <p id="home-desc">EazyPoll makes creating and managing surveys a breeze. Quickly set up polls, collect responses, and get clear insights with ease.</p><br>
            <div id="home-btns-links">
                <button id="get-started-btn">Get Started</button>
                <div id="tutorial-container" onclick="location.href='#';">
                    <img src="imgs/triangle.png" alt="" id="home-triangle">
                    <a id="home-link">Tutorial</a>
                </div>
            </div>
            </div>
        <img src="imgs/home.png" alt="" id="home-img">
    </main>

</body>
</html>