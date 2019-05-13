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

include ("./db/connection/dbConnection.php");


if(isset($_POST['selectExamYear'])){
	$date= $_POST['selectExamYear'];
	$year=intval($date);

	echo '<option value="">Select Student</option>';
	$sqlStudents="SELECT student.StudentID, student.FName, student.LName
			FROM useraccount user
      INNER JOIN  teacher ON user.UserID= teacher.UserID
			INNER JOIN class ON teacher.teacherID= class.teacherID
      INNER JOIN classhistory ON classhistory.ClassID= class.ClassID
		  INNER JOIN student ON student.StudentID = classhistory.StudentID
			WHERE user.UserID = '$currUserID' AND class.ClassYear = '$year'
			Group by student.LName;";
				
	  $result = $conn->query($sqlStudents) or die('Error showing students'.$conn->error);
		while ( $row = mysqli_fetch_array ($result) ) {
		  $studentName= $row["FName"]." ".$row["LName"];
		  echo '<option value="'.$row["StudentID"].'">'.$studentName.'</option>';
		}
}
								
								?>