<section class="content" style="opacity: 0.9">
    <form action="{$formAction}" method="POST">
        <div class="form-inline-element-wrapper">
            <label for="inputName">Ime</label>
            <input type="text" id="inputName" name="Name" class="user-data">
        </div>
        <div class="form-inline-element-wrapper">
            <label for="inputSurname">Prezime</label>
            <input type="text" id="inputSurname" name="Surname" class="user-data">
        </div>
        <div class="form-inline-element-wrapper">
            <label for="inputUserName">Korisničko ime</label>
            <input type="text" id="inputUserName" name="UserName">
        </div>
        <div class="form-inline-element-wrapper">
            <label for="inputEmail">Email</label>
            <input type="email" id="inputEmail" name="Email">
        </div>
        <div class="form-inline-element-wrapper">
            <label for="inputPassword">Lozinka</label>
            <input type="password" id="inputPassword" name="Password">
        </div>
        <div class="form-inline-element-wrapper">
            <label for="inputPasswordCheck">Potvrda loznike</label>
            <input type="password" id="inputPasswordCheck" name="PasswordCheck">
        </div>
        <div class="form-inline-element-wrapper">
            <label for="selectLogInType">Tip prijave: </label>
            <select id="selectLogInType" name="LogInType">
                <option value="1">Jednostupanjska</option>
                <option value="2">Dvostupanjska</option>
            </select>
        </div>
        <div class="form-inline-element-wrapper">
            <div class="g-recaptcha" data-sitekey="{$recaptchaPublic}"></div>
        </div>

        <input type="submit" id="btnSubmitUser" name="SubmitUser" value="Potvrdi">
    </form>
</section>