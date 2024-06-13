<?php 
    require_once("../db_conn.php");

    if(isset($_POST['addAttendanceBTN']))
    {
        date_default_timezone_set("Asia/Karachi");

        // Sanitize and validate input
        $selected_date = ($_POST['selected_date'] != "") ? $_POST['selected_date'] : date("Y-m-d");
        $attendance_month = date("M", strtotime($selected_date));
        $attendance_year = date("Y", strtotime($selected_date));

        if(isset($_POST['studentPresent']))
        {
            $studentPresent = $_POST['studentPresent'];
            $attendance = "P";

            foreach($studentPresent as $student_id)
            {
                // Check if attendance already exists for this student on the selected date
                $query = "SELECT * FROM attendance WHERE student_id='$student_id' AND curr_date='$selected_date'";
                $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

                if(mysqli_num_rows($result) > 0) {
                    // Attendance already exists, update it
                    mysqli_query($conn, "UPDATE attendance SET attendance='$attendance' WHERE student_id='$student_id' AND curr_date='$selected_date'") or die(mysqli_error($conn));
                } else {
                    // Attendance does not exist, insert new record
                    $query = "INSERT INTO attendance(student_id, curr_date, attendance_month, attendance_year, attendance) VALUES('$student_id', '$selected_date', '$attendance_month', '$attendance_year', '$attendance')";
                    mysqli_query($conn, $query) or die(mysqli_error($conn));
                }
            }
        }

        if(isset($_POST['studentAbsent']))
        {
            $studentAbsent = $_POST['studentAbsent'];
            $attendance = "A";

            foreach($studentAbsent as $student_id)
            {
                $query = "SELECT * FROM attendance WHERE student_id='$student_id' AND curr_date='$selected_date'";
                $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

                if(mysqli_num_rows($result) > 0) {
                    mysqli_query($conn, "UPDATE attendance SET attendance='$attendance' WHERE student_id='$student_id' AND curr_date='$selected_date'") or die(mysqli_error($conn));
                } else {
                    $query = "INSERT INTO attendance(student_id, curr_date, attendance_month, attendance_year, attendance) VALUES('$student_id', '$selected_date', '$attendance_month', '$attendance_year', '$attendance')";
                    mysqli_query($conn, $query) or die(mysqli_error($conn));
                }
            }
        }

        if(isset($_POST['studentLeave']))
        {
            $studentLeave = $_POST['studentLeave'];
            $attendance = "L";

            foreach($studentLeave as $student_id)
            {
                $query = "SELECT * FROM attendance WHERE student_id='$student_id' AND curr_date='$selected_date'";
                $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

                if(mysqli_num_rows($result) > 0) {
                    mysqli_query($conn, "UPDATE attendance SET attendance='$attendance' WHERE student_id='$student_id' AND curr_date='$selected_date'") or die(mysqli_error($conn));
                } else {
                    $query = "INSERT INTO attendance(student_id, curr_date, attendance_month, attendance_year, attendance) VALUES('$student_id', '$selected_date', '$attendance_month', '$attendance_year', '$attendance')";
                    mysqli_query($conn, $query) or die(mysqli_error($conn));
                }
            }
        }

        if(isset($_POST['studentHoliday']))
        {
            $studentHoliday = $_POST['studentHoliday'];
            $attendance = "S";

            foreach($studentHoliday as $student_id)
            {
                $query = "SELECT * FROM attendance WHERE student_id='$student_id' AND curr_date='$selected_date'";
                $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

                if(mysqli_num_rows($result) > 0) {
                    mysqli_query($conn, "UPDATE attendance SET attendance='$attendance' WHERE student_id='$student_id' AND curr_date='$selected_date'") or die(mysqli_error($conn));
                } else {
                    $query = "INSERT INTO attendance(student_id, curr_date, attendance_month, attendance_year, attendance) VALUES('$student_id', '$selected_date', '$attendance_month', '$attendance_year', '$attendance')";
                    mysqli_query($conn, $query) or die(mysqli_error($conn));
                }
            }
        }

        echo "Attendance added successfully"; 
    }
?>

<table border="3" cellspacing="0">
    <form method="POST">
        <tr>
            <th>Member Name</th>
            <th> P </th>
            <th> A </th>
            <th> L </th>
            <th> S </th>
        </tr>
        <?php
            $fetchingStudents = mysqli_query($conn, "SELECT * FROM attendance_students") OR die(mysqli_error($conn));
            while($data = mysqli_fetch_assoc($fetchingStudents))
            {
                $student_name = $data['student_name'];
                $student_id = $data['id'];
        ?>
                <tr>
                    <td><?php echo $student_name; ?></td>
                    <td> <input type="checkbox" name="studentPresent[]" value="<?php echo $student_id; ?>" /></td>
                    <td> <input type="checkbox" name="studentAbsent[]" value="<?php echo $student_id; ?>" /></td>
                    <td> <input type="checkbox" name="studentLeave[]" value="<?php echo $student_id; ?>" /></td>
                    <td> <input type="checkbox" name="studentHoliday[]" value="<?php echo $student_id; ?>" /></td>
                </tr>
        <?php

            }
        ?>
        <tr>
            <td>Select Date </td>
            <td colspan="4"> <input type="date" name="selected_date" /> </td>
        </tr>
        <tr>
            <th colspan="5"> <input type="submit" name="addAttendanceBTN" /></th>
        </tr>
    </form>
</table>
