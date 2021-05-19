<?php include './controller/main_controller.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./dist/css/main.css" />
    <title>Schedule Your Appoinment | PolyClinic</title>
  </head>
  <body>
    <nav class="container" id="home">
      <a href="/" class="navbar_brand">
        <img src="./dist/img/logo.svg" alt="Logo" />
      </a>
      <div class="navbar_nav">
        <a href="./index.html" class="navbar_link">Home</a>
        <a href="#appoinment" class="navbar_link">View Appoinment</a>
      </div>
    </nav>

    <section class="container">
      <div class="appoinment_ocw">
        <h1>Schedule an Appoinment</h1>
        <div class="card_list">
          <div class="card"></div>
          <div class="card"></div>
          <div class="card"></div>
          <div class="card"></div>
          <div class="card"></div>
          <div class="card"></div>
        </div>
      </div>
      <div class="appoinment_form">
        <div class="choose_doctor">
          
        </div>
        <div class="identities_form">
          <form method="POST">
            <label for="name">Name</label>
            <input type="text" name="full_name" />
            <label for="address">Address</label>
            <input type="text" name="address" />
            <label for="contact">Contact</label>
            <input type="text" name="contact" />
            <label for="id">ID Card Number</label>
            <input type="text" name="id_number" />
            <button type="submit" name="appoinment">Make Appoinment</button>
          </form>
        </div>
      </div>
    </section>
  </body>
</html>
