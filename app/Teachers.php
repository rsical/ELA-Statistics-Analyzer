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
		<title>Teachers</title>
		<?php include './includedFrameworks/bootstrapHead.html';?>
        
<script>
function deleteConfirmation() {
	alert("The teacher has been deleted.");
}
</script>


  </head>

<body>

<?php include './navigation/navBegin.html'; ?>

<div style="padding-top: 15px" class="container">

    <div class="row">
        <h2>Teachers</h2>
    </div>
    <div class="row form-group">
		<button type="button" class="btn btn-success" data-toggle="modal" data-target="#createTeacher"><span class="fa fa-plus"></span> Create Teacher</button>
	</div>
    <div class="row">
        <table class="table">
            <thead>
            <tr>
                <th> Teacher </th>
                <th></th>
                <th></th>
                <th></th>
                <th># of Classes Taught</th>
            <tr>
            
            </thead>
            <tbody>

                        
            <?php
            $teachers=getAllTeachers($conn);
            while($row = $teachers->fetch_assoc()){
                
                $firstName = $row["fname"];
                $lastName= $row["lname"];
                $teacherID= $row["teacherid"];
                $userID= $row["userid"];
                $numOfClasses=$row["numOfClassesTaught"]
            ?>
                
            
                <form action="DeleteTeacher.php" method="POST">
                <tr>
                    <td><?= $firstName ?> <?= $lastName ?></td>
                    <td><input type="hidden" class="form-control" name="teacherId" type="text" value="<?= $teacherID ?>" size="12" readonly>  </td>
                    <td><input type="hidden" class="form-control" name="id" type="text" value="<?= $userID ?>" size="12" readonly> </td>
                    <td><button  class="btn btn-danger" type="submit"  name="Delete" value="" onClick="deleteConfirmation()"><span class="fa fa-trash-alt"></span> Delete Teacher</button></td>
                    <td><?= $numOfClasses ?> </td>
                
                <tr>
            </form>
            <?php } ?>
            </tbody>
        
        </table>
    </div>





</div>

<?php include './navigation/navEnd.html'; ?>

<div class="modal fade" id="createTeacher" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <?php include 'AddTeacher.php';?>
            </div>
        </div>
    </div>
</div>


</body>
</html>
