<?php
//POST for assigning teacher to a class
include ("./db/connection/dbConnection.php");
include ("./models/AssignTeacher.php");

$success=assignTeacherToClass($conn,$_POST["teacherSelect"], $_POST["classID"]);

if($success){
    header("refresh:1; url=ManageClasses.php");

}

// $stmt = $conn->prepare("UPDATE ela.class set teacherid=? where classid=?");
// $stmt->bind_param("ss", $teacherid, $classid);

// $teacherid= $_POST["teacherSelect"];
// $classid = $_POST["classID"];
// $stmt->execute();




?>
