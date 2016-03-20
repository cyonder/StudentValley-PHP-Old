<?php
require_once("./includes/header.php");
?>
<div id="container">
    <?php require_once("./includes/sidebar.php"); ?>
    <div class="clearfix">
        <div id="content">
            Hello world
            <?php echo date("Y-m-d H:i:s");
            echo "<br>" . md5(uniqid(date("Y-m-d H:i:s"), true) * rand()) . '.png';
            echo "<br>" . md5("abcdefg");
            ?>
        </div>
    </div>
</div>
<?php
require_once("./includes/footer.php");
?>
