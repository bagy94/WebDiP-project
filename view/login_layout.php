<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 9.5.2017.
 * Time: 1:25
 */
?>


<html>

<head>
    <title>Prijava</title>
    <meta charset="UTF-8">
    <meta name="keyword" content="registracija" />
    <meta name="author" content="Davor Bagarić" />
    <meta name="title" content="registracija" />
    <meta name="createdAt" content="08.03.2017" />
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
    <h2>Prijava</h2>
    <?php $form->render();?>
    <!--<form action="">
        <div class="form-inline-element-wrapper">
            <label for="inputUserNameLogIn" id="lblUserName">Korisničko ime</label>
            <input type="text" id="inputUserNameLogIn" name="UserNameLogIn">
        </div>
        <div class="form-inline-element-wrapper">
            <label for="inputPasswordLogIn" id="lblPassword">Lozinka</label>
            <input type="password" id="inputPasswordLogIn" name="PasswordLogIn">
        </div>
        <div id="boxRememberMe">
            <label for="btnRadioYes">Zapamti me</label>
            <input type="radio" id="btnRadioYes" name="btnRadioRemember" value="yes" checked>Da
            <input type="radio" id="btnRadioNo" name="btnRadioRemember" value="no">Ne</div>
        <div id="btnBoxLogIn">
            <input type="submit" id="btnSubmitLogIn" name="btnSubmitLogIn" value="Prijava">
            <a href="registracija.html" id="linkRegistrationPage">Registracija</a>
        </div>
    </form>-->
    <div>
        Username: testni1 <br>
        Password: testni1AA
    </div>
</section>
<?php require_once "sections/footer.php"; ?>

</body>

</html>
