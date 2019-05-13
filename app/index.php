<?php

	include("./db/connection/dbConnection.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ELA Stats Login</title>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
        crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/"
        crossorigin="anonymous">


	<link href="./css/loginStyle.css" rel="stylesheet">

</head>
<body>
<?php
    session_start();
    
    $uname="";
    $password="";

	if (isset($_POST['uname']) && isset($_POST['password']))
	{
		$uname = $_POST['uname'];
		$password = $_POST['password'];


		$conn->set_charset("utf8");
		$uname = $conn->real_escape_string($uname);
		$password = $conn->real_escape_string($password);

	$result = $conn->query("SELECT * FROM UserAccount WHERE Email='".$uname."' AND Password='".sha1($password)."'");
  //$result = $conn->query("SELECT * FROM useraccount WHERE Email='".$uname."' AND Password='".$password."'");
  

		if ($result->num_rows)
		{
			$row = $result->fetch_assoc();
            $_SESSION['UserID'] = $row['UserID'];
            
            //get the account type
            $loginType = $row['AccountType'];

            $admin ="admin";
            $user ="teacher-new";

            //initally set all roles to false
            $_SESSION['isAdmin']=false;
            $_SESSION['isTeacher']=false;

            //if admin, set role to admin
            if($loginType === $admin){
                $_SESSION['isAdmin']=true;
            }

            //if teacher, set role to teacher
            else if($loginType === $user){
                $_SESSION['isTeacher']=true;

            }
            
           

            
          

		}

		$conn->close();
	}

	if (isset($_SESSION['UserID']))
	{

		if($loginType == $admin){
		header("Location: dashboard.php");
  }
    elseif($loginType == $user){
			header("Location: dashboard.php");
    }
	}
	else
	{
		  if ($uname!="")
		  { ?>
			   <span style='color:red;'>LOGIN FAILURE: <?= $uname ?> is not an authorized user.</span><br>
		   <?php }
		      else
		      {
			         echo "";
		      }
        }
        ?>

<form method='POST' action='index.php'>

    <div class="container w-100 h-100 ">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-lg-8 col-sm-10 col-md-10">
                <div class="login-panel">
                    <h4 class="login-panel-title">ELA Statistics Login</h4>
                    <p class="login-panel-tagline">Enter your .edu email and password</p>
                    <div class="login-panel-section">
                        <div class="form-group">
                            <div class="input-group margin-bottom-sm">

                                <input class="form-control" type="text" placeholder="Email address" name="uname">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">

                                <input class="form-control" type="password" placeholder="Password" name="password">

                            </div>
                        </div>

                        <div class="checkbox checkbox-circle checkbox-success checkbox-small">

                            <!-- <a href="#" class="pull-right">Forgot your password?</a> -->
                        </div>
                    </div>
                    <div class="login-panel-section">
                        <button type="submit" class="btn btn-default">
                            <i class="fa fa-sign-in-alt fa-fw" aria-hidden="true"></i> Login</button><!-- |
                            <a href="#">Request an Account</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


	


</body>
</html>
