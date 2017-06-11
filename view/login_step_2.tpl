<section class="content">
    {if isset($error)}
        <div class="box has-error">
            <p>{$error}</p>
        </div>
    {/if}
    <form action="{$actionCode}" method="POST">
        <div class="box login-code" >
            <label for="inputLogInCode">Kod za prijavu: </label>
            <input type="text" name="LogInCode" value="{if isset($activeCode)}{$activeCode}{/if}">
            <input type="submit" name="btnLogInCode" value="Potvrdi">
            <p>Kod poslan na mail</p>
        </div>
    </form>
</section>