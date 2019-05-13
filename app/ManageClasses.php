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

	if (isset($_SESSION['isAdmin'])) {
		if (!$_SESSION['isAdmin']) {
			header("Location: 403.php");
		}
	}
	
	unset($_SESSION['storyCreated']);
	unset($_SESSION['myCount']);
?>

<!DOCTYPE html>
<?php
  include ("./db/connection/dbConnection.php");
  include ("./models/GetClasses.php");
?>
<html lang="en">
  <head>
		<title>Classes</title>
		<?php include './includedFrameworks/bootstrapHead.html';?>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
		<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>



  </head>

<body>

<?php include './navigation/navBegin.html'; ?>

<div style="padding-top: 15px" class="container">

	<div class="row">
		<h2 >Classes</h2>
	</div>

	<!-- <div class="row form-group">
		<button type="button" class="btn btn-success" data-toggle="modal" data-target="#createTeacher"><span class="fa fa-plus"></span> Create Teacher</button>
	</div> -->
	<!-- <div class="row form-group">
		<button type="button" class="btn btn-success" data-toggle="modal" name="assignClass" data-target="#assignClass"><span class="fa fa-plus"></span> Assign Class</button>
	</div> -->

	<?php
		$sqlTeachers= ("SELECT distinct user.UserID, user.FName, user.LName, class.ClassYear, class.ClassID, teachers.TeacherID
		FROM useraccount user
		INNER JOIN teacher teachers ON user.UserID= teachers.UserID
		INNER JOIN class ON teachers.TeacherID= class.TeacherID
		INNER JOIN classhistory ON classhistory.ClassID= class.ClassID
		order by class.ClassYear desc, user.LName
			");

	$result = $conn->query($sqlTeachers) or die('Could not run query: '.$conn->error);
	if ($result->num_rows > 0) { ?>

			<div class="row">
			<table id="classesTable" class="table">
			<thead>
			<tr>
			 <th  style="">Teacher</th>
			 <!-- <th></th> -->
			 <th></th>
			 <th style="">Classroom</th>
			 <th style="">Year</th>
			 <th></th>
			 <th></th>
			 </tr>
			</thead>
			<tbody>
			<?php
			 while($row = $result->fetch_assoc()){
				 $UserId = $row["UserID"];
				 $TeacherId = $row["TeacherID"];
				 $FName = $row["FName"];
				 $LName = $row["LName"];
				 $classYear = $row["ClassYear"];
				 $grade = $row["ClassID"];
				 ?>

				<form action="DeleteTeacher.php" method="POST">
				 <tr>
				 <td><input class="form-control" name="Name" type="text"  value="<?= $FName ?> <?= $LName ?>" readonly>   <span style="display:none;"><?= $FName ?> <?= $LName ?></span></td>

					<!-- <td><button  class="btn btn-danger" type="submit"  name="Delete" value=""><span class="fa fa-trash-alt"></span> Delete Teacher</button></td>  -->

					<td><button  class="btn btn-primary" type="submit"  name="Unassign" value="">Unassign</button></td>

					<td><input class="form-control" name="grade" type="text"  id="editable" value="<?= $grade ?>" size="12" readonly> <span style="display:none;"><?= $grade ?></span></td>

					<td><input class="form-control"name ="classYear" type="text" value="<?= $classYear ?>" size="12" readonly> <span style="display:none;"><?= $classYear ?></span></td>

					<td><input name="id" type="hidden" value="<?= $UserId ?>"  ></td>

					<td><input name="teacherId" type="hidden" value="<?= $TeacherId ?>"  ></td>

			  </form>
				</tr>
		<?php } ?>
		</tbody>
		</table>
			 </div>
		<?php }
		else {
			echo "0 results";
		}
		 ?>

		<?php
			$unassignedClasses=getUnassignedClasses($conn);

			if  ($unassignedClasses->num_rows>0);{?>

			<div class="row">
				<h2> Unassigned classes </h2>
			</div>	
			<div class="row">
				<h6>*Note: A teacher can be assigned to more than one class*</h6>
			</div>
		 	
			<div class="row">
				<table id="unassignedTable" class="table">
					<thead>
						<tr>
							<th> Teacher </th>
							<th></th>
							<th> Grade </th>
							<th> Year </th>
							<th> Classroom </th>
							<th style="display:none;">Class ID</th>
						</tr>
					</thead>
					<tbody>

			<?php	 
				 while($row = $unassignedClasses->fetch_assoc()){
					
					$classID = $row["ClassID"];
					$grade = $row["Grade"];
					$classYear= $row["ClassYear"];
					?>
					
					<form action="UpdateClass.php" method="POST">
					<tr>
						<td>
						<select name="teacherSelect" class="form-control">
							<option  selected value="0">No Teacher Assigned</option>

							<?php
							$teachers=getAllTeachers($conn);
							while($row = $teachers->fetch_assoc()){
								
								$firstName = $row["fname"];
								$lastName= $row["lname"];
								$teacherID= $row["teacherid"];
								
							?>
							<option value=<?= $teacherID ?> > <?= $firstName ?> <?= $lastName ?> </option>

						<?php	} //end while ?>


						</select>
						
						</td>
						<td><button type="submit" class="btn btn-primary">Assign</button></td>
						<td><?= $grade ?></td>
						<td><?= $classYear ?> </td>
						<td><?= $classID ?> </td>
						<td style="display:none;"><input type="text" name="classID" value=<?= $classID ?>> </td>
					</tr>
					</form>


			<?php	 } //end while

				} //end if	?>
				


				
		
		
				</tbody>
				</table>
			</div>
		 
		




		
			<div class="modal fade" id="assignClass" role="dialog">
 	  		<div class="modal-dialog">
 	      	<div class="modal-content">
 	        	<div class="modal-header">
 	          	<button type="button" class="close" data-dismiss="modal">&times;</button>
 	        	</div>
 	        	<div class="modal-body">
 	          	<?php include 'AssignClass.php';?>
 	        	</div>
 	      	</div>
 	    	</div>
 			</div>

</div>

<?php include './navigation/navEnd.html'; ?>

<script>
$(document).ready( function () {
    // $('#classesTable').DataTable({
		
	// });

	//  $('#unassignedTable').DataTable({
		
	// });
} );
</script>



</body>
</html>
