<?php 

?>	  
	   
	    <h3 class ="title"><?php echo $this->hotel->title ?></h3>
		<div class ="address"><?php 
				$city=BookProHelper::getObjectAddress($this->hotel->city_id);
				echo $this->hotel->address1.', '. $city->title ?></div>
		
		
		