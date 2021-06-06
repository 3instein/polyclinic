<?php include '../controller/base_url.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        <?php include '../dist/css/main.css'; ?>
    </style>
    <title>Document</title>
</head>

<body>
    <div class="screening_page">
        <h1>Screening</h1>
        <form action="<?= base ?>controller/screening_controller.php" method="POST">
            <ol>
                <li>Any allergic reaction to medication</li>
                <input type="radio" name="question-1" value="true" required>Yes
                <input type="radio" name="question-1" value="false" required>No
                <li>High blood pressure</li>
                <input type="radio" name="question-2" value="true" required>Yes
                <input type="radio" name="question-2" value="false" required>No
            </ol>
            <button type="submit" name="screening_submit">Submit</button>
        </form>
    </div>
</body>

</html>