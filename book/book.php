<?php
require_once("./../includes/header.php");

if(isset($_GET['id'])){
    $book_id = mysqli_real_escape_string($dbConnection, $_GET['id']);

    /* The query below returns all the necessary information for book page. */
    $query = "SELECT * FROM BOOKS WHERE BOOK_ID = '$book_id'";

    $result = mysqli_query($dbConnection, $query) or die(mysqli_error($dbConnection));
    if(mysqli_num_rows($result) == 1){
        while($row = mysqli_fetch_assoc($result)){
            $school = $row['BOOK_SCHOOL'];
            $date = date_create($row['BOOK_DATE']);
            $type = $row['BOOK_TYPE'];
            $title = $row['BOOK_TITLE'];
            $content = $row['BOOK_CONTENT'];
            $price = $row['BOOK_PRICE'];
            $owner = $row['BOOK_OWNER'];
            $picture = $row['BOOK_PICTURE_ID'];
        }

        /* GET THE NAME OF THE OWNER OF THE AD */
        $query = "SELECT USER_FIRST_NAME, USER_LAST_NAME FROM USERS WHERE USER_ID = '$owner'";
        $result = mysqli_query($dbConnection, $query);
        while($data = mysqli_fetch_assoc($result)){
            $owner_first_name = $data['USER_FIRST_NAME'];
            $owner_last_name = $data['USER_LAST_NAME'];
        }

        /* Checking if the user is in his/her ad or not.
         * According to that, this statement will decide to display buttons or not.
         *
         * Because, we don't wanna display reply button to the user1 when he/she is in his/her own ad page.
         */
        if($owner == $user1_id){
            $message_button = "none";
            $edit_button = "block";
        }else{
            $message_button = "block";
            $edit_button = "none";
        }
    ?>
    <div id="container">
        <?php require_once("./../includes/sidebar.php"); ?>
        <div class="clearfix">
            <div id="content">
                <div id="book-a">
                    <div class="clearfix">
                        <div id="book-name"><?php echo $title; ?></div>
                        <div id="book-button">
                            <ul style="display: <?php echo $message_button ?>"><li><a href="/messages?SendTo=<?php echo $owner; ?>">Reply To Ad</a></li></ul>
                            <ul style="display: <?php echo $edit_button ?>"><li><a href="/book/post-ad?Edit=<?php echo $book_id; ?>">Edit This Ad</a></li></ul>
                        </div>
                    </div>
                </div>
                <div id="book-wrapper">
                    <div id="book-b">
                        <div id="book-picture">
                            <a href="/images/book/<?php echo $picture; ?>" data-lightbox="image">
                                <img src="/images/book/<?php echo $picture; ?>">
                            </a>
                        </div>
                    </div>
                    <div id="book-c">
                        <div class="book-title">INFORMATION</div>
                        <div id="book-info-content">
                            <div class="book-info"><span>Book ID: </span><?php echo $book_id ?></div>
                            <div class="book-info"><span>Posted Date: </span><?php echo date_format($date,"F j, Y") ?></div>
                            <div class="book-info"><span>Owner: </span><a href="/profile/<?php echo $owner; ?>"><?php echo $owner_first_name . " " . $owner_last_name ?></a></div>
                            <div class="book-info"><span>School: </span><?php echo $school ?></div>
                            <div class="book-info">
                                <span>Price: </span>
                                <?php
                                setlocale(LC_MONETARY, 'en_CA');
                                if($price == 0){
                                    echo "Free";
                                }else{
                                    echo money_format('$%!i', $price);
                                }
                                ?>
                            </div>
                            <div class="book-info"><span>Type: </span><?php echo $type ?></div>
                        </div>
                    </div>
                </div>
                <div id="book-d">
                    <div class="book-title">BOOK DETAILS</div>
                    <div id="book-details">
                        <?php echo $content; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    }else{
        echo "Book ad does not exist!";
    }
}else{
    echo "Need book ID for the book page!";
}
require_once("./../includes/footer.php");
?>
