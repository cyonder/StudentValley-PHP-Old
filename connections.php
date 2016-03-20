<?php
ob_start();
require_once("./includes/header.php");

/** When named a variable $user2, we refer to the user who is in interaction with user1 (logged in user).
 *
 * user1_id is defined in header.php. As long as, you have header.php required above the page
 * you don't need to redefine it. Use this if you'd like to redefine user1_id:
 * $user1_id = return_user_id($dbConnection, $_SESSION['email']);
 */

/* --- Explanation of If Statements Below ---
 *
 * --- RequestTo ---
 * If 'RequestTo' is set. It means user1 clicked 'Connect' button. So, REQUEST_FROM is user1 and REQUEST_TO is user2.
 * Checking if the user passed his/her own id from 'RequestTo'. If did, display error message. Otherwise,
 * check if the request is already sent with function 'request_exist'. This function is necessary if someone tries
 * to submit a request multiple times, so database size will increase. After request sent, send user1 back to
 * user2's profile page. (This button is in user2's profile page).
 *
 * --- CancelRequestFrom ---
 * If 'CancelRequestFrom' is set. It means user1 clicked 'Cancel Request' button. So, REQUEST_FROM is user1
 * and REQUEST_TO is user2. Checking if the user passed his/her own id from 'CancelRequestFrom'. If did, display
 * error message. Otherwise, send user1 back to user2's profile page. (This button is user2's profile page).
 *
 * --- AcceptRequestOf ---
 * If 'AcceptRequestOf' is set. It means user1 clicked 'Accept Request' button. So, REQUEST_FROM is user2 and
 * REQUEST_TO is user1. Because connection request came from user2 to user1. Checking if the user passed his/her own id
 * from 'AcceptRequestOf'. If did, display error message.
 * This statement will do double work. When the users add each other, this statement will add both users to each others
 * connection list. If a user has no connection, it will add it but if a user already has a connection, it will concatenate the
 * added user with a comma. So, all the connection list will be separated with coma.
 * (This button is in user1's connections page).
 *
 * --- DeleteRequestOf ---
 * NOTE: 'DeleteRequestOf' is different than 'CancelRequestFrom'..!
 * If 'DeleteRequestOf' is set. It means user1 clicked 'Delete Request' button. So, REQUEST_FROM is user2 and
 * REQUEST_TO is user1. Because connection request came from user2 to user1. Checking if the user passed his/her own id
 * from 'DeleteRequestOf'. If did, display error message. Otherwise, send user1 back to connections page.
 * (This button is in user1's connections page).
 *
 * --- DisconnectFrom ---
 * If 'DisconnectFrom' is set. It means user1 clicked 'Disconnect' button. So, This statement will do double work.
 * When one of the users disconnect form other one, this statement will delete both users from each others connection list.
 * This statement's process is little bit confusing. You can read details from inside the statement.
 * --------------------------------------- */

