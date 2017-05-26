<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 5.5.2017.
 * Time: 0:17
 */
?>
<html>
<head>
    <title>Greška</title>
    <meta charset="UTF-8">
    <meta name="keyword" content="error 404" />
    <meta name="author" content="Davor Bagarić" />
    <meta name="title" content="Error" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/base.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
require_once "sections/header.php";
require_once "sections/menu.php";
?>
<section class="content">
    <?php echo $error; ?>
</section>
<?php require_once "sections/footer.php"; ?>
</body>
