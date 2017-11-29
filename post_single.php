<?php
/*
USES:
	-TravelPost, TravelUserDetails, TravelImage

*/

session_start();

require_once('config.php');
require_once('imageClass.php');
require_once('loginClass.php');

$loginObj = new loginClass();
$loginObj->checkSession();

// Objects ****
$imageObj[] = new Images();


$IMAGE_PATH = "images/square-medium";

$userInfoArray[] = null;

if (isset($_GET['id']) && !empty($_GET['id'])) {
	$postID = $_GET['id'];
}
else {
	header("Location: error.php", true, 301);
	exit();	
}

function displayPost($postId, &$userInfoArray, &$imageObj) {
	try {
		$pdo = new PDO(DBCONNSTRING, DBUSER,DBPASS);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		// Get the postid
		$sql = "SELECT * FROM travelpost WHERE PostID = $postId";
		$result = $pdo->query($sql);
		$row = $result->fetch();
		
		$uid = $row['UID'];
		array_push($userInfoArray, $row['Title'], $row['Message'], $row['PostTime']); //*****
		
		// Get the first and last name ******
		$sql = "SELECT FirstName, LastName FROM traveluserdetails WHERE UID = $uid";
		$result = $pdo->query($sql);
		$row = $result->fetch();
		
		$fullName = $row['FirstName'] . ' ' . $row['LastName'];
		array_push($userInfoArray, $fullName);
		
		// Get the image
		$sql = "SELECT travelimage.ImageID, travelimage.Path
				FROM travelimage  
				INNER JOIN travelpostimages ON travelpostimages.ImageID=travelimage.ImageID
				WHERE travelpostimages.PostID = $postId";
		
		$count = 0;
		if ($result = $pdo->query($sql)) {
			while($row = $result->fetch()) {
				
				// Get the image path
				$imageObj[$count] = new Images();
				$imageObj[$count]->path = $row[1];
				
				// Get the image id
				$imageObj[$count]->imageID = $row[0];
				
				//*********** Get image title *******
				// Get the image
				$sqlImg = "SELECT travelimagedetails.Title
							FROM travelimagedetails
							WHERE travelimagedetails.ImageID=" . $imageObj[$count]->imageID . "";
				
				if ($resultImg = $pdo->query($sqlImg)) {
					$rowImg = $resultImg->fetch();
					$imageObj[$count]->imageTitle = $rowImg[0];
				}
				
				$count++;
			}
		}
		
		$pdo = null;
		
	}
	catch (PDOException $e) {
		header("Location: error.php?", true, 301);
		exit();	
	}
}

displayPost($postID, $userInfoArray, $imageObj);

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

    <div class="col-md-2">  <!-- start left navigation rail column -->
     <?php include 'side.php'; ?>
   </div>  <!-- end left navigation rail --> 

   <div class="col-md-10">  <!-- start main content column -->

     <!-- Customer panel  -->
    <div class="panel panel-danger spaceabove">           
     <div class="panel-heading"><h3><?php echo $userInfoArray[1]; ?></h3></div>
     <div class="panel-body">
      <div class="row">
        <div class="col-md-9"><?php echo $userInfoArray[2];
			 ?></div>
        
        <div class="col-md-3">
         <div class="panel panel-primary">
          <div class="panel-heading"><h4>Post Details</h4></div>

          <ul class="list-group">
            <li class="list-group-item"><strong>Date: </strong> 
              <?$dateFormatted = date("F d, Y", strtotime($userInfoArray[3]));
			echo $dateFormatted; ?>
            </li>
            <li class="list-group-item"><strong>Posted By: </strong> 
              <?php echo $userInfoArray[4]; ?>
            </li>
          </ul>
        </div>

      </div>
    </div>
  </div>
</div>           

<div class="panel panel-danger spaceabove">           
 <div class="panel-heading"><h4>Travel images for this post</h4></div>
 <div class="panel-body">
  <div class="row">
	  
   <?php 
	  foreach($imageObj as $img)
	 	 $img->displayImages();
	  ?>	
   
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