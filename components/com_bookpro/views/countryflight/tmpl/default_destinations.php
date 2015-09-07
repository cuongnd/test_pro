<?php 
/**
 * @package     Bookpro Hotel Module
 * @author         Nguyen Dinh Cuong
 * @link         http://ibookingonline.com
 * @copyright     Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('_JEXEC') or die('Restricted access');
$total=count($this->destinations);
JHtml::_('behavior.modal','a.jbmodal');
AImporter::helper('dest','tour','string');
?>
         
<div class="row-fluid ">
    <?php
    $i = 1;
    foreach ($this->destinations as $dest){

    
  	
    $link = JRoute::_('index.php?option=com_bookpro&view=destinationflight&dest_id=' . $dest -> id . '&Itemid=' . JRequest::getVar('Itemid'));
    
    
    if( $i == 1 ){
        echo '<div class="row-fluid">';
    }    
        ?>
    <div class="span6">
        <div class="content_hotels_products" style="padding-right:8px;padding-bottom:10px;">
    		<div class="destination_image">
    			 <span class="blog_itemimage"><img class="thumbnail"
                         src="<?php echo $dest->image; ?>" alt="<?php echo $dest->title ?>"> 
					
				</span>
    		</div>
    		<div class="content_hotels_detail">
                <h3 class="title_top_destination">
                    <a href="<?php echo $link ?>"><?php echo $dest->title; ?> </a>
                </h3>
				<p style="line-height:15px;font-size:11px;">
				<?php echo JText::sprintf('COM_BOOKPRO_DESTINATION_FLIGHT_TOTAL',$dest->countFlight) ?>
				
            </div>
            
            
        </div>
    </div>
    
    <?php 
    if (($i) % 2 == 0 && $i < $total) {
         echo '</div>';
         echo '<div class="row-fluid">';
   	}
    if(($i) == $total) {
       echo "</div>";
    }
    
               
       $i++;
   }
             ?>
             
           
</div>
     
     
     
     