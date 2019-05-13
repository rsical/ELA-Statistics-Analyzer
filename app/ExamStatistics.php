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
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<?php
  include ("./db/connection/dbConnection.php");
?>
<html lang="en">
  <head>
		<title>Exam Statistics</title>
		<link href="./css/examTableStyle.css" rel="stylesheet">
		<link href="./css/studentStatsCard.css" rel="stylesheet">
		<?php include './includedFrameworks/bootstrapHead.html';?>
  </head>

<body>

<?php include './navigation/navBegin.html'; ?>

<div style="padding-top: 15px" class="container">

	<div class="row">
		<h2 >Exam Statistics</h2>
	</div>
  <br>
	<div class="row" style="width:800px; margin:0 auto";>

      <form action="ExamStatistics.php" method="POST">
			<table class="table">
			<tr align="center">
			<th  style="">Exam Year</th>
			<?php
			if (isset($_SESSION['isAdmin'])) {
							if ($_SESSION['isAdmin']) {
								?>
			 <th  style="">Scope</th>
			<?php } }?>
			 <th style="">Class</th>
			 <th style="">Students</th>
			 </tr>

			<tr>
			<?php
				if (isset($_SESSION['isTeacher'])) {
						if ($_SESSION['isTeacher']) { ?>
							<td>	<select name="ExamID" id="ExamTeacher" class="chosen-select" required> <?php

							$sqlexamID="SELECT assessment.Date, assessment.ExamID, useraccount.UserID
							FROM assessment
							INNER JOIN classhistory ON classhistory.ClassHistoryID= assessment.ClassHistoryID
							INNER JOIN class ON class.ClassID = classhistory.ClassID
							INNER JOIN teacher ON teacher.TeacherID= class.TeacherID
							INNER JOIN useraccount ON useraccount.UserID = teacher.UserID
							WHERE useraccount.UserID=$currUserID
							GROUP BY DATE;" ;
							$GLOBALS['Yresult'] = $conn->query($sqlexamID) or die('Error showing exam year'.$conn->error);
						}
					}
					if (isset($_SESSION['isAdmin'])) {
							if ($_SESSION['isAdmin']) {?>
						<td>	<select name="ExamID" id="myExamID" class="chosen-select" required> <?php

								$sqlexamYear="SELECT  *
								FROM assessment Group by Date;" ;
								$GLOBALS['Yresult'] =$conn->query($sqlexamYear) or die('Error showing exam year'.$conn->error);

							}
						}
								echo '<option value="">Exam Year</option>';
								while ( $row = mysqli_fetch_array ($Yresult) ) {
									$date = $row["Date"];
									$year = intval($date);
									echo '<option value="'.$row["ExamID"].'|'.$row["Date"].'">'.$year.'</option>';
								}
				?>
						 </select>
	  	</td>

								<?php
				if (isset($_SESSION['isAdmin'])) {
						if ($_SESSION['isAdmin']) { ?>
				<br>
				<td>	<select name="scope" id="ExamScope"  class="chosen-select" required>
				      	<option value="" >Select Scope</option>
							</select>
				</td>
				<?php
				}
			}
			 ?>
				<br>

				<td>	<select name="grade" id="ExamGrade" class="chosen-select" required>
								<option value="">Select Class</option>
							</select>
				</td>
				<br>

				<td>	<select name="student" id="ExamStudent" class="chosen-select" style="width:200px" required >
								<option value="">Select Student</option>
							</select>
				</td>
				<td>
					<center><button class="btn btn-info" type ="submit" name="ViewExamStatistics" >View Statistics </button></center>
				</td>
			</tr>
		 </table>
		 <br><br>
	 </form>
	</div>

	<?php
	if (isset($_POST['ViewExamStatistics']))
  {
		$examAndDate= $_POST['ExamID'];
		$scopeData = $_POST['scope'];
		$myExamData = $_POST['grade'];
		$studentData = $_POST['student']; // it can be school id or student id

		$examData= explode('|', $examAndDate);
	  $exam= $examData[0];
		$Date= $examData[1];
		$GLOBALS['examYear']= intval($Date);

		$myStudentData= explode('|', $studentData);
		$student= $myStudentData[0];
		$schoolID= $myStudentData[1];

		//$myScopeData = explode('|', $scopeData);
		//$GLOBALS['scope']= $myScopeData[2];

		$myScopeData = explode('|', $myExamData);
	  $scope= $myScopeData[0];
	  $class= $myScopeData[3];

	  /*
		$exam= $_POST['ExamID'];
		$sqlTeachers= ("SELECT * FROM assessment WHERE ExamID = '$exam'");
		$scope= $_POST['scope'];
		$classResult= $_POST['grade'];
		$myresult= explode('|', $classResult);
		$schoolID=$myresult[0];
		$class=$myresult[1];
		$student= $_POST['student'];
	*/

		$sqlGetSchool="select School_Name from School where SchoolID='$schoolID';";
		$result = $conn->query($sqlGetSchool) or die('Could not run query: '.$conn->error);
		while ( $row = mysqli_fetch_array ($result) ) {
			$school=$row["School_Name"];
		}

		$sqlStudentName="select Student.StudentID, Student.FName, Student.LName from Student where Student.StudentID='$student';";
		$result = $conn->query($sqlStudentName) or die('Could not run query: '.$conn->error);
		while ( $row = mysqli_fetch_array ($result) ) {
			$studentName= $row["FName"]." ".$row["LName"];
		}

		$info=("select Question.QuestionNumber, Question.Indicator, Question.QuestionText, Question.CorrectAnswer
				from Question
				inner join StudentAnswers on Question.QuestionID=StudentAnswers.QuestionID
				inner join Assessment on StudentAnswers.ExamID=Assessment.ExamID
				inner join Book on Assessment.BookID=Book.BookID
				where Assessment.ExamID='$exam'");					//return exam info
	$result = $conn->query($info) or die('Could not run query: '.$conn->error);
if ($result->num_rows > 0) {
	  $barChartNum=1;
	  $rowNum=1;
	?>

	<div class="container">
		<div class="row">
			<div class="col-sm-6 col-md-6 col-lg-6 mx-auto">
			<div class="card">
				<div class="card-header">
					Exam Overview
				</div>
				<div class="row">
					<div class="col-md-3">
						<i class="far fa-file-alt float-left"></i>
					</div>
					<div class="col-md-8 px-3">
						<div class="card-block px-3">
							<div class="row">
								<p id="headingSpace" class="card-text">Exam Year <h5 class="examHeading"><?=$examYear?></h5></p>
							</div>
							<div class="row">
								<?php
								if ($scope == "school" ) {
										?>
										<p id="headingSpace" class="card-text">School <h5 class="examHeading"><?=$school?></h5></p>
										<?php
								}
								else
								{
									?>
									<p id="headingSpace" class="card-text">Class <h5 class="examHeading"><?=$class?></h5></p>
									<?php
								}
								?>
							</div>
							<?php
							if($student == "ALL") {
							?>
							<div class="row">
								<p id="headingSpace" class="card-text">Students <h5 class="examInfo" style="padding-left:10px;"> ALL</h3></p>
							</div>
							<?php
							}
							else {
								?>
							<div class="row">
								<p id="headingSpace" class="card-text">Student <h5 class="examInfo" style="padding-left:10px;"><?=$studentName?></h3></p>
							</div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<br>




			<div class="row">
			<table id="examTable" class="table">

				<script type="text/javascript">$(document).ready(function() {
				$('[data-toggle="toggle"]').change(function(){
					$(this).parents().next('.hide').toggle();
				});
				});</script>
			<thead>
			 <tr>
			 <th style="width:10%">Question Number</th>
			 <th style="width:15%">Indicator</th>
			 <th style="width:65%">Question</th>
			 <th style="width:10%">Answer</th>
			 </tr>
			</thead>

			<?php
			 while($row = $result->fetch_assoc()){
				 $GLOBALS['qNum'] = $row["QuestionNumber"];
				 $GLOBALS['indicator'] = $row["Indicator"];
				 $GLOBALS['question'] = $row["QuestionText"];
				 $GLOBALS['cAnswer'] = $row["CorrectAnswer"];
				 ?>
				 <tbody>
					 <?php $rowNum++;?>
				 <tr class="labels" data-toggle="collapse" data-target=".questionInfo<?=$rowNum?>" class="accordian-toggle">
					<td><input  style="border:none" name="qNum" type="text"   value="<?= $qNum?>" size="4" readonly></td>
					<td><input  style="border:none" name="indicator" type="text"   value="<?= $indicator?>" readonly></td>
					<td><input  style="border:none" name="question" type="text"   value="<?= $question?>" size="90" readonly></td>
					<td><input  style="border:none" name="cAnswer" type="text"   value="<?= $cAnswer?>" size="4" readonly></td>
				</tr>

				 <?php
				 $test=getPercentage($class, $scope, $qNum, $cAnswer, $student, $examYear);
				$letterChoices="select Choices.Letter
								from Choices
								inner join Question on Choices.QuestionID=Question.QuestionID
								inner join StudentAnswers on Question.QuestionID=StudentAnswers.QuestionID
								inner join Assessment on StudentAnswers.ExamID=Assessment.ExamID
								inner join Book on Assessment.BookID=Book.BookID
								where Assessment.ExamID='$exam' and Question.QuestionNumber='$qNum';";
				$result2 = $conn->query($letterChoices) or die('Could not run query: '.$conn->error);
				$count = 1;
				while($row2 = $result2->fetch_assoc()) {
					$letter = $row2["Letter"];
					$val= $test[$count-1];
					?>
					<tr class="hide">
						<td class="hiddenRow"><div class="accordian-body collapse questionInfo<?=$rowNum?>"> <input style="border:none" name="letter" type="text"   value="<?=$letter?>" size="4" readonly></td>
						<td class="hiddenRow"><div class="accordian-body collapse questionInfo<?=$rowNum?>"><input style="border:none" name="letter" type="text"   value="<?=$val.'%'?>" size="4" readonly></td>
						<td class="hiddenRow"><div class="accordian-body collapse questionInfo<?=$rowNum?>"><canvas width="600" height="200" id="barChart<?=$barChartNum?>"></canvas></td>
						<td class="hiddenRow"><div class="accordian-body collapse questionInfo<?=$rowNum?>"></td>
					</tr>
						<script>
							var correctColor='#2ecc71'; //shade of green
							var incorrectColor='#d63031'; //shade of red
							var ctx = document.getElementById('barChart<?=$barChartNum?>').getContext('2d');
							var chart = new Chart(ctx, {
								// The type of chart we want to create
								type: 'horizontalBar',
								// The data for our dataset
								data: {
									labels: ["<?=$letter?>"],
									datasets: [{
										label: "% of students who chose answer <?=$letter?>",
										<?php if($letter === $cAnswer) { ?>
										backgroundColor: correctColor, //green if correct
										<?php } else{ ?>
										backgroundColor: incorrectColor, //red if incorrect
										<?php } ?>
										data: [<?=$val?>],
									}]
								},
								// Configuration options go here
								options: {
									responsive: false,
									legend: {
										display: true
									},
									scales:
									{
										yAxes: [{
											display: true,
											barThickness: 50
										}],
										xAxes: [{
											display: true,
											barThickness: 10,
											ticks: {
											min: 0,
											max: 100,
											stepSize: 20
											}
										}]
									}
								}
							});
					</script>
							<?php $barChartNum++ ?>

					<?php
					$count++;
				}
			 }
}
				?>

			<?php
			} ?>
			</tbody>
		</table>
		<!-- <script>
			$('#examTable').DataTable( {
			})
		</script>  -->


			 </div>

<?php
function getPercentage (){
	include ("./db/connection/dbConnection.php");
	global $class, $schoolID, $scope, $qNum, $cAnswer, $student, $examYear;
	if($scope != "school")		//if class was selected
					{
						if($student == "ALL")
						{
							/*sets up a count to loop through the following query results 4 times for each student, totaling the people who chose each answer to divide by class
							size and multiply by 100 to get the percentage. Returns an array of percentages with one entry for each letter. */
							$count = 1;
							$choice1 = 0;
							$choice2 = 0;
							$choice3 = 0;
							$choice4 = 0;
							//False is 0
							$correct = 0;
							$letterQuery = "select Student.StudentID, StudentAnswers.LetterAnswer, Choices.Letter, Question.CorrectAnswer, Assessment.ClassSize
							from Question
							inner join Choices on Question.QuestionID=Choices.QuestionID
							inner join StudentAnswers on Choices.QuestionID=StudentAnswers.QuestionID
							inner join Assessment on StudentAnswers.ExamID=Assessment.ExamID
							inner join ClassHistory on Assessment.ClassHistoryID=ClassHistory.ClassHistoryID
							inner join Student on ClassHistory.StudentID=Student.StudentID
							where ClassHistory.ClassID='$class' and StudentAnswers.QuestionNumber='$qNum' and ClassHistory.ClassYear='$examYear';";
							$result3 = $conn->query($letterQuery) or die('Error showing exam year'.$conn->error);
							while ( $row = $result3->fetch_assoc() ) {
								$stuID = $row["StudentID"];
								$lAnswer = $row["LetterAnswer"];
								$qLetter = $row["Letter"];
								$size = $row["ClassSize"];
								if($lAnswer === $qLetter)
								{	$correct = !$correct;	}
								if(($count == 1) && ($correct))
								{	$choice1++;	}
								else if(($count == 2) && ($correct))
								{	$choice2++;}
								else if(($count == 3) && ($correct))
								{	$choice3++;}
								else if(($count == 4) && ($correct))
								{	$choice4++;}
								if($count == 4)
								{ $count = 0; }
								$count++;
								$correct = $correct==1?0:0;
							}
							$Percentage1= round((($choice1 / $size) * 100),2);
							$Percentage2= round((($choice2 / $size) * 100),2);
							$Percentage3= round((($choice3 / $size) * 100),2);
							$Percentage4= round((($choice4 / $size) * 100),2);
							$percentagesA1 = array("$Percentage1", "$Percentage2", "$Percentage3", "$Percentage4");
							return $percentagesA1;
						}
						else {		//one student was selected so retrieve their answer for the indicated question
							/*if the letter was selected, add 100% to array, otherwise add 0%. Return array of percentages with one entry for each letter.*/
							$studentAns = ("select Student.StudentID, StudentAnswers.LetterAnswer, Choices.Letter, Question.CorrectAnswer
							from Question
							inner join Choices on Question.QuestionID=Choices.QuestionID
							inner join StudentAnswers on Choices.QuestionID=StudentAnswers.QuestionID
							inner join Assessment on StudentAnswers.ExamID=Assessment.ExamID
							inner join ClassHistory on Assessment.ClassHistoryID=ClassHistory.ClassHistoryID
							inner join Student on ClassHistory.StudentID=Student.StudentID
							where ClassHistory.ClassID='$class' and StudentAnswers.QuestionNumber='$qNum' and Student.StudentID='$student'");
							$percentagesA2 = array();
							$result4 = $conn->query($studentAns) or die('Error showing exam year'.$conn->error);
							while ( $row = $result4->fetch_assoc() ) {
								$lAnswer = $row["LetterAnswer"];
								$qLetter = $row["Letter"];
								if($lAnswer === $qLetter)
								{	array_push($percentagesA2, 100);	}
								else {
									array_push($percentagesA2, 0);
								}
							}
							return $percentagesA2;
						}
					}
	else {
		if($student == "ALL") {
			/*first have a query to return the total number of students in the school.*/
			$numStu="select distinct Class.ClassID, COUNT(Assessment.ClassSize) as total
				from Assessment
				inner join ClassHistory on Assessment.ClassHistoryID=ClassHistory.ClassHistoryID
				inner join Class on ClassHistory.ClassID=Class.ClassID
				inner join Teacher on Class.TeacherID=Teacher.TeacherID
				where Teacher.SchoolID='$schoolID' and Class.ClassYear='$examYear';";
				$result5 = $conn->query($numStu) or die('Error showing exam year'.$conn->error);
			while ( $row = $result5->fetch_assoc() ) {
				$total = $row["total"];
			}
			/*then sets up a count to loop through the following query results 4 times for each student, totaling the people who chose each answer to divide by class
			size and multiply by 100 to get the percentage. Returns an array of percentages with one entry for each letter.*/
							$count = 1;
							$choice1 = 0;
							$choice2 = 0;
							$choice3 = 0;
							$choice4 = 0;
							$correct = 0;
							$schoolLetterQuery = ("select Student.StudentID, StudentAnswers.LetterAnswer, Choices.Letter, Question.CorrectAnswer, Class.ClassID
											from Question
											inner join Choices on Question.QuestionID=Choices.QuestionID
											inner join StudentAnswers on Choices.QuestionID=StudentAnswers.QuestionID
											inner join Assessment on StudentAnswers.ExamID=Assessment.ExamID
											inner join ClassHistory on Assessment.ClassHistoryID=ClassHistory.ClassHistoryID
											inner join Student on ClassHistory.StudentID=Student.StudentID
											inner join Class on ClassHistory.ClassID=Class.ClassID
											inner join Teacher on Class.TeacherID=Teacher.TeacherID
											where Teacher.SchoolID='$schoolID' and StudentAnswers.QuestionNumber='$qNum' and Class.ClassYear='$examYear'");
						$result6 = $conn->query($schoolLetterQuery) or die('Error showing exam year'.$conn->error);
							while ( $row = $result6->fetch_assoc() ) {
								$stuID = $row["StudentID"];
								$lAnswer = $row["LetterAnswer"];
								$qLetter = $row["Letter"];
								if($lAnswer === $qLetter)
								{	$correct = !$correct;	}
								if(($count == 1) && ($correct))
								{	$choice1++;	}
								else if(($count == 2) && ($correct))
								{	$choice2++;}
								else if(($count == 3) && ($correct))
								{	$choice3++;}
								else if(($count == 4) && ($correct))
								{	$choice4++;}
								if($count == 4)
								{ $count = 0; }
								$count++;
								$correct = $correct==1?0:0;
							}
							$Percentage1= round((($choice1 / $total) * 100),2);
							$Percentage2= round((($choice2 / $total) * 100),2);
							$Percentage3= round((($choice3 / $total) * 100),2);
							$Percentage4= round((($choice4 / $total) * 100),2);
							$percentagesA3 = array("$Percentage1", "$Percentage2", "$Percentage3", "$Percentage4");
							return $percentagesA3;
		}
		else {			//one student was selected so retrieve their answer for the indicated question
			/*if the letter was selected, add 100% to array, otherwise add 0%. Return array of percentages with one entry for each letter.*/
			$schoolStuAns = ("select Student.StudentID, StudentAnswers.LetterAnswer, Choices.Letter, Question.CorrectAnswer
			from Question
			inner join Choices on Question.QuestionID=Choices.QuestionID
			inner join StudentAnswers on Choices.QuestionID=StudentAnswers.QuestionID
			inner join Assessment on StudentAnswers.ExamID=Assessment.ExamID
			inner join ClassHistory on Assessment.ClassHistoryID=ClassHistory.ClassHistoryID
			inner join Student on ClassHistory.StudentID=Student.StudentID
			inner join Class on ClassHistory.ClassID=Class.ClassID
			inner join Teacher on Class.TeacherID=Teacher.TeacherID
			where Teacher.SchoolID='$schoolID' and StudentAnswers.QuestionNumber='$qNum' and Student.StudentID='$student'");
			$percentages = array();
			$result7 = $conn->query($schoolStuAns) or die('Error showing exam year'.$conn->error);
			while ( $row = $result7->fetch_assoc() ) {
				$lAnswer = $row["LetterAnswer"];
				$qLetter = $row["Letter"];
				if($lAnswer === $qLetter)
				{	array_push($percentages, 100);	}
				else {
					array_push($percentages, 0);
				}
			}
			return $percentages;
		}
	}
	}
?>



<?php include './navigation/navEnd.html'; ?>



</body>
</html>
