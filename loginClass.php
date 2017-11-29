<?php
class loginClass {
	public $UID;
	public $username;
	public $password;
	public $fName;
	public $lName;
	public $NOT_FOUND;
	
	function NOT_FOUND() {
		if ($this->NOT_FOUND == true) {
			echo '<div class="alert alert-warning alert-dismissable">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Error: </strong> E-mail or password did not match our records.
            </div>';
		}	
	}
	
	function setSession() {
		$_SESSION['UID'] = $this->UID;
		$_SESSION['username'] = $this->fName . ' ' . $this->lName;
	}
	
	function checkSession() {
		if (isset($_SESSION['UID']) && isset($_SESSION['username'])) {
			return true;
		}
		else {
			header("Location: login.php", true, 301);
			exit();
		}
	}
	
	function returnUserName() {
		echo $_SESSION['username'];
	}
	
	function deleteSession() {
		if (isset($_GET['logout'])) {
			if ($_GET['logout'] == 1) {
				unset($_SESSION['username']);
				unset($_SESSION['UID']);  
			}
		}
	}
}


?>