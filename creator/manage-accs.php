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

    $select = " SELECT * FROM survey_db.users WHERE user_id = '$id' ";
    $result = mysqli_query($conn, $select);
    while($row = mysqli_fetch_array($result)){
        $fname = $row['fname'];
        $lname = $row['lname'];
        $contact = $row['contact_num'];
        $address = $row['address'];
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
            $error_profile[] = 'First Name and Last Name should only contain letters!';
        }else{
            if(!$letter_fname || $number_fname){
                $error_profile[] = 'First Name should only contain letters!';
            }else{
                if(!$letter_lname || $number_lname){
                    $error_profile[] = 'Last Name should only contain letters!';
                }else{
                    if(!$number && $contact != "" || $letter){
                        $error_profile[] = 'Contact No. should only contain numbers';
                    }else{
                        $UpdateQuery = "UPDATE survey_db.users SET fname = '$fname',lname = '$lname', address = '$address', contact_num = '$contact' WHERE user_id = '$id'";
                        $query = mysqli_query($conn,$UpdateQuery);
                        if($query){
                            $success_profile[] = "Profile Updated Successfully!";
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
            $errorEmail[] = 'Emails do not match!';
        }else{
            $UpdateEmail = "UPDATE survey_db.users SET email = '$new' WHERE user_id = '$id'";
            $Emailquery = mysqli_query($conn,$UpdateEmail);
            if($Emailquery){
                $success_email[] = "Email Updated Successfully!";
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
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EazyPoll</title>
    <link rel="icon" href="../imgs/logo.png">
    <link rel="stylesheet" href="css/manage-accs.css">
    <script src="js/manage-accs.js" defer></script>
    <script>
            document.getElementById("account-save-pass-btn").addEventListener("click", function(event) {
                event.preventDefault();
            });
    </script>
</head>
<body>
    <nav id="nav-bar">
        <div id="nav-left-side">
            <img src="../imgs/logo.png" alt="Eazypoll logo" id="nav-logo">
            <h1 id="nav-title">EazyPoll</h1>
        </div>
        <div id="nav-center">
            <h2 id="manage-accs-title">Manage Account</h2>
        </div>
        <div id="nav-right-side">
            <img src="../imgs/dark-mode-green.png" alt="Dark mode button" id="nav-dark-mode">
            <img src="../imgs/info_button_light.svg" alt="Play tutorial button" id="nav-info">
        </div>
    </nav>

    <main>
        <div id="side-bar">
            <a href="home.php" class="side-bar-links">Back to Home</a>
            <a href="#profile-content" class="side-bar-links" id="profile-link">Profile</a>
            <a href="#account-content" class="side-bar-links">Account</a>
            <a href="#" class="side-bar-links">Sign Out</a>
        </div>

        <div id="profile-content">
            <img src="../imgs/default_profile_image_light.svg" alt="User's profile picture" id="profile-image">
            <button id="change-img-btn">Change Profile Picture</button>
            <?php
                $formHasErrors = false;
                $success = false;
                if(isset($error_profile)){
                    foreach($error_profile as $error_profile){
                        echo '<span class="error-msg">'.$error_profile.'</span>';
                    };
                    $formHasErrors = true;
                };
                if(isset($success_profile)){
                    foreach($success_profile as $success_profile){
                        echo '<span class="success-msg">'.$success_profile.'</span>';
                    };
                    $success = true;
                };
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
                    $EmailChangeFormHasErrors = false;
                    $EmailChangeSuccess = false;

                    if (isset($errorEmail)) {
                        foreach ($errorEmail as $errorEmail) {
                            echo '<span class="error-msg">' . $errorEmail . '</span>';
                        }
                        $EmailChangeFormHasErrors = true;
                    } 

                    if (isset($success_email)) {
                        foreach ($success_email as $success_email) {
                            echo '<span class="success-msg">' . $success_email . '</span>';
                        }
                        $EmailChangeSuccess = true;
                    }
                ?>
                <div>
                    <span class="account-fields-labels">Current Email: </span><input name="c-email" type="email" id="cuemail" class="account-inputs">
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
                    <span class="account-fields-labels">New Password: </span><input name="n-pass" type="password" id="npass" class="account-inputs">
                    <img src="../imgs/eye_close_light.svg" alt="" id="show-pass-img">
                </div>
                <div>
                    <span class="account-fields-labels">Confirm New Password: </span><input name="cn-pass" type="password" id="cnpass" class="account-inputs">
                </div>
                <button class="save-btns account-btns" id="account-save-pass-btn" name="save-password">Save Changes</button>
                <button class="save-btns account-btns" id="account-delete-btn">Delete Account</button>
            </form>
        </div>
    </main>
</body>
</html>