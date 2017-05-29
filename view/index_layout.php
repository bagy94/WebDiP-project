<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 4.5.2017.
 * Time: 23:44
 */

?>

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