<?php
	class imageInfo {
		public $imgID;
		public $imgTitle;
		public $imgRating; // Image rating
		public $imgArtist;
		public $imgCountry; // used to display the country
		public $imgCity; // used to display the city
		public $imgLat;
		public $imgLong;
		public $imgDesc;
		public $imgVotes;
		public $cityCode;
		public $countryCode;
		public $imgPath;
		
		function setTitle() {
			echo '<div class="panel-heading"><h3>' . $this->imgTitle . '</h3></div>';
		}
		
		function setDescription() {
			echo '<div class="well">' . $this->imgDesc . '</div>';
		}
		
		function setImageFull() {
			echo '<div class="modal-body text-center">';
			echo $this->imgPath;
			echo 'Hello';
			echo '<img src="images/medium/' . $this->imgPath . '" alt=\"...\"  class="img-thumbnail">';
			echo '<br><br>
                  <p><strong>Image Description Here</strong></p>
                  </div>';
		}
		
		function setImageThumb() {
			echo '<img src="images/medium/' . $this->imgPath . '" alt=\"...\" data-toggle="modal" data-target="#myModal">';
		}
		
		function setVotingInfo() {
			if ($this->imgVotes > 1) {
				echo '<li class="list-group-item"><strong class="text-primary">' . $this->imgRating . '/5</strong> [' . $this->imgVotes . ' votes] </li>';
			}
			else {
				echo '<li class="list-group-item"><strong class="text-primary">' . $this->imgRating . '/5</strong> [' . $this->imgVotes . ' vote] </li>';
			}
			
		}
		
		function setDetails() {
			echo '<div class="panel panel-primary">
              <div class="panel-heading"><h4>Image Details</h4></div>

              <ul class="list-group">
                <li class="list-group-item"><strong>Taken By: </strong> 
                  ' . $this->imgArtist . '
                </li>
                <li class="list-group-item"><strong>Country: </strong> 
                  ' . $this->imgCountry . '
                </li>
                <li class="list-group-item"><strong>City: </strong> 
                  ' . $this->imgCity . '
                </li>
                <li class="list-group-item"><strong>Latitude: </strong> 
                  ' . $this->imgLat . '
                </li>
                <li class="list-group-item"><strong>Longitude: </strong> 
                  ' . $this->imgLong . '
                </li>
              </ul>
            </div>';
		}
		
	}


?>