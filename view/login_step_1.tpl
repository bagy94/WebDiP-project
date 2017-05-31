<section class="content">
    <form action="{$actionSubmit}" method="POST" id="formLogIn">
        <div class="form-inline-element-wrapper">
            <label for="inputUserNameLogIn" id="lblUserName">Korisničko ime</label>
            <input type="text" id="inputUserNameLogIn" name="user-name">
        </div>
        <div class="form-inline-element-wrapper">
            <label for="inputPasswordLogIn" id="lblPassword">Lozinka</label>
            <input type="password" id="inputPasswordLogIn" name="password">
        </div>
        <div id="btnBoxLogIn">
            <input type="submit" id="btnSubmitLogIn" name="btnSubmitLogIn" value="Prijava">
            <input type="reset" id="linkRegistrationPage" value="Reset">
        </div>
    </form>
</section>