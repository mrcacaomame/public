<!DOCTYPE html>
<html lang = "ja">
    <head>
        <meta charset = "utf-8">
        <title>signup</title>
    </head>
    <body>
        <h1>Signin</h1>
        <form action="account" method="post">
            @csrf
            <input type="hidden" name="opt" value="signin">
            <label>Email address</label>
            <input type="text" name="name" placeholder="user name">
            <input type="email" name="email" placeholder="email address">
            <input type="password" name="password" placeholder="password">
            <input type="submit" value="send">
        </form>
        <a href="/account?opt=signup">signup</a>
    </body>
</html>