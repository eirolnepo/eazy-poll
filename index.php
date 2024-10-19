<?php
    include 'php/db_create.php';
    session_start();
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    if(isset($_POST['submit'])){
    
        $pass = $_POST['password'];
        $email = $_POST['email'];
        $cpass = $_POST['confirm-password'];
        $encpass = md5($pass);
        $fname = ucfirst(strtolower($_POST['sign-up-fname']));
        $lname = ucfirst(strtolower($_POST['sign-up-lname']));

        $uppercase = preg_match('@[A-Z]@', $pass);
        $lowercase = preg_match('@[a-z]@', $pass);
        $number    = preg_match('@[0-9]@', $pass);

        $lowercase_fname = preg_match('@[a-z]@', $fname);
        $uppercase_fname = preg_match('@[A-Z]@', $fname);

        $lowercase_lname = preg_match('@[a-z]@', $lname);
        $uppercase_lname = preg_match('@[A-Z]@', $lname);
     
        $select = " SELECT * FROM survey_db.users WHERE email = '$email' && pass = '$pass' ";
      
        $result = mysqli_query($conn, $select);
     
        if(mysqli_num_rows($result) > 0){
      
           $error[] = 'User already exists!';
      
        }else{
      
           if($pass != $cpass){
              $error[] = 'Passwords do not match!';
           }else{
             if (!$uppercase || !$lowercase || !$number || strlen($pass) < 8) {
               $error[] = 'Password should be atleast 8 characters in length and should include at least one upper case letter, and one number.';
             }else{
               if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                 $error[] = 'Email is invalid';
               }else{
                if(!$lowercase_lname && !$uppercase_lname && !$lowercase_fname && !$uppercase_fname){
                    $error[] = 'First Name and Last Name should only contain letters!';
                }else{
                    if(!$lowercase_fname || !$uppercase_fname){
                        $error[] = 'First Name should only contain letters!';
                    }else{
                        if(!$lowercase_lname || !$uppercase_lname){
                            $error[] = 'Last Name should only contain letters!';
                    }else{
                    $select1 = " SELECT email FROM survey_db.users WHERE email = '$email'";
                    $result1 = mysqli_query($conn, $select1);
                    if (mysqli_num_rows($result1) > 0) {
                        $error[] = 'Email already exists!';
                    }else{
            
                    $insert = "INSERT INTO survey_db.users(fname,lname,email,pass) VALUES(?,?,?,?)";
                    
            
                $stmt = $conn -> prepare ($insert);
                $stmt -> bind_param('ssss',$fname,$lname,$email,$encpass);
            
                if($stmt->execute()){
            
                $select1 = "SELECT user_id FROM survey_db.users WHERE email = '$email'";
                $result1 = mysqli_query($conn, $select1);
                while ( $row = mysqli_fetch_array($result1)){
                $id = $row['user_id'];}
                    $_SESSION['loggedin'] = true;
                    header("location: creator/home.php?id=$id");
                    exit;
                }
                else{
                $errors['db_error'] = "Database Error!";
                }}
            }
       }
           }}}}}
    };

    if(isset($_POST['log-in'])){
        $loading = 'true';
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $encpass = md5($pass);
     
        $num = 0;
     
        $select1 = "SELECT user_id FROM survey_db.users WHERE email = '$email'";
           $result1 = mysqli_query($conn, $select1);
           while ( $row = mysqli_fetch_array($result1)){
           $id = $row['user_id'];}
     
           $select = " SELECT * FROM survey_db.users WHERE email = '$email' && pass = '$encpass' ";
           $result = mysqli_query($conn, $select);
           if(mysqli_num_rows($result) > 0){
                $loading = 'false';
                $_SESSION['loggedin'] = true;
                header("location:creator/home.php?id=$id");
                exit;
           }else{
               $errorlogin[] = 'Incorrect email or password!';
               $loading = 'false';
           }
    }

    if(isset($_POST['change'])){

        $email = $_POST['email'];
        $npass = $_POST['password'];
        $cpass = $_POST['confirm-password'];
        $encpass = md5($npass);

        $uppercase = preg_match('@[A-Z]@', $npass);
        $lowercase = preg_match('@[a-z]@', $npass);
        $number    = preg_match('@[0-9]@', $npass);
     
           $select = " SELECT * FROM survey_db.users WHERE email = '$email' ";
           $result = mysqli_query($conn, $select);
           if(mysqli_num_rows($result) > 0){
                if($npass != $cpass){
                    $errorchange[] = 'Passwords do not match!';
                }else{
                    if (!$uppercase || !$lowercase || !$number || strlen($npass) < 8) {
                        $errorchange[] = 'Password should be atleast 8 characters in length and should include at least one upper case letter, and one number.';
                      }else{
                            $update = "UPDATE survey_db.users SET pass = '$encpass' WHERE email = '$email'";
                            $update_query = mysqli_query($conn, $update);
                            if($update_query){
                                $sucess[] = "Password changed sucessfully";
                            }
                        }    
                }
           }else{
               $errorchange[] = 'Incorrect email or password!';
           }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EazyPoll</title>
    <script type="text/javascript">
        window.history.forward();
    </script>
    <link rel="icon" href="imgs/logo.png">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>
</head>
<body>
    <div>
        <?php if (!empty($message)) echo $message; ?>
    </div>  
    <nav id="nav-bar">
        <img src="imgs/logo.png" alt="" id="nav-logo">
        <h1 id="nav-title">EazyPoll</h1>
        <div id="nav-btns-container">
            <button class="nav-btns" id="sign-in-btn">Sign In</button>
            <button class="nav-btns" id="sign-up-btn">Sign Up</button>
        </div>
        <img src="imgs/dark-mode-green.png" alt="" id="nav-dark-mode">
    </nav>

    <main>
        <div id="home-left-container">
            <h2 id="home-title">Ask. Answer. Achieve.</h2><br>
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

    <div id="sign-in-modal" class="modal">
        <div class="modal-content" id="sign-in-modal-content">
          <h2 id="sign-in-modal-title">Sign In</h2>
          <form id="sign-in-form" action="" method="post">
            <label for="email" class="sign-in-modal-label">Email</label>
            <input type="email" id="email" name="email" placeholder="Write your email" required>
            <label for="password" class="sign-in-modal-label">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <?php
                $formHasErrors = false;
                if(isset($errorlogin)){
                    foreach($errorlogin as $error){
                        echo '<span class="error-msg">'.$error.'</span>';
                    };
                    $formHasErrors = true;
                };
            ?>
            <button type="submit" name="log-in" id="modal-sign-in-btn">
                <?php
                    if(isset($_POST['log-in'])){
                        if ($loading == 'true'){
                            echo '<div class="loading-section"><dotlottie-player class="lottie" src="https://lottie.host/e3b63b70-be62-43c3-a29d-0dc2547e954d/zyUbGagAIh.json" background="transparent" speed="1" style="width: 50px; height: 50px;" loop autoplay></dotlottie-player></div>';
                        }else{
                            echo 'Sign In';
                        }
                    }else{
                        echo 'Sign In';
                    }
                ?>
            </button><br>
            <p id="no-account-text">Don't have an account?&nbsp;<a href="#" class="sign-in-modal-links" id="sign-in-up-btn">Sign Up</a></p>
            <a href="#" class="sign-in-modal-links" id="forgot-pass-link">Forgot password?</a>
          </form>
        </div>
    </div>

    <div id="sign-up-modal" class="modal">
        <div class="modal-content" id="sign-up-modal-content">
            <h2 id="sign-up-modal-title">Sign Up</h2>
            <form id="sign-up-form" action="" method="post">
                <label for="sign-up-email" class="sign-up-modal-label">Email</label>
                <input type="email" id="sign-up-email" name="email" placeholder="Write your email" required>
                <label for="sign-up-fname" class="sign-up-modal-label">First Name</label>
                <input type="text" id="sign-up-fname" name="sign-up-fname" placeholder="Write your first name" required>
                <label for="sign-up-lname" class="sign-up-modal-label">Last Name</label>
                <input type="text" id="sign-up-lname" name="sign-up-lname" placeholder="Write your last name" required>
                <label for="sign-up-password" class="sign-up-modal-label">Password</label>
                <input type="password" id="sign-up-password" name="password" placeholder="Enter your password" onkeyup='check();' required>
                <label for="confirm-password" class="sign-up-modal-label">Confirm Password <span id="sign-up-message"></span></label>
                <input type="password" id="sign-up-confirm-password" name="confirm-password" placeholder="Confirm your password" onkeyup='check();' required>
                <?php
                    $signUpFormHasErrors = false;
                    if (isset($error)) {
                        if (is_array($error)) {
                            foreach ($error as $errors) {
                                echo '<span class="error-msg">' . $errors . '</span>';
                            }
                        } elseif (is_string($error)) {
                            echo '<span class="error-msg">' . $error . '</span>';
                        }
                        $signUpFormHasErrors = true;
                    }
                ?>
                <button type="submit" name="submit" id="modal-sign-up-btn">Sign Up</button><br>
                <p id="have-account-text">Already have an account?&nbsp;<a href="#" class="sign-up-modal-links" id="sign-up-in-btn">Sign In</a></p>
            </form>
        </div>
    </div>

    <div id="change-pass-modal" class="modal">
        <div class="modal-content" id="sign-up-modal-content">
            <h2 id="change-pass-modal-title">Change Password</h2>
            <form id="change-pass-form" action="" method="post">
                <label for="change-pass-email" class="change-pass-modal-label">Email</label>
                <input type="email" id="change-pass-email" name="email" placeholder="Write your email" required>
                <label for="change-pass-password" class="change-pass-modal-label">New Password</label>
                <input type="password" id="change-password" name="password" placeholder="Enter your new password" onkeyup='check();'  required>
                <label for="confirm-password" class="change-pass-modal-label">Confirm New Password <span id="change-message"></span></label>
                <input type="password" id="confirm-change-password" name="confirm-password" placeholder="Confirm your new password" onkeyup='check();' required>
                <?php
                    $passwordChangeFormHasErrors = false;
                    $passwordChangeSuccess = false;

                    if (isset($errorchange)) {
                        foreach ($errorchange as $errorchanges) {
                            echo '<span class="error-msg">' . $errorchanges . '</span>';
                        }
                        $passwordChangeFormHasErrors = true;
                    } 

                    if (isset($success)) {
                        foreach ($success as $changesuccess) {
                            echo '<span class="success-msg">' . $changesuccess . '</span>';
                        }
                        $passwordChangeSuccess = true;
                    }
                ?>
                <button type="submit" name="change" id="modal-change-pass-btn">Change Password</button><br>
                <a href="#" class="change-pass-modal-links" id="change-pass-in-btn">Sign In</a>
            </form>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            <?php if ($formHasErrors) : ?>
                showModal(document.getElementById('sign-in-modal'));
            <?php endif; ?>

            <?php if ($signUpFormHasErrors) : ?>
                showModal(document.getElementById('sign-up-modal'));
            <?php endif; ?>

            <?php if ($passwordChangeFormHasErrors || $passwordChangeSuccess) : ?>
                showModal(document.getElementById('change-pass-modal'));
            <?php endif; ?>
        });
    </script>
</body>
</html>