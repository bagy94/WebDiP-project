<section class="content form-box">
    <form action="{$formAction}" method="POST" class="box" id="form-registration">
        <div class="form-inline-element-wrapper">
            <label for="inputName">Ime</label>
            <input type="text" id="inputName" name="name" class="user-data">
        </div>
        <div class="form-inline-element-wrapper">
            <label for="inputSurname">Prezime</label>
            <input type="text" id="inputSurname" name="surname" class="user-data">
        </div>
        <div class="form-inline-element-wrapper">
            <label for="inputBirthday">Rođendan</label>
            <input type="text" id="inputBirthday" name="birthday" class="user-data">
        </div>
        <div class="form-inline-element-wrapper warning hidden">
            Datum mora biti u formatu dd.mm.gggg.
        </div>
        <div class="form-inline-element-wrapper">
            <label for="selectGender">Spol</label>
            <select id="selectGender" name="gender">
                <option value="-1">Odaberite spol</option>
                <option value="M">Muško</option>
                <option value="F">Žensko</option>
            </select>
        </div>
        <div class="form-inline-element-wrapper">
            <label for="inputUserName">Korisničko ime</label>
            <input type="text" id="inputUserName" name="user-name">
        </div>
        <div class="form-inline-element-wrapper">
            <label for="inputEmail">Email</label>
            <input type="email" id="inputEmail" name="email">
        </div>
        <div class="form-inline-element-wrapper">
            <label for="inputPassword">Lozinka</label>
            <input type="password" id="inputPassword" name="password">
        </div>
        <div class="form-inline-element-wrapper">
            <label for="inputPasswordCheck">Potvrda loznike</label>
            <input type="password" id="inputPasswordCheck" name="password-check">
        </div>
        <div class="form-inline-element-wrapper">
            <label for="selectLogInType">Tip prijave: </label>
            <select id="selectLogInType" name="log-in-type">
                <option value="1">Jednostupanjska</option>
                <option value="2">Dvostupanjska</option>
            </select>
        </div>
        <div class="form-inline-element-wrapper" id="captcha">
            <div class="g-recaptcha" data-sitekey="{$recaptchaPublic}" data-theme="dark"></div>
        </div>

        <input type="submit" id="btnSubmitUser" name="SubmitUser" value="Potvrdi" disabled>
    </form>
</section>