if(isset($_GET['RequestTo'])){
    $user1 = $user1_id;
    $user2 = $_GET['RequestTo'];
    $request_date = date("Y-m-d H:i:s");

    if($user1 == $user2){
        /* YOU CANNOT SEND CONNECTION REQUEST TO YOURSELF AND NEITHER CANCEL IT, ACCEPT IT, DELETE IT OR DISCONNECT IT */
    }
    else if(request_exist($dbConnection, $user1, $user2)){
        /* YOU ALREADY SENT A REQUEST TO THIS USER..! */
    }else{
        $query = "INSERT INTO REQUESTS ";
        $query .= "(REQUEST_FROM, REQUEST_TO, REQUEST_DATE) ";
        $query .= "VALUES('{$user1}', '{$user2}', '{$request_date}')";

        mysqli_query($dbConnection, $query) or die(mysqli_error($dbConnection));

        header("location: /profile/".$user2);
    }
}
else if(isset($_GET['CancelRequestFrom'])){
    $user1 = $user1_id;
    $user2 = $_GET['CancelRequestFrom'];

    if($user1 == $user2){
        /* YOU CANNOT SEND CONNECTION REQUEST TO YOURSELF AND NEITHER CANCEL IT, ACCEPT IT, DELETE IT OR DISCONNECT IT */
    }
    else if(!request_exist($dbConnection, $user1, $user2)){
        /* YOU HAVEN'T SEND ANY REQUEST TO THIS USER YET..! */
    }else{
        $query = "DELETE FROM REQUESTS ";
        $query .= "WHERE REQUEST_FROM = ";
        $query .= "'{$user1}' AND ";
        $query .= "REQUEST_TO = ";
        $query .= "'{$user2}'";

        mysqli_query($dbConnection, $query) or die(mysqli_error($dbConnection));

        header("location: /profile/". $user2);
    }
}
else if(isset($_GET['AcceptRequestOf'])){
    $user1 = $user1_id;
    $user2 = $_GET['AcceptRequestOf'];

    if($user1 == $user2){
        /* YOU CANNOT SEND CONNECTION REQUEST TO YOURSELF AND NEITHER CANCEL IT, ACCEPT IT, DELETE IT OR DISCONNECT IT */
    }
    else if(!request_exist($dbConnection, $user2, $user1)){
        /* YOU CAN'T CONNECT TO THIS USER WITHOUT SENDING A REQUEST..! */
    }else{
        $result1 = mysqli_query($dbConnection, "SELECT USER_CONNECTIONS FROM USERS WHERE USER_ID = '$user1'");
        $data1 = mysqli_fetch_assoc($result1);
        $user1_connections = $data1['USER_CONNECTIONS']; //This is all the connections user1 has.
        $user1_each_connection = explode(",", $user1_connections); //This is every single connection user1 has. (In array).
        $user1_total_connection = count($user1_each_connection); //If user1 has no connections, explode will return 1..! (to avoid that!)

        if($user1_connections == NULL){
            $user1_total_connection = 0;
        }
        if($user1_total_connection == 0){
            mysqli_query($dbConnection, "UPDATE USERS SET USER_CONNECTIONS = '$user2' WHERE USER_ID = '$user1'");
        }else{
            mysqli_query($dbConnection, "UPDATE USERS SET USER_CONNECTIONS = CONCAT(USER_CONNECTIONS, ',$user2') WHERE USER_ID = '$user1'");
        }

        $result2 = mysqli_query($dbConnection, "SELECT USER_CONNECTIONS FROM USERS WHERE USER_ID = '$user2'");
        $data2 = mysqli_fetch_assoc($result2);
        $user2_connections = $data2['USER_CONNECTIONS']; //This is all the connections user2 has.
        $user2_each_connection = explode(",", $user2_connections); //This is every single connection user2 has. (In array).
        $user2_total_connection = count($user2_each_connection);  //If user2 has no connections, explode will return 1..! (to avoid that!)

        if($user2_connections == NULL){
            $user2_total_connection = 0;
        }
        if($user2_total_connection == 0){
            mysqli_query($dbConnection, "UPDATE USERS SET USER_CONNECTIONS = '$user1' WHERE USER_ID = '$user2'");
        }else{
            mysqli_query($dbConnection, "UPDATE USERS SET USER_CONNECTIONS = CONCAT(USER_CONNECTIONS, ',$user1') WHERE USER_ID = '$user2'");
        }

        mysqli_query($dbConnection, "DELETE FROM REQUESTS WHERE REQUEST_FROM = '$user2' AND REQUEST_TO = '$user1'"); //Delete the related connection request from REQUEST table.
        header("location: /connections");
    }
}
else if(isset($_GET['DeleteRequestOf'])){
    $user1 = $user1_id;
    $user2 = $_GET['DeleteRequestOf'];

    if($user1 == $user2){
        /* YOU CANNOT SEND CONNECTION REQUEST TO YOURSELF AND NEITHER CANCEL IT, ACCEPT IT, DELETE IT OR DISCONNECT IT */
    }
    else if(!request_exist($dbConnection, $user2, $user1)){
        /* This user haven't send you any request..! */
    }else{
        $query = "DELETE FROM REQUESTS ";
        $query .= "WHERE REQUEST_FROM = ";
        $query .= "'{$user2}' AND ";
        $query .= "REQUEST_TO = ";
        $query .= "'{$user1}'";

        mysqli_query($dbConnection, $query) or die(mysqli_error($dbConnection));

        header("location: /connections");
    }
}else if(isset($_GET['DisconnectFrom'])){
    $user1 = $user1_id;
    $user2 = $_GET['DisconnectFrom'];

    if($user1 == $user2){
        /* YOU CANNOT SEND CONNECTION REQUEST TO YOURSELF AND NEITHER CANCEL IT, ACCEPT IT, DELETE IT OR DISCONNECT IT */
    }else{
        $result1 = mysqli_query($dbConnection, "SELECT USER_CONNECTIONS FROM USERS WHERE USER_ID = '$user1'");
        $data1 = mysqli_fetch_assoc($result1);
        $user1_connections = $data1['USER_CONNECTIONS']; //This is all the connections user1 has.
        $user1_each_connection = explode(",", $user1_connections); //This is every single connection user1 has. (In array).

        $key1 = array_search($user2, $user1_each_connection); //Find which key is user2 in the array,
        unset($user1_each_connection[$key1]); //Remove the user2 from user1's connection list.
        $user1_new_total_connection = count($user1_each_connection); //Calculate the total number of user1's connections after deletion.
        $user1_each_connection = array_values($user1_each_connection); //Reorder arrays.
        for($i = 0; $i < $user1_new_total_connection; $i++){ //Create a string with comas for the new connection list.
            if($i == $user1_new_total_connection - 1){ //Check if it is adding the last connection to the list and don't add come at the end.
                $user1_new_connections .= $user1_each_connection[$i];
            }else{
                $user1_new_connections .= $user1_each_connection[$i] . ",";
            }
        }
        mysqli_query($dbConnection, "UPDATE USERS SET USER_CONNECTIONS = '$user1_new_connections' WHERE USER_ID = '$user1'");

        $result2 = mysqli_query($dbConnection, "SELECT USER_CONNECTIONS FROM USERS WHERE USER_ID = '$user2'");
        $data2 = mysqli_fetch_assoc($result2);
        $user2_connections = $data2['USER_CONNECTIONS']; //This is all the connections user2 has.
        $user2_each_connection = explode(",", $user2_connections); //This is every single connection user2 has. (In array).

        $key2 = array_search($user1, $user2_each_connection); //Find which key is user1 in the array,
        unset($user2_each_connection[$key2]); //Remove the user1 from user2's connection list.
        $user2_each_connection = array_values($user2_each_connection); //Reorder arrays.
        $user2_new_total_connection = count($user2_each_connection); //Calculate the total number of user2's connections after deletion.

        for($i = 0; $i < $user2_new_total_connection; $i++){ //Create a string with comas for the new connection list.
            if($i == $user2_new_total_connection - 1){ //Check if it is adding the last connection to the list and don't add come at the end.
                $user2_new_connections .= $user2_each_connection[$i];
            }else{
                $user2_new_connections .= $user2_each_connection[$i] . ",";
            }
        }
        mysqli_query($dbConnection, "UPDATE USERS SET USER_CONNECTIONS = '$user2_new_connections' WHERE USER_ID = '$user2'");

        header("location: /profile/". $user2);
    }
}

