<?php
$server = 'localhost';
$dbusername = 'root';

$user_agent = getenv("HTTP_USER_AGENT");

//if the user is on windows there is no password
if(strpos($user_agent, "Win")!== FALSE){
  $dbpassword = '';
}

//if user is on mac, password is root
elseif(strpos($user_agent, "Mac")!== FALSE){
  $dbpassword = 'root';
}

//assume password is root for any other os
else{
  $dbpassword = 'root';
}


$dbname = 'ela';
// Create connection

$conn = new mysqli($server, $dbusername, $dbpassword, $dbname);

// Check connection
if (mysqli_connect_errno())
{
  echo "Could not connect to the database!";
  exit;
}

?>
