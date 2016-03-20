<?php
ob_start();
session_start();
require_once("./includes/header-global.php");
if($_POST){
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];

    $query = "SELECT * FROM USERS ";
    $query .= "WHERE USER_EMAIL = ";
    $query .= "'$user_email'";

    $result = mysqli_query($dbConnection, $query) or die(mysqli_error($dbConnection));
    $num_rows = mysqli_num_rows($result);

    if($num_rows != 0){
        while($row = mysqli_fetch_assoc($result)){
            $db_user_email = $row['USER_EMAIL'];
            $db_user_password = $row['USER_PASSWORD'];
        }
        if($user_email == $db_user_email && password_verify($user_password, $db_user_password)){
            $_SESSION['email'] = $db_user_email;
            header('Location: /home');
        }else{
            $error_login = "The password you entered is incorrect.";
        }
    }else{
        $error_login = "The email you entered does not belong to any account.";
    }
}
?>
<div id="body-login">
    <div class="clearfix">
        <div id="body-login-wrapper">
            <form id="login-form" action="" method="post">
                <div id="logo-small-wrapper"><i>SV</i></div>
                <div class="clearfix">
                    <label for="email">Email:</label>
                    <input class="login-page-input" type="text" name="email">
                </div>
                <div class="clearfix">
                    <label for="password">Password:</label>
                    <input class="login-page-input" type="password" name="password">
                </div>
                <input class="login-page-checkbox" type="checkbox" name="remember"><span>Remember me</span>
                <a href="/forgot" class="login-page-forgot">Forgot your password?</a>
                <div id="clearfix">
                    <input class="login-page-button" type="submit" name="submit" value="Log In">
                    <div id="signup-link">or <a href="/signup">Sign up to Student Valley</a></div>
                </div>
                <div id="login-error"><?php echo $error_login?></div>
            </form>
        </div>
    </div>
</div>
<?php
require_once("./includes/footer.php");
?>