<?php
require_once("./includes/header.php");

if(isset($_GET['id'])){
    /** Need this variable ( $user2_id ) where we need to refer to the user who is in
     * interaction with user1 (logged in user).
     *
     * user1_id is defined in header.php. As long as, you have header.php required above the page
     * you don't need to redefine it. Use this if you'd like to redefine user1_id:
     * $user1_id = return_user_id($dbConnection, $_SESSION['email']);
     */
    $user2_id = mysqli_real_escape_string($dbConnection, $_GET['id']);

    $request_from_user1 = request_exist($dbConnection, $user1_id, $user2_id);
    $request_from_user2 = request_exist($dbConnection, $user2_id, $user1_id);

    /* --- Explanation of If Statements Below ---
     * If there is a connection request from user2:
     * Change button text to 'Accept Request', change link of the button to 'AcceptRequestOf' and change button color to green.
     *
     * If there is a connection request from user1:
     * Change button text to 'Cancel Request', change link of the button to 'CancelRequestFrom' and change button color to blue.
     *
     * If user1 and user2 are connected:
     * Change button text to 'Disconnect', change link of the button to 'DisconnectFrom' and change button color to red.
     *
     * If none of the if statements work:
     * Change button text to 'Connect' and change the link of the button to 'RequestTo'.
     * The color of the button is set to grey in default. Therefore, no need to change button color.
     * --------------------------------------- */
    if($request_from_user2){
        $button_text = "Accept Request";
        $link = "/connections?AcceptRequestOf=";
        $style = "border-top-color: #6BB72C; background: #6BB72C; color: #ffffff;";
    }else if($request_from_user1){
        $button_text = "Cancel Request";
        $link = "/connections?CancelRequestFrom=";
        $style = "border-top-color: #307fb7; background: #307fb7; color: #ffffff;";
    }else if(are_they_connected($dbConnection, $user1_id, $user2_id)){
        $button_text = "Disconnect";
        $link = "/connections?DisconnectFrom=";
        $style = "border-top-color: #B71C1F; background: #B71C1F; color: #ffffff;";
    }else{
        $button_text = "Connect";
        $link = "/connections?RequestTo=";
    }

    /* Checking if the user is in his/her page or not.
     * According to that, this statement will decide to display buttons or not.
     *
     * Because, we don't wanna display Connect Buttons to the user1 when he/she is in his/her own profile page.
     */
    if($user2_id == $user1_id){
        $buttons = "none";
    }else{
        $buttons = "block";
    }

    /* The query below returns all the necessary information for profile page. */
    $query = "SELECT * FROM USERS WHERE USER_ID = '$user2_id'";

    $result = mysqli_query($dbConnection, $query) or die(mysqli_error($dbConnection));
    if(mysqli_num_rows($result) == 1){
        while($row = mysqli_fetch_assoc($result)){
            $first_name = $row['USER_FIRST_NAME'];
            $last_name = $row['USER_LAST_NAME'];
            $email = $row['USER_EMAIL'];
            $school = $row['USER_SCHOOL'];
            $program = $row['USER_PROGRAM'];
            $phone = $row['USER_PHONE'];
            $registration_date = date_create($row['USER_REGISTRATION_DATE']);
            $website = $row['USER_WEBSITE'];
            $picture = $row['USER_PICTURE_ID'];
        }
        ?>

        <div id="container">
            <?php require_once("./includes/sidebar.php"); ?>
            <div class="clearfix">
                <div id="content">
                    <div id="profile-a">
                        <div class="clearfix">
                            <div id="profile-name"><?php echo $first_name . " " . $last_name; ?></div>
                            <div id="profile-buttons" style="display: <?php echo $buttons ?>">
                                <ul>
                                    <li><a href="<?php echo $link . $user2_id ?>" style="<?php echo $style ?>"><?php echo $button_text ?></a></li>
                                    <li><a href="/messages?SendTo=<?php echo $user2_id ?>">Send Message</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix">
                        <div id="profile-b">
                            <div id="profile-picture"><img src="/images/profile/<?php echo $picture ?>"></div>
                        </div>
                        <div id="profile-c">
                            <div class="profile-title">INFORMATION</div>
                            <div id="profile-content-a">
                                <div class="profile-info"><span>School: </span><?php echo $school ?></div>
                                <div class="profile-info"><span>Program: </span><?php echo $program ?></div>
                                <div class="profile-info"><span>Email: </span><?php echo $email ?></div>
                                <div class="profile-info"><span>Phone: </span><?php echo $phone ?></div>
                                <div class="profile-info"><span>Website: </span><a href="http://<?php echo $website; ?>" target="_blank"><?php echo $website ?></a></div>
                                <div class="profile-info"><span>Member Since: </span><?php echo  date_format($registration_date,"F j, Y") ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix">
                        <div id="profile-d">
                            <div class="profile-title">GROUPS</div>
                            <div id="profile-content-b">
                                <div class="profile-item groups-logo"><a href="#" title="Android Open Source Club">Android Open Source Club</a></div>
                                <div class="profile-item groups-logo"><a href="#" title="Seneca Tutors">Seneca Tutors</a></div>
                                <div class="profile-item groups-logo"><a href="#" title="International Canada Robot Project">International Canada Robot Project</a></div>
                                <div class="profile-item groups-logo"><a href="#" title="DBS Group Project">DBS Group Project</a></div>
                                <div class="profile-item groups-logo"><a href="#" title="CPA Seneca Student">CPA Seneca Student</a></div>
                            </div>
                        </div>
                        <div id="profile-e">
                            <div class="profile-title">COURSES</div>
                            <div id="profile-content-c">
                                <div class="profile-item courses-logo"><a href="#">OOP345</a></div>
                                <div class="profile-item courses-logo"><a href="#">DBS301</a></div>
                                <div class="profile-item courses-logo"><a href="#">CUL710</a></div>
                                <div class="profile-item courses-logo"><a href="#">INT322</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }else{
        echo "User does not exist!";
    }
}else{
    echo "Need user ID for the profile page!";
}
require_once("./includes/footer.php");
?>