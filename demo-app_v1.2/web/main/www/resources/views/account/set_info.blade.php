<!DOCTYPE html>
<html lang = "ja">
    <head>
        <meta charset = "utf-8">
        <title>signin</title>
    </head>
    <body>
        <h1>User information</h1>
        <form action="account" method="post">
            @csrf
            <input type="hidden" name="opt" value="set_info">
            <label>Username</label>
            <input type="text" name="name" placeholder="user name">
            <label>Password</label>
            <input type="password" name="password" placeholder="password">
            <input type="submit" value="send"> 
        </form>
    </body>
</html>