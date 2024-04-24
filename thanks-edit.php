<?php
include "db_conn.php";

// Check if ID is set and is a valid integer
if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id = $_GET["id"];

    // Check if form is submitted
    if (isset($_POST["submit"])) {
        // Sanitize and validate user input
        $name = $_POST['name'];
        $bcategory = $_POST['bcategory']; 
        $refamount = $_POST['refamount'];  
        $btype =  $_POST['btype'];
        $reftype = $_POST['reftype'];
        $comments = $_POST['comments'];

        // Prepare and execute SQL UPDATE statement
        $stmt = $conn->prepare("UPDATE `thanks_note` SET `name`=?, `bcategory`=?, `refamount`=?, `btype`=?, `reftype`=?, `comments`=? WHERE id=?");
        $stmt->bind_param("ssssssi", $name, $bcategory, $refamount, $btype, $reftype, $comments, $id);
        $stmt->execute();

        // Check if the query executed successfully
        if ($stmt->affected_rows > 0) {
            header("Location: thanks-note.php?msg=Data updated successfully");
            exit(); // Terminate script after redirection
        } else {
            echo "Failed: " . $stmt->error;
        }
        $stmt->close();
    }

    // Retrieve user details for editing
    $sql = "SELECT * FROM `thanks_note` WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "Invalid ID";
}
?>



<?php
include "db_conn.php";
$sql = "SELECT * FROM thanks_note";
$result=$conn->query($sql);
$rowCount = $result->num_rows;
?>




<!doctype html>
<html lang="en"> 

<head>
<div class="formbold-main-wrapper">
  <!-- Author: FormBold Team -->
  <!-- Learn More: https://formbold.com -->
  <div class="formbold-form-wrapper">
    
    <img src="your-image-url-here.jpg">

    <form action="" method="POST" enctype="multipart/form-data">
      <div class="formbold-form-title">
        <h2 class="">Update Form</h2>
        
      </div>
	  

      <div class="formbold-input-flex">
        <div>
          <label for="firstname" class="formbold-form-label">
            Member Name
          </label>
          <input
            type="text"
            name="name"
            id="name"
            class="formbold-form-input"
			value="<?php echo $row['name'] ?>"
          />
        </div> 
        <div>
          <label for="lastname" class="formbold-form-label"> Business Category</label>
          <input
            type="text"
            name="bcategory"
            id="bcategory"
            class="formbold-form-input"
			value="<?php echo $row['bcategory'] ?>"
          />
        </div>
      </div>

      <div class="formbold-input-flex">
        <div>
          <label for="email" class="formbold-form-label"> Refferal Amount </label>
          <input
            type="name"
            name="refamount"
            id="refamount"
            class="formbold-form-input"
			value="<?php echo $row['refamount'] ?>"
          />
        </div>
        <div>
          <label for="phone" class="formbold-form-label"> Business Type </label>
          <input
            type="text"
            name="btype"
            id="btype"
            class="formbold-form-input"
			value="<?php echo $row['btype'] ?>"
          />
        </div>
      </div>

      <div class="formbold-mb-3">
        <label for="address" class="formbold-form-label">
          Refferal type
        </label>
        <input
          type="text"
          name="reftype"
          id="reftype"
          class="formbold-form-input"
		  value="<?php echo $row['reftype'] ?>"
        />
      </div>

      <div class="formbold-mb-3">
        <label for="address2" class="formbold-form-label">
          Comments
        </label>
        <input
          type="text"
          name="comments"
          id="comments"
          class="formbold-form-input"
		  value="<?php echo $row['comments'] ?>"
        />
      </div>
        
      <button class="formbold-btn" name="submit">Update</button>
    </form>
  </div>
</div>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  body {
    font-family: 'Inter', sans-serif;
  }
  .formbold-mb-3 {
    margin-bottom: 15px;
  }
  .formbold-relative {
    position: relative;
  }
  .formbold-opacity-0 {
    opacity: 0;
  }
  .formbold-stroke-current {
    stroke: currentColor;
  }
  #supportCheckbox:checked ~ div span {
    opacity: 1;
  }

  .formbold-main-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px;
  }

  .formbold-form-wrapper {
    margin: 0 auto;
    max-width: 570px;
    width: 100%;
    background: white;
    padding: 40px;
  }

  .formbold-img {
    margin-bottom: 45px;
  }

  .formbold-form-title {
    margin-bottom: 30px;
  }
  .formbold-form-title h2 {
    font-weight: 600;
    font-size: 28px;
    line-height: 34px;
    color: #07074d;
  }
  .formbold-form-title p {
    font-size: 16px;
    line-height: 24px;
    color: #536387;
    margin-top: 12px;
  }

  .formbold-input-flex {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
  }
  .formbold-input-flex > div {
    width: 50%;
  }
  .formbold-form-input {
    text-align: center;
    width: 100%;
    padding: 13px 22px;
    border-radius: 5px;
    border: 1px solid #dde3ec;
    background: #ffffff;
    font-weight: 500;
    font-size: 16px;
    color: #536387;
    outline: none;
    resize: none;
  }
  .formbold-form-input:focus {
    border-color: #6a64f1;
    box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.05);
  }
  .formbold-form-label {
    color: #536387;
    font-size: 14px;
    line-height: 24px;
    display: block;
    margin-bottom: 10px;
  }

  .formbold-checkbox-label {
    display: flex;
    cursor: pointer;
    user-select: none;
    font-size: 16px;
    line-height: 24px;
    color: #536387;
  }
  .formbold-checkbox-label a {
    margin-left: 5px;
    color: #6a64f1;
  }
  .formbold-input-checkbox {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
  }
  .formbold-checkbox-inner {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    margin-right: 16px;
    margin-top: 2px;
    border: 0.7px solid #dde3ec;
    border-radius: 3px;
  }

  .formbold-btn {
    font-size: 16px;
    border-radius: 5px;
    padding: 14px 25px;
    border: none;
    font-weight: 500;
    background-color: #6a64f1;
    color: white;
    cursor: pointer;
    margin-top: 25px;
  }
  .formbold-btn:hover {
    box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.05);
  }
</style>



</html>