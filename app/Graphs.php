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
  include ("Security.php");
  ini_set('display_errors', 'On');
	error_reporting(E_ALL | E_STRICT);
?>
<html lang="en">
  <head>
		<title>Graphs</title>
		<?php include './includedFrameworks/bootstrapHead.html';
		?>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
  </head>

<body>

<?php include './navigation/navBegin.html'; 

?>
<div style="padding-top: 15px" class="container">

	<div class="row">
		<h2 >Graphing Options</h2>
	</div>
	<div class="row" style="width:800px; margin:0 auto;">
		<form action="Graphs.php" method="POST">
			<table class="table">
			<tr align="center">
			 <th style="">Exam Year</th>
			 <th style="">Scope</th>
			 <th style="">Class</th>
			 <!--<th style="">School</th>-->
			 </tr>
			
			<tr>
			<?php
				if (isset($_SESSION['isTeacher'])) {
						if ($_SESSION['isTeacher']) { ?>
							<td>	<select name="ExamID" id="ExamIDTeacher" class="chosen-select" required> <?php

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
						<td>	<select name="ExamID" id="ExamID" class="chosen-select" required> <?php

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
				<td>	<select name="scope" id="scope"  class="chosen-select" required>
				      	<option value="" >Select Scope</option>
							</select>
				</td>
				<?php
				}
			}
			 ?>
				<br>

				<td>	<select name="grade" id="grade" class="chosen-select" required>
								<option value="">Select Class</option>
							</select>
				</td>
				<br>
						
						<!--
						<td>	<select name="School" id="School" style="display: none">
						<option value="">Select School</option>
						</select>
						<br>
						</td>
-->
			</tr>
		 </table>

		 <table class="table">
			<tr align="center">
			 <!--<th style="">School</th>-->
			 <th style="">Category I</th>
			 <th style="">Category II</th>
			 </tr>
			 <tr>
			 <td>	<select name="Category1" id="cat1" required>
								<option value="">Select X category</option>
								<option value="1">Main Idea, Author Purpose, Draw Conclusion</option>
								<option value="2">Inference, Prediction, Fact/Fiction</option>
								<option value="3">Interpretation, Info Location, Sequence</option>
							</select>
				</td>
				<br>

				<td>	<select name="Category2" id="cat2" required>
								<option value="">Select Y Category</option>
								<option value="1">Main Idea, Author Purpose, Draw Conclusion</option>
								<option value="2">Inference, Prediction, Fact/Fiction</option>
								<option value="3">Interpretation, Info Location, Sequence</option>
							</select>
				</td>

				<td>
					<center><button onclick="drawGraph()" class="btn btn-info" type ="submit" name="drawGraph" >Graph </button></center>
				</td>
			 </tr>
			 </table>	 
		 <br><br>
	 </form>
	</div>
	
