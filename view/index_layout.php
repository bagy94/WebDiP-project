<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 4.5.2017.
 * Time: 23:44
 */

?>
<head>
    <title>Početna</title>
    <meta charset="UTF-8">
    <meta name="keyword" content="wellness"/>
    <meta name="keyword" content="project"/>
    <meta name="author" content="Davor Bagarić"/>
    <meta name="title" content="Početna"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/base.css" rel="stylesheet" type="text/css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="js/base.js" type="text/javascript"></script>
</head>
<body>
<?php
require_once "sections/header.php";
require_once "sections/menu.php";
?>
<section class="content">
    <div class="box">
        <select name="service-category-select">
            <?php if(isset($categoryList))echo $categoryList;?>
        </select>
    </div>
</section>
<?php require_once "sections/footer.php"; ?>
</body>

</html>