<!DOCTYPE html>
<html lang = "ja">
    <head>
        <meta charset = "utf-8">
        <title>information</title>
    </head>
    <body>
        <h1>Information</h1>
        <h3>Weight: {{$weight}}</h3>
        <h3>Height: {{$height}}</h3>
        <form action="information" method="post">
            @csrf
            <label>Set weight</label>
            <input type="text" name="weight">
            <label>Set height</label>
            <input type="text" name="height">
            <input type="submit" value="send">
        </form>
        <a href="reset-password">Reset the password.</a>
    </body>
</html>