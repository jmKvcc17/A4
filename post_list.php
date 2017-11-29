<?php
/*
STILL NEEDED:
-SORT BUTTON SPACING

*/

session_start();

require_once ('config.php');
require_once('loginClass.php');

$loginObj = new loginClass();
$loginObj->checkSession();

$orderBy;
$sortType;

// Check the inputs
if (isset($_GET['order']) && isset($_GET['type'])) {
	if ($_GET['order'] == 'PostTime' || $_GET['order'] == 'Title') {
		$orderBy = $_GET['order'];
	}
	else {
		ERROR_PAGE();
	}
	
	if ($_GET['type'] == 'ASC' || $_GET['type'] == 'DESC') {
		$sortType = $_GET['type'];
	}
	else {
		ERROR_PAGE();
	}
}
elseif (empty($_GET['order']) || empty($_GET['type'])){
	$orderBy = "Title";
	$sortType = "ASC";
}
else {
	ERROR_PAGE();
}

function ERROR_PAGE() {
	header("Location: error.php", true, 301);
	exit();
}

// Display all
function getDBRow($orderBy, $sortType) {
	$dateFormatted;
	
	try {
		$pdo = new PDO(DBCONNSTRING, DBUSER,DBPASS);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM travelpost ORDER BY $orderBy $sortType";
		$result = $pdo->query($sql);
		while ($row = $result->fetch())  {
			$dateFormatted = date("F d, Y", strtotime($row['PostTime'])); // Convert the sql date to php date
			
			echo '<a href="post_single.php?id=' . $row["PostID"] . '" class="list-group-item">';
			echo $row['Title'] . '<span class="label label-primary pull-right">' . $dateFormatted .  '</span><br/></a>';
		}
		$pdo = null;
		}
		catch (PDOException $e) {
			//die( $e->getMessage() );
			 ERROR_PAGE();
		}
}
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
       <div class="panel-heading"><h3>Post List</h3></div>
      <div class="well">
        <div class="text-right">
          <strong><span class="glyphicon glyphicon-sort"></span> Sort by: </strong>
		  <?php
		   if ($sortType == 'ASC') {
			   echo '<a href="post_list.php?order=PostTime&type=DESC" class="btn btn-info" role="button"><span class="glyphicon glyphicon-sort-by-attributes-alt"></span> Post Date </a>';        
			   echo ' <a href="post_list.php?order=Title&type=DESC" class="btn btn-info" role="button"><span class="glyphicon glyphicon-sort-by-attributes-alt"></span> Post Title </a>';
		   }
		   if ($sortType == 'DESC') {
			   echo '<a href="post_list.php?order=PostTime&type=ASC" class="btn btn-info" role="button"><span class="glyphicon glyphicon-sort-by-attributes"></span> Post Date </a>';        
			   echo ' <a href="post_list.php?order=Title&type=ASC" class="btn btn-info" role="button"><span class="glyphicon glyphicon-sort-by-attributes"></span> Post Title </a>';
		   }
			
	      ?>
         
        </div>
      </div>
      <div class="panel-body">

        <div class="list-group">
          
			  <?php
			  	getDBRow($orderBy, $sortType);
			  	
			  ?>
          
        </div>
      </div>
    </div>           
  </div>
</div>  <!-- end main content column -->
</div>  <!-- end main content row -->
  <!-- end container -->





 <!-- Bootstrap core JavaScript
   ================================================== -->
   <!-- Placed at the end of the document so the pages load faster -->
   <script src="bootstrap3_bookTheme/assets/js/jquery.js"></script>
   <script src="bootstrap3_bookTheme/dist/js/bootstrap.min.js"></script>
   <script src="bootstrap3_bookTheme/assets/js/holder.js"></script>
 </body>
 </html>