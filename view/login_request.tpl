<section class="content">
    {if isset($error)}
        <div class="box has-error">
            <p>{$error}</p>
        </div>
    {/if}
    <form action="index.php{$actionName}" method="POST">
        <div class="box login-code" >
            <label for="input{$inputName}">Email za novu lozinku:</label>
            <input type="email" id="input{$inputName}" name="{$inputName}">
            <div id="btnBoxLogIn">
                <input type="submit" id="btnSubmitLogIn" name="btnSubmitLogIn" value="Potvrdi">
            </div>
        </div>
    </form>
</section>