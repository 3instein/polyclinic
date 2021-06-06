<?php

include './../controller/appointment_controller.php';
include './../controller/patient_controller.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <base href="<?= base; ?>">

    <style>
        <?php include '../dist/css/main.css'; ?>
    </style>
    <title>Schedule Your Appointment</title>
</head>

<body>
    <div class="make_appointment_page">
        <h1>Choose Department</h1>
        <form action="<?= base ?>controller/appointment_controller.php" method="POST" id="select_doctor">
            <select name="department" id="department">
                <option disabled selected>Choose Department</option>
                <?php
                $department_list = listDepartment();
                if ($department_list) {
                    while ($row = $department_list->fetch_assoc()) {
                        echo "<option value='$row[id]'>$row[name]</option>";
                    }
                }
                ?>
            </select>
            <table border="1" cellpadding="5" cellspacing="0" class="doctor_table">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Doctor</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </form>
        <form action="<?= base ?>controller/appointment_controller.php" method="POST" id="select_doctor_mobile">
            <div class="make_appointment_card_wrapper">
            </div>
            <select name="department" id="department_mobile">
                <option disabled selected>Choose Department</option>
                <?php
                $department_list = listDepartment();
                if ($department_list) {
                    while ($row = $department_list->fetch_assoc()) {
                        echo "<option value='$row[id]'>$row[name]</option>";
                    }
                }
                ?>
            </select>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#department').on('change', function() {
                let selected = $('#department').val();
                let base = $('head base').attr('href');
                $.ajax({
                    url: base + '/controller/appointment_controller.php?action=getDepartmentId',
                    type: 'POST',
                    data: {
                        selected
                    },
                    success: (payload) => {
                        payload = JSON.parse(payload);
                        if (payload.message === 'success') {
                            let tableData = '';
                            payload.data.forEach(element => {
                                let row = '<tr>';
                                row += `<td><img src='` + base + `images/profile/${element.profile_picture}'></td>`;
                                row += `<td>${element.full_name}</td>`;
                                row += `<td>${element.day}</td>`;
                                row += `<td>${element.time}</td>`;
                                row += `<td>${element.availability}</td>`;
                                row += `<td><button type="submit" name="appointment" value="${element.schedule_id}" id="select_this_doctor">Select</button></td>`;
                                row += '</tr>';
                                tableData += row;
                            });
                            $('.doctor_table tbody').html(tableData);
                        } else {
                            alert(payload.message);
                        }
                    },
                });
            });

            $('#department_mobile').on('change', function() {
                let selected = $('#department_mobile').val();
                let base = $('head base').attr('href');
                $.ajax({
                    url: base + '/controller/appointment_controller.php?action=getDepartmentId',
                    type: 'POST',
                    data: {
                        selected
                    },
                    success: (payload) => {
                        payload = JSON.parse(payload);
                        if (payload.message === 'success') {
                            let tableData = '';
                            payload.data.forEach(element => {
                                const row = `
                                    <div class="make_appointment_card">
                                        <div class="top">
                                            <img src="${base}images/profile/${element.profile_picture}">
                                            <div>
                                                <p>${element.full_name}</p>
                                                <p>${element.availability}</p>
                                            </div>
                                        </div>
                                        <div class="bottom">
                                            <div>
                                                <div class="day">${element.day}</div>
                                                <div class="time">${element.time}</div>
                                            </div>
                                            <button name="appointment" value="${element.schedule_id}">Select</button>
                                        </div>
                                    </div>`;
                                tableData += row;
                            });
                            $('.make_appointment_card_wrapper').html(tableData);
                        } else {
                            alert(payload.message);
                        }
                    },
                });
            });
        });
    </script>
</body>

</html>