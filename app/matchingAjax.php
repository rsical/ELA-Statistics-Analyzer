
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


if(isset($_POST['matchExamID'])){
  $examData = $_POST['matchExamID'];
  $myresult= explode('|', $examData);

  $examId=$myresult[0];
  $examDate=$myresult[1];

  if (isset($_SESSION['isAdmin'])) {
      if ($_SESSION['isAdmin']) {
        $school = "school|";
        $school .=$examData;
        $class = "class|";
        $class .=$examData;
        echo '<option value=""> Select Scope</option>';
        echo '<option value="'.$school.'">School</option>';
        echo '<option value="'.$class.'">Class</option>';

   }
  }

}


if(isset($_POST['matchTeacher'])){
    $examData = $_POST['matchTeacher'];
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



if(isset($_POST['matchScope'])){
$scope = $_POST['matchScope'];
$myresult= explode('|', $scope);

$myScope=$myresult[0];
$examId=$myresult[1];
$examDate=$myresult[2];

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


if(isset($_POST['matchGrade'])){
  $scope =$_POST['matchGrade'];
  $myResult= explode('|', $scope);

  $myScope=$myResult[0];
  $dataOne=$myResult[1];
  $dataTwo=$myResult[2];
  $dataThree=$myResult[3];

  if($myScope == "school"){
    $examId=$dataOne;
    $examDate=$dataTwo;
    $schoolId= $dataThree;
    $examId .="|";
    $examId .=$examDate;
    $examId .="|";
    $examId .=$schoolId;

    $sqlstudent="SELECT class.ClassID, student.StudentID, student.FName, student.LName, assessment.Date, assessment.ExamID, assessment.BookID,
    book.BookID, school.SchoolID
    FROM School
    INNER JOIN useraccount ON school.SchoolID= useraccount.SchoolID
    INNER JOIN teacher ON teacher.UserID = useraccount.UserID
    INNER JOIN class ON teacher.TeacherID= class.TeacherID
    INNER JOIN classhistory history ON class.ClassID = history.ClassID
    INNER JOIN student student ON student.StudentID= history.StudentID
    INNER JOIN assessment assessment ON assessment.ClassHistoryID= history.ClassHistoryID
    INNER JOIN book book ON assessment.BookID = book.BookID
    WHERE school.SchoolID= '$schoolId' and assessment.Date='$examDate'
    Group by student.StudentID
    ORDER BY student.FName ASC;";

    $result = $conn->query($sqlstudent) or die('Error showing students'.$conn->error);
    echo '<option value=""> Select Student</option>';
    while ( $row = mysqli_fetch_array ($result) ) {
    $studentName= $row["FName"]." ".$row["LName"];
    echo '<option value="'.$row["StudentID"].'">'.$studentName.'</option>';
    }

  }

  else{
    $examId=$dataOne;
    $examDate=$dataTwo;
    $classId= $dataThree;
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
    while ( $row = mysqli_fetch_array ($result) ) {
      $studentName= $row["FName"]." ".$row["LName"];
      echo '<option value="'.$row["StudentID"].'">'.$studentName.'</option>';
    }
 }
}

if(isset($_POST['matchStudent'])){
  $studentId =$_POST['matchStudent'];


  $numberToMatch="SELECT student.StudentID, assessment.ExamID, class.ClassID
  FROM class class
  INNER JOIN classhistory history ON class.ClassID = history.ClassID
  INNER JOIN student student ON student.StudentID= history.StudentID
  INNER JOIN assessment assessment ON assessment.ClassHistoryID= history.ClassHistoryID
  INNER JOIN book book ON assessment.BookID = book.BookID
  WHERE student.StudentID= '$studentId'
  Group by student.StudentID;";

  $result = $conn->query($numberToMatch) or die('Error showing students'.$conn->error);
  echo '<option value=""> Select Student</option>';
  while ( $row = mysqli_fetch_array ($result) ) {
    $studentId =$row["StudentID"];
    $examId = $row["ExamID"];
  }
  $studentId .="|";
  $studentId .=$examId;
  $one=1;
  $two=2;
  $three=3;
  $four=4;
  //echo '<option value="'.$myScope.'|'.$row["ClassID"].'">'.$row["ClassID"].'</option>';
  echo '<option value="">Number</option>';
  echo '<option value="'.$studentId.'|1'.'">1</option>';
  echo '<option value="'.$studentId.'|2'.'">2</option>';
  echo '<option value="'.$studentId.'|3'.'">3</option>';
  echo '<option value="'.$studentId.'|4'.'">4</option>';
}
