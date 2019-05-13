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

<body onunload="clearSession()">

<?php include './navigation/navBegin.html'; ?>


<?php
if (isset($_POST['CreateBook']))
  {
		$GLOBALS['grade'] = $_POST['grade'];
		$month = $_POST['month'];
		$day = $_POST['day'];
		$year = $_POST['year'];
		$month_padded = sprintf("%02d", $month);
		$day_padded = sprintf("%02d", $day);
		
		$date=$year . $month_padded . $day_padded;
					
		$newBook=("INSERT INTO Book (Name,NumberOfQuestions,BookNumber,Grade,Year) VALUES('English Language Arts',28,1,'$grade','$date')");
		if($conn->query($newBook) == TRUE){
			?>

			<?php
			//echo "Preliminary exam information stored. Please continue by adding a story. ";
			$sqlBook="SELECT BookID from Book
				where Grade=$grade and Year=$year;" ;
				$result = $conn->query($sqlBook) or die('Could not find user id: '.$conn->error);
				$row = $result->fetch_assoc();
				$bookID=$row["BookID" ];
		}
		else
			echo "Error";
}      //old end of prelim info isset

if (!isset($_SESSION['examGrade'])) {
    $_SESSION['examGrade'] = $grade;
	$_SESSION['examMonth'] = $month;
	$_SESSION['examDay'] = $day;
	$_SESSION['examYear'] = $year;
}
	?>
	
	<div class="container">
		<div class="row">
			<div class="col-sm-6 col-md-6 col-lg-6 mx-auto">
			<div class="card">
				<div class="card-header">
					Exam-to-be-created Overview
				</div>
				<div class="row">
					<div class="col-md-3">
						<i class="far fa-file-alt float-left"></i>
					</div>
					<div class="col-md-8 px-3">
						<div class="card-block px-3">
							<div class="row">
								<p class="card-text">Exam Grade <h5 class="examHeading"><?=$_SESSION['examGrade']?></h5></p>
							</div>
							<div class="row">
								<p class="card-text">Exam Date <h3 class="examHeading"><?=$_SESSION['examMonth']."/".$_SESSION['examDay']."/".$_SESSION['examYear']?></h3></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<br>

<form method="post"><button class="btn btn-info" type ="submit" name="addStory" >Add Story Information</button><br><br></form>

<?php	
if (isset($_POST['addStory']))
  { 
?>
<form method="post">
<div class="row">
	<h3>Create Story:</h3>
</div>
    Title:<b>*</b> <input type="text" name="title" id="title" value="" required>&nbsp;&nbsp;&nbsp;
    Author's Full Name:<b>*</b> <input type="text" name="authorName" id="authorName" value="" required>&nbsp;&nbsp;&nbsp;
	<br><br>
	<b>*Required fields</b><br><br>
		<button class="btn btn-info" type ="submit" name="createStories" >Save Story Information</button>
</form>
<br><br><br><br>
<?php
  }
?>
  
  
<?php
if (isset($_POST['createStories'])) {
	$title = $_POST['title'];
	$author = $_POST['authorName'];
	
	$sqlCreateStory=("INSERT INTO Story (Title,Author) VALUES('$title','$author');");	
	
	if($conn->query($sqlCreateStory) == TRUE){
			echo "<br>Story Information Successfully Saved. Now create all questions for this story before creating another story.<br><br><br>";
		}
		else
			echo "Error.";
		
	if (!isset($_SESSION['storyCreated'])) {
		$_SESSION['storyCreated'] = 1;
	}
}  //old end of create story isset

if (isset($_SESSION['storyCreated']))  {
?>

<form method="post"><button class="btn btn-info" type ="submit" name="addQuestion" >Add a Question</button></form>

<?php
if (isset($_POST['addQuestion'])) {
	if (!isset($_SESSION['myCount']))
	{
	  $_SESSION['myCount'] = 1;
	}
	else
	{
	  $_SESSION['myCount']++;
	}
	
	if($_SESSION['myCount'] == 29) {
		$_SESSION['myCount'] = 1;
	}
	?>
<form action="AddExamAndAnswerKey.php" method="post">
<br>
<div class="row">
<h3>Create Question:</h3>
</div>
	Question Number:<b>*</b><input type="text" name="qNum" id="qNum" value="<?=$_SESSION['myCount']?>" placeholder="<?=$_SESSION['myCount']?>" size="1"><br>
	Standard (std):<b>*</b>
	<input type="radio" id="1" name="std" value="1" required> 1
	<input type="radio" id="2" name="std" value="2"> 2
	<input type="radio" id="3" name="std" value="3"> 3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	Performance Indicator:<b>*</b>
	<select name="indicator" id="indicator" required>
			<?php
			$sqlIndicator="select distinct Indicator from Categories;" ;
			$result = $conn->query($sqlIndicator) or die('Error showing school names'.$conn->error);
			echo '<option value="">Select Indicator</option>';
					while ( $row = mysqli_fetch_array ($result) ) {
						
							echo '<option value="'.$row["Indicator"].'">'.$row["Indicator"].'</option>';
					}
		?>
	</select>&nbsp;&nbsp;&nbsp;
	Correct Answer:<b>*</b>
	<select name="cAnswer" id="cAnswer" required>
		<?php
			echo '<option value="">Select Letter</option>';
			echo '<option value="A">A</option>';
			echo '<option value="B">B</option>';
			echo '<option value="C">C</option>';
			echo '<option value="D">D</option>';
			echo '<option value="F">F</option>';
			echo '<option value="G">G</option>';
			echo '<option value="H">H</option>';
			echo '<option value="J">J</option>';
		?>
	</select>
	<br>
	Supporting Evidence: <input type="text" name="evidence" id="evidence" value="" size="45"><br>
	Question Text:<b>*</b><input type="text" name="qText" id="qText" value="" size="50" required><br>
    Choice 1 Answer:<b>*</b> <input type="text" name="choice1" id="choice1" value="" size="45" required><br>
	Choice 2 Answer:<b>*</b> <input type="text" name="choice2" id="choice2" value="" size="45" required><br>
	Choice 3 Answer:<b>*</b> <input type="text" name="choice3" id="choice3" value="" size="45" required><br>
	Choice 4 Answer:<b>*</b> <input type="text" name="choice4" id="choice4" value="" size="45" required>&nbsp;&nbsp;&nbsp;
    
	<br><br>
	<b>*Required fields</b><br><br>
		<button class="btn btn-info" type ="submit" name="createQuestion" >Create Question</button>
</form>
<br><br><br><br>
<?php
}

}  //end of session variable true
?>


