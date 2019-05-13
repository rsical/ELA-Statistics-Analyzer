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
		<title>Classes</title>
		<?php include './includedFrameworks/bootstrapHead.html';?>
  </head>

<body>

<?php include './navigation/navBegin.html'; ?>

<div style="padding-top: 15px" class="container">

	<div class="row">
		<h2 >Classes</h2>
  </div>
	<br>

	<?php
	if (isset($_SESSION['isTeacher'])) {
	    if ($_SESSION['isTeacher']) {
	$sqlTeachers="SELECT user.FName, user.UserID, user.LName AS userLName, user.FName AS userFName, student.StudentID, class.ClassYear, class.Grade, student.FName, student.LName, class.ClassID, classhistory.ClassYear
			FROM useraccount user
            INNER JOIN  teacher ON user.UserID= teacher.UserID
						INNER JOIN class ON teacher.teacherID= class.teacherID
            INNER JOIN classhistory ON classhistory.ClassID= class.ClassID INNER JOIN student ON student.StudentID = classhistory.StudentID
						WHERE user.UserID = $currUserID
						Group by classhistory.ClassYear;" ;

	$GLOBALS['Tresult'] = $conn->query($sqlTeachers) or die('Could not run query'.$conn->error);

}
}

if (isset($_SESSION['isAdmin'])) {
		if ($_SESSION['isAdmin']) {
		$sqlTeachers= ("SELECT user.FName, user.UserID, user.LName AS userLName, user.FName AS userFName, student.StudentID, class.ClassYear, class.Grade, student.FName, student.LName, class.ClassID
			FROM useraccount user
            INNER JOIN  teacher ON user.UserID= teacher.UserID
						INNER JOIN class ON teacher.teacherID= class.teacherID
            INNER JOIN classhistory ON classhistory.ClassID= class.ClassID INNER JOIN student ON student.StudentID = classhistory.StudentID
						WHERE classhistory.ClassYear = (SELECT MAX(ClassYear) FROM classhistory)
						Group by user.UserID");
$GLOBALS['Tresult'] = $conn->query($sqlTeachers) or die('Could not run query: '.$conn->error);

}
}
	if ($Tresult->num_rows > 0) { ?>

			<div class="row" style="width:800px; margin:0 auto;">
			<table class="table">
			<tr>
			 <th  style="">Teacher</th>
			 <th style="">Year</th>
			 <th style="">Class</th>
			 <th style="">Students</th>
			 </tr>

			<?php
			 while($row = $Tresult->fetch_assoc()){
				 echo'<form action="ViewStudents.php" method="POST">';
				 $UserId = $row["UserID"];
				 $studentId = $row["StudentID"];
				 $classId = $row["ClassID"];
				 $userFName = $row["userFName"];
				 $userLName = $row["userLName"];
				 $mygrade = $row["Grade"];
				 $grades= "Grade";
				 $myYear= $row['ClassYear'];
				 ?>


				 <tr>
				 			<td><input style="border:none" class="form-control" name="teachersName" type="text"   value="<?= $userFName ?> <?= $userLName ?>" readonly></td>

				 			<td><input style="border:none" class="form-control" name="myYear" type="text"   value="<?= $myYear ?>" size="12" readonly></td>

							<td><input style="border:none" class="form-control" name="mygrade" type="text"   value="<?= $mygrade ?> <?= $grades ?>" size="12" readonly></td>

				 			<td><button  class="btn btn-info" type="submit"  name="ViewStudents" value=""><span class="fa fa-eye"></span> View Students</button></td>

				 			<td><input style="border:none" name="UserId" type="hidden" value="<?= $UserId ?>"  ></td>

				 			<td><input style="border:none" name="studentId" type="hidden" value="<?= $studentId ?>"  ></td>

				 <td><input name="classId" type="hidden" value="<?= $classId ?>"  ></td>
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
