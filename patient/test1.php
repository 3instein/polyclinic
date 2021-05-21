<?php include './test.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="http://code.jquery.com/jquery.js" type="text/javascript"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <title>Document</title>
</head>

<body>
  <select name="test" id="test">
    <option value="1">a</option>
    <option value="2">b</option>
    <option value="3">c</option>
    <option value="4">d</option>
  </select>

  <?php
    $_GET['test'] = '';
    $data = $_GET['test'];
    echo $data;
  ?>

  <script>
    $(document).ready(function() {
      $("#test").on("change", function() {
        let selected = $("#test").val();
        $.ajax({
          url: "test1.php",
          type: "GET",
          data: {
            'test': selected
          },
          success: alert(selected),
        });
      });
    });
  </script>
</body>

</html>