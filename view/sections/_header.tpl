
<!DOCTYPE html>
<head>
    <title>{$title}</title>
    <meta charset="UTF-8">
    <meta name="keyword" content="{$keyword}"/>
    <meta name="author" content="Davor Bagarić"/>
    <meta name="title" content="Početna"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{$ps->css.base}" rel="stylesheet" type="text/css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="{$ps->js.base}" type="text/javascript"></script>
</head>
<body>
    <header>
        <h1>{$title}</h1>
    </header>
    <nav class="menu">
        <ul>
            {foreach from=$menu key=k item=v}
                <li><a href="{$k}">{$v}</a></li>
            {/foreach}
        </ul>
    </nav>
