
<?php

function assignTeacherToClass($conn, $teacherId, $classId){


$stmt = $conn->prepare("UPDATE ela.class set teacherid=? where classid=?");
$stmt->bind_param("ss", $teacherid, $classid);

$teacherid= $teacherId;
$classid = $classId;

if($stmt->execute() === true){
    return true;
}

return false;


}


?>