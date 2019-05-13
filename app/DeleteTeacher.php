<!DOCTYPE html>

<html lang="en">
  <head>

    <title>Project</title>
    	<meta charset="utf-8">
    <link rel="stylesheet" href="css/styleSheet.css" />
</head>

<body>

		<?php
  include ("./db/connection/dbConnection.php");
  $zero= 0;

  if (isset($_POST['Delete'])){
  
    $id=$_POST['id'];
    $teacherId=$_POST['teacherId'];

    $sqlUpdateClass= "UPDATE class SET TeacherID= $zero
    WHERE TeacherID='$teacherId';";

    if ($conn->query($sqlUpdateClass) == TRUE) {
    $sqlDeleteTeacher= "DELETE from teacher
       WHERE UserID='$id'";
    }
    if ($conn->query($sqlDeleteTeacher) == TRUE) {
        $sqlDeleteUser= "DELETE from useraccount
        WHERE UserID='$id';";
      }
        if ($conn->query($sqlDeleteUser) == TRUE) {

        header("refresh:1; url=Teachers.php");
      }
	  alert("The teacher has been deleted.");
    }

    if (isset($_POST['Unassign'])){
    $id=$_POST['id'];
    $teacherId=$_POST['teacherId'];
    $classId=$_POST['grade'];

    $sqlUpdateClass= "UPDATE class SET TeacherID= $zero
    WHERE TeacherID='$teacherId' and classID='$classId' ;";

    if ($conn->query($sqlUpdateClass) == TRUE) {
        header("refresh:1; url=ManageClasses.php");
    }
    

        
      
    }


		$conn->close();


		?>

  </body>
</html>
