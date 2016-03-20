<?php
ob_start();
require_once("./../includes/header.php");

$picture_error = NULL;
$type_error = NULL;
$school_error = NULL;
$price_error = NULL;
$title_error = NULL;
$content_error= NULL;

$school = NULL;
$content = NULL;

$post_ad_title = "POST A NEW AD FOR YOUR BOOK";
$picture = "Default.png"; //If page doesn't receive id, show default picture.
$action = "/book/post-ad?Post=New";
$pass = 0;

if(isset($_GET['Edit'])){
    if(preg_match("/^[0-9]*$/", $_GET['Edit'])){
        $book_id = mysqli_real_escape_string($dbConnection, $_GET['Edit']); //The book id, which will be edited.
        $post_ad_title = "EDIT THE AD FOR YOUR BOOK"; //Changing title, because the form is gonna serve for editing.

        $query = "SELECT * FROM BOOKS WHERE BOOK_ID = $book_id";
        $result = mysqli_query($dbConnection, $query);

        while($row = mysqli_fetch_assoc($result)){
            $school = $row['BOOK_SCHOOL'];
            $date = date_create($row['BOOK_DATE']);
            $title = $row['BOOK_TITLE'];
            $content = $row['BOOK_CONTENT'];
            $owner = $row['BOOK_OWNER'];
            $picture = $row['BOOK_PICTURE_ID'];
            $type = $row['BOOK_TYPE'];

            /* If ad-type is free, disable price box */
            if($type == "Free" || $type = "Trade"){ $display = "disabled style='background-color: #bdc7d8'"; }
            // If price is 0, don't populate price */
            if($row['BOOK_PRICE'] != "0" && $type == "Free"){ $price = $row['BOOK_PRICE']; }
        }
    }else{
        echo "Cannot process your request!"; //DELETE THIS..!
        /*CANNOT PROCESS YOUR REQUEST*/
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $book_owner = $user1_id;
    $book_date = date("Y-m-d H:i:s");

    /* PICTURE */
    if($_FILES['bookPicture']['type'] == "image/jpeg" || $_FILES['bookPicture']['type'] == "image/png"){
        if($_FILES['bookPicture']['size'] < 1048576){
            $book_picture_id = md5(uniqid(date("Y-m-d H:i:s"), true) * rand()) . '.png'; // Create unique id for picture.
            move_uploaded_file($_FILES['bookPicture']['tmp_name'], "../images/book/" . $book_picture_id);
            $pass++;
        }else{
            $picture_error = "Maximum picture size is 1MB";
        }
    }else{
        $picture_error = "Acceptable file extensions are JPEG and PNG";
    }

    /* AD-TYPE */
    if(isset($_POST['ad-type'])){
        $book_type = $_POST['ad-type'];
        $pass++;
    }else{
        $type_error = "Please, choose a ad type";
    }

    /* SCHOOL */
    if(isset($_POST['school'])){
        $book_school = $_POST['school'];
        $pass++;
    }else{
        $school_error = "Please, choose a school";
    }

    /* PRICE */
    if(!empty($_POST['price'])){
        $book_price = preg_replace("/[^0-9.]/", "" , $_POST['price']); //Remove everything but digits and dot.
        $pass++;
    }else{
        if($_POST['ad-type'] == "Free"){
            $book_price = 0;
            $pass++;
        }else{
            $price_error = "Please, add an amount";
        }
    }

    /* TITLE */
    if(!empty($_POST['title'])){
        if(strlen($_POST['title']) >= 5){
            /*Converting apostrophe into html entity. If we don't, SQL will give an error or don't display text after apostrophe */
            $book_title = trim(str_replace("'", "&apos;", $_POST['title']));
            $pass++;
        }else{
            $title_error = "Title should be at least 5 characters long";
        }
    }else{
        $title_error = "Please, add a title";
    }

    /* CONTENT */
    if(!empty($_POST['bookEditor'])){
        if(strlen($_POST['bookEditor']) >= 15){
            $book_content = trim(str_replace("'", "&apos;", $_POST['bookEditor']));
            $pass++;
        }else{
            $content_error = "Description should be at least 15 characters long";
        }
    }else{
        $content_error = "Please, add description";
    }

    /* INSERT INTO TABLE */
    if($pass == 6){ //If pass is 6, it means form is validated.
        $query = "INSERT INTO BOOKS (";
        $query .= "BOOK_SCHOOL, BOOK_DATE, BOOK_TYPE, BOOK_TITLE, BOOK_CONTENT, BOOK_PRICE, BOOK_OWNER, BOOK_PICTURE_ID";
        $query .= ")VALUES(";
        $query .= "'{$book_school}', '{$book_date}', '{$book_type}', '{$book_title}', '{$book_content}', '{$book_price}', '{$book_owner}', '{$book_picture_id}'";
        $query .= ")";

        mysqli_query($dbConnection, $query) or die(mysqli_error($dbConnection));

        /*After inserting the books into database, get the book_id of it and redirect user to that page*/
        $query = NULL;
        $query = "SELECT BOOK_ID FROM BOOKS WHERE BOOK_OWNER = '$user1_id' AND BOOK_DATE = '$book_date'";
        $result = mysqli_query($dbConnection, $query);

        $data = mysqli_fetch_assoc($result);
        $id = $data["BOOK_ID"];

        header("location: /book/" . $id);
    }else{
        $pass = FALSE;
    }
}

/* If post's type matches with any of the type,
* mark it as "selected" otherwise, use "Select Type" as selected.
*
* So, "selected" attribute of post's type will overwrite the "selected"
* attribute of "Select Type".
* */
switch($type){
    case "Sell":
        $selected_0 = "selected";
        $selected_1 = NULL;
        $selected_2 = NULL;
        $selected_3 = NULL;
        break;
    case "Trade":
        $selected_0 = NULL;
        $selected_1 = "selected";
        $selected_2 = NULL;
        $selected_3 = NULL;
        break;
    case "Rent":
        $selected_0 = NULL;
        $selected_1 = NULL;
        $selected_2 = "selected";
        $selected_3 = NULL;
        break;
    case "Free":
        $selected_0 = NULL;
        $selected_1 = NULL;
        $selected_2 = NULL;
        $selected_3 = "selected";
        break;
}

?>
<div id="container">
    <?php require_once("./../includes/sidebar.php"); ?>
    <div class="clearfix">
        <div id="content">
            <div id="books-menu">
                <div class="clearfix">
                    <ul>
                        <li><a href="/books" id="books-icon-1">Last Ads</a></li>
                        <li><a href="/book/post-ad" id="books-icon-2">Post Ad</a></li>
                        <li><a href="/book/my-ads" id="books-icon-3">My Ads</a></li>
                    </ul>
                </div>
            </div>
            <div id="post-ad-title"><?php echo $post_ad_title; ?></div>
            <div id="post-ad-content">
                <div id="post-ad-error" <?php if($pass === 0) echo "style='display:none'"; else echo "style='display:block'"; ?>>
                    <span <?php if(!isset($picture_error))echo "style='display:none'"; ?>><?php echo $picture_error ?></span>
                    <span <?php if(!isset($type_error))echo "style='display:none'"; ?>><?php echo $type_error ?></span>
                    <span <?php if(!isset($school_error))echo "style='display:none'"; ?>><?php echo $school_error ?></span>
                    <span <?php if(!isset($price_error))echo "style='display:none'"; ?>><?php echo $price_error ?></span>
                    <span <?php if(!isset($title_error))echo "style='display:none'"; ?>><?php echo $title_error ?></span>
                    <span <?php if(!isset($content_error))echo "style='display:none'"; ?>><?php echo $content_error ?></span>
                </div>
                <div id="post-ad-subtitle">Upload Book Picture</div>
                <div id="post-ad-form">
                    <div class="clearfix">
                        <div id="post-ad-form-a">
                            <img src="/images/book/<?php echo $picture ?>" height="80" width="80">
                        </div>
                        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                            <div class="clearfix">
                                <div id="post-ad-form-b">
                                    <div id="post-ad-form-b-1">
                                        <input type="file" name="bookPicture">
                                    </div>
                                    <div id="post-ad-form-b-2"></div>
                                </div>
                            </div>
                            <div id="post-ad-subtitle">Upload Book Information</div>
                            <div id="post-ad-information">
                                <div class="clearfix">
                                    <label for="ad-type" class="post-ad-label">
                                        <span>Ad Type:</span>
                                        <select name="ad-type" class="post-ad-input" id="post-ad-input" onchange="disablePrice();">
                                            <option value="Select Type" disabled selected>Select Type</option>
                                            <option value="Sell" <?php echo $selected_0 ?>>Sell</option>
                                            <option value="Trade" <?php echo $selected_1 ?>>Trade</option>
                                            <option value="Rent" <?php echo $selected_2 ?>>Rent</option>
                                            <option value="Free" <?php echo $selected_3 ?>>Free</option>
                                        </select>
                                    </label>

                                    <script>
                                        /*If ad-type is selected as free, disable price box*/
                                        function disablePrice(){
                                            if(document.getElementById("post-ad-input").value == "Free" || document.getElementById("post-ad-input").value == "Trade"){
                                                document.getElementById("post-ad-price").disabled = "True";
                                                document.getElementById("post-ad-price").style.backgroundColor = "bdc7d8";
                                            }else{
                                                document.getElementById("post-ad-price").disabled = "";
                                                document.getElementById("post-ad-price").style.backgroundColor = "FFFFFF";
                                            }
                                        }
                                    </script>

                                    <label for="school" class="post-ad-label">
                                        <span>School:</span>
                                        <select name="school" class="post-ad-input">
                                            <option value="Select School" disabled selected>Select School</option>
                                            <?php
                                            /* This code will print school options from database.
                                             *
                                             * If post's school matches with any of the school from database,
                                             * mark it as "selected" otherwise, use "Select School" as selected.
                                             *
                                             * So, "selected" attribute of post's school will overwrite the "selected"
                                             * attribute of "Select School".
                                             * */

                                            $query = "SELECT * FROM SCHOOLS ORDER BY SCHOOL_TYPE ASC";
                                            $schools_result = mysqli_query($dbConnection, $query);

                                            foreach($schools_result as $school_result){
                                                if($school == $school_result['SCHOOL_NAME']){
                                                    echo "<option value='" . $school_result['SCHOOL_NAME'] . "' selected>" . $school_result['SCHOOL_NAME']  . "</option>";
                                                }else{
                                                    echo "<option value='" . $school_result['SCHOOL_NAME'] . "'>" . $school_result['SCHOOL_NAME'] . "</option>";
                                                }
                                            }
                                            ?>
                                            <option value="Other">Other</option>
                                        </select>
                                    </label>
                                    <label for="price" class="post-ad-label">
                                        <script type="text/javascript" src="/js/autonumeric.js"></script>
                                        <span>Price:</span>
                                        <input class="post-ad-input post-ad-input-3" id="post-ad-price" type="text" data-a-sign="$"
                                               name="price" autocomplete="off" <?php echo $display; ?> value="<?php echo $price ?>">
                                        <script>
                                            jQuery(function($) {
                                                $('#post-ad-price').autoNumeric('init');
                                            })
                                        </script>
                                    </label>
                                    <label for="title" class="post-ad-label">
                                        <span>Title:</span>
                                        <input class="post-ad-input post-ad-input-2" type="text" name="title" maxlength="42" autocomplete="off" value="<?php echo htmlentities($title) ?>">
                                    </label>
                                </div>
                                <div id="post-ad-form-c">
                                    <textarea name="bookEditor" id="bookEditor"><?php echo $content; ?></textarea>
                                    <script type="text/javascript" src="/lib/froala/js/froala_editor.min.js"></script>
                                    <script type="text/javascript" src="/lib/froala/js/plugins/colors.min.js"></script>
                                    <script type="text/javascript" src="/lib/froala/js/plugins/lists.min.js"></script>
                                    <script>$.Editable.DEFAULTS.key = 'GqpC-7A26yC-21yikadgsB-31xpoE2wkA-7=='</script>
                                    <script>
                                        $(function() {
                                            $('#bookEditor').editable({
                                                theme: 'custom',
                                                inlineMode: false,
                                                buttons: ['bold', 'italic', 'underline', 'color', 'formatBlock' , 'sep',
                                                    'align' , 'insertOrderedList', 'insertUnorderedList'],
                                                placeholder: 'Type description for your ad...',
                                                width : '465',
                                                minHeight: '132'
                                            })
                                        });
                                    </script>
                                </div>
                            </div>
                            <div id="post-ad-form-d">
                                <div id="post-ad-form-d-1"><input type="submit" name="submit" value="Post This Ad" class="post-ad-button"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once("./../includes/footer.php");
?>
