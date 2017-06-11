<section class="content">
    <div class="box control-panel">
        <div class="inline-box-wrapper">
            <label for="inputMaxRows">Maksimalan broj redova u tablici: </label>

            <div class="input-box">
                <input type="number" name="max_rows" id="inputMaxRows" value="{$max_rows}">
                <input type="image" src="../view/asset/ic_save.png" name="save" class="btn-icn save">
            </div>
        </div>
        <div class="inline-box-wrapper">
            <label for="inputActivationLinkDuration">Trajanje aktivacijskog linka: </label>
            <div class="input-box">
                <input type="number" name="act_link_duration" id="inputActivationLinkDuration" value="{$act_link_duration}">
                <input type="image" src="../view/asset/ic_save.png" name="save" class="btn-icn save">
            </div>
        </div>
        <div class="inline-box-wrapper">
            <label for="inputMaxTimesLogIn">Maksimalan broj pokušaja prijavljivanja: </label>
            <div class="input-box">
                <input type="number" name="max_log_in" id="inputMaxTimesLogIn" value="{$max_log_in}">
                <input type="image" src="../view/asset/ic_save.png" name="save" class="btn-icn save">
            </div>
        </div>
        <div class="inline-box-wrapper">
            <label for="inputSessionDuration">Istek sesije(min): </label>
            <div class="input-box">
                <input type="number" name="session_duration" id="inputSessionDuration" value="{$session_duration}">
                <input type="image" src="../view/asset/ic_save.png" name="save" class="btn-icn save">
            </div>
        </div>
        <div class="inline-box-wrapper">
            <label for="inputLogInCodeDuration">Trajanje koda za prijavu (min): </label>
            <div class="input-box">
                <input type="number" name="log_in_code_duration" id="inputLogInCodeDuration" value="{$log_in_code_duration}">
                <input type="image" src="../view/asset/ic_save.png" name="save" class="btn-icn save">
            </div>
        </div>
        <div class="inline-box-wrapper">
            <label for="inputCookieDuration">Trajanje kolačića(prijava..): </label>
            <div class="input-box">
                <input type="number" name="cookie_duration" id="inputCookieDuration" value="{$cookie_duration}">
                <input type="image" src="../view/asset/ic_save.png" name="save" class="btn-icn save">
            </div>
        </div>
        <div class="inline-box-wrapper">
            <label for="inputLogInCodeDuration">Vrijeme sustava: </label>
            <div class="input-box">
                <div class="box clock">

                </div>
                <input type="image" src="../view/asset/ic_save.png" name="save" class="btn-icn save" id="inputSaveInterval">

                <input type="image" src="../view/asset/ic_open_page.png" name="open" class="btn-icn">

            </div>
        </div>
    </div>
</section>