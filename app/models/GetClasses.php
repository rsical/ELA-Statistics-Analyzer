
<?php

function getUnassignedClasses($conn){

//get all classes with a teacher id of 0 (indicating no teacher) or null (also indicating no teacher)
$query="SELECT ClassID, Grade, ClassYear FROM class
WHERE TeacherID is null or teacherId=0";

$result = $conn->query($query) or die('Could not run query: '.$conn->error);

return $result;

}

//get all possible teachers to assign to a class
function getAllTeachers($conn){

    $query="SELECT  fname,lname, useraccount.userid, class.teacherid, count(class.teacherid) as numOfClassesTaught FROM class, teacher, useraccount where teacher.userid=useraccount.userid and teacher.teacherid=class.teacherid group by teacherid order by lname";

    $result = $conn->query($query) or die('Could not run query: '.$conn->error);

    return $result;
}

?>