<?php
if (isset($_POST['drawGraph'])){
	if (isset($_POST['ExamID'])){
		$examArray= $_POST['ExamID'];	
		$examData= explode('|', $examArray);
		$exam= $examData[0];
		$examDate= $examData[1];
		$examYear= substr($examDate,0,4);
	}

	if (isset($_POST['grade'])){
		$gradeArray= $_POST['grade'];
		$gradeData= explode('|', $gradeArray);
		$scope = $gradeData[0];
		$class= $gradeData[3];
	}
	if (isset($_POST['Category1'])){
		$cat1= $_POST['Category1'];
	}
	if (isset($_POST['Category2'])){
		$cat2= $_POST['Category2'];
	}
?>
	<div class="container"style="width:700px;">
		<canvas id="myChart"></canvas>
	</div>
		<script> 
			let myChart = document.getElementById('myChart').getContext('2d');
			 
			<?php
			$class = preg_replace("/[^0-9,.]/", "", $class);
			$grades = '';
			$cat1str = strval($cat1);
			$cat2str = strval($cat2);
			$classStr = strval($class);
			if($scope=="class"){
				$sqlQuery = $conn->prepare("SELECT avg(CASE 
											WHEN sa.LetterAnswer = q.CorrectAnswer AND q.std = ? THEN 1 
											WHEN q.std = ? THEN 0
										END) AS GRADE1,
										avg(CASE 
											WHEN sa.LetterAnswer = q.CorrectAnswer AND q.std = ? THEN 1 
											WHEN q.std = ? THEN 0
										END) AS GRADE2
										FROM studentanswers sa 
										JOIN question q on sa.QuestionID = q.QuestionID
										JOIN assessment a ON sa.ExamID = a.ExamID
										WHERE q.std IN (?, ?) /*exam categories via inputs*/
										AND sa.ExamID IN 
											(SELECT a.ExamID FROM assessment a
											JOIN classhistory ch on a.ClassHistoryID = ch.ClassHistoryID
											WHERE ch.ClassID = ?)
										AND a.BookID = 1
										GROUP BY sa.ExamID
										ORDER BY sa.ExamID");
			}
			else{
				$sqlQuery = $conn->prepare("SELECT avg(CASE 
											WHEN sa.LetterAnswer = q.CorrectAnswer AND q.std = ? THEN 1 
											WHEN q.std = ? THEN 0
										END) AS GRADE1,
										avg(CASE 
											WHEN sa.LetterAnswer = q.CorrectAnswer AND q.std = ? THEN 1 
											WHEN q.std = ? THEN 0
										END) AS GRADE2
										FROM studentanswers sa 
										JOIN question q on sa.QuestionID = q.QuestionID
										JOIN assessment a ON sa.ExamID = a.ExamID
										WHERE q.std IN (?, ?) /*exam categories via inputs*/
										AND sa.ExamID IN 
											(SELECT a.ExamID FROM assessment a
											JOIN classhistory ch on a.ClassHistoryID = ch.ClassHistoryID
											JOIN class c on ch.ClassID = c.ClassID
											JOIN teacher t on c.TeacherID = t.TeacherID
											WHERE t.SchoolID = ?)
										AND a.BookID = 1
										GROUP BY sa.ExamID
										ORDER BY sa.ExamID");
			}
			$sqlQuery->bind_param("sssssss", $cat1str, $cat1str, $cat2str, $cat2str, $cat1str, $cat2str, $classStr);
			$sqlQuery->execute();
			$result = $sqlQuery->get_result();
			$grade1array = array();
			$grade2array = array();
					while($row = $result->fetch_assoc()){
						$grade1 = $row['GRADE1'];
						$grade2 = $row['GRADE2'];
						array_push($grade1array, $grade1);
						array_push($grade2array, $grade2);
						
						$grades = $grades.'{x: '.$grade1.',y: '.$grade2.' },';
					}
					$grades = trim($grades, ",");

					$catArray = array(
						"1" => "Main Idea, Author Purpose, Draw Conclusion",
						"2" => "Inference, Prediction, Fact/Fiction",
						"3" => "Interpretation, Info Location, Sequence"
					);

					$cat1Name = ($catArray[$cat1]);
					$cat2Name = ($catArray[$cat2]);
						
			echo "var xCoord = 'Category: '.concat('$cat1Name');";
			echo "var yCoord = 'Category: '.concat('$cat2Name');";
			$xCatAvg = round(array_sum($grade1array)/count($grade1array), 2);
			$yCatAvg = round(array_sum($grade2array)/count($grade2array), 2);

			?>
			//Global Options
			Chart.defaults.global.defaultFontFamily = 'Lato';
			Chart.defaults.global.defaultFontSize = 18;
			Chart.defaults.global.defaultFontColor = '#700';
			
			
			let scatterChart = new Chart(myChart, {
				type:'scatter', // bar, horizontalBar, pie, line, doughnut, radar, polarArea, etc
				data:{
					labels:['First', 'Second', 'Third', 'Fourth'],
					datasets:[{
						
						data: [<?php echo $grades; ?>],
						pointBorderWidth: 1,
						pointRadius:5,
						pointBorderColor: 'rgba(155, 10, 132, 0.9)',
						showLine: false
					},
					{
						label:'Average',
						data: [{x:0,y:0},{x:1,y:1}],
						showLine: true,
						fill: false
					},
					{
						data: [{x:<?php echo $xCatAvg; ?>,y:0},{x:<?php echo $xCatAvg; ?>,y:1}],
						showLine: true,
						fill: false,
						borderColor: 'rgba(10,125,125,0.4)'
					},
					{
						data: [{x:0,y:<?php echo $yCatAvg; ?>},{x:1,y:<?php echo $yCatAvg; ?>}],
						showLine: true,
						fill: false,
						borderColor: 'rgba(10,125,125,0.4)'
					}
				]	
				},
				options:{
					//responsive: false,
					aspectRatio:1,
					title:{
						display:true,
						<?php
						if($scope=="class"){
							echo "text:['Results for Class: '.concat($class).concat(', Exam: ').concat($examYear).concat(' | X-Cat Avg:').concat($xCatAvg).concat(', Y-Cat Avg: ').concat($yCatAvg) ],";
						}
						else{
							echo "text:['Results for School: '.concat($class).concat(', Exam: ').concat($examYear).concat(' | X-Cat Avg:').concat($xCatAvg).concat(', Y-Cat Avg: ').concat($yCatAvg) ],";	
						}
						?>
						fontSize:25
					},
					legend:{
						position:'right',
						display:false
					},
					layout:{
						padding:{
						left:50,
						right:0,
						bottom:0,
						top:0
						}
					},
					tooltips:{
						enabled:true
					},
					scales: {
						xAxes: [{
							type: 'linear',
							position: 'bottom',
							scaleLabel: {
								display: true,
								labelString: xCoord
							},
							ticks: {
								min: 0,
								max: 1,
								stepSize: 0.1
							}
						}],
						yAxes: [{
							type: 'linear',
							scaleLabel: {
								display: true,
								labelString: yCoord
							},
							ticks: {
								min: 0,
								max: 1,
								stepSize: 0.1
							}
						}]
					}
				}
			}
				);
		
		</script>
<?php
}
?>
</div>
		
	


<?php include './navigation/navEnd.html'; ?>



</body>
</html>
