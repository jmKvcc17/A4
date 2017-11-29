<?php
/***
TO DO:
	-FIX THE HIGHLIGHT
	-SANITIZE INPUTS



*/


session_start();

require_once('config.php');
require_once('searchClass.php');
require_once('loginClass.php');

$loginObj = new loginClass();
$loginObj->checkSession();


$searchObj = new searchClass();
$searchResObj[] = null;



// Set the search parameters
if ((!empty($_POST['search']) && isset($_POST['search'])) && (!empty($_POST['filter'] && isset($_POST['filter'])))) {
	$searchObj->searchField = $_POST['search'];
	$searchObj->searchType = $_POST['filter'];
	
	if ($searchObj->searchType == "title") {
		titleSearch($searchObj, $searchResObj);
	}
	
	if ($searchObj->searchType == "content") {
		contentSearch($searchObj, $searchResObj);
	}
}

// used to search for content
function contentSearch(&$searchObj, &$searchResObj) {
	try {
		$pdo = new PDO(DBCONNSTRING, DBUSER,DBPASS);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "SELECT Title, Message, PostID FROM travelpost WHERE Message LIKE '%$searchObj->searchField%'";
		
		$result = $pdo->query($sql);
		
		$count = 0;
		while ($row = $result->fetch()) {
			$searchResObj[$count] = new searchResult();
			
			$searchResObj[$count]->searchTitle = $row['Title'];
			$searchResObj[$count]->searchMessage = $row['Message'];
			$searchResObj[$count]->searchPostID = $row['PostID'];

			$count++;
		}
	}
	catch (PDOexception $e) {
		ERROR_PAGE();
	}
	
}


// used to search for title
function titleSearch(&$searchObj, &$searchResObj) {
	try {
		$pdo = new PDO(DBCONNSTRING, DBUSER,DBPASS);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "SELECT Title, Message, PostID FROM travelpost WHERE Title LIKE '%$searchObj->searchField%'";
		
		$result = $pdo->query($sql);
		
		$count = 0;
		while ($row = $result->fetch()) {
			$searchResObj[$count] = new searchResult();
			
			$searchResObj[$count]->searchTitle = $row['Title'];
			$searchResObj[$count]->searchMessage = $row['Message'];
			$searchResObj[$count]->searchPostID = $row['PostID'];

			$count++;
		}
	}
	catch (PDOexception $e) {
		ERROR_PAGE();
	}
	
}

function ERROR_PAGE() {
	header("Location: error.php", true, 301);
	exit();
}

function printTitleSearch(&$searchResObj, &$searchObj) {
	if (!empty($searchResObj[0]->searchTitle)) {
		foreach($searchResObj as $var) {
			$var->printTitleSearch();
		}
	}
	else {
		echo 'No results for search term <strong>' . $searchObj->searchField . '</strong>';
	}
}

function printContentSearch(&$searchResObj, &$searchObj) {
	if (!empty($searchResObj[0]->searchTitle)) {
		foreach($searchResObj as $var) {
			$var->printContentSearch($searchObj->searchField);
		}
	}
	else {
		echo 'No results for search term <strong>' . $searchObj->searchField . '</strong>';
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
       <div class="panel-heading"><h3>Search</h3></div>
       <div class="panel-body">
        <form method="POST">
          <div class="form-group">
            <input type="search" name="search" class="form-control">
          </div>
          <div class="radio">
            <label><input type="radio" name="filter" value="title" checked> Find in Post Title</label><br/>
            <label> <input type="radio" name="filter" value="content"> Find in Post Content</label><br/>
          </div>
            <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>
          </form>
        </div>
      </div>  
      <!-- Search results go HERE -->
	   <?php 
	   	if ($searchObj->searchField != null) {
			echo '<div class="panel panel-danger spaceabove">           
         			<div class="panel-heading"><h4>Search results for "' . $searchObj->searchField  . '"</h4></div>
        			 <div class="panel-body">';
			
			if ($searchObj->searchType == "title") {
				printTitleSearch($searchResObj, $searchObj); 
			}
			if ($searchObj->searchType == "content")
				printContentSearch($searchResObj, $searchObj); 
			
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
	   
	   ?>
        
          
	
          <!-- If no results where found show this instead 
          <p>No results for search term <strong> SEARCH TERM </strong></p>
			-->			

        
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