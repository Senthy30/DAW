<!DOCTYPE html>

<html>

    <head>
        <title>Home</title>
    </head>

    <body>

    <form action="takeScreenshot.php" method="POST">

        <h2>Take Screenshot</h2>

        <label> URL </label> <br>
        <input type="text" name="urlToCapture" placeholder="URL" value="https://www.google.com/"> <br> 

        <label> File Name </label> <br>
        <input type="text" name="nameToSave" placeholder="File Name" value="screenshot.png"> <br> 

        <label> Width </label> <br>
        <input type="text" name="widthVal" placeholder="Width" value="1920"> <br> 

        <label> Height </label> <br>
        <input type="text" name="heightVal" placeholder="Height" value="1080"> <br> 

        <button type=submit> Submit </button>

        </form>

    </body>

</html>