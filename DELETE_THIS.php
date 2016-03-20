<?php

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
$display = "style='display: block'";
$pass = 0;


if(isset($_GET['Post'])){
    $book_id = $_GET['Post'];
    $post_ad_title = "EDIT THE AD FOR YOUR BOOK";
    $action = "/book/post-ad?Post=" . $book_id;
    $display = "style='display: block'";

    $query = "SELECT * FROM BOOKS WHERE BOOK_ID = $book_id";
    $result = mysqli_query($dbConnection, $query);

    while($row = mysqli_fetch_assoc($result)){
        $school = $row['BOOK_SCHOOL'];
        $date = date_create($row['BOOK_DATE']);
        $title = $row['BOOK_TITLE'];
        $content = $row['BOOK_CONTENT'];
        $owner = $row['BOOK_OWNER'];
        $picture = $row['BOOK_PICTURE_ID'];

        if($row['BOOK_TYPE'] === "Free"){
            $type = $row['BOOK_TYPE'];
            $display = "disabled style='background-color: #bdc7d8'";
        }else{
            $type = $row['BOOK_TYPE'];
            $display = "";
        }

        if($row['BOOK_PRICE'] === "0"){ // If price is 0, don't populate price.
            $price = "";
        }else{
            $price = $row['BOOK_PRICE'];
            $display = "";
        }
    }
}else{
    $action = "/book/post-ad?Post=New";
}

if($_GET['Post'] == 'New'){
    //    $picture = $row['BOOK_PICTURE_ID'];
    $school = $_POST['school'];
    $type = $_POST['ad-type'];
    if($type == "Free"){
        $display = "disabled style='background-color: #bdc7d8'";
    }
    $title = trim(str_replace("'", "&apos;", $_POST['title']));
    $content = trim(str_replace("'", "&apos;", $_POST['bookEditor']));
    $price = preg_replace("/[^0-9.]/", "", $_POST['price']); //Remove everything but digits and dot.
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