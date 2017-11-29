<?php
class profileClass {
	
	public $username; //
	public $password;
	public $startDate; //

	public $address; //
	public $city; // 
	public $country; //
	public $phone; // 
	public $email; //
	public $privacySetting; //
	
	// user activity
	public $posts; //
	public $images; //
	public $following; //
	public $followedby; //
	
	
	
	function getInfo() {
		// Set the user's name
		$tempID = $_SESSION['UID'];
		
		
		
		$pdo = new PDO(DBCONNSTRING, DBUSER,DBPASS);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// from traveluesrdetails
		$sql = "SELECT * FROM traveluserdetails WHERE UID=$tempID";
		$result = $pdo->query($sql);
		$row = $result->fetch();
		
		
		$this->address = $row['Address'];
		$this->country = $row['Country'];
		$this->city = $row['City'];
		$this->phone = $row['Phone'];
		$this->email = $row['Email'];
		$this->username = $row['Email'];
		$this->privacySetting = $row['Privacy'];
		
		//start date
		$sql = "SELECT DateJoined FROM traveluser WHERE UID=$tempID";
		$result = $pdo->query($sql);
		$row = $result->fetch();
		
		$tempDate = date("F d, Y", strtotime($row['DateJoined']));
		
		$this->startDate = $tempDate;
		
		// User following
		$sql = "SELECT COUNT(UIDFollowing) AS Following FROM traveluserfollowing WHERE UID=$tempID";
		$result = $pdo->query($sql);
		$row = $result->fetch();
		
		$this->following = $row['Following'];
		
		
		
		// User followed by
		$sql = "SELECT COUNT(UIDFollowing) AS Following FROM traveluserfollowing WHERE UIDFollowing=$tempID";
		$result = $pdo->query($sql);
		$row = $result->fetch();
		
		$this->followedby = $row['Following'];
		
		// User posts
		$sql = "SELECT COUNT(UID) AS Post FROM travelpost WHERE UID=$tempID";
		$result = $pdo->query($sql);
		$row = $result->fetch();
		
		$this->posts = $row['Post'];
		
		// User images
		$sql = "SELECT COUNT(UID) AS Image FROM travelimage WHERE UID=$tempID";
		$result = $pdo->query($sql);
		$row = $result->fetch();
		
		$this->images = $row['Image'];
		
		// User pass
		$sql = "SELECT Pass FROM traveluser WHERE UID=$tempID";
		$result = $pdo->query($sql);
		$row = $result->fetch();
		
		$this->password = $row['Pass'];

		
	}
	
	
}


?>