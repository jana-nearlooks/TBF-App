<?php 
    require_once("../db_conn.php");

    $firstDayOfMonth = date("1-m-Y");
    $totalDaysInMonth = date("t", strtotime($firstDayOfMonth));
   
    // Fetching Students 
    $fetchingStudents = mysqli_query($conn, "SELECT * FROM attendance_students") OR die(mysqli_error($conn));
    $totalNumberOfStudents = mysqli_num_rows($fetchingStudents);

    $studentsNamesArray = array();
    $studentsIDsArray = array();
    $counter = 0;
    while($students = mysqli_fetch_assoc($fetchingStudents))
    {
        $studentsNamesArray[] = $students['student_name'];
        $studentsIDsArray[] = $students['id'];
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Attendance Report</title>
    <style>
        .card {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .card-body {
            max-height: 500px; /* Adjust the height as needed */
            overflow-y: auto;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            text-align: center;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            position: sticky;
            top: 0;
            z-index: 1;
        }
        td {
            background-color: white;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="card-body">
        <table id="example2" class="table table-striped table-bordered">
            <?php 
                for($i = 1; $i<=$totalNumberOfStudents + 2; $i++)
                {
                    if($i == 1)
                    {
                        echo "<tr>";
                        echo "<th rowspan='2'>Names</th>";
                        for($j = 1; $j<=$totalDaysInMonth; $j++)
                        {
                            echo "<th>$j</th>";
                        }
                        echo "</tr>";
                    } else if($i == 2)
                    {
                        echo "<tr>";
                        for($j = 0; $j<$totalDaysInMonth; $j++)
                        {
                            echo "<th>" . date("D", strtotime("+$j days", strtotime($firstDayOfMonth))) . "</th>";
                        }
                        echo "</tr>";
                    } else 
                    {
                        echo "<tr>";
                        echo "<td>" . $studentsNamesArray[$counter] . "</td>";
                        for($j = 1; $j<=$totalDaysInMonth; $j++)
                        {
                            $dateOfAttendance = date("Y-m-$j");
                            $fetchingStudentsAttendance = mysqli_query($conn, "SELECT attendance FROM attendance WHERE student_id = '". $studentsIDsArray[$counter] ."' AND curr_date = '". $dateOfAttendance ."'") OR die(mysqli_error($conn));
                            
                            $isAttendanceAdded = mysqli_num_rows($fetchingStudentsAttendance);
                            if($isAttendanceAdded > 0)
                            {
                                $studentAttendance = mysqli_fetch_assoc($fetchingStudentsAttendance);
                                if($studentAttendance['attendance'] == "P")
                                {
                                    $color = "green";
                                }else if($studentAttendance['attendance'] == "A")
                                {
                                    $color = "red";
                                }else if($studentAttendance['attendance'] == "S")
                                {
                                    $color = "yellow";
                                }else if($studentAttendance['attendance'] == "L")
                                {
                                    $color = "blue";
                                }

                                echo "<td style='background-color: $color; color:white'>" . $studentAttendance['attendance'] . "</td>";
                            }else {
                                echo "<td></td>";
                            }
                        }
                        echo "</tr>";
                        $counter++;
                    }
                }
            ?>
        </table>
    </div>
</div>

<script>
    // Add any JavaScript you need here for additional interactivity
</script>

</body>
</html>
