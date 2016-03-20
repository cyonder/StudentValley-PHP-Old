<?php
ob_start();
require_once("./includes/header.php");

/** user1_id is defined in header.php. As long as, you have header.php required above the page
 * you don't need to redefine it. Use this if you'd like to redefine user1_id:
 * $user1_id = return_user_id($dbConnection, $_SESSION['email']);
 */

$query = "SELECT * FROM USERS WHERE USER_ID = '$user1_id'";

$result = mysqli_query($dbConnection, $query) or die(mysqli_error($dbConnection));
while($row = mysqli_fetch_assoc($result)){
    $first_name = $row['USER_FIRST_NAME'];
    $last_name = $row['USER_LAST_NAME'];
    $email = $row['USER_EMAIL'];
    $password = $row['USER_PASSWORD'];
    $display_name = $row['USER_DISPLAY_NAME'];
    $school = $row['USER_SCHOOL'];
    $program = $row['USER_PROGRAM'];
    $phone = $row['USER_PHONE'];
    $website = $row['USER_WEBSITE'];
    $picture = $row['USER_PICTURE_ID'];
}


if($_GET['Change']){
    if($_GET['Change'] == "Photo"){
        if(isset($_FILES['profilePicture'])){
            if($_FILES['profilePicture']['type'] == "image/jpeg" || $_FILES['profilePicture']['type'] == "image/png"){
                if($_FILES['profilePicture']['size'] < 1048576){
                    $picture_id = md5(uniqid(date("Y-m-d H:i:s"), true) * rand()) . '.png'; // Create unique id for picture.
                    move_uploaded_file($_FILES['profilePicture']['tmp_name'], "images/profile/" . $picture_id);
                    $picture_feedback = "Picture uploaded successfully";

                    $query = "UPDATE USERS SET USER_PICTURE_ID = '$picture_id' WHERE USER_ID = $user1_id";
                    mysqli_query($dbConnection, $query) or die(mysqli_error($dbConnection));
                    header("location: ".$_SERVER['PHP_SELF']);
                }else{
                    $picture_error = "Maximum picture size is 1MB";
                }
            }else{
                $picture_error = "Acceptable file extensions are JPEG and PNG";
            }
        }
    }
    else if($_GET['Change'] == "Profile"){
        if(isset($_POST['firstName'])){
            $new_first_name = $_POST['firstName'];
        }
        if(isset($_POST['lastName'])){
            $new_last_name = $_POST['lastName'];
        }
        if(isset($_POST['displayName'])){
            $new_display_name = $_POST['displayName'];
        }
        if(isset($_POST['school'])){
            $new_school = $_POST['school'];
        }
        if(isset($_POST['program'])){
            $new_program = $_POST['program'];
        }
        if(isset($_POST['phone'])){
            $new_phone = $_POST['phone'];
        }
        if(isset($_POST['website'])){
            $new_website = $_POST['website'];
        }

        $query = "UPDATE USERS SET ";
        $query .= "USER_FIRST_NAME = '$new_first_name', ";
        $query .= "USER_LAST_NAME = '$new_last_name', ";
        $query .= "USER_DISPLAY_NAME = '$new_display_name', ";
        $query .= "USER_SCHOOL = '$new_school', ";
        $query .= "USER_PROGRAM = '$new_program', ";
        $query .= "USER_PHONE = '$new_phone', ";
        $query .= "USER_WEBSITE = '$new_website' ";
        $query .= "WHERE USER_ID = '$user1_id'";

        mysqli_query($dbConnection, $query) or die(mysqli_error($dbConnection));
        echo "<script language='JavaScript'>";
        echo "alert('Your profile updated successfully');";
        echo "window.location.replace('http://studentvalley.org/settings');";
        echo "</script>";
//        header("location: ".$_SERVER['PHP_SELF']);
    }
    else if($_GET['Change'] == "Email"){
        if(isset($_POST['email'])){
            $newEmail = $_POST['email'];
        }

        if(user_exist($dbConnection, $newEmail)){
            $email_error = "This email belongs to an account!";
        }else{
            $email_error = NULL;
            $email_feedback = "Email set successfully";

            $query = "UPDATE USERS SET USER_EMAIL = '$newEmail' WHERE USER_ID = '$user1_id'";
            mysqli_query($dbConnection, $query) or die(mysqli_error($dbConnection));

            session_destroy();
            echo "<script language='JavaScript'>";
            echo "alert('Please, login again with your new email address!');";
            echo "window.location.replace('http://studentvalley.org');";
            echo "</script>";
        }
    }
    else if($_GET['Change'] == "Password"){
        if(isset($_POST['currentPassword'])){
            $currentPassword = $_POST['currentPassword'];
        }
        if(isset($_POST['newPassword'])){
            $newPassword = crypt($_POST['newPassword']);
        }

        if(!password_verify($currentPassword, $password)){
            $password_error = "Incorrect password!";
        }else{
            $password_error = NULL;
            $password_feedback = "Password set successfully";

            $query = "UPDATE USERS SET USER_PASSWORD = '$newPassword' WHERE USER_ID = '$user1_id'";
            mysqli_query($dbConnection, $query) or die(mysqli_error($dbConnection));

            session_destroy();
            echo "<script language='JavaScript'>";
            echo "alert('Please, login again with your new password!');";
            echo "window.location.replace('http://studentvalley.org');";
            echo "</script>";
        }
    }
    else{
        /* THE THING YOU ARE TRYING TO CHANGE DOES NOT EXIST */
    }
}
?>
<div id="container">
    <?php require_once("./includes/sidebar.php"); ?>
    <div class="clearfix">
        <div id="content">
            <div id="settings-a">
                <div class="settings-title">UPDATE YOUR PROFILE INFORMATION</div>
                <div class="settings-content">

                    <div class="settings-subtitle">Change Profile Picture</div>
                    <div id="settings-picture">
                        <div class="clearfix">
                            <div id="settings-picture-a">
                                <img src="/images/profile/<?php echo $picture ?>" height="80" width="80">
                            </div>
                            <form action="/settings?Change=Photo" method="post" enctype="multipart/form-data">
                                <div class="clearfix">
                                    <div id="settings-picture-b">
                                        <div id="settings-picture-b-1">
                                            <input type="file" name="profilePicture">
                                        </div>
                                        <div id="settings-picture-b-2">
                                            <label class="error_picture"><?php echo $picture_error ?></label>
                                            <label class="feedback_picture"><?php echo $picture_feedback ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="settings-picture-c">
                                    <input type="submit" name="submit" value="Upload Photo" class="settings-button">
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="settings-subtitle settings-subtitle-2">Change Profile Information</div>
                    <div id="settings-information">
                        <div id="settings-information-a">
                            <form action="/settings?Change=Profile" method="post" id="profile">
                                <label for="firstName" class="settings-label">
                                    <span>First Name:</span>
                                    <input class="settings-input" type="text" name="firstName" autocomplete="off" value="<?php echo $first_name ?>">
                                </label>
                                <label for="lastName" class="settings-label">
                                    <span>Last Name:</span>
                                    <input class="settings-input" type="text" name="lastName" autocomplete="off" value="<?php echo $last_name ?>">
                                </label>
                                <label for="displayName" class="settings-label">
                                    <span>Display Name:</span>
                                    <input class="settings-input" type="text" name="displayName" autocomplete="off" value="<?php echo $display_name ?>">
                                </label>
                                <label for="school" class="settings-label">
                                    <span>School:</span>
                                    <select name="school" class="settings-input">
                                        <option value="Select Your School" disabled selected>Select Your School</option>
                                        <?php
                                        /* This code will print school options from database.
                                         *
                                         * If user's school matches with any of the school from database,
                                         * mark it as "selected" otherwise, use "Select Your School" as selected.
                                         *
                                         * So, "selected" attribute of user's school will overwrite the "selected"
                                         * attribute of "Select Your School".
                                         * */

                                        $query_schools = "SELECT * FROM SCHOOLS ORDER BY SCHOOL_TYPE ASC";
                                        $query_users = "SELECT USER_SCHOOL FROM USERS WHERE USER_ID = $user1_id";

                                        $schools_result = mysqli_query($dbConnection, $query_schools);
                                        $users_result = mysqli_query($dbConnection, $query_users);

                                        while($data = mysqli_fetch_assoc($users_result)){
                                            $user_school = $data['USER_SCHOOL'];
                                        }

                                        foreach($schools_result as $school_result){
                                            if($user_school == $school_result['SCHOOL_NAME']){
                                                echo "<option value=" . $school_result['SCHOOL_SHORT_NAME'] . " selected>" . $school_result['SCHOOL_NAME']  . "</option>";
                                            }else{
                                                echo "<option value=" . $school_result['SCHOOL_SHORT_NAME'] . ">" . $school_result['SCHOOL_NAME'] . "</option>";
                                            }
                                        }
                                        ?>
                                        <option value="Other">Other</option>
                                    </select>
                                </label>
                                <label for="program" class="settings-label">
                                    <span>Program:</span>
                                    <input class="settings-input" type="text" name="program" autocomplete="off" value="<?php echo $program ?>">
                                </label>
                                <label for="phone" class="settings-label">
                                    <span>Phone:</span>
                                    <input class="settings-input" type="text" name="phone" autocomplete="off" value="<?php echo $phone ?>">
                                </label>
                                <label for="website" class="settings-label">
                                    <span>Website:</span>
                                    <input class="settings-input" type="text" name="website" autocomplete="off" value="<?php echo $website ?>">
                                </label>
                                <script>$("profile").validate();</script>

                                <div id="settings-information-b">
                                    <input type="submit" name="submit" value="Save Changes" class="settings-button">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div id="settings-b">
                <div class="settings-title">GENERAL ACCOUNT SETTINGS</div>
                <div class="settings-content">

                    <div class="settings-subtitle">Change Email</div>
                    <div id="settings-email">
                        <div id="settings-email-a">
                            <form action="/settings?Change=Email" method="post" id="email">
                                <label for="email" class="settings-label">
                                    <span>Email:</span>
                                    <input class="settings-input" type="text" name="email" autocomplete="off" value="<?php echo $email ?>">
                                    <label class="error_message"><?php echo $email_error; ?></label>
                                    <label class="feedback_message"><?php echo $email_feedback ?></label>
                                </label>
                                <script>$("email").validate();</script>

                                <div id="settings-email-b">
                                    <input type="submit" name="submit" value="Save Changes" class="settings-button">
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="settings-subtitle settings-subtitle-2">Change Password</div>
                    <div id="settings-password">
                        <div id="settings-password-a">
                            <form action="/settings?Change=Password" method="post" id="password">
                                <label for="currentPassword" class="settings-label">
                                    <span>Current Password:</span>
                                    <input class="settings-input" type="password" name="currentPassword">
                                    <label class="error_message"><?php echo $password_error; ?></label>
                                    <label class="feedback_message"><?php echo $password_feedback ?></label>
                                </label>
                                <label for="newPassword" class="settings-label">
                                    <span>New Password:</span>
                                    <input class="settings-input" type="password" name="newPassword" id="newPassword">
                                </label>
                                <label for="newPasswordAgain" class="settings-label">
                                    <span>Re-type New Password:</span>
                                    <input class="settings-input" type="password" name="newPasswordAgain">
                                </label>
                                <script>$("password").validate();</script>

                                <div id="settings-password-b">
                                    <input type="submit" name="submit" value="Save Changes" class="settings-button">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once("./includes/footer.php");
?>