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
AImporter::helper('dest');
?>
         
<div class="row-fluid">
    <?php
    $i = 0;
    foreach ($this->destinations as $dest){

	$total_hotel=DestHelper::getHotelByDest($dest->id);
	
	if($total_hotel>0){
    
    $link = JRoute::_('index.php?option=com_bookpro&view=hotels&city_id=' . $dest -> id . '&Itemid=' . JRequest::getVar('Itemid'));
    $ipath = BookProHelper::getIPath($dest->image);
    $thumb = AImage::thumb($ipath, 160, 140);

    if( $i == 0 )
        echo '<div class="row-fluid">';?>
    <div class="span<?php echo (12/$this->products_per_row) ?>">
        <div class="content_hotels_products">
    
            <div class="row-fluid">

                <div class="span12">
                
                    <span class="blog_itemimage"><img class="thumbnail"
                            src="<?php echo $thumb ?>" alt="<?php echo $dest->title ?>"> </span>
               
                </div>
            </div>
            <div class="content_hotels_detail">
                <strong>
                    <a href="<?php echo $link ?>"><?php echo $dest->title; ?> </a><br/>
                    <p>
                    <span class="text1_hotels"><?php echo $total_hotel ?> <?php echo JText::_(' hotel(s)')?><span style="padding-left:5px;"></span>,
                    <a rel="{handler: 'iframe', size: {x: 570, y: 530}}" href="index.php?option=com_bookpro&task=displaymap&tmpl=component&dest_id=<?php echo $dest->id; ?>" class="jbmodal text_hotels_price"><span class="icon-map-marker"></span> 
                    <span style="color:#0896FF;">
                        <?php echo JText::_('COM_BOOKPRO_VIEW_MAP');?>
                    </span>
                    </a>
                    
                    </span>
                     </p>
                     <p>
                     <?php echo DestHelper::buildDestType($dest) ?>
                     </p>
                     
                </strong>
            </div><br/>
        </div>
    </div>
    
    <?php 
    if (($i+1) % $this->products_per_row == 0) {
                    echo '</div>';
                    echo ' <br/> <div class="row-fluid">';
                }
                if(($i+1) == $this->count) {
                    echo "</div>";
                }
                if($total < $this->count)
                {
                    if(($i+1) == $total) {
                        echo "</div>";
                        }
                }
               
                    $i++;
             }
             }
             ?>
             
           
     </div>
     
     
     
     