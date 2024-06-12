<?php 
require_once("../db_conn.php");

$vendorAutoloadPath = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($vendorAutoloadPath)) {
    die('Error: Composer dependencies are not installed. Please run "composer install" in the project directory.');
}

require_once($vendorAutoloadPath);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use FPDF\FPDF;

// Handle form submission for filtering
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('m');
$selectedStudent = isset($_GET['student']) ? $_GET['student'] : '';
$absenteeFilter = isset($_GET['absentee_filter']) ? $_GET['absentee_filter'] : '';
$exportFormat = isset($_GET['export']) ? $_GET['export'] : '';

// Fetching Students
$studentQuery = "SELECT * FROM attendance_students";
if ($selectedStudent) {
    $studentQuery .= " WHERE id = '$selectedStudent'";
}

$fetchingStudents = mysqli_query($conn, $studentQuery) OR die(mysqli_error($conn));
$totalNumberOfStudents = mysqli_num_rows($fetchingStudents);

$studentsNamesArray = array();
$studentsIDsArray = array();
while($students = mysqli_fetch_assoc($fetchingStudents)) {
    $studentsNamesArray[] = $students['student_name'];
    $studentsIDsArray[] = $students['id'];
}

// Total number of students for pagination
$totalStudentsQuery = "SELECT COUNT(*) as count FROM attendance_students";
if ($selectedStudent) {
    $totalStudentsQuery .= " WHERE id = '$selectedStudent'";
}
$totalStudentsResult = mysqli_query($conn, $totalStudentsQuery) OR die(mysqli_error($conn));
$totalStudentsRow = mysqli_fetch_assoc($totalStudentsResult);
$totalStudents = $totalStudentsRow['count'];

