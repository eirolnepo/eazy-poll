<?php
    include 'php/db_connect.php';

    if(isset($_POST['submit'])){
    
        $pass = $_POST['password'];
        $email = $_POST['email'];
        $cpass = $_POST['confirm-password'];
        $encpass = md5($pass);

        $uppercase = preg_match('@[A-Z]@', $pass);
        $lowercase = preg_match('@[a-z]@', $pass);
        $number    = preg_match('@[0-9]@', $pass);
     
        $select = " SELECT * FROM survey_db.users WHERE email = '$email' && pass = '$pass' ";
      
        $result = mysqli_query($conn, $select);
     
        if(mysqli_num_rows($result) > 0){
      
           $error[] = 'user already exist!';
      
        }else{
      
           if($pass != $cpass){
              $error[] = 'Password do not matched!';
           }else{
             if (!$uppercase || !$lowercase || !$number || strlen($pass) < 8) {
               $error[] = 'Password should be atleast 8 characters in length and should include at least one upper case letter, and one number.';
             }else{
               if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                 $error[] = 'Email is invalid';
               }else{
               $select1 = " SELECT email FROM survey_db.users WHERE email = '$email'";
               $result1 = mysqli_query($conn, $select1);
               if (mysqli_num_rows($result1) > 0) {
                 $error[] = 'Email already exist!';
               }else{
     
              $insert = "INSERT INTO survey_db.users(email,pass) VALUES(?,?)";
             
      
         $stmt = $conn -> prepare ($insert);
         $stmt -> bind_param('ss',$email,$encpass);
      
         if($stmt->execute()){
     
           $select1 = "SELECT user_id FROM survey_db.users WHERE email = '$email'";
           $result1 = mysqli_query($conn, $select1);
           while ( $row = mysqli_fetch_array($result1)){
           $id = $row['user_id'];}
           header("location: home.php?id=$id");
         }
         else{
           $errors['db_error'] = "Database Error!";
         }}
       }
       }
           }}
    };

    if(isset($_POST['log-in'])){

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
              header("location:home.php?id=$id");
           }else{
               $errorlogin[] = 'Incorrect email or password!';
           }
    }

    if(isset($_POST['change'])){

        $email = $_POST['email'];
        $opass = $_POST['old-password'];
        $npass = $_POST['password'];
        $cpass = $_POST['confirm-password'];
        $o_encpass = md5($opass);
        $encpass = md5($npass);

        $uppercase = preg_match('@[A-Z]@', $npass);
        $lowercase = preg_match('@[a-z]@', $npass);
        $number    = preg_match('@[0-9]@', $npass);
     
           $select = " SELECT * FROM survey_db.users WHERE email = '$email' && pass = '$o_encpass' ";
           $result = mysqli_query($conn, $select);
           if(mysqli_num_rows($result) > 0){
                if($npass != $cpass){
                    $errorchange[] = 'Password do not matched!';
                }else{
                    if (!$uppercase || !$lowercase || !$number || strlen($npass) < 8) {
                        $errorchange[] = 'Password should be atleast 8 characters in length and should include at least one upper case letter, and one number.';
                      }else{
                            $update = "UPDATE survey_db.users SET pass = '$encpass' WHERE email = '$email'";
                            $update_query = mysqli_query($conn, $update);
                            if($update_query){
                                $sucess[] = "Password change sucessfully";
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
    <link rel="icon" href="imgs/logo.png">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>
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
          <?php
                if(isset($errorlogin)){
                    foreach($errorlogin as $errorlogin){
                        echo '<span class="error-msg">'.$errorlogin.'</span>';
                    };
                };
            ?>
          <form id="sign-in-form" action="" method="post">
            <label for="email" class="sign-in-modal-label">Email</label>
            <input type="email" id="email" name="email" placeholder="Write your email" required>
            <label for="password" class="sign-in-modal-label">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <button type="submit" name="log-in" id="modal-sign-in-btn">Sign In</button><br>
            <p id="no-account-text">Don't have an account?&nbsp;<a href="#" class="sign-in-modal-links" id="sign-in-up-btn">Sign Up</a></p>
            <a href="#" class="sign-in-modal-links" id="forgot-pass-link">Forgot password?</a>
          </form>
        </div>
    </div>

    <div id="sign-up-modal" class="modal">
        <div class="modal-content" id="sign-up-modal-content">
            <h2 id="sign-up-modal-title">Sign Up</h2>
            <?php
                if(isset($error)){
                    foreach($error as $error){
                        echo '<span class="error-msg">'.$error.'</span>';
                    };
                };
            ?>
            <form id="sign-up-form" action="" method="post">
                <label for="sign-up-email" class="sign-up-modal-label">Email</label>
                <input type="email" id="sign-up-email" name="email" placeholder="Write your email" required>
                <label for="sign-up-password" class="sign-up-modal-label">Password</label>
                <input type="password" id="sign-up-password" name="password" placeholder="Enter your password" required>
                <label for="confirm-password" class="sign-up-modal-label">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password" required>
                <button type="submit" name="submit" id="modal-sign-up-btn">Sign Up</button><br>
                <p id="have-account-text">Already have an account?&nbsp;<a href="#" class="sign-up-modal-links" id="sign-up-in-btn">Sign In</a></p>
            </form>
        </div>
    </div>

    <div id="change-pass-modal" class="modal">
        <div class="modal-content" id="sign-up-modal-content">
            <h2 id="change-pass-modal-title">Change Password</h2>
            <?php
                if(isset($errorchange)){
                    foreach($errorchange as $errorchange){
                        echo '<span class="error-msg">'.$errorchange.'</span>';
                    };
                }else if (isset($sucess)){
                    foreach($sucess as $sucess){
                        echo '<span class="sucess-msg">'.$sucess.'</span>';
                    };
                };
            ?>
            <form id="change-pass-form" action="" method="post">
                <label for="change-pass-email" class="change-pass-modal-label">Email</label>
                <input type="email" id="change-pass-email" name="email" placeholder="Write your email" required>
                <label for="change-pass-password" class="change-pass-modal-label">Old Password</label>
                <input type="password" id="change-pass-password" name="old-password" placeholder="Enter your old password" required>
                <label for="change-pass-password" class="change-pass-modal-label">New Password</label>
                <input type="password" id="change-pass-password" name="password" placeholder="Enter your new password" required>
                <label for="confirm-password" class="change-pass-modal-label">Confirm New Password</label>
                <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your new password" required>
                <button type="submit" name="change" id="modal-change-pass-btn">Change Password</button><br>
                <a href="#" class="change-pass-modal-links" id="change-pass-in-btn">Sign In</a>
            </form>
        </div>
    </div>
</body>
</html>