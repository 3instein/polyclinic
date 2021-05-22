<?php

include './../controller/doctor_controller.php';
include './../controller/patient_controller.php';
include './../controller/schedule_controller.php';
include './../controller/department_controller.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

    <title>Document</title>
</head>

<body>
    <h1>Choose Department</h1>
    <form action="./../controller/appointment_controller.php" method="POST">
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
                    <th>Doctor</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </form>

    <script>
        $(document).ready(function() {
            $('#department').on('change', function() {
                let selected = $('#department').val();
                $.ajax({
                    url: 'http://localhost/polyclinic/controller/appointment_controller.php?action=getDepartmentId',
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
                                row += `<td>${element.full_name}</td>`;
                                row += `<td>${element.time}</td>`;
                                row += `<td>${element.availability}</td>`;
                                row += `<td><button type="submit" name="appointment" value="${element.schedule_id}">Select</button></td>`;
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
        });
    </script>
</body>

</html>