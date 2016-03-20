<?php
require_once("./../includes/header.php");

if(isset($_GET['id'])){
    $group_id = mysqli_real_escape_string($dbConnection, $_GET['id']);

    $query = "SELECT * FROM GROUPS ";
    $query .= "WHERE GROUP_ID = ";
    $query .= "'$group_id'";

    $result = mysqli_query($dbConnection, $query) or die(mysqli_error($dbConnection));
    if(mysqli_num_rows($result) == 1){
        while($row = mysqli_fetch_assoc($result)){
            $name = $row['GROUP_NAME'];
            $type = $row['GROUP_TYPE'];
            $admin = $row['GROUP_ADMIN'];
            $members = $row['GROUP_MEMBERS'];
            $banned_members = $row['GROUP_BANNED_MEMBERS'];
            $creation_date = $row['GROUP_CREATION_DATE'];
            $picture = $row['GROUP_PICTURE_ID'];
        }
        ?>
        <div id="container">
            <?php require_once("./../includes/sidebar.php"); ?>
            <div class="clearfix">
                <div id="content">
                    <div id="group-a">
                        <div class="clearfix">
                            <div id="group-name"><?php echo $name ?></div>
                            <div id="group-buttons">
                                <ul>
                                    <li><a href="/groups?Join=<?php echo $group_id ?>">Join Group</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="group-b">
                        <img src="/images/group/<?php echo $picture ?>" height="145" width="743">
                    </div>
                    <div id="group-c">
                        <div id="group-menu">
                            <ul>
                                <div class="clearfix">
                                    <li class="group-memu-item" id="group-icon-1"><a href="/group/<?php echo $group_id ?>">Feeds</a></li>
                                    <li class="group-memu-item" id="group-icon-2"><a href="/group/<?php echo $group_id ?>/post">Add Post</a></li>
                                    <li class="group-memu-item" id="group-icon-3"><a href="/group/<?php echo $group_id ?>/members">Members</a></li>
                                    <li class="group-memu-item" id="group-icon-4"><a href="/group/<?php echo $group_id ?>/manage">Manage</a></li>
                                </div>
                            </ul>
                        </div>
                        <div class="clearfix">
                            <div id="group-d">
                                <form id="group-search-bar" action="/group/<?php echo $group_id ?>/search" method="post">
                                    <label for="search">
                                        <input type="text" name="search" placeholder="Press enter to search...">
                                    </label>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div id="group-e">


                    </div>
                </div>
            </div>
        </div>
    <?php
    }else{
        echo "Group does not exist!";
    }
}
require_once("./../includes/footer.php");
?>
