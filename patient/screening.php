<?php
    
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Screening</h1>
    <form action="../controller/screening_controller.php" method="POST">
        <ol>
            <li>Any allergic reaction to medication</li>
            <input type="radio" name="question-1" value="true">Yes
            <input type="radio" name="question-1" value="false">No
            <li>High blood pressure</li>
            <input type="radio" name="question-2" value="true">Yes
            <input type="radio" name="question-2" value="false">No
        </ol>
        <button type="submit" name="screening_submit">Submit</button>
    </form>
</body>
</html>