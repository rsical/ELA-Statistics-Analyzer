<?php
	session_start();
	unset($_SESSION['UserID']);
	unset($_SESSION['storyCreated']);
	unset($_SESSION['myCount']);
	session_destroy();
	 echo "<script>window.close();</script>";
	header("Location: index.php")
?>
