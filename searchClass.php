<?php

class searchClass {
	public $searchField;
	public $searchType;
}

class searchResult {
	public $searchTitle;
	public $searchMessage;
	public $searchPostID;
	
	function printTitleSearch() {
		
			echo '<div class="panel panel-default">
					<div class="panel-heading">
						<h4><a href="post_single.php?id=' . $this->searchPostID . '">' . $this->searchTitle . '</a></h4>
				  </div>
				  <div class="panel-body">
					' . substr($this->searchMessage, 0, 200) . '<a href="post_single.php?id=' . $this->searchPostID . '">[read more...]</a>               
				  </div></div>';
		
	}
	
	function printContentSearch($strReplace) {
			echo '<div class="panel panel-default">
					<div class="panel-heading">
						<h4><a href="post_single.php?id=' . $this->searchPostID . '">' . $this->searchTitle . '</a></h4>
				  </div>
				  <div class="panel-body">
					' . str_ireplace($strReplace, '<mark>' . $strReplace . '</mark>' ,$this->searchMessage) . '<a href="post_single.php?id=' . $this->searchPostID . '"</a>               
				  </div></div>';
		
	}
}

?>