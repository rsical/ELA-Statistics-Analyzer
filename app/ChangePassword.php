
	<?php
  //include './includedFrameworks/bootstrapHead.html';
  include ("./db/connection/dbConnection.php");

if (isset($_POST['buttonChangePassword'])) {

	$oldPass = sha1($conn->real_escape_string($_POST['oldPassword']));
	//$oldPass = $conn->real_escape_string($_POST['oldPass']);
	$pwd = sha1($conn->real_escape_string($_POST['newPasswordOnce']));
	$pwd2 = sha1($conn->real_escape_string($_POST['newPasswordTwice']));


	$user ="SELECT * FROM useraccount WHERE UserID='$currUserID';";
	$result=$conn->query($user);



	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$userId = $row["UserID"];
		$currentPass = $row["Password"];

		if (($oldPass != $currentPass)){
			echo "<script>alert('Current password is incorrect');</script>";
		}

		elseif (($pwd2 != $pwd)){
			echo "<script>alert('New passwords must match');</script>";
		}		

		else {
			$update="UPDATE useraccount SET Password='$pwd' WHERE UserID='$userId';";
			$conn->query($update);

			echo "<script>alert('Password Updated Successfully');</script>";
		}


	}
}


?>


<div class="container">
	<form id="changePasswordForm" method="post">

			<div class="form-group row">
				<label for="oldPassword">Current Password</label>
				<input type="password" class="form-control" name="oldPassword" id="oldPassword" placeholder="Current password" required>

				<div style="display: none;" id="oldPasswordError">
					<small class="text-danger">Your current password is incorrect</small>
				</div>
			</div>

			<div class="form-group row ">
				<label for="newPasswordOnce">New Password</label>
				<input type="password" class="form-control" name="newPasswordOnce" id="newPasswordOnce" placeholder="New Password" required>
			</div>

			<div class="form-group row">
				<label for="newPasswordTwice">Confirm New Password</label>
				<input type="password" class="form-control" name="newPasswordTwice" id="newPasswordTwice" placeholder="New Password" required>

				<div style="display: none;" id="newPasswordError">
					<small class="text-danger">Passwords do not match</small>
				</div>
			</div>

			<div class="form-group row">
				<button type="button submit" name="buttonChangePassword" class="btn btn-primary btn-block">Change Password</button>
			</div>
	</form>
</div>
