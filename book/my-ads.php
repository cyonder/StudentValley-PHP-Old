<?php
require_once("./../includes/header.php");

/** user1_id is defined in header.php. As long as, you have header.php required above the page
 * you don't need to redefine it. Use this if you'd like to redefine user1_id:
 * $user1_id = return_user_id($dbConnection, $_SESSION['email']);
 */

/* The query below returns all the necessary information for book page. */
$query = "SELECT * FROM BOOKS WHERE BOOK_OWNER = '$user1_id' ORDER BY BOOK_DATE DESC";
$result = mysqli_query($dbConnection, $query) or die(mysqli_error($dbConnection));
$num_of_ads = mysqli_num_rows($result);

$book_id = array();
$school = array();
$date = array();
$type = array();
$title = array();
$content = array();
$price = array();
$owner = array();
$picture = array();

if($num_of_ads > 0){
    while($row = mysqli_fetch_assoc($result)){
        $book_id[] = $row['BOOK_ID'];
        $school[] = $row['BOOK_SCHOOL'];
        $date[] = date_create($row['BOOK_DATE']);
        $type[] = $row['BOOK_TYPE'];
        $title[] = $row['BOOK_TITLE'];
        $content[] = $row['BOOK_CONTENT'];
        $price[] = $row['BOOK_PRICE'];
        $owner[] = $row['BOOK_OWNER'];
        $picture[] = $row['BOOK_PICTURE_ID'];
    }
}else{
    /* You don't have any book advertisement yet. */
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
            <div id="my-ads-block-wrapper">
            <?php for($i = 0; $i < $num_of_ads; $i++){ ?>
                <div id="my-ads-block">
                    <div id="my-ads-name">
                        <a href="/book/<?php echo $book_id[$i]; ?>"><?php echo $title[$i]; ?></a>
                    </div>
                    <div id="my-ads-content">
                        <div id="my-ads-picture">
                            <a href="/images/book/<?php echo $picture[$i] ?>" data-lightbox="image">
                                <img src="/images/book/<?php echo $picture[$i] ?>">
                            </a>
                        </div>
                        <div id="my-ads-menu">
                            <?php setlocale(LC_MONETARY, 'en_CA'); //To use money_format(); ?>
                            <div class="my-ads-buttons"><span>Posted Date: </span><?php echo date_format($date[$i],"F j, Y") ?></div>
                            <div class="my-ads-buttons"><span>Price: </span><?php echo  money_format('$%!i', $price[$i]) ?></div>
                            <div class="my-ads-buttons"><a href="/book/post-ad?Edit=<?php echo $book_id[$i]; ?>">Edit This Ad</a></div>
                            <div class="my-ads-buttons"><a href="/book/post-ad?Delete=<?php echo $book_id[$i]; ?>">Delete This Ad</a></div>
                            <div class="my-ads-buttons"><a href="/book/<?php echo $book_id[$i]; ?>">Open This Ad</a></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php
require_once("./../includes/footer.php");
?>
