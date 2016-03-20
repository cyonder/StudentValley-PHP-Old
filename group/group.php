<?php
require_once("./../includes/header.php");

/** user1_id is defined in header.php. As long as, you have header.php required above the page
 * you don't need to redefine it. Use this if you'd like to redefine user1_id:
 * $user1_id = return_user_id($dbConnection, $_SESSION['email']);
 */

if(isset($_GET['id'])){
    $group_id = mysqli_real_escape_string($dbConnection, $_GET['id']);
    $user1_picture_id = return_user_picture_id($dbConnection, $_SESSION['email']);

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

        /* INSERT INTO PINS */
        if(isset($_POST['pinEditor'])){
            $pin_author = $user1_id;
            $pin_group = $group_id;
            /*Converting apostrophe into html entity. If we don't, SQL will give an error or don't display text after apostrophe */
            $pin_content = trim(str_replace("'", "&apos;", $_POST['pinEditor']));
            $pin_date = date("Y-m-d H:i:s");

            $query = "INSERT INTO PINS (";
            $query .= "PIN_AUTHOR, PIN_GROUP, PIN_CONTENT, PIN_DATE";
            $query .= ")VALUES(";
            $query .= "'{$pin_author}', '{$pin_group}', '{$pin_content}', '{$pin_date}'";
            $query .= ")";

//            if(multiple_submission($pin_content)){
            mysqli_query($dbConnection, $query);
//            }else{
            /*YOU ALREADY SUBMITTED*/
//            }
        }

        /* GET THE ALL PINS OF THE GROUP */
        $query = "SELECT * FROM PINS WHERE PIN_GROUP = $group_id ORDER BY PIN_DATE DESC";
        $result = mysqli_query($dbConnection, $query);
        $pin_num_rows = mysqli_num_rows($result);

        $pin_id = array();
        $author = array();
        $group = array();
        $content = array();
        $vote = array();
        $date = array();

        while($row = mysqli_fetch_assoc($result)){
            $pin_id[] = $row['PIN_ID'];
            $author[] = $row['PIN_AUTHOR'];
            $group[] = $row['PIN_GROUP'];
            $content[] = $row['PIN_CONTENT'];
            $vote[] = $row['PIN_VOTE'];
            $date[] = date_create($row['PIN_DATE']);
        }

        /* GET THE INFORMATION OF USERS, WHO PINNED IN THE GROUP */
        $author_first_name = array();
        $author_last_name = array();
        $author_picture_id = array();

        foreach($author as $value){
            $query = "SELECT * FROM USERS WHERE USER_ID = $value";
            $result = mysqli_query($dbConnection, $query);
            $row = mysqli_fetch_assoc($result);

            $author_first_name[] = $row['USER_FIRST_NAME'];
            $author_last_name[] = $row['USER_LAST_NAME'];
            $author_picture_id[] = $row['USER_PICTURE_ID'];
        }

        /* INSERT INTO PIN COMMENTS */
        if(isset($_POST["group-comment"])){
            $comment_author = $user1_id;
            $comment_group = $group_id;
            $comment_pin = mysqli_real_escape_string($dbConnection, $_GET['Comment']);
            $comment_content = trim($_POST['group-comment']);
            $comment_date = date("Y-m-d H:i:s");

            $query = "INSERT INTO PIN_COMMENTS (";
            $query .= "COMMENT_AUTHOR, COMMENT_GROUP, COMMENT_PIN, COMMENT_CONTENT, COMMENT_DATE";
            $query .= ")VALUES(";
            $query .= "'{$comment_author}', '{$comment_group}', '{$comment_pin}', '{$comment_content}', '{$comment_date}'";
            $query .= ")";

//            if(multiple_submission($comment_content)){
            mysqli_query($dbConnection, $query);
//            }else{
            /*YOU ALREADY SUBMITTED*/
//            }
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
                        <img src="/images/group/<?php echo $picture ?>">
                    </div>
                    <div id="group-c">
                        <div id="group-menu">
                            <ul>
                                <div class="clearfix">
                                    <li class="group-memu-item" id="group-icon-1"><a href="/group/<?php echo $group_id ?>">Feeds</a></li>
                                    <li class="group-memu-item" id="group-icon-2"><a href="javascript:void(0)">Add a Pin</a></li>
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
                        <script>
                            $(document).ready(
                                function(){
                                    $("#group-icon-2").click(function(){
                                        $("#toggle-editor").toggle();
                                    });
                                });
                        </script>
                        <div id="toggle-editor">
                            <form action="" method="post" name="pinEditorForm">
                                <textarea name="pinEditor" id="pinEditor"></textarea>
                                <div class="clearfix">
                                    <div id="pinButton"><input type="submit" value="Pin On Group Page"</div>
                                </div>
                            </form>
                        </div>
                        <script type="text/javascript" src="/lib/froala/js/froala_editor.min.js"></script>
                        <script type="text/javascript" src="/lib/froala/js/plugins/colors.min.js"></script>
                        <script type="text/javascript" src="/lib/froala/js/plugins/tables.min.js"></script>
                        <script type="text/javascript" src="/lib/froala/js/plugins/lists.min.js"></script>
                        <script type="text/javascript" src="/lib/froala/js/plugins/urls.min.js"></script>
                        <script>$.Editable.DEFAULTS.key = 'GqpC-7A26yC-21yikadgsB-31xpoE2wkA-7=='</script>
                        <script type="text/javascript" src="/lib/froala/js/plugins/video.min.js"></script>
                        <script>
                            $(function() {
                                $('#pinEditor').editable({
                                    theme: 'custom',
                                    inlineMode: false,
                                    buttons: ['bold', 'italic', 'underline', 'color', 'formatBlock' , 'sep',
                                        'align' , 'insertOrderedList', 'insertUnorderedList' , 'table', 'sep',
                                        'createLink', 'insertImage', 'insertVideo'],
                                    placeholder: 'Share with your group...'
                                })
                            });
                        </script>
                    </div>
                    <div id="group-f">
                        <?php for($i = 0; $i < $pin_num_rows; $i++){ ?>
                            <div id="group-pin">
                                <div class="clearfix">
                                    <div id="group-pin-info">
                                        <div id="group-pin-pic">
                                            <img src="/images/profile/<?php echo $author_picture_id[$i] ?>" height="32" width="32">
                                        </div>
                                        <div id="group-pin-name">
                                            <a href="/profile/<?php echo $author[$i] ?>"><?php echo $author_first_name[$i] . " " . $author_last_name[$i] ?></a>
                                        </div>
                                        <div id="group-pin-date">
                                            <?php echo date_format($date[$i],"F j, Y \a\\t g:ia"); ?>
                                        </div>
                                    </div>
                                    <div id="group-pin-vote">
                                        <?php
                                        if($vote[$i] == NULL){
                                            echo "<span class='pin-votes'>+999</span>";
                                        }else{
                                            echo "<span class='pin-votes'>" . $vote[$i] . "</span>";
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div id="group-pin-content">
                                    <?php echo $content[$i] ?>
                                </div>
                                <div id="group-pin-buttons">
                                    <div class="clearfix">
                                        <div id="group-pin-buttons-a">
                                            <a href="/group/<?php echo $group_id ?>?UpVotePin=<?php echo $pin_id[$i] ?>" title="Up Vote" id="group-upvote-button">Up Vote</a>
                                            <a href="/group/<?php echo $group_id ?>?DownVotePin=<?php echo $pin_id[$i] ?>" title="Down Vote" id="group-downvote-button">Up Vote</a>
                                        </div>
                                        <div id="group-pin-buttons-b">
                                            <?php if($author[$i] == $user1_id){ //Display edit and delete buttons to its author only! ?>
                                                <a href="/group/<?php echo $group_id ?>?EditPin=<?php echo $pin_id[$i] ?>" title="Edit" id="group-edit-button">Edit</a>
                                                <a href="/group/<?php echo $group_id ?>?DeletePin=<?php echo $pin_id[$i] ?>" title="Delete" id="group-delete-button">Delete</a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div id="group-pin-comments">
                                    <div class="clearfix">
                                        <?php
                                        /* GET THE COMMENTS OF RELATED PIN */
                                        $query = "SELECT * FROM PIN_COMMENTS WHERE COMMENT_PIN = $pin_id[$i] ORDER BY COMMENT_DATE ASC";
                                        $result = mysqli_query($dbConnection, $query);

                                        while($row = mysqli_fetch_assoc($result)){
                                            $comment_id = $row['COMMENT_ID'];
                                            $comment_author = $row['COMMENT_AUTHOR'];
                                            $comment_group = $row['COMMENT_GROUP'];
                                            $comment_pin = $row['COMMENT_PIN'];
                                            $comment_content = $row['COMMENT_CONTENT'];
                                            $comment_vote = $row['COMMENT_VOTE'];
                                            $comment_date = date_create($row['COMMENT_DATE']);

                                            /* GET THE USERS, WHO COMMENTED IN THE PIN */
                                            $query2 = "SELECT * FROM USERS WHERE USER_ID = $comment_author";
                                            $result2 = mysqli_query($dbConnection, $query2);
                                            $row2 = mysqli_fetch_assoc($result2);

                                            $comment_author_first_name = $row2['USER_FIRST_NAME'];
                                            $comment_author_last_name = $row2['USER_LAST_NAME'];
                                            $comment_author_picture_id = $row2['USER_PICTURE_ID'];

                                            ?>
                                            <div class="clearfix">
                                                <div id="group-pin-comments-a">
                                                    <div id="group-comment-pic"><img src="/images/profile/<?php echo $comment_author_picture_id ?>" height="32" width="32"></div>
                                                    <div class="clearfix">
                                                        <div id="group-comment-info">
                                                            <div class="clearfix">
                                                                <div id="group-comment-name"><a href="/profile/<?php echo $comment_author ?>"><?php echo $comment_author_first_name . " " . $comment_author_last_name ?></a></div>
                                                                <div id="group-comment-date"><?php echo date_format($comment_date,"F j, Y \a\\t g:ia"); ?></div>
                                                            </div>
                                                        </div>
                                                        <div id="group-comment-bottom">
                                                            <div id="group-comment-content"><?php echo $comment_content ?></div>
                                                            <div id="group-comment-buttons">
                                                                <div class="clearfix">
                                                                    <div id="group-comment-buttons-a">
                                                                        <a href="/group/<?php echo $group_id ?>?UpVoteComment=<?php echo $pin_id[$i] ?>" title="Up Vote" id="group-upvote-button">Up Vote</a>
                                                                        <a href="/group/<?php echo $group_id ?>?DownVoteComment=<?php echo $pin_id[$i] ?>" title="Down Vote" id="group-downvote-button">Up Vote</a>
                                                                    </div>
                                                                    <div id="group-comment-buttons-b">
                                                                        <div id="group-comment-vote">
                                                                            <?php
                                                                            if($comment_vote == NULL){
                                                                                echo "<span class='comment-votes'>+999</span>";
                                                                            }else{
                                                                                echo "<span class='comment-votes'>" . $comment_vote . "</span>";
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                    <div id="group-comment-buttons-c">
                                                                        <?php if($comment_author == $user1_id){ //Display edit and delete buttons to its author only! ?>
                                                                            <a href="/group/<?php echo $group_id ?>?EditComment=<?php echo $pin_id[$i] ?>" title="Edit" id="group-edit-button">Edit</a>
                                                                            <a href="/group/<?php echo $group_id ?>?DeleteComment=<?php echo $pin_id[$i] ?>" title="Delete" id="group-delete-button">Delete</a>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div id="group-pin-comments-b"><img src="/images/profile/<?php echo $user1_picture_id ?>" height="32" width="32"></div>
                                        <div id="group-pin-comments-c">
                                            <script type="text/javascript" src="/js/autosize.js"></script>
                                            <form action="/group/<?php echo $group_id ?>?Comment=<?php echo $pin_id[$i] ?>"
                                                  method="post" id="group-comment-form" name="group-comment-form">
                                                <textarea name="group-comment" id="group-comment" placeholder="Add a comment..." spellcheck="false"></textarea>
                                            </form>
                                            <script>
                                                $(document).ready(function() {
                                                    $("#group-comment").autogrow();
                                                });
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <script>
                            $(function() {
                                $('textarea#group-comment').on('keydown', function(e) {
                                    if(e.keyCode == 13 && !e.shiftKey){
                                        $(this).closest('form').submit();
                                    }
                                });
                            });
                        </script>
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
