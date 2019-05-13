


		<?php
  include ("./db/connection/dbConnection.php");

  if (isset($_POST['Create']))
  {

		$fName = $_POST['fname'];
		$lName = $_POST['lname'];
		$classId = $_POST['classId'];
		
		$sqlClassYear=("select ClassYear from Class where ClassID='$classId'");
		$result = $conn->query($sqlClassYear) or die('Could not run query: '.$conn->error);
			while($row = $result->fetch_assoc()){
				$classYear = $row["ClassYear"];
			}
			
		
		$CreateStudent =("INSERT INTO student (FName, LName) VALUES ('$fName', '$lName')");

  		  if($conn->query($CreateStudent) == TRUE){

			$AddStuToClass =("INSERT INTO ClassHistory (ClassID, ClassYear, StudentID)
			VALUES ('$classId','$classYear',(select max(StudentID) from Student))");


			if($conn->query($AddStuToClass) == TRUE){
				//echo "Student Successfully Added to Class. ";
			}

}
else
	echo "Error";

}



  ?>

	<form method="post">
	<center><h3> Add Student</h3></center>
	<table align='center'>
	<br>
  <tr>
  <td style='color:black;'>First Name  </td>
  <td><input style='color:black;' type="text" name="fname" required></td>
  </tr>
  <tr>
	<td style='color:black;'>Last Name  </td>
	<td><input style='color:black;' type="text" name="lname" required></td>
	</tr>
	<tr>
		<td style='color:black;'>Select Class</td>
		<td>	<select name="classId" required>
			<?php
			$sqlClass="SELECT  class.ClassID, UserAccount.UserID, class.ClassYear
			 						FROM class
									INNER JOIN teacher ON teacher.TeacherID= class.TeacherID
									INNER JOIN UserAccount ON UserAccount.UserID=Teacher.UserID
									WHERE UserAccount.UserID='$currUserID'
									Group by class.ClassID;" ;
			$result = $conn->query($sqlClass) or die('Error showing school names'.$conn->error);

			//incorporate into drop down list
			 echo '<option value=""> Select Class</option>';

			 while ( $row = mysqli_fetch_array ($result) ) {
				 $ClassData= $row["ClassID"]."--".$row["ClassYear"];
							echo '<option value="'.$row["ClassID"].'">'.$ClassData.'</option>';
					}
		?>
	</select>
		</td>
	</table>
	<br>
	<center><button class="button suggestion suggestionsButton" type ="submit" name="Create" >Create </button></center>
</form>
