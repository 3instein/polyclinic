<?php

include './../controller/appointment_controller.php';
include './../controller/doctor_controller.php';
include './../controller/patient_controller.php';
include './../controller/schedule_controller.php';
include './../controller/department_controller.php';
include 'test.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="http://code.jquery.com/jquery.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>

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
            <tr>
                <th>Doctor</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
            <tr>
                <?php
                // $department_id = getDepartmentid();
                // $schedule = getSchedule($department_id);

                // if ($schedule) {
                //     while ($row = $schedule->fetch_assoc()) {
                //         $doctor_name = getDoctor($row['doctor_id'], "name");
                //         echo "<td>$doctor_name</td>";
                //     }
                // }
                ?>
            </tr>
        </table>
        <br />
        <button name="appointment">Make Appoinment</button>
    </form>

    <script>
        $(document).ready(function() {
            $('#department').on('change', function() {
                let selected = $('#department').val();
                $.ajax({
                    url: 'test.php',
                    type: 'POST',
                    data: {'selected': selected},
                    success: alert(selected),
                
                })
            });
        });
    </script>
</body>

</html>