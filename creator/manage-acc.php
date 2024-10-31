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

    if (isset($_POST['sign-out'])) {
        session_destroy();
        header("Location: ../index.php");
        exit;
    }

    $select = " SELECT * FROM survey_db.users WHERE user_id = '$id' ";
    $result = mysqli_query($conn, $select);
    while($row = mysqli_fetch_array($result)){
        $fname = $row['fname'];
        $lname = $row['lname'];
        $contact = $row['contact_num'];
        $address = $row['address'];
        $email = $row['email'];
    }

    if(isset($_POST['profile-save'])){
        $fname = ucfirst(strtolower($_POST['first-name']));
        $lname = ucfirst(strtolower($_POST['last-name']));
        $address = ucfirst(strtolower($_POST['address']));
        $contact = $_POST['contact'];

        $letter_fname = preg_match('@[a-zA-Z]@', $fname);
        $number_fname    = preg_match('@[0-9]@', $fname);

        $letter_lname = preg_match('@[a-zA-Z]@', $lname);
        $number_lname    = preg_match('@[0-9]@', $lname);

        $number    = preg_match('@[0-9]@', $contact);
        $letter = preg_match('@[A-Za-z]@', $contact);

        if(!$letter_lname && !$letter_fname || $number_fname && $number_lname){
            $_SESSION['errorProfile'] = 'Fisrt Name and Last Name should only contain letters!';
            header('Location: '.$_SERVER['PHP_SELF'].'?id='.$id);
            exit();
        }else{
            if(!$letter_fname || $number_fname){
                $_SESSION['errorProfile'] = 'First Name should only contain letters!';
                header('Location: '.$_SERVER['PHP_SELF'].'?id='.$id);
                exit();
            }else{
                if(!$letter_lname || $number_lname){
                    $_SESSION['errorProfile'] = 'Last Name should only contain letters!';
                    header('Location: '.$_SERVER['PHP_SELF'].'?id='.$id);
                    exit();
                }else{
                    if(!$number && $contact != "" || $letter){
                        $_SESSION['errorProfile'] = 'Contact No. should only contain numbers!';
                        header('Location: '.$_SERVER['PHP_SELF'].'?id='.$id);
                        exit();
                    }else{
                        $UpdateQuery = "UPDATE survey_db.users SET fname = '$fname',lname = '$lname', address = '$address', contact_num = '$contact' WHERE user_id = '$id'";
                        $query = mysqli_query($conn,$UpdateQuery);
                        if($query){
                            $_SESSION['successProfile'] = 'Profile updated successfully!';
                            header('Location: '.$_SERVER['PHP_SELF'].'?id='.$id);
                            exit();
                        }
                    }
                }
            }
        }
    }

    if(isset($_POST['save-email'])){
        $current = $_POST['c-email'];
        $new = $_POST['n-email'];
        $confirmEmail = $_POST['cn-email']; 

        if($new != $confirmEmail){
            $_SESSION['errorEmail'] = 'Emails do not matched!'; // Store error message in session
            header('Location: '.$_SERVER['PHP_SELF'].'?id='.$id);
            exit();
        }else{
            $UpdateEmail = "UPDATE survey_db.users SET email = '$new' WHERE user_id = '$id'";
            $Emailquery = mysqli_query($conn,$UpdateEmail);
            if($Emailquery){
                $_SESSION['successEmail'] = 'Email updated successfully!'; // Store error message in session
                header('Location: '.$_SERVER['PHP_SELF'].'?id='.$id);
                exit();
            }
        }

    }

    if(isset($_POST['save-password'])){

        $selectpass = "SELECT pass FROM survey_db.users WHERE user_id ='$id'";
        $resultpass = mysqli_query($conn, $selectpass);
        while($row = mysqli_fetch_array($resultpass)){
            $pass = $row['pass'];
        }

        $current = $_POST['c-pass'];
        $new = $_POST['n-pass'];
        $confirmPass = $_POST['cn-pass']; 
        $encnewpass = md5($new);
        $enccurrent = md5($current);

        $uppercase = preg_match('@[A-Z]@', $new);
        $lowercase = preg_match('@[a-z]@', $new);
        $number    = preg_match('@[0-9]@', $new);

        if($enccurrent != $pass){
            $_SESSION['error'] = 'Current password is incorrect!'; // Store error message in session
            header('Location: '.$_SERVER['PHP_SELF'].'?id='.$id);
            exit();
        }else{
            if($new != $confirmPass){
                $_SESSION['error'] = 'New passwords do not match!'; // Store error message in session
                header('Location: '.$_SERVER['PHP_SELF'].'?id='.$id);
                exit();
            }else{
                if (!$uppercase || !$lowercase || !$number || strlen($npass) < 8) {
                    $_SESSION['error'] = 'Password should be atleast 8 characters in length and should <br> include at least one upper case letter, and one number.'; // Store error message in session
                    header('Location: '.$_SERVER['PHP_SELF'].'?id='.$id);
                    exit();
                }else{
                    $UpdatePass = "UPDATE survey_db.users SET pass = '$encnewpass' WHERE user_id = '$id'";
                    $Passquery = mysqli_query($conn,$UpdatePass);
                    if($Passquery){
                        $_SESSION['success'] = 'Password updated successfully!';
                        header('Location: '.$_SERVER['PHP_SELF'].'?id='.$id);
                        exit();
                    }
                }
            }
        }
    }

    if(isset($_POST['confirm-delete-btn'])){
        $pass_del = $_POST['acc-del-password'];
        $confirm_del = $_POST['acc-del-confirm'];
        $encpass = md5($pass_del);

        $select = " SELECT pass FROM survey_db.users WHERE user_id = '$id' ";
        $result = mysqli_query($conn, $select);
        while($row = mysqli_fetch_array($result)){
            $pass = $row['pass'];

            if ($pass == $encpass && $confirm_del == "DELETE"){
                $delete_sql = "DELETE FROM survey_db.users WHERE user_id = '$id'";
                $delete_result = mysqli_query($conn, $delete_sql);
                if($delete_result){
                    header("Location: ../index.php");
                    exit;
                }
            }else{
                echo "Invalid Inputs!";
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
    <link rel="stylesheet" href="css/manage-acc.css">
    <script src="js/manage-acc.js" defer></script>
</head>
<body>
    <script src="../js/check-dark.js"></script>
    <nav id="nav-bar">
        <div id="nav-left-side">
            <img src="../imgs/logo.png" alt="Eazypoll logo" id="nav-logo">
            <h1 id="nav-title">EazyPoll</h1>
        </div>
        <div id="nav-center">
            <h2 id="manage-acc-title">Manage Account</h2>
        </div>
        <div id="nav-right-side">
            <img src="../imgs/dark-mode-green.png" alt="Dark mode button" id="nav-dark-mode">
            <img src="../imgs/info_button_light.svg" alt="Play tutorial button" id="nav-info">
        </div>
    </nav>

    <main>
        <div id="side-bar">
            <a href="home.php?id=<?php echo $id; ?>" class="side-bar-links">Back to Home</a>
            <a href="#profile-content" class="side-bar-links" id="profile-link">Profile</a>
            <a href="#account-content" class="side-bar-links">Account</a>
            <form id="signOutForm" method="POST" action="manage-acc.php?id=<?php $id?>">
                <input type="hidden" name="sign-out" value="1">
            </form>
            <a href="#" class="side-bar-links" onclick="document.getElementById('signOutForm').submit();">Sign Out</a>
        </div>

        <div id="profile-content">
            <div id="profile-img-container">
                <img src="../imgs/default_profile_image_light.svg" alt="User's profile picture" id="profile-img">
                <input type="file" id="image-input" accept="image/*" style="display: none;" />
            </div>
            <button id="change-img-btn">Change Profile Picture</button>
            <?php
                    if (isset($_SESSION['errorProfile'])) {
                        echo "<div class='error-msg'>{$_SESSION['errorProfile']}</div>";
                        unset($_SESSION['errorProfile']); 
                    }
                    if (isset($_SESSION['successProfile'])) {
                        echo "<div class='success-msg'>{$_SESSION['successProfile']}</div>";
                        unset($_SESSION['successProfile']); 
                    }  
                ?>
            <div id="fields-container">
                <form action="" method="post" id="fields-container">
                    <div id="left-fields">
                        <div>
                            <span class="fields-labels">First Name: </span><input type="text" id="fname" class="profile-inputs" value="<?php echo $fname; ?>" name="first-name">
                        </div>
                        <div>
                            <span class="fields-labels">Contact No: </span><input type="text" maxlength="11" minlength="11" id="contact" class="profile-inputs" value="<?php echo $contact; ?>" name="contact">
                        </div>
                        <button class="save-btns" id="profile-save-btn" name="profile-save">Save Changes</button> 
                    </div>
                    <div id="right-fields">
                        <div>
                            <span class="fields-labels">Last Name: </span><input type="text" id="lname" class="profile-inputs" value="<?php echo $lname; ?>" name="last-name">
                        </div>
                        <div>
                            <span class="fields-labels">Address: </span><input type="text" id="address" class="profile-inputs" value="<?php echo $address; ?>" name="address">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="account-content">
            <form action="" method="post" id="account-content">
                <?php
                    if (isset($_SESSION['errorEmail'])) {
                        echo "<div class='error-msg'>{$_SESSION['errorEmail']}</div>";
                        unset($_SESSION['errorEmail']); 
                    }
                    if (isset($_SESSION['successEmail'])) {
                        echo "<div class='success-msg'>{$_SESSION['successEmail']}</div>";
                        unset($_SESSION['successEmail']); 
                    }  
                ?>
                <div>
                    <span class="account-fields-labels">Current Email: </span><input name="c-email" type="email" id="cuemail" class="account-inputs" value="<?php echo $email ; ?>" readonly>
                </div>
                <div>
                    <span class="account-fields-labels">New Email: </span><input name="n-email" type="email" id="nemail" class="account-inputs">
                </div>
                <div>
                    <span class="account-fields-labels">Confirm New Email: </span><input name="cn-email" type="email" id="cnemail" class="account-inputs">
                </div>
                <button class="save-btns account-btns" id="account-save-email-btn" name="save-email">Save Changes</button>
                <?php
                    if (isset($_SESSION['error'])) {
                        echo "<div class='error-msg'>{$_SESSION['error']}</div>";
                        unset($_SESSION['error']); 
                    }
                    if (isset($_SESSION['success'])) {
                        echo "<div class='success-msg'>{$_SESSION['success']}</div>";
                        unset($_SESSION['success']); 
                    }  
                ?>
                <div>
                    <span class="account-fields-labels">Current Password: </span><input name="c-pass" type="password" id="cupass" class="account-inputs">
                </div>
                <div id="npass-container">
                    <span class="account-fields-labels">New Password: </span><input name="n-pass" type="password" id="npass" class="account-inputs" onkeyup='check();'>
                    <img src="../imgs/eye_close_light.svg" alt="" id="show-pass-img">
                </div>
                <div>
                    <span class="account-fields-labels">Confirm New Password: </span><input name="cn-pass" type="password" id="cnpass" class="account-inputs" onkeyup='check();'>
                </div>
                <span id="matched-message"></span>
                <button class="save-btns account-btns" id="account-save-pass-btn" name="save-password">Save Changes</button>
                <button class="save-btns account-btns" id="account-delete-btn" name="account-delete-btn">Delete Account</button>
            </form>
        </div>
    </main>

    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <form action="" method="post">
                <h2 id="acc-del-title">Confirm Account Deletion</h2>
                <p class="acc-del-desc">Enter your password for confirmation</p>
                <input type="password" name="acc-del-password" class="acc-del-inputs" placeholder="Enter your password" />
                <p class="acc-del-desc">Type the word "DELETE" to proceed</p>
                <input type="text" name="acc-del-confirm" class="acc-del-inputs" placeholder="DELETE" />
                <button id="confirm-delete-btn" name="confirm-delete-btn">Confirm</button>
            </form>
        </div>
    </div>
</body>
</html>