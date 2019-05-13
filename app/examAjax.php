
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

include ("./db/connection/dbConnection.php");

//DEPENDENT DROPDOWN FOR STUDENT STATISTICS

if(isset($_POST['myExamID'])){
    $examData = $_POST['myExamID'];
    $myresult= explode('|', $examData);

    $examId=$myresult[0];
    $examDate=$myresult[1];

    if (isset($_SESSION['isAdmin'])) {
        if ($_SESSION['isAdmin']) {

          $school =$examData;
          $school .= "|school";

          $class =$examData;
          $class .= "|class";
          echo '<option value=""> Select Scope</option>';
          echo '<option value="'.$school.'">School</option>';
          echo '<option value="'.$class.'">Class</option>';

     }
    }
    /*
    if (isset($_SESSION['isTeacher'])) {
      if ($_SESSION['isTeacher']) {

        $class =$examData;
        $class .= "|class";
          echo '<option value="" hidden> Select Scope</option>';
          echo '<option value="'.$class.'" hidden>Class</option>';
     }
   }*/
  }




  if(isset($_POST['ExamTeacher'])){
      $examData = $_POST['ExamTeacher'];
      $myresult= explode('|', $examData);

      $examId=$myresult[0];
      $examDate=$myresult[1];

      if (isset($_SESSION['isTeacher'])) {
          if ($_SESSION['isTeacher']) {

            $sqlgrade="SELECT  class.Grade, assessment.ExamID, class.ClassID, useraccount.UserID
                 FROM useraccount
                 INNER JOIN teacher ON useraccount.UserID= teacher.UserID
                 INNER JOIN class ON class.TeacherID = teacher.TeacherID
                 INNER JOIN classhistory history ON class.ClassYear=history.ClassYear
                 INNER JOIN assessment assessment ON assessment.ClassHistoryID= history.ClassHistoryID
                 WHERE assessment.Date= '$examDate' AND useraccount.UserID= '$currUserID'
                 Group by class.ClassID;";
                 $GLOBALS['myCresult']= $conn->query($sqlgrade) or die('Error showing grades'.$conn->error);

                 $myScope ="class|";
                 $myScope .= $examId;
                 $myScope .="|";
                 $myScope .= $examDate;

            echo '<option value=""> Select Class</option>';
            while ( $row = mysqli_fetch_array ($myCresult) ) {
                echo '<option value="'.$myScope.'|'.$row["ClassID"].'">'.$row["ClassID"].'</option>';
            }

       }
      }
    }

