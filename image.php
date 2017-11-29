<?php
/*
-TO DO: 
	

*/

session_start();

require_once('config.php');
require_once('indImageClass.php');
require_once('loginClass.php');

$loginObj = new loginClass();
$loginObj->checkSession();

// Create the object to hold all page info
$imgObj = new imageInfo();

// Check if the id is set
if (isset($_GET['id']) && !empty($_GET['id'])) {
	$id = $_GET['id'];	
	// Check if a vote has been cast
	if (isset($_GET['points']) && !empty($_GET['points'])) {
		$imgPoints = floor($_GET['points']);
		if ($imgPoints >= 1 && $imgPoints <= 5) {
			addImagePoints($id, $imgPoints);
		}
		else {
			ERROR_PAGE();
		}
	}
}
else {
	ERROR_PAGE();
}

function setPageValues($id, &$imgObj) {
	getFirstSet($id, $imgObj);
}

function addImagePoints($id, $imgPoints) {
	try {
		$pdo = new PDO(DBCONNSTRING, DBUSER,DBPASS);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "INSERT INTO travelimagerating (ImageID, Rating)
				VALUES ($id, $imgPoints)";


		$pdo->beginTransaction();
		$statement = $pdo->prepare($sql);
		$statement->bindValue(1, $id);
		$statement->bindValue(2, $imgPoints);
		$statement->execute();
		$pdo->commit();
	}
	catch(PDOException $e) {
		ERROR_PAGE();
	}
	
}


// Returns the image title
function getFirstSet($id, &$imgObj) {
	try {
		$pdo = new PDO(DBCONNSTRING, DBUSER,DBPASS);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		// Get the postid
		$sql = "SELECT * FROM travelimagedetails WHERE ImageID = $id";
		$result = $pdo->query($sql);
		$row = $result->fetch();
		
		// Set the title
		$imgObj->imgTitle = $row['Title'];
		//Set the description
		$imgObj->imgDesc = $row['Description'];
		// Set the latitude
		$imgObj->imgLat = $row['Latitude'];
		// Set the longitude
		$imgObj->imgLong = $row['Longitude'];
		// Set the city code
		$imgObj->cityCode = $row['CityCode'];
		// Set the country code
		$imgObj->countryCode = $row['CountryCodeISO'];
		
		// Get the city
		if ($imgObj->cityCode != null) {
			$sql = "SELECT AsciiName FROM geocities WHERE GeoNameID=$imgObj->cityCode";
			$result = $pdo->query($sql);
			$row = $result->fetch();
			$imgObj->imgCity = $row['AsciiName'];
		}
		else {
			$imgObj->imgCity = 'None';
		}
		
		// Get the country
		$sql = "SELECT CountryName FROM geocountries WHERE ISO='$imgObj->countryCode'";
		$result = $pdo->query($sql);
		$row = $result->fetch();
		$imgObj->imgCountry = $row['CountryName'];
		
		// Get the user country
		$sql = "SELECT CountryName FROM geocountries WHERE ISO='$imgObj->countryCode'";
		$result = $pdo->query($sql);
		$row = $result->fetch();
		$imgObj->imgCountry = $row['CountryName'];
		
		// Get the artist and path
		$sql = "SELECT travelimage.Path, traveluserdetails.FirstName, traveluserdetails.LastName FROM travelimage 
				INNER JOIN traveluserdetails ON travelimage.UID = traveluserdetails.UID
				WHERE travelimage.ImageID=$id";
		$result = $pdo->query($sql);
		$row = $result->fetch();
		
		$imgObj->imgPath = $row[0];
		
		$imgObj->imgArtist = $row[1] . ' ' . $row[2];
		
		// Get the votes
		$sql = "SELECT AVG(Rating) as RatingAvg, COUNT(Rating) as Votes 
				FROM travelimagerating
				WHERE ImageID = $id";
		$result = $pdo->query($sql);
		$row = $result->fetch();
		
		$ratingAvg = $row['RatingAvg'];
		
		// Store the avg rating and votes in the obj
		$imgObj->imgRating = number_format($row['RatingAvg'], 2, '.', '');
		$imgObj->imgVotes = $row['Votes'];
		
	}
	catch(PDOException $e) {
		ERROR_PAGE();
	}
	
}

function ERROR_PAGE() {
	header("Location: error.php", true, 301);
	exit();
}


setPageValues($id, $imgObj);

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
		 <?php
		 	$imgObj->setTitle();
		 ?>
       
       <div class="panel-body">

        <div class="row">
          <div class="col-md-9 text-center"> 
            <?php $imgObj->setImageThumb(); ?>
            <!-- Modal -->
            <div class="modal fade" id="myModal" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $imgObj->imgTitle ?></h4>
                  </div>
					
				  
					<?php
						//$imgObj->setImageFull();
						
					?>
                  <div class="modal-body text-center">
                    <img src="images/medium/<?php echo $imgObj->imgPath?>" alt=\"...\"  class="img-thumbnail">
                    <br><br>
                    <p><strong><?php echo $imgObj->imgDesc?></strong></p>
                  </div>
				
                </div>
              </div>
            </div>
            <!-- END Modal -->
            <br/> <br/>
            <?php $imgObj->setDescription();  ?>

          </div>
          <div class="col-md-3">
            <div class="panel panel-primary">
              <div class="panel-heading"><h4>Rating</h4></div>
              <ul class="list-group">
               	<?php $imgObj->setVotingInfo() ?>
                <li class="list-group-item">

                  <form action="image.php" method="get" oninput="x.value=' ' + rng.value + ' '">
                    <div class="form-group text-center">
                      <output id="x" for="rng"> 3 </output> <span class="glyphicon glyphicon-thumbs-up"></span> <br>
                      <input type="range" id="rng" name="points" min="1" max="5" step="1">
                      <!-- The value of the hiddem input field is the ImageID -->
                      <input type="hidden" name="id" value="<?php echo $id?>">
                    </div>
                    <div class="form-group text-center">
                      <button type="submit" class="btn btn-info"><span class="glyphicon glyphicon-ok"></span> Vote!</button>

                    </div>
                  </form>
                </li>
              </ul>
            </div>

            
          <?php 
			 $imgObj->setDetails(); 
		  ?>
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