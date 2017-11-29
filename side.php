<?php
/*
TO DO:
	

*/

require_once('loginClass.php');

$loginObj = new loginClass();

function setRandomPost() {
	$pdo = new PDO(DBCONNSTRING, DBUSER,DBPASS);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "SELECT PostID FROM travelpost ORDER BY RAND() LIMIT 0, 1";
	$result = $pdo->query($sql);
	$row = $result->fetch();
	
	$randID = $row['PostID'];
	echo $randID;
}

function setRandomImage() {
	$pdo = new PDO(DBCONNSTRING, DBUSER,DBPASS);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "SELECT ImageID FROM travelimage ORDER BY RAND() LIMIT 0, 1";
	$result = $pdo->query($sql);
	$row = $result->fetch();
	
	$randID = $row['ImageID'];
	echo $randID;
	
}


?>
<div class="rail">

   <div class="alert alert-danger">
   
   <strong><span class="glyphicon glyphicon-user"></span> <?php $loginObj->returnUserName(); ?> </strong><br/>
   CS3500 Student<br/>
   <span class="member-box-links"><a href="profile.php">Profile</a> | <a href="login.php?logout=1">Logout</a></span>
  <hr>
   <ul class="nav nav-stacked">
   <li class="nav-header"> <strong><span class="glyphicon glyphicon-globe"></span>  My Travels</strong></li> 
     <li><a href="post_list.php"><span class="glyphicon glyphicon-th-list"></span> Post List</a></li>
     <!-- Substitute post_single.php?id=1 for a random PostID from the database -->
     <li><a href="post_single.php?id=<?php setRandomPost(); ?>"><span class="glyphicon glyphicon-file"></span> Single Post</a></li>
     <!-- Substitute image.php?id=1 for a random ImageID from the database -->
     <li><a href="image.php?id=<?php setRandomImage(); ?>"><span class="glyphicon glyphicon-picture"></span> Single Image</a></li>
     <li><a href="search.php"><span class="glyphicon glyphicon-search"></span> Search</a></li> 
   </ul>
 </div>
</div>
