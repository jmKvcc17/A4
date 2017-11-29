<?php
	class Images {
		public $path;
		public $imageID;
		public $imageTitle;
		
		public function displayImages() {
				echo '<div class="col-md-3 text-center">';
				echo '<div class="thumbnail">';
				echo '<a href="image.php?id=' . $this->imageID .'">';
				echo '<img src="images/square-medium/' . $this->path . '" alt="' . $this->imageTitle . '" class="img-thumbnail">';
				echo '</a>';
				echo '<div class="caption">';
				echo '<p> <a href="image.php?id=1">'. $this->imageTitle . '</a></p>';
				echo '<p>';
				echo '<a href="image.php?id=' . $this->imageID .  '" class="btn btn-info" role="button">';
				echo '<span class="glyphicon glyphicon-info-sign"></span> view';
				echo '</a>';
				echo '</p>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}
		}

		
		
	



?>