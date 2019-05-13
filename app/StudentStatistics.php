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
		<title>Student Statistics</title>
		<?php include './includedFrameworks/bootstrapHead.html';?>
		<link href="./css/studentStatsCard.css" rel="stylesheet">
  </head>

<body>

<?php include './navigation/navBegin.html'; ?>

<div style="padding-top: 15px" class="container">

	<div class="row">
		<h2 >Student Statistics</h2>
	</div>
  <br>
	<div class="row" style="width:800px; margin:0 auto;">
		<form action="StudentStatistics.php" method="POST">
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
				<br>

				<?php

				if (isset($_SESSION['isTeacher'])) {
						if ($_SESSION['isTeacher']) { ?>
							<td>	<select name="ExamID" id="ExamIDTeacher" class="examHeading" required> <?php

							$sqlexamY="SELECT assessment.Date, assessment.ExamID, useraccount.UserID
							FROM assessment
							INNER JOIN classhistory ON classhistory.ClassHistoryID= assessment.ClassHistoryID
							INNER JOIN class ON class.ClassID = classhistory.ClassID
							INNER JOIN teacher ON teacher.TeacherID= class.TeacherID
							INNER JOIN useraccount ON useraccount.UserID = teacher.UserID
							WHERE useraccount.UserID=$currUserID
							GROUP BY DATE;" ;
							$GLOBALS['Yresult'] = $conn->query($sqlexamY) or die('Error showing exam year'.$conn->error);
						}
					}
					if (isset($_SESSION['isAdmin'])) {
							if ($_SESSION['isAdmin']) {?>
						<td>	<select name="ExamID" id="ExamID" class="examHeading" required> <?php

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
				<td>	<select name="scope" id="scope" class="examHeading" required>
				      	<option value="" >Select Scope</option>
							</select>
				</td>
				<?php
				}
			}
			 ?>
				<br>
				<td>	<select name="grade" id="grade" class="examHeading" required>
								<option value="">Select Class</option>
							</select>
				</td>
				<br>

				<td>	<select name="myStudent" id="student" class="chosen-select" style="width:200px" required >
								<option value="">Select Student</option>
							</select>
				</td>

				<td>
					<center><button class="btn btn-info" type ="submit" name="ViewStatistics" >View Statistics </button></center>
				</td>
			</tr>
		 </table>
		 <br><br>
	 </form>
	</div>


	<?php

				if(isset($_POST['ViewStatistics'])){
		  	$examAndDate= $_POST['ExamID'];
				$scopeData = $_POST['scope'];
				$myExamData = $_POST['grade'];
				$GLOBALS['studentData'] = $_POST['myStudent']; // it can be school id or student id

				$examData= explode('|', $examAndDate);
			  $GLOBALS['examId']= $examData[0];
				$GLOBALS['examDate']= $examData[1];

				$myStudentData= explode('|', $studentData);
				$GLOBALS['student']= $myStudentData[0];
				$GLOBALS['schoolId']= $myStudentData[1];
				$GLOBALS['myClassId']= $myStudentData[2];


				$myScopeData = explode('|', $myExamData);
			  $GLOBALS['scope']= $myScopeData[0];
				$GLOBALS['categories']= array(1, 2, 3);


	?>

				 <?php
				  foreach($categories as $category){
									$sqlResp="SELECT  Count(assessment.ExamID) as respondents, school.School_Name, book.NumberOfQuestions, assessment.Date, class.Grade
									FROM assessment
									INNER JOIN book book ON book.BookID = assessment.BookID
									INNER JOIN classhistory ON classhistory.ClassHistoryID= assessment.ClassHistoryID
									INNER JOIN student ON student.StudentID = classhistory.StudentID
									INNER JOIN class ON class.ClassID = classhistory.ClassID
									INNER JOIN teacher ON teacher.TeacherID = class.TeacherID
									INNER JOIN school ON school.SchoolID = teacher.SchoolID
									WHERE assessment.Date= '$examDate' and school.SchoolId= '$schoolId';" ;
									$result = $conn->query($sqlResp) or die('Error showing exam year'.$conn->error);

									while ( $row = mysqli_fetch_array ($result) ) {
										$GLOBALS['resp'] = $row["respondents"];
										$GLOBALS ['numQuestions']= $row["NumberOfQuestions"];
										$GLOBALS ['date'] = $row["Date"];
										$GLOBALS ['myGrade'] = $row["Grade"];
										$GLOBALS ['year'] = intval($date);
										$GLOBALS ['schoolName'] = $row["School_Name"];

									}}

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
								<p class="card-text">Exam Year <h5 class="examHeading"><?=$year?></h5></p>
								<p id="headingSpace" class="card-text">Grade <h5 class="examHeading"><?=$myGrade?></h5></p>
							</div>
							<div class="row">
								<p class="card-text"><h5 class="examInfo"><?=$schoolName?></h5></p>
							</div>
							<div class="row">
								<p class="card-text"><h3 class="examInfo"><?=$resp?></h3> Examinees</p>
							</div>
							<div class="row">
								<p class="card-text"><h3 class="examInfo"><?=$numQuestions?></h3> Questions</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>




			<div class="row py-3" style="width:800px; margin:0 auto;">
				<table class="table table-striped">

						<?php

					 if($scope != "school" && $student != "ALL"){ ?>

							 <?php
	 		 								$sqlStudent="SELECT * FROM student
																		WHERE StudentID = '$student';" ;

	 		 								$result = $conn->query($sqlStudent) or die('Error showing exam year'.$conn->error);

	 		 								while ( $row = mysqli_fetch_array ($result) ) {
	 		 									$FName = $row["FName"];
												$LName = $row["LName"];
											  $GLOBALS['myStudentName']= $FName.' '.$LName;
											}
	 		 				?>
										<thead>
											<tr>
												  <th><h3><?= $myStudentName ?>'s Exam Statistics</h3></th>
												  <th></th>

											</tr>
										</thead>



					 <tbody>
						<tr>
						<th>Grade (Minimum Passing Percentage 60%)</th>
						<?php
							$sqlExamPoints="SELECT COUNT(question.points) as totalPoints, school.SchoolID
							FROM question
							INNER JOIN studentanswers ON studentanswers.QuestionID= question.QuestionID
							INNER JOIN assessment ON assessment.ExamID= studentanswers.ExamID
							INNER JOIN book book ON book.BookID = assessment.BookID
		          INNER JOIN classhistory ON classhistory.ClassHistoryID= assessment.ClassHistoryID
		          INNER JOIN student ON student.StudentID = classhistory.StudentID
		          INNER JOIN class ON class.ClassID = classhistory.ClassID
		          INNER JOIN teacher ON teacher.TeacherID = class.TeacherID
		          INNER JOIN school ON school.SchoolID = teacher.SchoolID
		          WHERE school.SchoolID= '$schoolId'
							AND assessment.BookID= (SELECT BookID FROM assessment WHERE ExamID= '$examId');" ;

							$result = $conn->query($sqlExamPoints) or die('Error showing exam year'.$conn->error);
							while ( $row = mysqli_fetch_array ($result) ) {

							$GLOBALS['EPoints']= $row["totalPoints"];
						}


							foreach($categories as $category){
								//Getting number of questions for each categoryPoints
								$sqlExamPoints="SELECT COUNT(studentanswers.QuestionID) AS NumberOfQuestions,  COUNT(question.Points) as totalPoints,
								categories.Category
								FROM categories
								INNER JOIN question ON question.Indicator = categories.Indicator
								INNER JOIN studentanswers ON  studentanswers.QuestionID= question.QuestionID
								INNER JOIN  assessment exam ON studentanswers.ExamID= exam.ExamID
								INNER JOIN book book ON exam.BookID= book.BookID
								INNER JOIN classhistory history ON history.ClassHistoryID= exam.ClassHistoryID
								INNER JOIN  student ON student.StudentID= history.StudentID
								WHERE student.StudentID='$student' AND exam.BookID=(SELECT BookID FROM assessment
								WHERE ExamID= '$examId') AND categories.Category = '$category';" ;


								$result = $conn->query($sqlExamPoints) or die('Error showing category data'.$conn->error);
								while ( $row = mysqli_fetch_array ($result) ) {
									if($category == 1){
										$GLOBALS['numQuestionsOne']= $row["NumberOfQuestions"];
									}
									if($category == 2){
										$GLOBALS['numQuestionsTwo']= $row["NumberOfQuestions"];
									}
									if($category == 3){
										$GLOBALS['numQuestionsThree']= $row["NumberOfQuestions"];
									}

							}
//Getting correct answers for student selected
		 								$sqlCanswers="SELECT studentanswers.QuestionID, COUNT(studentanswers.LetterAnswer) as cAnswers, question.CorrectAnswer, COUNT(question.Points) as Tpoints, categories.Category, question.Indicator
										FROM categories
										INNER JOIN question ON question.Indicator = categories.Indicator
										INNER JOIN studentanswers ON  studentanswers.QuestionID= question.QuestionID
										INNER JOIN  assessment exam ON studentanswers.ExamID= exam.ExamID
										INNER JOIN book book ON exam.BookID= book.BookID
										INNER JOIN classhistory history ON history.ClassHistoryID= exam.ClassHistoryID
										INNER JOIN  student ON student.StudentID= history.StudentID
										WHERE student.StudentID='$student' AND exam.BookID=(SELECT BookID FROM assessment
	                  WHERE ExamID= '$examId') AND studentanswers.LetterAnswer = question.CorrectAnswer AND categories.Category = '$category';" ;

		 								$result = $conn->query($sqlCanswers) or die('Error showing students grade'.$conn->error);

		 								while ( $row = mysqli_fetch_array ($result) ) {
										if($category == 1){
		 								$correctOne = $row["cAnswers"];
										$categoryOneName= $row["Indicator"];

		 							  $pointsOne= $row["Tpoints"];
										}
										if($category == 2){
										$correctTwo = $row["cAnswers"];
										$pointsTwo= $row["Tpoints"];
										$categoryTwoName= $row["Indicator"];
									  }
									  if($category == 3){
									  $correctThree = $row["cAnswers"];
									  $pointsThree= $row["Tpoints"];
										$categoryThreeName= $row["Indicator"];
							    	}
                  }
								}
											$correctA = $correctOne + $correctTwo + $correctThree;
											$points = $pointsOne + $pointsTwo + $pointsThree;
											$Percentage= ($correctA / $numQuestions)*100;
											$Mresult= PassRateSingle($points, $EPoints, $resp);
		 				?>

		 								<td><?=round($Percentage)?>%, <?=$Mresult ?></td>

					</tr>
					<tr>
						<th>Correct Answers</th>
						<td><?= $points?>/<?=$numQuestions?></td>
					</tr>
					<tr>
						<td></td>
						<td><input style="border:none" type="text"   value="<?= $categoryOneName.': '.$pointsOne.' /'.$numQuestionsOne ?>" size="19" readonly></td>
					</tr>
					<tr>
						<td></td>
						<td><input style="border:none" type="text"   value="<?= $categoryTwoName.': '.$pointsTwo.' /'.$numQuestionsTwo ?>" size="19" readonly></td>
					</tr>
					<tr>
						<td></td>
						<td><input style="border:none" type="text"   value="<?= $categoryThreeName.': '.$pointsThree.' /'.$numQuestionsThree ?>" size="19" readonly></td>
					</tr>
					</tbody>
				<?php

		}
						if( $scope == "school" || $student == "ALL"){



								if($scope != "school" && $student == "ALL"){ ?>
									<thead>
										<tr>
											<th><h4>Exam Statistics For All Students In Class</h4></th> <!-- TODO: if class scope: 'Class Exam Statistics', if school scope:'Class Exam Statistics'-->
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
										<th>Highest Grade</th>
									<?php
									$sqlEanswer="SELECT count(studentanswers.QuestionID), studentanswers.LetterAnswer, question.CorrectAnswer, COUNT(question.Points) as Highest, exam.ExamID,
									school.SchoolID
									FROM studentanswers
									INNER JOIN question question ON studentanswers.QuestionID= question.QuestionID
									INNER JOIN  assessment exam ON studentanswers.ExamID= exam.ExamID
									INNER JOIN book book ON exam.BookID= book.BookID
									INNER JOIN classhistory history ON history.ClassHistoryID= exam.ClassHistoryID
									INNER JOIN  student ON student.StudentID= history.StudentID
									INNER JOIN class ON class.ClassID = history.ClassID
									INNER JOIN teacher ON teacher.TeacherID = class.TeacherID
									INNER JOIN school ON school.SchoolID = teacher.SchoolID
									WHERE class.ClassID= '$myClassId' AND exam.BookID=(SELECT BookID FROM assessment
									WHERE ExamID= '$examId') AND studentanswers.LetterAnswer = question.CorrectAnswer
									GROUP BY exam.ExamID
									ORDER BY Highest DESC limit 1;" ;

									$GLOBALS['highestQ'] = $conn->query($sqlEanswer) or die('Error showing exam year'.$conn->error);

								}

								if($scope == "school"){
									?>
										<thead>
											<tr>
												<th><h4>Exam Statistics For All Students In School</h4></th> <!-- TODO: if class scope: 'Class Exam Statistics', if school scope:'Class Exam Statistics'-->
												<th></th>
											</tr>
										</thead>
										<tbody>
											<tr>
											<th>Highest Grade</th>
										<?php
												$sqlEanswer="SELECT count(studentanswers.QuestionID), studentanswers.LetterAnswer, question.CorrectAnswer, COUNT(question.Points) as Highest, exam.ExamID,
												school.SchoolID
												FROM studentanswers
												INNER JOIN question question ON studentanswers.QuestionID= question.QuestionID
												INNER JOIN  assessment exam ON studentanswers.ExamID= exam.ExamID
												INNER JOIN book book ON exam.BookID= book.BookID
												INNER JOIN classhistory history ON history.ClassHistoryID= exam.ClassHistoryID
												INNER JOIN  student ON student.StudentID= history.StudentID
							          INNER JOIN class ON class.ClassID = history.ClassID
							          INNER JOIN teacher ON teacher.TeacherID = class.TeacherID
							          INNER JOIN school ON school.SchoolID = teacher.SchoolID
												WHERE school.SchoolID= '$schoolId' AND exam.BookID=(SELECT BookID FROM assessment
	                    	WHERE ExamID= '$examId') AND studentanswers.LetterAnswer = question.CorrectAnswer
												GROUP BY exam.ExamID
												ORDER BY Highest DESC limit 1;" ;

												$GLOBALS['highestQ']  = $conn->query($sqlEanswer) or die('Error showing exam year'.$conn->error);
											}
												while ( $row = mysqli_fetch_array ($highestQ) ) {
													$highest = $row["Highest"];

								?>
												<td><?=$highest?> Points</td>
							</tr>
					<?php	 }
					?>

					<th>Lowest Grade</th>
					<?php
				    	if($student == "ALL"){
								$sqlEanswer="SELECT count(studentanswers.QuestionID), studentanswers.LetterAnswer, question.CorrectAnswer, COUNT(question.Points) as Highest, exam.ExamID, school.SchoolID
								FROM studentanswers
								INNER JOIN question question ON studentanswers.QuestionID= question.QuestionID
								INNER JOIN  assessment exam ON studentanswers.ExamID= exam.ExamID
								INNER JOIN book book ON exam.BookID= book.BookID
								INNER JOIN classhistory history ON history.ClassHistoryID= exam.ClassHistoryID
								INNER JOIN  student ON student.StudentID= history.StudentID
								INNER JOIN class ON class.ClassID = history.ClassID
								INNER JOIN teacher ON teacher.TeacherID = class.TeacherID
								INNER JOIN school ON school.SchoolID = teacher.SchoolID
								WHERE class.ClassID= '$myClassId' AND exam.BookID=(SELECT BookID FROM assessment
								WHERE ExamID= '$examId') AND studentanswers.LetterAnswer = question.CorrectAnswer
								GROUP BY exam.ExamID
								ORDER BY Highest ASC;" ;
								$GLOBALS ['LowestArray'] = array();

								$GLOBALS ['LowestQ'] = $conn->query($sqlEanswer) or die('Error Showing Lowest Grade'.$conn->error);
							}
								if($scope == "school"){
									$sqlEanswer="SELECT count(studentanswers.QuestionID), studentanswers.LetterAnswer, question.CorrectAnswer, COUNT(question.Points) as Highest, exam.ExamID, school.SchoolID
									FROM studentanswers
									INNER JOIN question question ON studentanswers.QuestionID= question.QuestionID
									INNER JOIN  assessment exam ON studentanswers.ExamID= exam.ExamID
									INNER JOIN book book ON exam.BookID= book.BookID
									INNER JOIN classhistory history ON history.ClassHistoryID= exam.ClassHistoryID
									INNER JOIN  student ON student.StudentID= history.StudentID
									INNER JOIN class ON class.ClassID = history.ClassID
									INNER JOIN teacher ON teacher.TeacherID = class.TeacherID
									INNER JOIN school ON school.SchoolID = teacher.SchoolID
									WHERE school.SchoolID= '$schoolId' AND exam.BookID=(SELECT BookID FROM assessment
									WHERE ExamID= '$examId') AND studentanswers.LetterAnswer = question.CorrectAnswer
									GROUP BY exam.ExamID
									ORDER BY Highest ASC;" ;
									$GLOBALS ['LowestArray'] = array();

									$GLOBALS ['LowestQ'] = $conn->query($sqlEanswer) or die('Error Showing Lowest Grade'.$conn->error);
								}
									while ( $row = mysqli_fetch_array ($LowestQ) ) {
											$LowestArray[]=$row["Highest"];
									}
									$arraySize= COUNT($LowestArray);
									if($arraySize < $resp)
									{
										$GLOBALS['Lowest']= 0;
									}
									else{
										$GLOBALS['Lowest']= $LowestArray[0];
									}

					?>
									<td><?=$Lowest?> Points</td>
				</tr>
		<?php
		?>

		<tr>
		<th>Mean</th>
		<?php

							if($student == "ALL"){
								$classNumber="SELECT COUNT(*) as numStudents
								FROM classhistory
								WHERE ClassID= '$myClassId';" ;

								$result = $conn->query($classNumber) or die('Could not find class size '.$conn->error);
								$row = $result->fetch_assoc();
								$GLOBALS['classSize']= $row["numStudents"];


								$sqlCanswers="SELECT studentanswers.QuestionID, studentanswers.LetterAnswer as cAnswers, question.CorrectAnswer, COUNT(question.Points) as Tpoints, school.SchoolID
								FROM studentanswers
								INNER JOIN question question ON studentanswers.QuestionID= question.QuestionID
								INNER JOIN  assessment exam ON studentanswers.ExamID= exam.ExamID
								INNER JOIN book book ON exam.BookID= book.BookID
								INNER JOIN classhistory history ON history.ClassHistoryID= exam.ClassHistoryID
								INNER JOIN  student ON student.StudentID= history.StudentID
								INNER JOIN class ON class.ClassID = history.ClassID
								INNER JOIN teacher ON teacher.TeacherID = class.TeacherID
								INNER JOIN school ON school.SchoolID = teacher.SchoolID
								WHERE class.ClassID= '$myClassId' AND exam.BookID=(SELECT BookID FROM assessment
								WHERE ExamID= '$examId') AND studentanswers.LetterAnswer = question.CorrectAnswer;" ;

								$GLOBALS['MeanQ'] = $conn->query($sqlCanswers) or die('Error showing exam year'.$conn->error);
							}
							if($scope == "school"){
							$sqlCanswers="SELECT studentanswers.QuestionID, studentanswers.LetterAnswer as cAnswers, question.CorrectAnswer, COUNT(question.Points) as Tpoints, school.SchoolID
							FROM studentanswers
							INNER JOIN question question ON studentanswers.QuestionID= question.QuestionID
							INNER JOIN  assessment exam ON studentanswers.ExamID= exam.ExamID
							INNER JOIN book book ON exam.BookID= book.BookID
							INNER JOIN classhistory history ON history.ClassHistoryID= exam.ClassHistoryID
							INNER JOIN  student ON student.StudentID= history.StudentID
							INNER JOIN class ON class.ClassID = history.ClassID
							INNER JOIN teacher ON teacher.TeacherID = class.TeacherID
							INNER JOIN school ON school.SchoolID = teacher.SchoolID
							WHERE school.SchoolID= '$schoolId' AND exam.BookID=(SELECT BookID FROM assessment
							WHERE ExamID= '$examId') AND studentanswers.LetterAnswer = question.CorrectAnswer;" ;

							$GLOBALS['MeanQ'] = $conn->query($sqlCanswers) or die('Error showing exam year'.$conn->error);
						}
						while ( $row = mysqli_fetch_array ($MeanQ) ) {
							$correctA = $row["cAnswers"];
							$points= $row["Tpoints"];

							if($student =="ALL"){
								$GLOBALS['Average'] = $points / $classSize;
							}
							if($scope =="school"){
							$GLOBALS['Average'] = $points / $resp;
						}

		?>
						<td><?= number_format((float)$Average,2, '.', ' ')?> Points</td>
	</tr>

	<?php }
			?>

			<tr>
			<th>Median</th>
			<?php
							if($student =="ALL"){
								$sqlCanswers="SELECT count(studentanswers.QuestionID), studentanswers.LetterAnswer, question.CorrectAnswer, COUNT(question.Points) as grades, exam.ExamID, school.SchoolID
								FROM studentanswers
								INNER JOIN question question ON studentanswers.QuestionID= question.QuestionID
								INNER JOIN  assessment exam ON studentanswers.ExamID= exam.ExamID
								INNER JOIN book book ON exam.BookID= book.BookID
								INNER JOIN classhistory history ON history.ClassHistoryID= exam.ClassHistoryID
								INNER JOIN  student ON student.StudentID= history.StudentID
								INNER JOIN class ON class.ClassID = history.ClassID
								INNER JOIN teacher ON teacher.TeacherID = class.TeacherID
								INNER JOIN school ON school.SchoolID = teacher.SchoolID
								WHERE class.ClassID= '$myClassId' AND exam.BookID=(SELECT BookID FROM assessment
								WHERE ExamID= '$examId') AND studentanswers.LetterAnswer = question.CorrectAnswer
								GROUP BY exam.ExamID
								ORDER BY grades ASC;" ;

								$GLOBALS['MedianQ']= $conn->query($sqlCanswers) or die('Error showing exam year'.$conn->error);
							}

							if($scope =="school"){
							$sqlCanswers="SELECT count(studentanswers.QuestionID), studentanswers.LetterAnswer, question.CorrectAnswer, COUNT(question.Points) as grades, exam.ExamID, school.SchoolID
							FROM studentanswers
							INNER JOIN question question ON studentanswers.QuestionID= question.QuestionID
							INNER JOIN  assessment exam ON studentanswers.ExamID= exam.ExamID
							INNER JOIN book book ON exam.BookID= book.BookID
							INNER JOIN classhistory history ON history.ClassHistoryID= exam.ClassHistoryID
							INNER JOIN  student ON student.StudentID= history.StudentID
							INNER JOIN class ON class.ClassID = history.ClassID
							INNER JOIN teacher ON teacher.TeacherID = class.TeacherID
							INNER JOIN school ON school.SchoolID = teacher.SchoolID
							WHERE school.SchoolID= '$schoolId' AND exam.BookID=(SELECT BookID FROM assessment
							WHERE ExamID= '$examId') AND studentanswers.LetterAnswer = question.CorrectAnswer
							GROUP BY exam.ExamID
							ORDER BY grades ASC;" ;

							$GLOBALS['MedianQ'] = $conn->query($sqlCanswers) or die('Error showing exam year'.$conn->error);
							}
								$GLOBALS ['GradesArray'] = array();
							while ( $row = mysqli_fetch_array ($MedianQ) ) {

								$GradesArray[]=$row["grades"];
					 }

							Arsort($GradesArray);

								$GLOBALS['sdv']=Stand_Deviation($GradesArray);
								$GLOBALS['Median']=FindMedian($GradesArray);
								$GLOBALS['myMode']=FindMode($GradesArray);

								//copying mode array in a variable
											$s= ", ";
											foreach ($myMode as $val){
												if(count($myMode)> 1)
											$GLOBALS['Mode'] = $val.$s.$Mode ;
											else
											$GLOBALS['Mode'] = $val;
										 }
			?>
							<td><?= $Median?> Points</td>
		</tr>
		<tr>
			<th>Mode</th>
			<td><?= $Mode?> Points</td>
		</tr>
		<tr>
			<th>Standard Deviation, σ</th>
			<td><?= number_format((float)$sdv,2, '.', ' ')?></td>
		</tr>

		<tr>
				<th>Pass Rate (Minimum Passing Percentage 60%)</th>
				<?php
				if($student == "ALL"){
								$sqlExamPoints="SELECT COUNT(question.points) as totalPoints, school.SchoolID
								FROM question
								INNER JOIN studentanswers ON studentanswers.QuestionID= question.QuestionID
								INNER JOIN assessment ON assessment.ExamID= studentanswers.ExamID

								INNER JOIN book book ON assessment.BookID= book.BookID
								INNER JOIN classhistory history ON history.ClassHistoryID= assessment.ClassHistoryID
								INNER JOIN  student ON student.StudentID= history.StudentID
								INNER JOIN class ON class.ClassID = history.ClassID
								INNER JOIN teacher ON teacher.TeacherID = class.TeacherID
								INNER JOIN school ON school.SchoolID = teacher.SchoolID
								WHERE class.ClassID= '$myClassId' AND assessment.BookID= (SELECT BookID FROM assessment WHERE ExamID= '$examId');" ;

								$GLOBALS['PassQ'] = $conn->query($sqlExamPoints) or die('Error showing exam year'.$conn->error);
				}
				if($scope == "school"){
								$sqlExamPoints="SELECT COUNT(question.points) as totalPoints, school.SchoolID
								FROM question
								INNER JOIN studentanswers ON studentanswers.QuestionID= question.QuestionID
								INNER JOIN assessment ON assessment.ExamID= studentanswers.ExamID

								INNER JOIN book book ON assessment.BookID= book.BookID
								INNER JOIN classhistory history ON history.ClassHistoryID= assessment.ClassHistoryID
								INNER JOIN  student ON student.StudentID= history.StudentID
								INNER JOIN class ON class.ClassID = history.ClassID
								INNER JOIN teacher ON teacher.TeacherID = class.TeacherID
								INNER JOIN school ON school.SchoolID = teacher.SchoolID
								WHERE school.SchoolID= '$schoolId' AND assessment.BookID= (SELECT BookID FROM assessment WHERE ExamID= '$examId');" ;

								$GLOBALS['PassQ'] = $conn->query($sqlExamPoints) or die('Error showing exam year'.$conn->error);
					}
								while ( $row = mysqli_fetch_array ($PassQ) ) {

									$GLOBALS['ExamPoints']= $row["totalPoints"];
								}

								if($student == "ALL"){
									$GLOBALS['pass']= PassRate($GradesArray, $ExamPoints, $classSize);
									$GLOBALS['minPercentage']= ($pass/$classSize) * 100;
								}
								if($scope == "school"){
									$GLOBALS['pass']= PassRate($GradesArray, $ExamPoints, $resp);
									$GLOBALS['minPercentage']= ($pass/$resp) * 100;
								}



				?>

								<td><?= round($minPercentage)?>% Of Students Passed The Test</td>
			</tr>




						<tr>
								<th>Easiest Question</th>
		  					<?php
								if($student =="ALL"){
									$sqlEanswer="SELECT count(studentanswers.QuestionID) as total, studentanswers.LetterAnswer as cAnswers, question.CorrectAnswer, studentanswers.QuestionNumber, school.SchoolID
									FROM studentanswers
									INNER JOIN question question ON studentanswers.QuestionID= question.QuestionID
									INNER JOIN  assessment exam ON studentanswers.ExamID= exam.ExamID
									INNER JOIN book book ON exam.BookID= book.BookID
									INNER JOIN classhistory history ON history.ClassHistoryID= exam.ClassHistoryID
									INNER JOIN  student ON student.StudentID= history.StudentID

									INNER JOIN class ON class.ClassID = history.ClassID
									INNER JOIN teacher ON teacher.TeacherID = class.TeacherID
									INNER JOIN school ON school.SchoolID = teacher.SchoolID
									WHERE class.ClassID= '$myClassId' AND exam.BookID=(SELECT BookID FROM assessment
									WHERE ExamID= '$examId') AND studentanswers.LetterAnswer = question.CorrectAnswer
									GROUP BY studentanswers.QuestionID
									ORDER BY COUNT(studentanswers.QuestionID) DESC LIMIT 1;" ;

									$GLOBALS['easiestQ'] = $conn->query($sqlEanswer) or die('Error showing exam year'.$conn->error);

								}
								if($scope == "school"){
		  	 								$sqlEanswer="SELECT count(studentanswers.QuestionID) as total, studentanswers.LetterAnswer as cAnswers, question.CorrectAnswer, studentanswers.QuestionNumber, school.SchoolID
												FROM studentanswers
												INNER JOIN question question ON studentanswers.QuestionID= question.QuestionID
												INNER JOIN  assessment exam ON studentanswers.ExamID= exam.ExamID
												INNER JOIN book book ON exam.BookID= book.BookID
												INNER JOIN classhistory history ON history.ClassHistoryID= exam.ClassHistoryID
												INNER JOIN  student ON student.StudentID= history.StudentID

												INNER JOIN class ON class.ClassID = history.ClassID
												INNER JOIN teacher ON teacher.TeacherID = class.TeacherID
												INNER JOIN school ON school.SchoolID = teacher.SchoolID
												WHERE school.SchoolID= '$schoolId' AND exam.BookID=(SELECT BookID FROM assessment
	                    	WHERE ExamID= '$examId') AND studentanswers.LetterAnswer = question.CorrectAnswer
												GROUP BY studentanswers.QuestionID
												ORDER BY COUNT(studentanswers.QuestionID) DESC LIMIT 1;" ;

		  	 								$GLOBALS['easiestQ'] = $conn->query($sqlEanswer) or die('Error showing exam year'.$conn->error);
											}
		  	 								while ( $row = mysqli_fetch_array ($easiestQ) ) {
		  	 									$question = $row["QuestionNumber"];
													$total= $row["total"];


													if($student == "ALL"){
															$GLOBALS['easyPercentage']= ($total / $classSize) * 100;

													}
													if($scope == "school"){
															$GLOBALS['easyPercentage']= ($total / $resp) * 100;
													}

		  	 				?>

												<td>Question Number <?= $question?>
												<br>
												<?= round($easyPercentage)?>% of Students Got It Right</td>
		  				</tr>
		 		<?php	 }
			 ?>
			 <tr>
			 <th>Hardest Question</th>
			 <?php
			 	if($student == "ALL"){
							$sqlEanswer="SELECT count(studentanswers.QuestionID) as total, studentanswers.LetterAnswer as cAnswers, question.CorrectAnswer, studentanswers.QuestionNumber, school.SchoolID
							FROM studentanswers
							INNER JOIN question question ON studentanswers.QuestionID= question.QuestionID
							INNER JOIN  assessment exam ON studentanswers.ExamID= exam.ExamID
							INNER JOIN book book ON exam.BookID= book.BookID
							INNER JOIN classhistory history ON history.ClassHistoryID= exam.ClassHistoryID
							INNER JOIN  student ON student.StudentID= history.StudentID
							INNER JOIN class ON class.ClassID = history.ClassID
							INNER JOIN teacher ON teacher.TeacherID = class.TeacherID
							INNER JOIN school ON school.SchoolID = teacher.SchoolID
							WHERE class.ClassID= '$myClassId'  AND exam.BookID=(SELECT BookID FROM assessment
							WHERE ExamID= '$examId') AND studentanswers.LetterAnswer = question.CorrectAnswer
							GROUP BY studentanswers.QuestionID
							ORDER BY COUNT(studentanswers.QuestionID) ASC LIMIT 1;" ;

							$GLOBALS['hardestQ'] = $conn->query($sqlEanswer) or die('Error showing exam year'.$conn->error);
				}

				if($scope =="school"){
			 				$sqlEanswer="SELECT count(studentanswers.QuestionID) as total, studentanswers.LetterAnswer as cAnswers, question.CorrectAnswer, studentanswers.QuestionNumber, school.SchoolID
			 				FROM studentanswers
			 				INNER JOIN question question ON studentanswers.QuestionID= question.QuestionID
			 				INNER JOIN  assessment exam ON studentanswers.ExamID= exam.ExamID
			 				INNER JOIN book book ON exam.BookID= book.BookID
			 				INNER JOIN classhistory history ON history.ClassHistoryID= exam.ClassHistoryID
			 				INNER JOIN  student ON student.StudentID= history.StudentID
							INNER JOIN class ON class.ClassID = history.ClassID
							INNER JOIN teacher ON teacher.TeacherID = class.TeacherID
							INNER JOIN school ON school.SchoolID = teacher.SchoolID
							WHERE school.SchoolID= '$schoolId' AND exam.BookID=(SELECT BookID FROM assessment
			 				WHERE ExamID= '$examId') AND studentanswers.LetterAnswer = question.CorrectAnswer
			 				GROUP BY studentanswers.QuestionID
			 				ORDER BY COUNT(studentanswers.QuestionID) ASC LIMIT 1;" ;

			 				$GLOBALS['hardestQ'] = $conn->query($sqlEanswer) or die('Error showing exam year'.$conn->error);
						}
			 				 while ( $row = mysqli_fetch_array ($hardestQ) ) {
			 				 $question = $row["QuestionNumber"];
			 				 $total= $row["total"];

							 if($student == "ALL"){
									 $GLOBALS['hardPercentage']= ($total / $classSize) * 100;

							 }
							 if($scope == "school"){
									 $GLOBALS['hardPercentage']= ($total / $resp) * 100;
							 }

						//	 $Percentage= ($total / $resp) * 100;

			 ?>
			 				<td>Question Number <?= $question?>
			 				<br>
			 				<?= round($hardPercentage)?>% of Students Got It Right</td>
				 </tr>
			 <?php	 }
			 }
		 }?>
	</tbody>
	</table>
	</div>
	<?php
	function Stand_Deviation($arr)
	    {
	        $num_of_elements = count($arr);

	        $variance = 0.0;

	                // calculating mean using array_sum() method
	        $average = array_sum($arr)/$num_of_elements;

	        foreach($arr as $i)
	        {
	            // sum of squares of differences between
	                        // all numbers and means.
	            $variance += pow(($i - $average), 2);
	        }

	        return (float)sqrt($variance/$num_of_elements);
	    }


			function FindMedian($arr) {
			    $count = count($arr); //total numbers in array
			    $midNumber = floor(($count-1)/2); // find the middle value, or the lowest middle value
			    if($count % 2) { // odd number, middle is the median
			        $median = $arr[$midNumber];
			    } else { // even number, calculate avg of 2 medians
			        $low = $arr[$midNumber];
			        $high = $arr[$midNumber+1];
			        $median = (($low+$high)/2);
			    }
			    return $median;
			}

			function FindMode($arr) {
			  $values = array();
			  foreach ($arr as $v) {
			    if (isset($values[$v])) {
			      $values[$v] ++;
			    } else {
			      $values[$v] = 1;  // counter of appearance
			    }
			  }
			  arsort($values);  // sort the array by values, in non-ascending order.
			  $modes = array();
			  $x = $values[key($values)]; // get the most appeared counter
			  reset($values);
			  foreach ($values as $key => $v) {
			    if ($v == $x) {   // if there are multiple 'most'
			      $modes[] = $key;  // push to the modes array
			    } else {
			      break;
			    }
			  }
			  return $modes;
			}

	function PassRate($arr, $Epoints, $respondents){



		$total= 0;
		$grade= ($Epoints/$respondents);
		$passGrade = $grade * 0.60;
	  $arrLength = count($arr);

		for($x=0; $x<$arrLength; $x++){
			if($arr[$x] > $passGrade)
				$total= $total +1;
		}
		return $total;
	}

	function PassRateSingle($StudentGrade, $Allpoints, $respondents){
		$myGrade= ($Allpoints/$respondents);
		$passGrade = $myGrade * 0.60;
	 	$result= "Unsatisfactory!";
			if($StudentGrade >= $passGrade){
				$result= "Satisfactory!";
		}

		return $result;

	}
		 ?>

<?php include './navigation/navEnd.html'; ?>



</body>
</html>
