<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php  2-04-2014 6:16:16
 **/

// No direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

?>
<div class='header-customtrip'>
	<h3 class="buildexpert">BUILD YOUR DREAM HOLIDAY WITH OUR EXPERTS</h3>
	<h5 class="sticksev">STICK YOUR PREFERRED SERVICES</h5>
	<p class="note1">(The symbol * is required field. Your personal data is protected by
		the terms and conditions)</p>
</div>
<br>
<?php

foreach ( ($this->model->getListCountries ()) as $key => $value ) {
	// get country id
	// get destination
	if ($value=="Yunnan")
	$countryid = $this->model->getCountryIDByName ( "China" );
	else $countryid = $this->model->getCountryIDByName ( $value );
	// echo "<br><br>Destination of ".$value;
	$dests = $this->model->getDestCountries ( $countryid );
	
	if ($value =="China") $value ="Yunnan";
	
	$num = round ( count ( $dests ) / 4 ); // $num: number values in row
	if ($num * 4 < count ( $dests ))
		$num ++;
	?>
<p class="fieldcustom2">Where would you like to visit in <?php echo $value;?> ? <a
		href="">(Click below sight for reference)</a>
</p>
<div class="row-fluid">
               <?php
	
for($i = 0; $i < count ( $dests ); $i ++) {
		$j = 1;
		?>
         <div class="span3 fieldcustom3" >
         <?php
		while ( $j <= $num && $i < count ( $dests ) ) {
			$obj = $dests [$i];
			?>
            <input type="checkbox" name="<?php echo strtolower($value);?>[]" value="<?php echo $obj->title;?>">&nbsp
            <a class="dest" href="" target="_blank"><?php echo $obj->title;?></a><br>
            <?php
			++ $j;
			++ $i;
		}
		?>
         </div>
         <?php
		$i --;
	}
	?>
               </div>
<br>
<div class="row-fluid">
	<div class="span3 fieldcustom2">
		<p>Other
	
	</div>
	<div class="span6 ">
		<input type="text" name="other<?php echo $key;?>" class="span12">
	</div>
</div>
<?php }?>

<!-- end fill country -->
<div class="row-fluid">
	<div class="span4 title" id="textitle">ACTIVITIES (*)</div>
	<div class="span4 title" id="textitle">TRANSPORT (*)</div>
	<div class="span4 title" id="textitle">ACCOMMODATION (*)</div>
</div>
<div class="row-fluid">
	<div class="span4 textitle1">
	    <?php foreach ( ($this->model->getProgram()) as $key => $value ) { ?>
		       <input type="checkbox" name="program[]" value="<?php echo $value;?>">&nbsp<?php echo $value;?><br>
		<?php }?>
		
	</div>
	<div class="span4 textitle1">
	    <?php foreach ( ($this->model->getTransport()) as $key => $value ) { ?>
		<input type="checkbox" name="transport[]" value="<?php echo $value;?>">&nbsp <?php echo $value;?><br>
		<?php }?>
		<div class="title">MEALS (*)</div>
      <?php foreach ( ($this->model->getMeals()) as $key => $value ) { ?>
		       <input type="checkbox" name="meal[]" value="<?php echo $value;?>">&nbsp<?php echo $value;?><br>
		<?php }?>
	</div>
	<div class="span4 textitle1">
	    <?php foreach ( ($this->model->getAccommodation()) as $key => $value ) { ?>
		<input type="checkbox" name="hotel[]" value="<?php echo $value;?>">&nbsp<?php echo $value;?><br>
		<?php }?>
		<div class="title">TRAVEL TYPE (*)</div>
		 <?php foreach ( ($this->model->getTravelType()) as $key => $value ) { ?>
		       <input type="checkbox" name="traveltype[]" value="<?php echo $value;?>">&nbsp<?php echo $value;?><br>
		<?php }?>
	</div>
</div>
