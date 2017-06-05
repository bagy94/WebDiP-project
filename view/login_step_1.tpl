<section class="content form-box">
    {if isset($error)}
            <div class="box has-error">
                <p>{$error}</p>
            </div>
    {/if}
    <form action="{$actionSubmit}" method="POST" id="formLogIn" class="box">
        <div class="form-inline-element-wrapper">
            <label for="inputUserNameLogIn" id="lblUserName">Korisničko ime</label>
            <input type="text" id="inputUserNameLogIn" name="user_name">
        </div>
        <div class="form-inline-element-wrapper">
            <label for="inputPasswordLogIn" id="lblPassword">Lozinka</label>
            <input type="password" id="inputPasswordLogIn" name="password">
        </div>
        <div class="form-inline-element-wrapper">
            <label for="box-remember-me" id="lblPassword">Zapamti me</label>
            <div id="box-remember-me">
                <input type="radio" id="inputRememberYes" name="remember_me[]" value="yes">
                <p>Da</p>
                <input type="radio" id="inputRememberNo" name="remember_me[]" value="no">
                <p>Ne</p>
            </div>
        </div>
        <div id="btnBoxLogIn">
            <input type="submit" id="btnSubmitLogIn" name="btnSubmitLogIn" value="Prijava" disabled>
        </div>
    </form>
</section>