<!DOCTYPE html>
<html lang = "ja">
    <head>
        <meta charset = "utf-8">
        <title>Reset password</title>
    </head>
    <body>
        <h1>Reset password</h1>
        <form action="reset-password" method="post">
            @csrf
            <label>Name</label>
            <input type="text" name="name">
            <label>Email</label>
            <input type="text" name="email">
            <label>Original password</label>
            <input type="password" name="original_password">
            <label>New password</label>
            <input type="password" name="new_password">
            <input type="submit" value="send">
        </form>

    </body>
</html>