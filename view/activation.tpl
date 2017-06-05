<section class="content">
    {if isset($error)}
        <div class="err-box">
            <p>{$error}</p>
        </div>
    {else}
        <div class="user-box">
            Aktivacija uspijesna
            <script>redirectToLogin()</script>
        </div>
    {/if}
</section>