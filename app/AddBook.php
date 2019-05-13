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
	
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<?php
  include ("./db/connection/dbConnection.php");
?>
<html lang="en">
  <head>
		<title>Add An Exam and Answer Key</title>
		<?php include './includedFrameworks/bootstrapHead.html';?>
		<link href="./css/studentStatsCard.css" rel="stylesheet">

  </head>

<body>

<?php include './navigation/navBegin.html'; ?>



<div style="padding-top: 15px" class="container">
	<div class="row">
		<h2 >Add An Exam &amp; Answer Key</h2>
	</div>
<br>

	<div class="row">
		<h3>Preliminary General Exam Information:</h3>
	</div>
	<form action="AddExamAndAnswerKey.php" method="post">
		<table align='left'>
  <td style='color:black;'></td>
		<td>Select Grade: <select name="grade" id="grade" required>
			<?php
			echo '<option value="">Select Grade</option>';
			for($i=1; $i<11; $i++ )
			{
				echo '<option value="'.$i.'">'.$i.'</option>';
			}
		?>
	</select>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	Set Exam Date: 
	<select name="month" id="month" required>
			<?php
			$months = array("January","February","March","April","May","June","July","August","September","October","November","December");
			
			echo '<option value="">Select Month</option>';
			for($i=1; $i<(count($months)+1); $i++ )
			{
				echo '<option value="'.$i.'">'.$months[$i-1].'</option>';
			}
		?>
	</select>
	<select name="day" id="day" required>
			<?php
			echo '<option value="">Select Day</option>';
			for($i=1; $i<32; $i++ )
			{
				echo '<option value="'.$i.'">'.$i.'</option>';
			}
		?>
	</select>
	<select name="year" id="year" required>
			<?php
			// Year to start available options at
			  $earliest_year = 1999; 
			  // Set your latest year you want in the range, in this case we use PHP to just set it to the current year.
			  $latest_year = date('Y'); 
			
			echo '<option value="">Select Year</option>';
			foreach ( range( $latest_year, $earliest_year ) as $i ) {
			// Prints the option with the next year in range.
			echo '<option value="'.$i.'">'.$i.'</option>';
			}
		?>
	</select>
		</td>
	<button class="btn btn-info" type ="submit" name="CreateBook" >Done</button>
	</table>
	</form>
	<br>
	<br>
	


</div>

<?php include './navigation/navEnd.html'; ?>



</body>
</html>