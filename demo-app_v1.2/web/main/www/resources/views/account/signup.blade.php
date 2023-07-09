<!DOCTYPE html>
<html lang = "ja">
    <head>
        <meta charset = "utf-8">
        <title>signup</title>
    </head>
    <body>
        <h1>Signup</h1>
        <form action="account" method="post">
            @csrf
            <input type="hidden" name="opt" value="signup">
            <label>Email address</label>
            <input type="text" name="email" placeholder="email address">
            <input type="submit" value="send">
        </form>
        <a href="/account?opt=signin">signin</a>
    </body>
</html>