/* Return the information of people who sent request to user1 */
$query = "SELECT u.* FROM USERS u , REQUESTS r WHERE (r.REQUEST_TO = '{$user1_id}') and (u.USER_ID = r.REQUEST_FROM) ORDER BY r.REQUEST_DATE DESC;";

$result = mysqli_query($dbConnection, $query) or die(mysqli_error($dbConnection));

$first_name = array();
$last_name = array();
$school = array();
$program = array();
$picture = array();

if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
        $user_id[] = $row['USER_ID'];
        $first_name[] = $row['USER_FIRST_NAME'];
        $last_name[] = $row['USER_LAST_NAME'];
        $school[] = $row['USER_SCHOOL'];
        $program[] = $row['USER_PROGRAM'];
        $picture[] = $row['USER_PICTURE_ID'];
    }
}else{
    /* YOU DON'T HAVE ANY CONNECTION REQUEST*/
}

/* Return the information of user1's connections */
$result1 = mysqli_query($dbConnection, "SELECT USER_CONNECTIONS FROM USERS WHERE USER_ID = '$user1_id'");
$data1 = mysqli_fetch_assoc($result1);
$user1_connections = $data1['USER_CONNECTIONS']; //This is all the connections user1 has.
$user1_each_connection = explode(",", $user1_connections); //This is every single connection user1 has. (In array).
$user1_total_connection = count($user1_each_connection);

