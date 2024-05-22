<form method="POST">
    <input type="text" name="student_name" placeholder="Member Name" required autofocus />
    <input type="submit" value="Add Member" name="submit">
</form>

<?php 

    if(isset($_POST['submit']))
    {
        require_once("../db_conn.php");
        $student_name = $_POST['student_name'];

        $query = "INSERT INTO attendance_students(student_name) VALUE('$student_name')";
        $execQuery = mysqli_query($conn, $query) or die(mysqli_error($conn));

        echo "Student has been added Successfully!";
    }

?>