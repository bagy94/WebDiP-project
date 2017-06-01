
<!DOCTYPE html>
<html>
<head>
    <title>{$title}</title>
    <meta charset="UTF-8">
    <meta name="keyword" content="{$keyword}"/>
    <meta name="author" content="Davor Bagarić"/>
    <meta name="title" content="Početna"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{$ps->icon}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    {foreach from = $ps->links.js item=v}
        <script src="{$v}" type="text/javascript" rel="script"></script>
    {/foreach}
    {foreach from = $ps->links.css item=v}
        <link rel="stylesheet" href="{$v}" type="text/css"/>
    {/foreach}

</head>
<body style="background-image: {$ps->theme.body.background_image}">
    <header style="background-color: {$ps->theme.header.background_color}">
        <h1>{$title}</h1>
    </header>
    <div class="page">
        <nav class="menu">
            <ul>
                {foreach from=$ps->links.menu key=k item=v}
                    <li style="background-color:{$ps->theme.menu.li.background_color};box-shadow: {$ps->theme.menu.li.box_shadow};"><a href="{$v}" style="color: {$ps->theme.menu.a.color}">{$k}</a></li>
                {/foreach}
            </ul>
        </nav>
