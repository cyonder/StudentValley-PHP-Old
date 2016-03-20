<?php
ob_start();
require_once("./includes/header-global.php");

/* If the email that is used for registration is same, signup.php will return error
 * According to that, this function will decide to display the error message or not.
 * */
if(isset($_GET['error'])){
    $error = "block";
}else{
    $error = "none";
}

/*
 * If form inputs are valid, this sign up process will start.
 * jQuery don't let the user submit form unless it's correct.
*/

/* Check if the-mail address is already exist
 * If it is, send user back to index.php with an error message
 *
 * If the e-mail doesn't exist do the process below.
 * Check if the user tried to log in from signup.php or index.php by using $_GET['attempt']
 * According to that send the user to related page with an error.
 * If everything is okay register the user to the StudentValley.
 * */

if($_POST){
    $user_first_name = trim($_POST['firstName']);
    $user_last_name = trim($_POST['lastName']);
    $user_email = trim($_POST['email']);
    $user_password = crypt(trim($_POST['password']));
    $user_registration_date = date("Y-m-d H:i:s");

    $result = user_exist($dbConnection, $user_email);
    if($result){
        if(isset($_GET['attempt'])){
            header("Location: /signup?error=email");
        }else{
            header("Location: /index?error=email");
        }
    }else{ //UN-COMMENT BELOW SECTION TO MAKE USER REGISTRATION ACTIVE.
        $result = NULL; //Reset to re-use this variable.
        $query = "INSERT INTO USERS (";
        $query .= "USER_FIRST_NAME, USER_LAST_NAME, USER_EMAIL, USER_PASSWORD, USER_REGISTRATION_DATE";
        $query .= ")VALUES(";
        $query .= "'{$user_first_name}', '{$user_last_name}', '{$user_email}', '{$user_password}', '{$user_registration_date}'";
        $query .= ")";

        $result = mysqli_query($dbConnection, $query) or die(mysqli_error($dbConnection));
        $_SESSION['email'] = $user_email;
        header("Location: /start/welcome");
    }
}
?>
<div id="body-signup">
    <div class="clearfix">
        <div id="body-signup-wrapper">
            <form id="signup" action="/signup?attempt=signup" method="post">
                <div id="logo-small-wrapper"><i>SV</i></div>
                <div class="clearfix">
                    <label for="firstName">First Name:</label>
                    <input class="signup-page-input" type="text" name="firstName">
                </div>
                <div class="clearfix">
                    <label for="lastName">Last Name:</label>
                    <input class="signup-page-input" type="text" name="lastName">
                </div>
                <div class="clearfix">
                    <label for="email">Email:</label>
                    <input class="signup-page-input" type="email" name="email">
                </div>
                <div class="clearfix">
                    <label for="password">Password:</label>
                    <input class="signup-page-input" type="password" name="password">
                </div>
                <div id="clearfix">
                    <input class="signup-page-button" type="submit" name="submit" value="Sign Up">
                    <div id="login-link">or <a href="login">Log into existing account</a></div>
                </div>
                <div id="signup-error" style="display: <?php echo $error ?>">
                    Sorry, the email you tried belongs to an existing account. <br /> <br /> If it is you <a href="login">try to log in</a>
                    or if you forgot your password you can <a href="forgot">try to reset</a>.
                </div>
            </form>
            <script>$("signup").validate();</script>
        </div>
    </div>
</div>
<?php
require_once("./includes/footer.php");
?>