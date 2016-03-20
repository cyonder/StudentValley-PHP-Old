<?php
session_start();
require($_SERVER['DOCUMENT_ROOT'] . "/lib/config.php");
require($_SERVER['DOCUMENT_ROOT'] . "/lib/functions-database.php");
require($_SERVER['DOCUMENT_ROOT'] . "/lib/functions-common.php");
$dbConnection = open_connection();
?>

<html>
<head>
    <title>Welcome to Student Valley - Sign Up</title>
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/validation-plugin.js"></script>
    <script type="text/javascript" src="/js/signup-validator.js"></script>
    <script type="text/javascript" src="/js/lightbox.min.js"></script>
    <link type="text/css" rel="stylesheet" href="/css/reset.css">
    <link type="text/css" rel="stylesheet" href="/css/header-global.css">
    <link type="text/css" rel="stylesheet" href="/css/index.css">
    <link type="text/css" rel="stylesheet" href="/css/one-column.css">
    <link type="text/css" rel="stylesheet" href="/css/lightbox.css">
    <link type="text/css" rel="stylesheet" href="/css/footer.css">
</head>
<body>
<div id="global-container">
    <div id="header">
        <div class="clearfix">
            <div id="header-wrapper">
                <div id="header-a">
                    <div id="logo-wrapper"><a href="http://studentvalley.org" title="Go to Student Valley" id="logo"><u>Student
                                Valley</u></a></div>
                </div>
                <div id="header-b">
                    <form id="login" action="/login" method="post">
                        <table>
                            <tr>
                                <th>Email</th>
                                <th>Password</th>
                            </tr>
                            <tr>
                                <td><input class="login-input" type="text" name="email"></td>
                                <td><input class="login-input" type="password" name="password"></td>
                                <td><input class="login-button" type="submit" name="submit" value="Log In"></td>
                            </tr>
                            <tr>
                                <td><input class="login-checkbox" type="checkbox" name="remember">Remember me</td>
                                <td><a href="/forgot" class="login-forgot">Forgot your password?</a></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>