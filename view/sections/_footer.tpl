        </div>
<footer style="background-color: {$ps->theme.footer.background_color}">
    {foreach from=$ps->footer key=k item=v}
        <a href="{$v}">{$k}</a></li>
    {/foreach}
</footer>
</body>
</html>