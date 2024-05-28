<?php 
require_once("../db_conn.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST as $memberName => $attendance) {
        foreach ($attendance as $date => $status) {
            if (!empty(trim($status))) {
                $query = "INSERT INTO attendance (member_name, curr_date, attendance) 
                          VALUES ('$memberName', '$date', '$status')
                          ON DUPLICATE KEY UPDATE attendance = '$status'";
                mysqli_query($conn, $query) OR die(mysqli_error($conn));
            }
        }
    }
    echo "<p>Attendance records updated successfully.</p>";
}

// Fetching members 
$fetchingmembers = mysqli_query($conn, "SELECT * FROM tbf_mem") OR die(mysqli_error($conn));
$totalNumberOfmembers = mysqli_num_rows($fetchingmembers);

$membersNamesArray = array();
while($members = mysqli_fetch_assoc($fetchingmembers)) {
    $membersNamesArray[] = $members['name'];
}

// Fetching Existing Attendance Data
$existingAttendance = array();
$fetchingAttendance = mysqli_query($conn, "SELECT * FROM attendance") OR die(mysqli_error($conn));
while($attendance = mysqli_fetch_assoc($fetchingAttendance)) {
    $existingAttendance[$attendance['member_name']][$attendance['curr_date']] = $attendance['attendance'];
}

// Define months array
$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

// Function to get class based on attendance status
function getStatusClass($status) {
    switch ($status) {
        case "P":
            return "present";
        case "A":
            return "absent";
        case "L":
            return "late";
        case "S":
            return "sick";
        default:
            return "";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Table</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        table { width: 100%; border-collapse: collapse; }
        td { padding: 10px; text-align: center; border: 1px solid #ddd; cursor: pointer; }
        .present { background-color: green; color: white; }
        .absent { background-color: red; color: white; }
        .leave { background-color: blue; color: white; }
        .substitute { background-color: brown; color: white; }
    </style>
    <script>
        function changeAttendance(cell) {
            const statuses = ["P", "A", "L", "S", " "];
            const classes = ["present", "absent", "leave", "substitute", ""];
            let currentStatus = cell.textContent;

            let index = statuses.indexOf(currentStatus);
            index = (index + 1) % statuses.length;
            cell.textContent = statuses[index];
            cell.className = classes[index];
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('form').addEventListener('submit', function (event) {
                const table = document.getElementById('attendanceTable');
                const inputs = {};
                
                for (let row of table.rows) {
                    for (let cell of row.cells) {
                        if (cell.hasAttribute('data-member-name')) {
                            const memberName = cell.getAttribute('data-member-name');
                            const date = cell.getAttribute('data-date');
                            const status = cell.textContent;
                            
                            if (status.trim()) { // Only add non-empty statuses
                                if (!inputs[memberName]) {
                                    inputs[memberName] = {};
                                }
                                inputs[memberName][date] = status;
                            }
                        }
                    }
                }
                
                for (const [memberName, attendance] of Object.entries(inputs)) {
                    for (const [date, status] of Object.entries(attendance)) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `${memberName}[${date}]`;
                        input.value = status;
                        this.appendChild(input);
                    }
                }
            });
        });
    </script>
</head>
<body>

<form action="#" method="POST">
    <div class="card">
        <div class="card-body">
            <div class="d-lg-flex align-items-center mb-8 gap-5">
                <div class="table-responsive mt-3">
                    <table id="attendanceTable" class="table table-striped table-bordered">
                        <tr>
                            <td rowspan="2">Names</td>
                            <?php 
                                foreach ($months as $month) {
                                    echo "<td colspan='3'>$month</td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <?php 
                                foreach ($months as $month) {
                                    echo "<td>Date 1</td><td>Date 2</td><td>Date 3</td>";
                                }
                            ?>
                        </tr>

                        <?php 
                            for($i = 0; $i < $totalNumberOfmembers; $i++) {
                                echo "<tr>";
                                echo "<td>" . $membersNamesArray[$i] . "</td>";
                                
                                foreach ($months as $index => $month) {
                                    for ($j = 1; $j <= 3; $j++) {
                                        $date = ($index + 1) . "-$j"; // Create a date string like "1-1" for January 1st
                                        $status = isset($existingAttendance[$membersNamesArray[$i]][$date]) ? $existingAttendance[$membersNamesArray[$i]][$date] : "";
                                        $class = getStatusClass($status);
                                        echo "<td onclick='changeAttendance(this)' data-member-name='" . $membersNamesArray[$i] . "' data-date='$date' class='$class'>$status</td>";
                                    }
                                }
                                echo "</tr>";
                            }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Submit Attendance</button>
</form>

</body>
</html>
