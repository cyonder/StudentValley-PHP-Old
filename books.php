<?php
require_once("./includes/header.php");
?>
<div id="container">
    <?php require_once("./includes/sidebar.php"); ?>
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
            <div id="books-bar">
                <form id="books-search-bar" action="" method="post">
                    <ul>
                        <li><input type="text" name="search" id="books-search-box" placeholder="Type the book name..."></li>
                        <li>
                            <select name="school" id="books-dropdown">
                                <option value="All">All</option>
                                <?php
                                $query = "SELECT * FROM SCHOOLS ORDER BY SCHOOL_TYPE ASC";
                                $school_results = mysqli_query($dbConnection, $query);

                                foreach($school_results as $school_result){
                                    echo "<option value=" . $school_result['SCHOOL_SHORT_NAME'] . ">" . $school_result['SCHOOL_NAME']  . "</option>";
                                }
                                ?>
                                <option value="Other">Other</option>
                            </select>
                        </li>
                        <li><input type="submit" name="submit" value="Find" id="books-button"></li>
                    </ul>
                </form>
            </div>
            <div id="books-ads">
                <table>
                    <thead>
                        <tr>
                            <th id="books-post-school">School</th>
                            <th id="books-post-date">Date</th>
                            <th id="books-post-title">Title</th>
                            <th id="books-post-price">Price</th>
                        </tr>
                    </thead>
                    <?php
                    $query = "SELECT * FROM BOOKS ORDER BY BOOK_DATE DESC";
                    $result = mysqli_query($dbConnection, $query);
                    setlocale(LC_MONETARY, 'en_CA'); //To use money_format();

                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){

                            /* Return the short name of the school according to the seller's school */
                            $query = "SELECT SCHOOL_SHORT_NAME FROM SCHOOLS WHERE SCHOOL_NAME = '$row[BOOK_SCHOOL]'";
                            $result2 = mysqli_query($dbConnection, $query) or die(mysqli_error($dbConnection));
                            $data = mysqli_fetch_assoc($result2);
                            $school_short_name = $data['SCHOOL_SHORT_NAME'];


                            echo "<tbody>";
                                echo "<tr>";
                                    echo "<td>" . $school_short_name . "</td>";
                                        $date = date_create($row['BOOK_DATE']);
                                    echo "<td>" . date_format($date,"F j") . "</td>";
                                    echo "<td id='books-title'><a href='/book/$row[BOOK_ID]' title='$row[BOOK_TITLE]'>" . $row['BOOK_TITLE'] . "<a/></td>";
                                    echo "<td>" . money_format('$%!i', $row['BOOK_PRICE']) . "</td>";
                                echo "</tr>";
                            echo "</tbody>";
                        }
                    }else{
                        echo "There is no book ad at the moment!";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
require_once("./includes/footer.php");
?>
