
<div style="box-shadow:4px 5px 6px 1px #64843a;text-align: left;padding: 18px;background-color: rgba(140, 146, 135, 0.6);border-radius: 7px;max-width:800px;color: #573988;">
    <p style="max-width: 80%;margin:5px;padding:3px;font-size:16px;padding: 3px;width: 80%;border-bottom: 1px solid rgba(214, 107, 107, 0.84);padding-left: 11px;font-family: Arial Black, Gadget, sans-serif;font-size: 25px;font-style: italic;margin: 3px 7px 7px 5px;">
        Prijava u web sustav wellness centra
    </p>
    <p style="max-width:80%;margin:5px;padding:3px;font-size:16px;">
        Ime: {$name}
    </p>
    <p style="max-width: 80%;margin:5px;padding:3px;font-size:16px;">
        Prezime: {$surname}
    </p>
    <div style="display: block">
        <p style="display: inline-block;margin:5px;padding:3px;font-size:16px;">
            Za uspijeĹˇnu prijavu u sustav koristite kod:
        </p>
        <p style="display: inline-block;margin:5px;padding:3px;font-size:16px;color:#6a8235;">
            {$code}
        </p>
    </div>
    <div style="display: inline-block;">
        <p style="display: inline-block;margin:5px;padding:3px;font-size:16px;">
            Poslano u:
        </p>
        <p style="display: inline-block;margin:5px;padding:3px;font-size:16px;color: rgb(150, 104, 14);">
            {$sentOn}
        </p>
    </div>
    <div style="display: inline-block;float: right">
        <p style="display: inline-block;margin:5px;padding:3px;font-size:16px;">
            Vrijedi do:
        </p>
        <p style="display: inline-block;margin:5px;padding:3px;font-size:16px;color: rgb(150, 104, 14);">
            {$expire}
        </p>
    </div>
    <div style="display: block;text-align: center;margin-top: 7px">
        <a href="{$log_in_link}" style="color: #2e1f4a;text-decoration: blink;margin-left: 4px;border-radius: 5px;border: 3px solid rgba(140, 127, 127, 0.58);font-size: 18px;letter-spacing: 3px;padding: 4px;background: rgba(146, 131, 152, 0.47)">
            Prijava
        </a>
    </div>
</div>