<?php
require($_SERVER['DOCUMENT_ROOT'] . "/lib/session_check.php");
require($_SERVER['DOCUMENT_ROOT'] . "/lib/config.php");
require($_SERVER['DOCUMENT_ROOT'] . "/lib/functions-database.php");
require($_SERVER['DOCUMENT_ROOT'] . "/lib/functions-common.php");
$dbConnection = open_connection();

/** Need this variable ( $user1_id ) where we need to refer to logged in user. */
$user1_id = return_user_id($dbConnection, $_SESSION['email']);

/* According to this statement, the title of the page will be set.
 * If $_GET['id'] is set, return_title function will return the title of the page
 * according to id and file which called. */
if(isset($_GET['id'])){
    $page_title = return_title($dbConnection, $_GET['id'], $_SERVER['PHP_SELF']);
}else{
    $page_title = "Student Valley";
}
?>

<html>
<head>
    <title><?php echo $page_title ?></title>
    <link type="text/css" rel="stylesheet" href="/css/reset.css">
    <link type="text/css" rel="stylesheet" href="/css/header.css">
    <link type="text/css" rel="stylesheet" href="/css/sidebar.css">
    <link type="text/css" rel="stylesheet" href="/css/two-column.css">
    <link type="text/css" rel="stylesheet" href="/css/lightbox.css">
    <link type="text/css" rel="stylesheet" href="/css/footer.css">
    <link type="text/css" rel="stylesheet" href="/lib/froala/css/themes/studentValley.css">
    <link type="text/css" rel="stylesheet" href="/lib/froala/css/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" href="/lib/froala/css/froala_editor.min.css">
    <link type="text/css" rel="stylesheet" href="/lib/froala/css/froala_style.min.css">
    <script type="text/javascript" src="/js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="/js/validation-plugin.js"></script>
    <script type="text/javascript" src="/js/settings-validator.js"></script>
    <script type="text/javascript" src="/js/lightbox.min.js"></script>
</head>
<body>
<div id="global-container">
    <div id="header">
        <div class="clearfix">
            <div id="header-wrapper">
                <div id="header-a">
                    <div id="logo-wrapper"><a href="/home" title="Go to Student Valley" id="logo"><u>Student
                                Valley</u></a>
                    </div>
                </div>
                <div id="header-b">
                    <form id="search-bar" action="/search" method="post">
                        <label for="search">
                            <input type="text" name="search" placeholder="Press enter to search...">
                        </label>
                    </form>
                </div>
                <div id="header-c">
                    <div id="nav">
                        <ul>
                            <li><a href="/profile/<?php echo $user1_id ?>">Profile</a></li>
                            <li><a href="/messages">Messages</a></li>
                            <li><a href="/home">Feeds</a></li>
                            <li><a href="/includes/logout">Log Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
