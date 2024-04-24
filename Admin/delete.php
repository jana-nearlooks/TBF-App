<?php
include "../db_conn.php";
$s_no = $_GET["s_no"];
$sql = "DELETE FROM `tbf_mem` WHERE s_no = $s_no";
$result = mysqli_query($conn, $sql);

if ($result) {
  header("Location: members-list.php?msg=Data deleted successfully");
} else {
  echo "Failed: " . mysqli_error($conn);
}