if($user1_connections == NULL){ //If user1 has no connections, explode will return 1..! (to avoid that!)
    $user1_total_connection = 0;
    /* YOU DON'T HAVE ANY CONNECTIONS */
}else{
    $conn_first_name = array();
    $conn_last_name = array();
    $conn_school = array();
    $conn_program = array();
    $conn_picture = array();

    if(isset($_GET['Letter'])){
        $letter = $_GET['Letter'];
        $index = 0; /* Defining index variable */
        $user1_total_connection = 0;
    }

    foreach($user1_each_connection as $value){
        $query = "SELECT * FROM USERS ";
        $query .= "WHERE USER_ID = '{$value}' ";
        $query .= "AND USER_FIRST_NAME LIKE '$letter%'";

        $result = mysqli_query($dbConnection, $query) or die(mysqli_error($dbConnection));
        $row = mysqli_fetch_assoc($result);

        if(mysqli_num_rows($result) > 0){
            $index++;
            $user1_total_connection = $index;

            $conn_user_id[] = $row['USER_ID'];
            $conn_first_name[] = $row['USER_FIRST_NAME'];
            $conn_last_name[] = $row['USER_LAST_NAME'];
            $conn_school[] = $row['USER_SCHOOL'];
            $conn_program[] = $row['USER_PROGRAM'];
            $conn_picture[] = $row['USER_PICTURE_ID'];
        }
    }
}
?>
<div id="container">
    <?php require_once("./includes/sidebar.php"); ?>
    <div class="clearfix">
        <div id="content">
            <div id="clearfix">
                <div id="conn-a">
                    <div class="conn-title">CONNECTION REQUESTS</div>
                    <div class="conn-content">
                        <?php for($i = 0; $i < count($user_id); $i++){ ?>
                            <div id="clearfix">
                                <div class="conn-request">
                                    <div class="conn-picture">
                                        <img src="/images/profile/<?php echo $picture[$i] ?>" height="80" width="80">
                                    </div>
                                    <div id="clearfix">
                                        <div class="conn-name">
                                            <a href="/profile/<?php echo $user_id[$i]; ?>"><?php echo $first_name[$i] . " " . $last_name[$i]; ?></a>
                                        </div>
                                    </div>
                                    <div id="clearfix">
                                        <div id="conn-details">
                                            <div class="conn-school">
                                                <?php echo $school[$i]; ?>
                                            </div>
                                            <div class="conn-program">
                                                <?php echo $program[$i]; ?>
                                            </div>
                                        </div>
                                        <div id="conn-buttons">
                                            <ul>
                                                <li>
                                                    <a href="/connections?AcceptRequestOf=<?php echo $user_id[$i]; ?>"
                                                       style="border-top-color: #6BB72C; background: #6BB72C; color: #ffffff;">Accept Request</a>
                                                </li>
                                                <li>
                                                    <a href="/connections?DeleteRequestOf=<?php echo $user_id[$i]; ?>"
                                                       style="border-top-color: #B71C1F; background: #B71C1F; color: #ffffff;">Delete Request</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div id="conn-b">
                    <div class="conn-title">YOUR CONNECTIONS
                    <div id="conn-find">
                        <a href="/connections?Letter=a" class="conn-find-item">A</a>
                        <a href="/connections?Letter=b" class="conn-find-item">B</a>
                        <a href="/connections?Letter=c" class="conn-find-item">C</a>
                        <a href="/connections?Letter=d" class="conn-find-item">D</a>
                        <a href="/connections?Letter=e" class="conn-find-item">E</a>
                        <a href="/connections?Letter=f" class="conn-find-item">F</a>
                        <a href="/connections?Letter=g" class="conn-find-item">G</a>
                        <a href="/connections?Letter=h" class="conn-find-item">H</a>
                        <a href="/connections?Letter=i" class="conn-find-item">I</a>
                        <a href="/connections?Letter=j" class="conn-find-item">J</a>
                        <a href="/connections?Letter=k" class="conn-find-item">K</a>
                        <a href="/connections?Letter=l" class="conn-find-item">L</a>
                        <a href="/connections?Letter=m" class="conn-find-item">M</a>
                        <a href="/connections?Letter=n" class="conn-find-item">N</a>
                        <a href="/connections?Letter=o" class="conn-find-item">O</a>
                        <a href="/connections?Letter=p" class="conn-find-item">P</a>
                        <a href="/connections?Letter=q" class="conn-find-item">Q</a>
                        <a href="/connections?Letter=r" class="conn-find-item">R</a>
                        <a href="/connections?Letter=s" class="conn-find-item">S</a>
                        <a href="/connections?Letter=t" class="conn-find-item">T</a>
                        <a href="/connections?Letter=u" class="conn-find-item">U</a>
                        <a href="/connections?Letter=v" class="conn-find-item">V</a>
                        <a href="/connections?Letter=w" class="conn-find-item">W</a>
                        <a href="/connections?Letter=x" class="conn-find-item">X</a>
                        <a href="/connections?Letter=y" class="conn-find-item">Y</a>
                        <a href="/connections?Letter=z" class="conn-find-item">Z</a>
                    </div></div>
                    <div class="conn-content">
                        <?php for($i = 0; $i < $user1_total_connection; $i++){?>
                            <div id="clearfix">
                                <div class="conn-request">
                                    <div class="conn-picture">
                                        <img src="/images/profile/<?php echo $conn_picture[$i] ?>" height="80" width="80">
                                    </div>
                                    <div id="clearfix">
                                        <div class="conn-name">
                                            <a href="profile/<?php echo $conn_user_id[$i]; ?>"><?php echo $conn_first_name[$i] . " " . $conn_last_name[$i]; ?></a>
                                        </div>
                                    </div>
                                    <div id="clearfix">
                                        <div id="conn-details">
                                            <div class="conn-school">
                                                <?php echo $conn_school[$i]; ?>
                                            </div>
                                            <div class="conn-program">
                                                <?php echo $conn_program[$i]; ?>
                                            </div>
                                        </div>
                                        <div id="conn-buttons">
                                            <ul>
                                                <li>
                                                    <a href="/messages?SendTo=<?php echo $conn_user_id[$i]; ?>">Send Message</a>
                                                </li>
                                                <li>
                                                    <a href="/connections?DisconnectFrom=<?php echo $conn_user_id[$i]; ?>"
                                                       style="border-top-color: #B71C1F; background: #B71C1F; color: #ffffff;">Disconnect</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once("./includes/footer.php");
?>
