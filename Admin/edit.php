<?php
include "../db_conn.php";

// Check if s_no is set and is a valid integer
$s_no = $_GET["s_no"];

if (isset($_POST["btn"])) {
    // Sanitize and validate user input
    $user_id = $_POST["user_id"];
    $name = $_POST["name"];
    $mobile = $_POST["mobile"];
    $email = $_POST["email"];
    $bname = $_POST["bname"];
    $bcategory = $_POST["bcategory"];
    $baddress = $_POST["baddress"];
    $password = $_POST["password"];
    $repassword = $_POST["repassword"];

    // File handling for image
    $image = $_FILES['image']['name'];
    $target = "../uploads/".basename($image);

    // Move uploaded image to desired location
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    // Prepare and execute SQL UPDATE statement with image update
    $stmt = $conn->prepare("UPDATE `tbf_mem` SET `user_id`=?, `name`=?, `mobile`=?, `email`=?, `bname`=?, `bcategory`=?, `baddress`=?, `password`=?, `repassword`=?, `image`=? WHERE s_no=?");
    $stmt->bind_param("ssssssssssi", $user_id, $name, $mobile, $email, $bname, $bcategory, $baddress, $password, $repassword, $image, $s_no);
    $stmt->execute();

    // Check if the query executed successfully
    if ($stmt->affected_rows > 0) {
        header("Location: members-list.php?msg=Data updated successfully");
        exit(); // Terminate script after redirection
    } else {
        echo "Failed: " . $stmt->error;
    }
    $stmt->close();
}

// Retrieve user details for editing
$sql = "SELECT * FROM `tbf_mem` WHERE s_no=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $s_no);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();
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

        <!-- HTML form for editing image -->

    
        <label class="block">
      <span class="sr-only">Choose photo</span>
      <input type="file" class="block w-full text-sm text-gray-500
        file:me-4 file:py-2 file:px-4
        file:rounded-lg file:border-0
        file:text-sm file:font-semibold
        file:bg-blue-600 file:text-white
        hover:file:bg-blue-700
        file:disabled:opacity-50 file:disabled:pointer-events-none
        dark:text-neutral-500
        dark:file:bg-blue-500
        dark:hover:file:bg-blue-400
      ">
    </label>
    
        
      </div>
	  <div class="formbold-input-flex">
			<label for="bsValidation3" class="formbold-form-label">User_Id:</label>
				<input type="text" class="formbold-form-input" id="bsValidation3" placeholder="" value="<?php echo $row["user_id"] ?>" name="user_id" required="" readonly>
															
		</div>

      <div class="formbold-input-flex">
        <div>
          <label for="firstname" class="formbold-form-label">
            Name
          </label>
          <input
            type="text"
            name="name"
            id="firstname"
            class="formbold-form-input"
			value="<?php echo $row['name'] ?>"
          />
        </div> 
        <div>
          <label for="lastname" class="formbold-form-label"> Mobile No:</label>
          <input
            type="text"
            name="mobile"
            id="lastname"
            class="formbold-form-input"
			value="<?php echo $row['mobile'] ?>"
          />
        </div>
      </div>

      <div class="formbold-input-flex">
        <div>
          <label for="email" class="formbold-form-label"> Email </label>
          <input
            type="email"
            name="email"
            id="email"
            class="formbold-form-input"
			value="<?php echo $row['email'] ?>"
          />
        </div>
        <div>
          <label for="phone" class="formbold-form-label"> Business Name </label>
          <input
            type="text"
            name="bname"
            id="phone"
            class="formbold-form-input"
			value="<?php echo $row['bname'] ?>"
          />
        </div>
      </div>

      <div class="formbold-mb-3">
        <label for="address" class="formbold-form-label">
          Business Category
        </label>
        <input
          type="text"
          name="bcategory"
          id="address"
          class="formbold-form-input"
		  value="<?php echo $row['bcategory'] ?>"
        />
      </div>

      <div class="formbold-mb-3">
        <label for="address2" class="formbold-form-label">
          Business Address
        </label>
        <input
          type="text"
          name="baddress"
          id="address2"
          class="formbold-form-input"
		  value="<?php echo $row['baddress'] ?>"
        />
      </div>

      <div class="formbold-input-flex">
        <div>
          <label for="state" class="formbold-form-label"> Enter Password </label>
          <input
            type="text"
            name="password"
            id="state"
            class="formbold-form-input"
			value="<?php echo $row['password'] ?>"
          />
        </div>
        
      <button class="formbold-btn" name="btn">Update</button>
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