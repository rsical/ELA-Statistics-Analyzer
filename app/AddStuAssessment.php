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

	if (isset($_SESSION['isTeacher'])) {
		if (!$_SESSION['isTeacher']) {
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
		<title>Upload Student Results</title>
		<link href="./css/examTableStyle.css" rel="stylesheet">
		<?php include './includedFrameworks/bootstrapHead.html';?>



  </head>

<body>

<?php include './navigation/navBegin.html'; ?>

<div style="padding-top: 15px" class="container">

	<div class="row">
		<h2 >Upload Student Results</h2>
	</div>
<br>
<div class="row" style="width:800px; margin:0 auto"; >

      <form action="AddStuAssessment.php" method="POST">
			<table class="table">
			<tr align="center">
			<th style="">Exam Year</th>
			<th style="">Students</th>
			 </tr>

			<tr>
				<td>	<select name="ExamY" id="selectExamYear" required>
					<?php
					$sqlexamY="SELECT  *
					FROM assessment Group by Date;" ;
					$result = $conn->query($sqlexamY) or die('Error showing exam year'.$conn->error);
							echo '<option value="">Exam Year</option>';
							while ( $row = mysqli_fetch_array ($result) ) {
								$date=$row["Date"];
								$year=intval($date);
									echo '<option value='.$row["Date"].'>'.$year.'</option>';

							}
				?>
			</select>
	  	</td>
				<td>	<select name="student" id="examStudent" required>	
							<option value="">Select Student</option>				
						</select>
				</td>
				<td>
					<center><button class="btn btn-info" type ="submit" name="SelectStudent">Select Student</button></center>
				</td>
			</tr>
		 </table>
		 <br><br>
	 </form>
	</div>
	
	


<div class="row">
	<table id="assessmentTable" class="table" style="width:800px; margin:0 auto"; >
	<form action="AddStuAssessment.php" method="POST">
				
<?php
	if (isset($_POST['SelectStudent'])) {
	 		 	$date= $_POST['ExamY'];
				$year=intval($date);
				$student = $_POST['student'];
				
				
				$sqlStudent="SELECT * FROM student WHERE StudentID = '$student';" ;

				$result = $conn->query($sqlStudent) or die('Error showing exam year'.$conn->error);

				while ( $row = mysqli_fetch_array ($result) ) {
					$FName = $row["FName"];
					$LName = $row["LName"];
				}
?>
		<thead>
			<tr>
				  <th colspan="3"><h3><?= $FName ?> <?= $LName ?>'s Assessment for the <?= $year ?> ELA Exam</h3></th>
			</tr>
			<tr>
				  <th style="width:5%">Question Number</th>
				  <th style="width:65%">Question</th>
				  <th style="width:30%">Student Answer</th>
			</tr>
		</thead>
				
				
		<tbody>
		
		<input hidden style="border:none" name="yr" type="text"   value="<?= $date?>" size="4" readonly>
		<?php
		$test=getExamQuestions($year);
	
		$length = count($test);
		
		$i = 0;		  
		  ?>
		  
			<tr>
				<td><input style="border:none" name="qNum1" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question1" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans1" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans1" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans1" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans1" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum2" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question2" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans2" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans2" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans2" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans2" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum3" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question3" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans3" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans3" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans3" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans3" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum4" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question4" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans4" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans4" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans4" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans4" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum5" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question5" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans5" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans5" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans5" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans5" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
		  
			<tr>
				<td><input style="border:none" name="qNum6" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question6" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans6" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans6" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans6" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans6" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum7" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question7" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans7" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans7" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans7" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans7" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum8" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question8" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans8" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans8" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans8" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans8" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum9" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question9" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans9" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans9" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans9" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans9" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum10" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question10" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans10" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans10" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans10" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans10" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum11" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question11" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans11" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans11" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans11" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans11" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum12" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question12" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans12" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans12" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans12" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans12" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum13" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question13" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans13" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans13" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans13" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans13" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum14" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question14" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans14" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans14" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans14" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans14" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum15" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question15" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans15" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans15" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans15" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans15" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum16" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question16" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans16" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans16" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans16" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans16" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum17" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question17" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans17" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans17" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans17" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans17" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum18" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question18" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans18" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans18" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans18" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans18" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum19" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question19" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans19" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans19" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans19" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans19" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum20" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question20" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans20" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans20" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans20" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans20" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum21" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question21" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans21" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans21" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans21" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans21" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum22" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question22" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans22" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans22" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans22" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans22" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum23" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question23" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans23" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans23" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans23" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans23" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum24" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question24" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans24" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans24" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans24" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans24" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum25" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question25" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans25" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans25" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans25" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans25" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum26" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question26" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans26" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans26" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans26" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans26" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum27" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question27" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans27" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans27" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans27" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans27" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
			
			<tr>
				<td><input style="border:none" name="qNum28" type="text"   value="<?= $test[$i]?>" size="4" readonly></td>
				<?php $i++; ?>
				<td><input style="border:none" name="question28" type="text"   value="<?= $test[$i]?>" size="90" readonly></td>
				<?php $i++; 
				if ($test[$i]=="A") {
					$c1="A";
					$c2="B";
					$c3="C";
					$c4="D";
				}
				else if ($test[$i]=="F"){
					$c1="F";
					$c2="G";
					$c3="H";
					$c4="J";
				}
				?>
				<td>
					<input type="radio" name="ans28" value="<?= $c1?>"> <?= $c1?>
					<input type="radio" name="ans28" value="<?= $c2?>"> <?= $c2?>
					<input type="radio" name="ans28" value="<?= $c3?>"> <?= $c3?>
					<input type="radio" name="ans28" value="<?= $c4?>"> <?= $c4?>
				</td>
				<?php $i++; ?>
			</tr>
		<!--end of exam input part-->
		
			
			
			
			
			<tr><td></td><td><center><button class="btn btn-info" type ="submit" name="CreateAssessment">Create Assessment</button></center></td></tr>
		</tbody>
<?php	}	?>	
		</form>	
	</table>
</div>	

<?php
if (isset($_POST['CreateAssessment'])) {
	$date= $_POST['yr'];
	$year= intval($date);
	
	$sqlAssessment="INSERT INTO Assessment (BookID,ClassHistoryID,Date,ClassSize)
	VALUES((select BookID from Book where year(Year)='$year'),
	(select ClassHistory.ClassHistoryID
	from ClassHistory
	inner join Class on ClassHistory.ClassHistoryID=Class.ClassID
	inner join Teacher on Class.TeacherID=Teacher.TeacherID
	inner join UserAccount on Teacher.UserID=UserAccount.UserID
	where UserAccount.UserID='$currUserID' and Class.ClassYear='$year'),
	'$date',(select Class.Size
			from Class
			inner join Teacher on Class.TeacherID=Teacher.TeacherID
			inner join UserAccount on Teacher.UserID=UserAccount.UserID
			where Class.ClassYear='$year' and UserAccount.UserID='$currUserID'));";
	
	
	if($conn->query($sqlAssessment) == TRUE){
		echo "Student Assessment Created Successfully. ";
	}
	else
		echo "Error encountered when creating Student Assessment. ";
	
	
	$sqlQ="select distinct Question.QuestionNumber, Question.QuestionID
				from Question
				inner join StudentAnswers on Question.QuestionID=StudentAnswers.QuestionID
				inner join Assessment on StudentAnswers.ExamID=Assessment.ExamID
				inner join Book on Assessment.BookID=Book.BookID
				where Book.Year=(select Year from Book where year(Year)='$year');";
				
	$result2 = $conn->query($sqlQ) or die('Error showing exam year'.$conn->error);
	
	$qNumArr = array();
	$qIDArr = array();

	while ( $row2 = mysqli_fetch_array ($result2) ) {
		$qNum = $row2["QuestionNumber"];
		$qID = $row2["QuestionID"];
		
		array_push($qNumArr,$qNum);
		array_push($qIDArr,$qID);
	}
	
	$AnsArr = array();
	
	for ($i = 0; $i < 28; $i++) {
		
		if( isset($_POST["ans".($i+1)]) ) {
			$AnsArr[$i] = $_POST["ans".($i+1)];		//add student's letter answer to array of answers
		}
		else
			$AnsArr[$i] = "";							//add a placeholder
		
	}
	
	$length = count($qNumArr);
	
	for ($i = 0; $i < $length; $i++) {
		$sqlStuAns="INSERT INTO StudentAnswers (ExamID,QuestionID,QuestionNumber,LetterAnswer) VALUES((select max(ExamID) from Assessment),'$qIDArr[$i]','$qNumArr[$i]','$AnsArr[$i]');";
		
		if($conn->query($sqlStuAns) == TRUE){
			}
		else
			echo "Error encountered when trying to add Student Answers.";
		
		
		if($i==($length-1)) {
			echo "<br>Student Answers Successfully Added to Database.";
			
		}
		
		
	}
	
}
?>
	
	
	
	
	
</div>





<?php
function getExamQuestions (){
	include ("./db/connection/dbConnection.php");
	global $year;
	$examArr = array();
	
	$sqlExam="select distinct Question.QuestionText, Question.QuestionNumber, Question.CorrectAnswer, Book.Year
				from Question
				inner join StudentAnswers on Question.QuestionID=StudentAnswers.QuestionID
				inner join Assessment on StudentAnswers.ExamID=Assessment.ExamID
				inner join Book on Assessment.BookID=Book.BookID
				where Book.Year=(select Year from Book where year(Year)='$year');";
			
			$result2 = $conn->query($sqlExam) or die('Error showing exam year'.$conn->error);

			while ( $row2 = mysqli_fetch_array ($result2) ) {
				 $qNum = $row2["QuestionNumber"];
				 $date = $row2["Year"];
				 $question = $row2["QuestionText"];
				 $cAnswer = $row2["CorrectAnswer"];
				 
				 
				 array_push($examArr,$qNum);
				 array_push($examArr,$question);
				 
				 if($cAnswer=="A" || $cAnswer=="B" || $cAnswer=="C" || $cAnswer=="D") {
					 array_push($examArr,"A");
				 }
				 else if ($cAnswer=="F" || $cAnswer=="G" || $cAnswer=="H" || $cAnswer=="J") {
					 array_push($examArr,"F");
				 }
				 else
					 array_push($examArr,"0");
				
			}
			return $examArr;
	
}
	
	
	
?>







<?php include './navigation/navEnd.html'; ?>



</body>
</html>