if(isset($_POST['ExamScope']) ){
  $scopeData = $_POST['ExamScope'];
  $myresult= explode('|', $scopeData);


  $examId=$myresult[0];
  $examDate=$myresult[1];
  $myScope=$myresult[2];

    if($myScope == "school"){
      $sqlSchool="SELECT school.School_Name, school.SchoolId
          FROM assessment
          INNER JOIN classhistory ON classhistory.ClassHistoryID = assessment.ClassHistoryID
          INNER JOIN class ON class.ClassID = classhistory.ClassID
          INNER JOIN teacher ON teacher.TeacherID = class.TeacherID
          INNER JOIN useraccount ON useraccount.UserID = teacher.UserID
          INNER JOIN school ON school.SchoolID = useraccount.SchoolID
          WHERE assessment.Date = '$examDate'
          GROUP BY school.School_Name;";
          $result = $conn->query($sqlSchool) or die('Error showing schools'.$conn->error);
          $myScope .="|";
          $myScope .= $examId;
          $myScope .="|";
          $myScope .= $examDate;

          echo '<option value=""> Select School</option>';
          while ( $row = mysqli_fetch_array ($result) ) {
          echo '<option value='.$myScope.'|'.$row["SchoolId"].'>'.$row["School_Name"].'</option>';
          }
    }

    else{
      if (isset($_SESSION['isTeacher'])) {
          if ($_SESSION['isTeacher']) {
         $sqlgrade="SELECT  class.Grade, assessment.ExamID, class.ClassID, useraccount.UserID
              FROM useraccount
              INNER JOIN teacher ON useraccount.UserID= teacher.UserID
              INNER JOIN class ON class.TeacherID = teacher.TeacherID
              INNER JOIN classhistory history ON class.ClassYear=history.ClassYear
              INNER JOIN assessment assessment ON assessment.ClassHistoryID= history.ClassHistoryID
              WHERE assessment.Date= '$examDate' AND useraccount.UserID= '$currUserID'
              Group by class.ClassID;";
              $GLOBALS['myCresult']= $conn->query($sqlgrade) or die('Error showing grades'.$conn->error);
            }
          }
          if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin']) {
              $sqlgrade="SELECT  class.Grade, assessment.ExamID, class.ClassID
              FROM class class
              INNER JOIN classhistory history ON class.ClassYear=history.ClassYear
              INNER JOIN assessment assessment ON assessment.ClassHistoryID= history.ClassHistoryID
              WHERE assessment.Date='$examDate'
              Group by class.ClassID;";
              $GLOBALS['myCresult'] = $conn->query($sqlgrade) or die('Error showing grades'.$conn->error);
            }
          }
          $myScope .="|";
          $myScope .= $examId;
          $myScope .="|";
          $myScope .= $examDate;
          echo '<option value=""> Select Class</option>';
          while ( $row = mysqli_fetch_array ($myCresult) ) {
              echo '<option value="'.$myScope.'|'.$row["ClassID"].'">'.$row["ClassID"].'</option>';
          }
        }
      }




      if(isset($_POST['ExamGrade'])){
        $scope =$_POST['ExamGrade'];
        $myResult= explode('|', $scope);

        $myScope=$myResult[0];
        $dataOne=$myResult[1];
        $dataTwo=$myResult[2];
        $dataThree=$myResult[3];

        if($myScope == "school"){
          $examId=$dataOne;
          $examDate=$dataTwo;
          $schoolId= $dataThree;
		      $schoolData = "ALL";
          $schoolData .="|";
          $schoolData .=$schoolId;
          $schoolData .="|";
          $schoolData .=$schoolId;

            echo '<option value='.$schoolData.'>Select Students</option>';
            echo '<option value='.$schoolData.'>ALL</option>';


        }

        else{
			$displayALL = "ALL";
			$displayALL .="|";
			$displayALL .= 0;
			
          if (isset($_SESSION['isTeacher'])) {
              if ($_SESSION['isTeacher']) {
                $GLOBALS['examId']=$dataOne;
                $GLOBALS['examDate']=$dataTwo;
                $GLOBALS['classId']= $dataThree;

              }}
              if (isset($_SESSION['isAdmin'])) {
                if ($_SESSION['isAdmin']) {
                $GLOBALS['examId']=$dataOne;
                $GLOBALS['examDate']=$dataTwo;
                $GLOBALS['classId']= $dataThree;
              }}

          $sqlSchool="SELECT  school.SchoolId, book.NumberOfQuestions, assessment.Date, assessment.ExamID, class.ClassID
          FROM assessment
          INNER JOIN book book ON book.BookID = assessment.BookID
          INNER JOIN classhistory ON classhistory.ClassHistoryID= assessment.ClassHistoryID
          INNER JOIN student ON student.StudentID = classhistory.StudentID
          INNER JOIN class ON class.ClassID = classhistory.ClassID
          INNER JOIN teacher ON teacher.TeacherID = class.TeacherID
          INNER JOIN school ON school.SchoolID = teacher.SchoolID
          WHERE class.ClassID= '$classId'
          GROUP BY class.ClassID;";
          $schoolResult = $conn->query($sqlSchool) or die('Error showing exam year'.$conn->error);

          while ( $row = mysqli_fetch_array ($schoolResult) ) {
            $GLOBALS['mySchoolId'] = $row["SchoolId"];
          }

          $sqlstudent="SELECT class.ClassID, student.StudentID, student.FName, student.LName, assessment.Date, assessment.ExamID, assessment.BookID,
          book.BookID
          FROM class class
          INNER JOIN classhistory history ON class.ClassID = history.ClassID
          INNER JOIN student student ON student.StudentID= history.StudentID
          INNER JOIN assessment assessment ON assessment.ClassHistoryID= history.ClassHistoryID
          INNER JOIN book book ON assessment.BookID = book.BookID
          WHERE class.ClassID= '$classId' and assessment.Date='$examDate'
          Group by student.StudentID;";

          $result = $conn->query($sqlstudent) or die('Error showing students'.$conn->error);
          echo '<option value=""> Select Student</option>';
		  echo '<option value='.$displayALL.'>ALL</option>';
          while ( $row = mysqli_fetch_array ($result) ) {
            $studentName= $row["FName"]." ".$row["LName"];
            $studentId= $row["StudentID"];
            $studentId .= "|";
            $studentId .= $mySchoolId;
            $studentId .= "|";
            $studentId .= $classId;

            echo '<option value='.$studentId.'>'.$studentName.'</option>';
          }
       }
      }
?>