// Form for filtering
?>
<!DOCTYPE html>
<html>
<head>
    <title>Attendance Report</title>
    <style>
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
        }
        .card {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .card-body {
            margin: 10px 0;
        }
        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }
        .highlight-red {
            background-color: red;
            color: white;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-body" id="attendanceTable">
            <form method="GET" action="">
                <label for="student">Select Student:</label>
                <select id="student" name="student">
                    <option value="">All Students</option>
                    <?php
                    $studentsDropdown = mysqli_query($conn, "SELECT * FROM attendance_students") OR die(mysqli_error($conn));
                    while($student = mysqli_fetch_assoc($studentsDropdown)) {
                        $selected = ($student['id'] == $selectedStudent) ? 'selected' : '';
                        echo "<option value='" . $student['id'] . "' $selected>" . $student['student_name'] . "</option>";
                    }
                    ?>
                </select> 
                <label for="absentee_filter">Show Absentees Only:</label>
                <input type="checkbox" id="absentee_filter" name="absentee_filter" value="1" <?php if ($absenteeFilter) echo 'checked'; ?>>
                <button type="submit">Filter</button>
                <button type="submit" name="export" value="pdf">Export to PDF</button>
                <button type="submit" name="export" value="excel">Export to Excel</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-container">
                <?php

                function getSpecialDates($month, $year) {
                    $dates = getSecondAndFourthSaturdays($month, $year);
                    if ($month == 6) {
                        $dates[] = 15;  
                    }
                    sort($dates);
                    return $dates;
                }

                function getSecondAndFourthSaturdays($month, $year) {
                    $saturdays = [];
                    $count = 0;
                    for ($day = 1; $day <= 31; $day++) {
                        if (date('n', strtotime("$year-$month-$day")) != $month) break;
                        if (date('w', strtotime("$year-$month-$day")) == 6) {
                            $count++;
                            if ($count == 2 || $count == 4) {
                                $saturdays[] = $day;
                            }
                        }
                    }
                    return $saturdays;
                } 

                $table = "<table id='attendanceTable'>";
                $table .= "<thead><tr><th>Names</th>";
                $year = date('Y');
                $allDates = [];
                for ($month = 1; $month <= 12; $month++) {
                    $specialDates = getSpecialDates($month, $year);
                    foreach ($specialDates as $date) {
                        $allDates[] = "$year-$month-$date";
                        $table .= "<th>" . date("F j", strtotime("$year-$month-$date")) . "</th>";
                    }
                }
                $table .= "<th>Total Absences</th></tr></thead><tbody>";

                $studentsToShow = [];
                for($i = 0; $i < $totalNumberOfStudents; $i++) {
                    $absences = 0; 
                    $rowStyle = ""; 

                    $attendanceData = [];
                    foreach ($allDates as $dateOfAttendance) {
                        $fetchingStudentsAttendance = mysqli_query($conn, "SELECT attendance FROM attendance WHERE student_id = '". $studentsIDsArray[$i] ."' AND curr_date = '". $dateOfAttendance ."'") OR die(mysqli_error($conn));
                        
                        $isAttendanceAdded = mysqli_num_rows($fetchingStudentsAttendance);
                        if($isAttendanceAdded > 0) {
                            $studentAttendance = mysqli_fetch_assoc($fetchingStudentsAttendance);
                            $attendance = $studentAttendance['attendance'];

                            if ($attendance == "A") {
                                $absences++;
                                $color = "red";
                            } else if ($attendance == "P") {
                                $color = "green";
                            } else if ($attendance == "S") {
                                $color = "yellow";
                            } else if ($attendance == "L") {
                                $color = "blue";
                            }

                            $attendanceData[] = "<td style='background-color: $color; color:white'>" . $attendance . "</td>";
                        } else {
                            $attendanceData[] = "<td></td>";
                        }
                    }

                    if (!$absenteeFilter || $absences >= 2) {
                        $studentsToShow[] = [
                            'name' => $studentsNamesArray[$i],
                            'attendanceData' => $attendanceData,
                            'absences' => $absences,
                            'rowStyle' => ($absences >= 2) ? "class='highlight-red'" : ""
                        ];
                    }
                }

                foreach ($studentsToShow as $student) {
                    $table .= "<tr>";
                    $table .= "<td>" . $student['name'] . "</td>";
                    $table .= implode("", $student['attendanceData']);
                    $table .= "<td " . $student['rowStyle'] . ">" . $student['absences'] . "</td>";
                    $table .= "</tr>";
                }

                $table .= "</tbody></table>";

                if ($exportFormat) {
                    if ($exportFormat == 'pdf') {
                        exportToPDF($table);
                    } elseif ($exportFormat == 'excel') {
                        exportToExcel($studentsNamesArray, $studentsIDsArray, $allDates);
                    }
                } else {
                    echo $table;
                }

                function exportToPDF($table) {
                    $pdf = new FPDF();
                    $pdf->AddPage();
                    $pdf->SetFont('Arial', 'B', 12);
                    $pdf->WriteHTML($table);
                    $pdf->Output();
                    exit;
                }

                function exportToExcel($studentsNamesArray, $studentsIDsArray, $allDates) {
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();

                    $sheet->setCellValue('A1', 'Names');
                    $col = 'B';
                    foreach ($allDates as $date) {
                        $sheet->setCellValue($col . '1', date("F j", strtotime($date)));
                        $col++;
                    }

                    $row = 2;
                    foreach ($studentsNamesArray as $index => $name) {
                        $sheet->setCellValue('A' . $row, $name);
                        $col = 'B';
                        foreach ($allDates as $date) {
                            $studentID = $studentsIDsArray[$index];
                            $attendanceQuery = "SELECT attendance FROM attendance WHERE student_id = '$studentID' AND curr_date = '$date'";
                            $result = mysqli_query($conn, $attendanceQuery) OR die(mysqli_error($conn));
                            $attendance = mysqli_fetch_assoc($result)['attendance'] ?? '';
                            $sheet->setCellValue($col . $row, $attendance);
                            $col++;
                        }
                        $row++;
                    }

                    $writer = new Xlsx($spreadsheet);
                    $filePath = 'attendance.xlsx';
                    $writer->save($filePath);
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment; filename="attendance.xlsx"');
                    readfile($filePath);
                    exit;
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
