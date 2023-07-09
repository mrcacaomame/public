<!DOCTYPE html>
<html lang = "ja">
    <head>
        <meta charset = "utf-8">
        <title>signin</title>
    </head>
    <body>
        <h1>Onetime password</h1>
        <form action="account" method="post">
            @csrf
            <input type="hidden" name="opt" value="onetimepass">
            <label>Onetime password</label>
            <input type="text" placeholder="onetime password" name="password">
            <input type="submit" value="send"> 
        </form>
    </body>
</html>
