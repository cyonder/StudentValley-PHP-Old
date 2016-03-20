<?php
    session_start();
    if($_SESSION['email']){
        header("location: /home");
    }
    require_once("./includes/header-global.php");

    /* If the email that is used for registration is same, signup.php will return error
     * According to that, this function will decide to display the error message or not.
     * */
    if(isset($_GET['error'])){
        $error = "block";
    }else{
        $error = "none";
    }
?>
<div id="body">
    <div class="clearfix">
        <div id="body-wrapper">
            <div id="headline-top">
                <p>Student Valley is a network that connects people at schools</p>
            </div>
            <div id="body-a">
                <div id="content">
                    <div class="clearfix">
                        <div class="statement">
                            <div class="picture"><img src="/images/Course-Statement.png"></div>
                            <div class="definition">Detailed information about courses</div>
                            <div class="clearfix">
                                <div class="comment">Find out everything about the course before you take it.</div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix">
                        <div class="statement">
                            <div class="picture"><img src="/images/Homework-Statement.png"></div>
                            <div class="definition">Get help on your homework, assignment, essay, etc.</div>
                            <div class="clearfix">
                                <div class="comment">Ask questions other students in your program or get study notes
                                    and
                                    last semester's tests.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix">
                        <div class="statement">
                            <div class="picture"><img src="/images/Professor-Statement.png"></div>
                            <div class="definition">Professor reviews based on student feedback</div>
                            <div class="clearfix">
                                <div class="comment">Find out who is teaching great or who is high marker ;)</div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix">
                        <div class="statement">
                            <div class="picture"><img src="/images/Book-Statement.png"></div>
                            <div class="definition">Trade, sell or rent your book</div>
                            <div class="clearfix">
                                <div class="comment">A new book is expensive? Come in and see who has the book you want.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="body-b">
                <div class="clearfix">
                    <table>
                        <caption>Take a look inside</caption>
                        <tr>
                            <td class="screenshot">
                                <a href="/images/Media-Picture.png" data-lightbox="image">
                                    <img src="/images/Media-Picture.png">
                                </a>
                            </td>
                            <td class="screenshot">
                                <a href="/images/Media-Picture.png" data-lightbox="image">
                                    <img src="/images/Media-Picture.png">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="screenshot">
                                <a href="/images/Media-Picture.png" data-lightbox="image">
                                    <img src="/images/Media-Picture.png">
                                </a>
                            </td>
                            <td class="screenshot">
                                <a href="/images/Media-Picture.png" data-lightbox="image">
                                    <img src="/images/Media-Picture.png">
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="form">
    <div id="form-wrapper">
        <div id="headline-bottom">
            <p>New to Student Valley? Sign Up - It's Free</p>
        </div>
        <form id="signup" action="/signup" method="post">
            <table>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Password</th>
                </tr>
                <tr>
                    <td><input class="signup-input" type="text" name="firstName"></td>
                    <td><input class="signup-input" type="text" name="lastName"></td>
                    <td><input class="signup-input" type="email" name="email"></td>
                    <td><input class="signup-input" type="password" name="password"></td>
                    <td><input class="signup-button" type="submit" name="submit" value="Sign Up"></td>
                </tr>
            </table>
        </form>
        <script>$("signup").validate();</script>
    </div>
</div>
<div id="error">
    <div id="error-wrapper" style="display: <?php echo $error ?>">
        <p>Sorry, the email you tried belongs to an existing account. If it is you, please <a href="login">try to log in</a>
        or if you forgot your password you can <a href="forgot">try to reset</a>.</p>
    </div>
</div>
<?php
require_once("./includes/footer.php");
?>