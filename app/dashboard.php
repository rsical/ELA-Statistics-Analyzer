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

<!DOCTYPE HTML>
<html lang="en">

<head>
    
    <title>ELA Stats</title>

    <?php include './includedFrameworks/bootstrapHead.html';?>


 
</head>

<body>
 

    
    <?php include './navigation/navBegin.html'; ?>

        <div class="content p-4">

            <h1 class="display-5 mb-4">Welcome!</h1>

            <p>More features coming soon!</p>

            


        </div>

    <?php include './navigation/navEnd.html'; ?>

		

</body>

</html>
