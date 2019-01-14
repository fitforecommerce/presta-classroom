<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <script type="text/javascript" src="/public/assets/js/jquery-3.3.1.min.js"></script>

  <link href='/public/assets/css/bootstrap.css' rel='stylesheet' type='text/css'/>
  <link href='/public/assets/css/font-awesome.min.css' rel='stylesheet' type='text/css'/>
  <link href='/public/assets/css/main.css' rel='stylesheet' type='text/css'/>

  <title>PrestaClassroom</title>

</head>
<body>
    {if $logged_in_user != ''}
      {include file='navigation.tpl'}
    {/if}
    <main>
      {block name=title}
        {$title}
      {/block}
      {if $msg != '' }
        <div id="flashmessage">
          {$msg}
        </div>
      {/if}
      {include file='status.tpl'}
      {block name=content}
        {$content}
      {/block}
    </main>
    {include file='footer.tpl'}
</body>
</html>