<?php
if (isset($_POST['createQuestion'])) {
	$qNum=$_POST['qNum'];
	$std=$_POST['std'];
	$indicator=$_POST['indicator'];
	$indicator = str_replace("'", "''", $indicator);
	//$firstLetter=$_POST['letter'];
	$cAnswer=$_POST['cAnswer'];
	$evidence=$_POST['evidence'];
	$text=$_POST['qText'];
	$choice1=$_POST['choice1'];
	$choice2=$_POST['choice2'];
	$choice3=$_POST['choice3'];
	$choice4=$_POST['choice4'];
	
	if($cAnswer == "A" || $cAnswer == "B" || $cAnswer == "C" || $cAnswer == "D")
	{
		$firstLetter = "A";
		$secondLetter = "B";
		$thirdLetter = "C";
		$fourthLetter = "D";
	}
	else{
		$firstLetter = "F";
		$secondLetter = "G";
		$thirdLetter = "H";
		$fourthLetter = "J";
	}
	
	if($evidence == "")
	{
		$sqlCreateQ=("INSERT INTO Question (StoryID,CorrectAnswer,QuestionType,Points,Std,QuestionText,Indicator,QuestionNumber) VALUES((SELECT StoryID FROM Story WHERE StoryID=(SELECT max(StoryID) FROM Story)),'$cAnswer','R',1,'$std','$text','$indicator','$qNum'); ");
	}
	else
	{
		$sqlCreateQ=("INSERT INTO Question (StoryID,CorrectAnswer,QuestionType,Points,Std,QuestionText,Indicator,Evidence,QuestionNumber) VALUES((SELECT StoryID FROM Story WHERE StoryID=(SELECT max(StoryID) FROM Story)),'$cAnswer','R',1,'$std','$text','$indicator','$evidence','$qNum'); ");
	}
	if($conn->query($sqlCreateQ) == TRUE){
			echo "<br>Question Created Successfully. Please add all further questions for this story. <br><br><br>";
			
			$sqlCreateChoice1=("INSERT INTO Choices (QuestionID,Letter,Text) VALUES((select QuestionID from Question where QuestionID=(select max(QuestionID) from Question)),'$firstLetter','$choice1'); ");
			if($conn->query($sqlCreateChoice1) == TRUE){
				//echo "<br>Choice 1 Created Successfully.<br><br><br>";
			}
			else
				echo "Error adding choices.";
			
			$sqlCreateChoice2=("INSERT INTO Choices (QuestionID,Letter,Text) VALUES((select QuestionID from Question where QuestionID=(select max(QuestionID) from Question)),'$secondLetter','$choice2'); ");
			if($conn->query($sqlCreateChoice2) == TRUE){
				//echo "<br>Choice 2 Created Successfully.<br><br><br>";
			}
			else
				echo "Error adding choices.";
			
			$sqlCreateChoice3=("INSERT INTO Choices (QuestionID,Letter,Text) VALUES((select QuestionID from Question where QuestionID=(select max(QuestionID) from Question)),'$thirdLetter','$choice3'); ");
			if($conn->query($sqlCreateChoice3) == TRUE){
				//echo "<br>Choice 3 Created Successfully.<br><br><br>";
			}
			else
				echo "Error adding choices.";
			
			$sqlCreateChoice4=("INSERT INTO Choices (QuestionID,Letter,Text) VALUES((select QuestionID from Question where QuestionID=(select max(QuestionID) from Question)),'$fourthLetter','$choice4'); ");
			if($conn->query($sqlCreateChoice4) == TRUE){
				//echo "<br>Choice 4 Created Successfully.<br><br><br>";
			}
			else
				echo "Error adding choices.";
		}
		else
		{
			echo "Error adding question. ";
			echo $sqlCreateQ;
		}
}
//}   //new end of create story isset
//}   //new end of prelim info isset
?>



</div>

<?php include './navigation/navEnd.html'; ?>



</body>
</html>