<?php
	session_start();
	if (isset($_SESSION['UserID']))
	{
		$currUserID = $_SESSION['UserID'];
	}
	else
	{
		header("Location: logout.php");
	}

	unset($_SESSION['storyCreated']);
	unset($_SESSION['myCount']);
?>

<!DOCTYPE html>
<?php
  include ("./db/connection/dbConnection.php");
?>
<html lang="en">
  <head>
		<title>View Students</title>
		<?php include './includedFrameworks/bootstrapHead.html';?>
  </head>

<body>

<?php include './navigation/navBegin.html'; ?>

<div style="padding-top: 15px" class="container">

	<div class="row">
		<h2 >Students</h2>
  </div>
	<br>

	<?php
	if (isset($_POST['ViewStudents'])){
		$UserId = $_POST['UserId'];
		$studentId = $_POST['studentId'];
		$classId = $_POST['classId'];
		$teachersName = $_POST['teachersName'];
		$mygrade = $_POST['mygrade'];
		$myYear = $_POST['myYear'];


		$sqlStudents= ("SELECT user.FName, user.UserID, user.LName AS userLName, user.FName AS userFName, student.StudentID, class.ClassYear, class.Grade, student.FName, student.LName, class.ClassID
			FROM useraccount user
      INNER JOIN  teacher ON user.UserID= teacher.UserID
			INNER JOIN class ON teacher.teacherID= class.teacherID
      INNER JOIN classhistory ON classhistory.ClassID= class.ClassID
		  INNER JOIN student ON student.StudentID = classhistory.StudentID
			WHERE user.UserID = '$UserId' AND class.Grade= '$mygrade' AND class.ClassYear = '$myYear'
			Group by student.LName");

	$result = $conn->query($sqlStudents) or die('Could not run query: '.$conn->error);
}
	if ($result->num_rows > 0) { ?>
			<div class="row" style="width:600px; margin:0 auto;">
				<?php echo "<b>" .$teachersName." --".$myYear."--- ".$mygrade. "</b>"; ?>
			</div>

			<div class="row" style="width:600px; margin:0 auto;">
			<table class="table">
			<tr>
			 <th  style="">STUDENTS</th>
			 </tr>

			<?php
			 while($row = $result->fetch_assoc()){
				 $studentId = $row["StudentID"];
				 $FName = $row["FName"];
				 $LName = $row["LName"];
				 ?>


				 <tr>
				 			<td align='center'><input style="border:none" class="form-control" name="teachersName" type="text"   value="<?= $FName ?> <?= $LName ?>" readonly></td>

				 			<td><input style="border:none" name="studentId" type="hidden" value="<?= $studentId ?>" readonly ></td>

					</tr>
				</form>
		<?php } ?>
		</table>
			 </div>
		<?php }
		else {
			echo "0 results";
		}
		 ?>

<?php include './navigation/navEnd.html'; ?>



</body>
</html>
