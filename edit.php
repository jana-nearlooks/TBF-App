<?php
include "db_conn.php";

$s_no = $_GET["s_no"];

if (isset($_POST["btn"])) {
	$user_id = $_POST["user_id"];
    $name = $_POST["name"];
    $mobile = $_POST["mobile"];
    $email = $_POST["email"];
    $bname = $_POST["bname"];
    $bcategory = $_POST["bcategory"];
    $baddress = $_POST["baddress"];
    $password = $_POST["password"];
    $repassword = $_POST["repassword"];

    $stmt = $conn->prepare("UPDATE `tbf_mem` SET 'user_id'=?, `name`=?,`mobile`=?,`email`=?,`bname`=?,`bcategory`=?,`baddress`=?,`password`=?,`repassword`=? WHERE s_no=?");
    $stmt->bind_param("ssssssssi", $user_id, $name, $mobile, $email, $bname, $bcategory, $baddress, $password, $s_no);
    $stmt->execute();

    if ($stmt) {
       header("Location: members-list.php?msg=Data updated successfully");
    } else {
        echo "Failed: " . $conn->error;
    }
    $stmt->close();
}

$sql = "SELECT * FROM `tbf_mem` WHERE s_no='$s_no'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

?>


<?php
include "db_conn.php";
$sql = "SELECT * FROM tbf_mem";
$result=$conn->query($sql);
$rowCount = $result->num_rows;
?>

<?php
include "db_conn.php";

$sql = "SELECT * FROM tbf_mem ORDER BY User_ID DESC LIMIT 1";
$query = mysqli_query($conn, $sql);

if ($query && mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);

    if (!isset($row['User_id']) || $row['User_id'] == '') {
        // If no previous invoices exist, start with TBF001
        $invoice = "TBF001";
    } else {
        // If previous invoices exist, increment the last invoice number
        $invoice = $row['User_id'];
        // Extract the numeric part of the invoice number
        $numericPart = substr($invoice, 3); // Remove the "TBF" prefix
        $autoIncrement = intval($numericPart);
        $autoIncrement++;
        // Create the new invoice number with the incremented value
        $invoice = "TBF" . str_pad($autoIncrement, 3, '0', STR_PAD_LEFT);
    }
} else {
    // If no previous invoices exist, start with TBF001
    $invoice = "TBF001";
}

echo $invoice; // This will output the incremented invoice number
?>

<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
	<!--plugins-->
	<link href="assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />

	<link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />

	<link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="assets/plugins/fancy-file-uploader/fancy_fileupload.css" rel="stylesheet" />
	<link href="assets/plugins/Drag-And-Drop/dist/imageuploadify.min.css" rel="stylesheet" />

	<link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<!-- loader-->



	<link href="assets/css/pace.min.css" rel="stylesheet" />
	<script src="assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->







	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
	<!-- Theme Style CSS -->



	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

	<link rel="stylesheet" href="assets/css/dark-theme.css" />
	<link rel="stylesheet" href="assets/css/semi-dark.css" />
	<link rel="stylesheet" href="assets/css/header-colors.css" />
	<title>TBF - Members List</title>

	<style>
		.active-link {
			color: #fd3550 !important;
		}
	</style>
</head>

