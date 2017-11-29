<?php
session_start();

require_once('config.php');
require_once('loginClass.php');

$loginObj = new loginClass();

function getUserInfo(&$loginObj) {
	if (!empty($_POST['email']) && !empty($_POST['pwd'])) {
		searchUser($loginObj);
	}
}

function getName(&$loginObj) {
	try {
		$pdo = new PDO(DBCONNSTRING, DBUSER,DBPASS);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "SELECT FirstName, LastName FROM traveluserdetails WHERE UID=$loginObj->UID";
		$result = $pdo->query($sql);
		$row = $result->fetch();

		$loginObj->fName = $row['FirstName'];
		$loginObj->lName = $row['LastName'];
	}
	catch (PDOException $e) {
		echo $e;
	}
}

function searchUser(&$loginObj) {
	try {
		$username = $_POST['email'];
		$password = $_POST['pwd'];

		$pdo = new PDO(DBCONNSTRING, DBUSER,DBPASS);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "SELECT UID, UserName FROM traveluser WHERE UserName='$username' AND Pass='$password'";
		$result = $pdo->query($sql);
		$row = $result->fetch();
		
		
		if ($row['UID'] == null)
			$loginObj->NOT_FOUND = true;
		// If the user is not found
		else {
			$loginObj->UID = $row['UID'];
			
			getName($loginObj);
			
			$loginObj->setSession();
			
			header("Location: index.php", true, 301);
			exit();
		}
	}
	catch(PDOException $e) {
		echo $e;
	}
	
}

$loginObj->deleteSession();
getUserInfo($loginObj);


?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta http-equiv="Content-Type" content="text/html; 
 charset=UTF-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <meta name="description" content="">
 <meta name="author" content="">
 <title>Travel Journal</title>

 <link rel="shortcut icon" href="../../assets/ico/favicon.png">

 <!-- Google fonts used in this theme  -->
 <link href='http://fonts.googleapis.com/css?family=Roboto+Slab:400,700' rel='stylesheet' type='text/css'>
 <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic,700italic' rel='stylesheet' type='text/css'>  

 <!-- Bootstrap core CSS -->
 <link href="bootstrap3_bookTheme/dist/css/bootstrap.min.css" rel="stylesheet">
 <!-- Bootstrap theme CSS -->
 <!-- <link href="bootstrap3_bookTheme/theme.css" rel="stylesheet"> -->


 <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
   <!--[if lt IE 9]>
   <script src="bootstrap3_bookTheme/assets/js/html5shiv.js"></script>
   <script src="bootstrap3_bookTheme/assets/js/respond.min.js"></script>
 <![endif]-->
</head>

<body>

  <?php include 'header.php'; ?>

  <div class="container">
   <div class="row">  <!-- start main content row -->
     <div class="col-md-12">  <!-- start main content column -->
      <div class="panel panel-danger spaceabove">           
       <div class="panel-heading"><h3>User Login</h3></div>
       <div class="panel-body">
        <div class="row">
          <div class=col-md-12>
			  <?php
			 	$loginObj->NOT_FOUND();
			  ?>
            <!-- IF THERE IS AN ERROR for the user or password information, then display this 
            <div class="alert alert-warning alert-dismissable">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Error: </strong> E-mail or password did not match our records.
            </div>
			-->
            <!-- END disp;ay error -->

            <form action="login.php" method="post">
              <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
              </div>
              <div class="form-group">
                <label for="pwd">Password:</label>
                <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pwd">
              </div>
              <button type="submit" class="btn btn-primary">Login</button>
            </form>

          </div>
        </div>
      </div>
    </div>           
  </div>


</div>  <!-- end main content column -->
</div>  <!-- end main content row -->
</div>   <!-- end container -->





 <!-- Bootstrap core JavaScript
   ================================================== -->
   <!-- Placed at the end of the document so the pages load faster -->
   <script src="bootstrap3_bookTheme/assets/js/jquery.js"></script>
   <script src="bootstrap3_bookTheme/dist/js/bootstrap.min.js"></script>
   <script src="bootstrap3_bookTheme/assets/js/holder.js"></script>
 </body>
 </html>