<body>
	<!--wrapper-->
	

							<div class="modal fade" id="exampleLargeModal" tabindex="-1" aria-hidden="true">

								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title"><i class="fa fa-thumbs-up"></i> User Business</h5>
											<button type="button" class="btn-close" data-bs-dismiss="modal"
												aria-label="Close"></button>
										</div>
										<div class="card-body p-4">
											<center><img src="../assets/images/favicon.png" alt="" width="100"
													class="mb-3"></center>

													<form action="" class="row g-3 needs-validation" novalidate="" method="post">
														<div class="col-md-12">
															<label for="bsValidation3" class="form-label">Upload Photo:</label>
															<input id="fancy-file-upload" type="file" name="files" accept=".jpg, .png, image/jpeg, image/png" multiple>
															<div class="invalid-feedback">
																Please upload a photo.
															 </div>
														</div>
														<div class="col-md-6">
															<label for="bsValidation3" class="form-label">User_Id:</label>
															<input type="text" class="form-control" id="bsValidation3" placeholder="" value="<?php echo $invoice; ?>" name="user_id" required="" readonly>
															
														</div>
														<div class="col-md-6">
															<label for="bsValidation3" class="form-label">Name:</label>
															<input type="text" class="form-control" id="bsValidation3" placeholder="" required="" name="name" value="<?php echo $row['name'] ?>">
															<div class="invalid-feedback">
																Please enter a valid name.
															  </div>
														</div>
														<div class="col-md-6">
															<label for="bsValidation3" class="form-label">Mobile No:</label>
															<input type="text" class="form-control" id="bsValidation3" placeholder="" required="" name="mobile" value="<?php echo $row['mobile'] ?>">

															<div class="invalid-feedback">
																Please enter a valid Mobile No.
															  </div>
														</div>
														<div class="col-md-6">
															<label for="bsValidation3" class="form-label">Email:</label>
															<input type="text" class="form-control" id="bsValidation3" placeholder="" required="" name="email" value="<?php echo $row['email'] ?>">
															<div class="invalid-feedback">
																Please enter a Email.
															  </div>
														</div>
														<div class="col-md-6">
															<label for="bsValidation3" class="form-label">Business Name:</label>
															<input type="text" class="form-control" id="bsValidation3" placeholder="" required="" name="bname" value="<?php echo $row['bname'] ?>">

															<div class="invalid-feedback">
																Please enter a Business Name.
															  </div>
														</div>
														<div class="col-md-6">
															<label for="bsValidation3" class="form-label">Business Category:</label>
															<input type="text" class="form-control" id="bsValidation3" placeholder="" required="" name="bcategory" value="<?php echo $row['bcategory'] ?>">

															<div class="invalid-feedback">
																Please enter a Business Category.
															  </div>
														</div>
														<div class="col-md-6">
															<label for="bsValidation3" class="form-label">Business Address:</label>
															<input type="text" class="form-control" id="bsValidation3" placeholder="" required="" name="baddress" value="<?php echo $row['baddress'] ?>">
															<div class="invalid-feedback">
																Please enter a Business Address.
															  </div>
														</div>
														<div class="col-6">
															<label for="inputChoosePassword" class="form-label">Enter Password</label>
															<div class="input-group" id="show_hide_password">
																<input type="password" class="form-control border-end-0" id="inputChoosePassword" placeholder="Enter Password" name="password" value="<?php echo $row['password'] ?>"> <a href="javascript:;" class="input-group-text bg-transparent"><i class="bx bx-hide"></i></a>
															</div>
														</div>
														<div class="col-6">
															<label for="inputChoosePassword" class="form-label">Re-Enter Password</label>
															<div class="input-group" id="show_hide_password">
																<input type="password" class="form-control border-end-0" id="inputChoosePassword" placeholder="Enter Password" name="repassword" value="<?php echo $row['repassword'] ?>"> <a href="javascript:;" class="input-group-text bg-transparent"><i class="bx bx-hide"></i></a>
															</div>
														</div>
												  
											
													<div class="col-md-12 mt-5">
															<div class="d-md-flex d-grid align-items-center gap-3">
																<button type="submit" class="btn btn-primary px-4" name="btn">Update</button>
															
															</div>
														</div>
													</form>

										</div>

									</div>
								</div>
							</div>
						</div>
						<hr />



						
					</div>
				</div>
				


			</div>
		</div>
		<!--end page wrapper -->
		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button-->
		<a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		<footer class="page-footer">

			<p>Designed & Developed by <a href="https://nearlooks.com/">Nearlook</a> Mart Pvt Ltd </p>
		</footer>
	</div>
	<!--end wrapper-->


	
	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
	<script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
	<script src="assets/plugins/chartjs/js/chart.js"></script>
	<script src="assets/js/index.js"></script>

	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="assets/plugins/fancy-file-uploader/jquery.ui.widget.js"></script>
	<script src="assets/plugins/fancy-file-uploader/jquery.fileupload.js"></script>
	<script src="assets/plugins/fancy-file-uploader/jquery.iframe-transport.js"></script>
	<script src="assets/plugins/fancy-file-uploader/jquery.fancy-fileupload.js"></script>
	<script src="assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js"></script>
	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});
	</script>
	<script>
		$('#fancy-file-upload').FancyFileUpload({
			params: {
				action: 'fileuploader'
			},
			maxfilesize: 1000000
		});
	</script>
	<script>
		$(document).ready(function () {
			$('#image-uploadify').imageuploadify();
		})
	</script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>
	<script>
		new PerfectScrollbar(".app-container")
	</script>
</body>



</html>